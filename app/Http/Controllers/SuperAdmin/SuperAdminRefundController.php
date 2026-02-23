<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CancelBooking;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SuperAdminRefundController extends Controller
{
    /**
     * Daftar cancel booking yang sudah di-approve admin dan menunggu refund dari superadmin
     */
    public function index(Request $request)
    {
        $query = CancelBooking::withoutTempatKosScope()
            ->with(['rent.room', 'user', 'tempatKos', 'approvedByAdmin']);

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('account_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['admin_approved', 'approved', 'rejected']);
        }

        $cancelBookings = $query->latest()->paginate(15);

        $pendingRefundCount = CancelBooking::withoutTempatKosScope()
            ->where('status', 'admin_approved')
            ->count();

        return view('superadmin.refunds.index', compact('cancelBookings', 'pendingRefundCount'));
    }

    /**
     * Detail satu cancel booking + form refund
     */
    public function show(CancelBooking $cancelBooking)
    {
        $cancelBooking->load(['rent.room', 'user', 'tempatKos', 'approvedByAdmin', 'processor']);

        $refundMethods = [
            'manual_transfer' => [
                'label' => 'Transfer Bank',
                'options' => [
                    'bca'     => ['name' => 'BCA'],
                    'bni'     => ['name' => 'BNI'],
                    'mandiri' => ['name' => 'Mandiri'],
                    'bri'     => ['name' => 'BRI'],
                    'cimb'    => ['name' => 'CIMB Niaga'],
                ]
            ],
            'e_wallet' => [
                'label' => 'E-Wallet',
                'options' => [
                    'dana'  => ['name' => 'DANA'],
                    'ovo'   => ['name' => 'OVO'],
                    'gopay' => ['name' => 'GoPay'],
                    'shopeepay' => ['name' => 'ShopeePay'],
                ]
            ],
        ];

        $defaultRefundAmount = $cancelBooking->rent->deposit_paid ?? 0;

        return view('superadmin.refunds.show', compact(
            'cancelBooking',
            'refundMethods',
            'defaultRefundAmount'
        ));
    }

    /**
     * Superadmin memproses refund (transfer uang ke user)
     */
    public function processRefund(Request $request, CancelBooking $cancelBooking)
    {
        if ($cancelBooking->status !== 'admin_approved') {
            return back()->with('error', 'Refund ini sudah diproses atau belum disetujui admin.');
        }

        $validated = $request->validate([
            'refund_method'         => 'required|in:manual_transfer,e_wallet',
            'refund_sub_method'     => 'required|string',
            'refund_account_number' => 'required|string|max:50',
            'refund_amount'         => 'required|numeric|min:0',
            'refund_proof'          => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'admin_notes'           => 'nullable|string|max:1000',
        ], [
            'refund_method.required'         => 'Metode pengembalian wajib dipilih',
            'refund_sub_method.required'     => 'Sub-metode wajib dipilih',
            'refund_account_number.required' => 'Nomor rekening tujuan wajib diisi',
            'refund_amount.required'         => 'Jumlah pengembalian wajib diisi',
            'refund_proof.required'          => 'Bukti transfer wajib diunggah',
        ]);

        try {
            DB::beginTransaction();

            // Upload bukti transfer
            $proofPath = $request->file('refund_proof')->store('refund-proofs', 'public');

            // Update cancel booking menjadi approved (selesai)
            $cancelBooking->update([
                'status'                => 'approved',
                'refund_method'         => $validated['refund_method'],
                'refund_sub_method'     => $validated['refund_sub_method'],
                'refund_account_number' => $validated['refund_account_number'],
                'refund_amount'         => $validated['refund_amount'],
                'refund_proof'          => $proofPath,
                'admin_notes'           => $validated['admin_notes'],
                'processed_by'          => Auth::id(),
                'processed_at'          => now(),
            ]);

            // Update rent status ke cancelled
            $cancelBooking->rent->update([
                'status'   => 'cancelled',
                'end_date' => now(),
            ]);

            // Kembalikan status room ke available
            if ($cancelBooking->rent->room) {
                $cancelBooking->rent->room->update(['status' => 'available']);
            }

            // Hapus billings yang belum dibayar
            $cancelBooking->rent->billings()
                ->whereIn('status', ['unpaid', 'pending', 'overdue'])
                ->delete();

            // Tandai notifikasi cancel_refund milik superadmin sebagai read
            Notification::where('user_id', Auth::id())
                ->where('type', 'cancel_refund')
                ->where('rent_id', $cancelBooking->rent_id)
                ->where('status', 'unread')
                ->update(['status' => 'read']);

            DB::commit();

            return redirect()->route('superadmin.refunds.index')
                ->with('success', 'Refund berhasil diproses! Dana sebesar Rp ' .
                    number_format($validated['refund_amount'], 0, ',', '.') .
                    ' telah dikembalikan ke user.');
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($proofPath)) {
                Storage::disk('public')->delete($proofPath);
            }

            return back()->with('error', 'Gagal memproses refund: ' . $e->getMessage())->withInput();
        }
    }
}
