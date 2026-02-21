<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Disbursement;
use App\Models\Payment;
use App\Models\TempatKos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DisbursementController extends Controller
{
    public function index(Request $request)
    {
        $holdingSummary = Payment::withoutTempatKosScope()
            ->with('tempatKos.admins')
            ->where('status', 'confirmed')
            ->where('disbursement_status', 'holding')
            ->select(
                'tempat_kos_id',
                DB::raw('SUM(amount) as total_holding'),
                DB::raw('COUNT(*) as payment_count')
            )
            ->groupBy('tempat_kos_id')
            ->get();

        $stats = [
            'total_holding' => Payment::withoutTempatKosScope()
                ->where('status', 'confirmed')->where('disbursement_status', 'holding')
                ->sum('amount'),

            'total_disbursed' => Payment::withoutTempatKosScope()
                ->where('status', 'confirmed')->where('disbursement_status', 'disbursed')
                ->sum('amount'),

            'holding_count' => Payment::withoutTempatKosScope()
                ->where('status', 'confirmed')->where('disbursement_status', 'holding')
                ->count(),

            'holding_this_month' => Payment::withoutTempatKosScope()
                ->where('status', 'confirmed')->where('disbursement_status', 'holding')
                ->whereMonth('verified_at', now()->month)->whereYear('verified_at', now()->year)
                ->sum('amount'),

            'disbursed_this_month' => Disbursement::where('status', 'processed')
                ->whereMonth('processed_at', now()->month)->whereYear('processed_at', now()->year)
                ->sum('total_amount'),

            'platform_fee_total'       => Disbursement::where('status', 'processed')->sum('fee_amount'),
            'platform_fee_this_month'  => Disbursement::where('status', 'processed')
                ->whereMonth('processed_at', now()->month)->whereYear('processed_at', now()->year)
                ->sum('fee_amount'),
            'platform_fee_this_year'   => Disbursement::where('status', 'processed')
                ->whereYear('processed_at', now()->year)
                ->sum('fee_amount'),
        ];

        $disbursements = Disbursement::with(['tempatKos', 'admin', 'processor'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('tempat_kos_id'), fn($q) => $q->where('tempat_kos_id', $request->tempat_kos_id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $tempatKosList = TempatKos::where('status', 'active')->orderBy('nama_kos')->get();

        return view('superadmin.disbursements.index', compact(
            'holdingSummary', 'stats', 'disbursements', 'tempatKosList'
        ));
    }

    /**
     * Form pencairan dana.
     *
     * Query string yang didukung:
     *   ?tempat_kos_id=X            → wajib, filter payment per kos
     *   ?payment_id=Y               → opsional, pre-select & highlight satu payment spesifik
     *                                 (dipakai ketika klik "Cairkan" dari dashboard/notifikasi)
     */
    public function create(Request $request)
    {
        $request->validate(['tempat_kos_id' => 'required|exists:tempat_kos,id']);

        $tempatKos = TempatKos::with('admins')->findOrFail($request->tempat_kos_id);

        $holdingPayments = Payment::withoutTempatKosScope()
            ->with(['billing.room', 'user'])
            ->where('tempat_kos_id', $request->tempat_kos_id)
            ->where('status', 'confirmed')
            ->where('disbursement_status', 'holding')
            ->orderBy('verified_at', 'asc')
            ->get();

        $totalHolding = $holdingPayments->sum('amount');
        $admins       = $tempatKos->admins;

        $defaultFeePercent = Disbursement::where('tempat_kos_id', $tempatKos->id)
            ->latest()->value('fee_percent') ?? 10;

        // Payment spesifik yang di-highlight dari dashboard / notifikasi
        $preSelectedPaymentId = $request->payment_id
            ? (int) $request->payment_id
            : null;

        // Validasi: payment_id harus masuk dalam daftar holding kos ini
        if ($preSelectedPaymentId && !$holdingPayments->contains('id', $preSelectedPaymentId)) {
            $preSelectedPaymentId = null;
        }

        return view('superadmin.disbursements.create', compact(
            'tempatKos', 'holdingPayments', 'totalHolding',
            'admins', 'defaultFeePercent', 'preSelectedPaymentId'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tempat_kos_id'    => 'required|exists:tempat_kos,id',
            'admin_id'         => 'required|exists:users,id',
            'payment_ids'      => 'required|array|min:1',
            'payment_ids.*'    => 'required|exists:payments,id',
            'fee_percent'      => 'required|numeric|min:0|max:100',
            'transfer_method'  => 'required|string|max:100',
            'transfer_account' => 'required|string|max:100',
            'transfer_proof'   => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'description'      => 'nullable|string|max:1000',
        ], [
            'payment_ids.required' => 'Pilih minimal satu payment untuk dicairkan',
            'fee_percent.required' => 'Persentase fee platform wajib diisi',
            'fee_percent.max'      => 'Fee tidak boleh lebih dari 100%',
        ]);

        try {
            DB::beginTransaction();

            $payments = Payment::withoutTempatKosScope()
                ->whereIn('id', $validated['payment_ids'])
                ->where('tempat_kos_id', $validated['tempat_kos_id'])
                ->where('status', 'confirmed')
                ->where('disbursement_status', 'holding')
                ->get();

            if ($payments->count() !== count($validated['payment_ids'])) {
                DB::rollBack();
                return back()->with('error', 'Beberapa payment tidak valid atau sudah dicairkan sebelumnya.');
            }

            $grossAmount = $payments->sum('amount');
            $feeCalc     = Disbursement::calculateFee($grossAmount, (float) $validated['fee_percent']);

            $proofPath = $request->file('transfer_proof')->store('disbursement-proofs', 'public');

            $disbursement = Disbursement::create([
                'tempat_kos_id'    => $validated['tempat_kos_id'],
                'admin_id'         => $validated['admin_id'],
                'gross_amount'     => $feeCalc['gross_amount'],
                'fee_percent'      => $feeCalc['fee_percent'],
                'fee_amount'       => $feeCalc['fee_amount'],
                'total_amount'     => $feeCalc['admin_amount'],
                'payment_count'    => $payments->count(),
                'description'      => $validated['description'],
                'status'           => 'processed',
                'transfer_method'  => $validated['transfer_method'],
                'transfer_account' => $validated['transfer_account'],
                'transfer_proof'   => $proofPath,
                'processed_at'     => now(),
                'processed_by'     => auth()->id(),
            ]);

            foreach ($payments as $payment) {
                $payment->markAsDisbursed($disbursement->id);
            }

            DB::commit();

            return redirect()->route('superadmin.disbursements.show', $disbursement)
                ->with('success',
                    "Dana berhasil dicairkan! " .
                    "Bruto: Rp " . number_format($grossAmount, 0, ',', '.') .
                    " | Fee ({$feeCalc['fee_percent']}%): Rp " . number_format($feeCalc['fee_amount'], 0, ',', '.') .
                    " | Diterima Admin: Rp " . number_format($feeCalc['admin_amount'], 0, ',', '.')
                );

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($proofPath)) Storage::disk('public')->delete($proofPath);
            return back()->with('error', 'Gagal memproses pencairan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Disbursement $disbursement)
    {
        $disbursement->load(['tempatKos', 'admin', 'processor', 'payments.billing.room', 'payments.user']);
        return view('superadmin.disbursements.show', compact('disbursement'));
    }

    public function holdingSummary()
    {
        $summary = Payment::withoutTempatKosScope()
            ->with('tempatKos:id,nama_kos')
            ->where('status', 'confirmed')->where('disbursement_status', 'holding')
            ->select('tempat_kos_id', DB::raw('SUM(amount) as total_holding'), DB::raw('COUNT(*) as payment_count'))
            ->groupBy('tempat_kos_id')->get();

        return response()->json($summary);
    }
}