<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CancelBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminCancelBookingController extends Controller
{
    /**
     * Display all cancel requests
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Query builder
        if ($user->isSuperAdmin()) {
            $query = CancelBooking::withoutTempatKosScope()
                ->with(['rent.room', 'user']);
        } else {
            $query = CancelBooking::with(['rent.room', 'user']);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending', 'approved']);
        }

        $cancelBookings = $query->latest()->paginate(15);

        // Count pending
        $pendingCount = $user->isSuperAdmin()
            ? CancelBooking::withoutTempatKosScope()->where('status', 'pending')->count()
            : CancelBooking::where('status', 'pending')->count();

        return view('admin.cancel-bookings.index', compact('cancelBookings', 'pendingCount'));
    }

    /**
     * Show refund form
     */
    public function showRefundForm(CancelBooking $cancelBooking)
    {
        if ($cancelBooking->status !== 'pending') {
            return back()->with('error', 'Pembatalan ini sudah diproses sebelumnya.');
        }

        $cancelBooking->load(['rent.room', 'user']);

        // Payment methods untuk refund
        $refundMethods = [
            'manual_transfer' => [
                'label' => 'Transfer Bank',
                'options' => [
                    'bca' => ['name' => 'BCA'],
                    'bni' => ['name' => 'BNI'],
                    'mandiri' => ['name' => 'Mandiri'],
                ]
            ],
            'e_wallet' => [
                'label' => 'E-Wallet',
                'options' => [
                    'dana' => ['name' => 'DANA'],
                    'ovo' => ['name' => 'OVO'],
                    'gopay' => ['name' => 'GoPay'],
                ]
            ],
        ];

        // Default refund amount = DP yang sudah dibayar
        $defaultRefundAmount = $cancelBooking->rent->deposit_paid;

        return view('admin.cancel-bookings.refund-form', compact(
            'cancelBooking',
            'refundMethods',
            'defaultRefundAmount'
        ));
    }

    /**
     * Process refund
     */
    public function processRefund(Request $request, CancelBooking $cancelBooking)
    {
        if ($cancelBooking->status !== 'pending') {
            return back()->with('error', 'Pembatalan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'refund_method' => 'required|in:manual_transfer,e_wallet',
            'refund_sub_method' => 'required|string',
            'refund_account_number' => 'required|string|max:50',
            'refund_amount' => 'required|numeric|min:0',
            'refund_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'admin_notes' => 'nullable|string|max:1000',
        ], [
            'refund_method.required' => 'Metode pengembalian wajib dipilih',
            'refund_sub_method.required' => 'Sub-metode wajib dipilih',
            'refund_account_number.required' => 'Nomor rekening tujuan wajib diisi',
            'refund_amount.required' => 'Jumlah pengembalian wajib diisi',
            'refund_proof.required' => 'Bukti transfer wajib diunggah',
        ]);

        try {
            DB::beginTransaction();

            // Upload bukti transfer
            $proofPath = $request->file('refund_proof')
                ->store('refund-proofs', 'public');

            // Update cancel booking
            $cancelBooking->update([
                'status' => 'approved',
                'refund_method' => $validated['refund_method'],
                'refund_sub_method' => $validated['refund_sub_method'],
                'refund_account_number' => $validated['refund_account_number'],
                'refund_amount' => $validated['refund_amount'],
                'refund_proof' => $proofPath,
                'admin_notes' => $validated['admin_notes'],
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            // Update rent status ke 'cancelled'
            $cancelBooking->rent->update([
                'status' => 'cancelled',
                'end_date' => now(),
            ]);

            // Kembalikan room status ke 'available'
            $cancelBooking->rent->room->update(['status' => 'available']);

            // Hapus billings yang belum dibayar
            $cancelBooking->rent->billings()
                ->whereIn('status', ['unpaid', 'pending', 'overdue'])
                ->delete();

            DB::commit();

            return redirect()->route('admin.cancel-bookings.index')
                ->with('success', 'Pengembalian dana berhasil diproses. User dapat melihat status di dashboard mereka.');

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($proofPath)) {
                Storage::disk('public')->delete($proofPath);
            }

            return back()->with('error', 'Gagal memproses refund: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Reject cancel request
     */
    public function reject(Request $request, CancelBooking $cancelBooking)
    {
        if ($cancelBooking->status !== 'pending') {
            return back()->with('error', 'Pembatalan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ], [
            'admin_notes.required' => 'Alasan penolakan wajib diisi',
        ]);

        try {
            DB::beginTransaction();

            $cancelBooking->update([
                'status' => 'rejected',
                'admin_notes' => $validated['admin_notes'],
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.cancel-bookings.index')
                ->with('success', 'Permintaan pembatalan ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak pembatalan: ' . $e->getMessage());
        }
    }
}