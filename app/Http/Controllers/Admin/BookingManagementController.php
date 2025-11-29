<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingManagementController extends Controller
{
    /**
     * Tampilkan daftar booking (pending & active)
     */
    public function index(Request $request)
    {
        $query = Rent::with(['user', 'room']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: tampilkan pending dan active
            $query->whereIn('status', ['pending', 'active']);
        }
        
        // Sort by latest
        $bookings = $query->latest()->paginate(15);
        
        // Count pending bookings
        $pendingCount = Rent::where('status', 'pending')->count();
        
        return view('admin.bookings.index', compact('bookings', 'pendingCount'));
    }
    
    /**
     * Tampilkan detail booking
     */
    public function show(Rent $booking)
    {
        $booking->load(['user', 'room.kosInfo']);
        
        return view('admin.bookings.show', compact('booking'));
    }
    
    /**
     * Approve booking
     */
    public function approve(Request $request, Rent $booking)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);
        
        // Validasi: hanya booking dengan status pending yang bisa di-approve
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }
        
        DB::beginTransaction();
        try {
            // Update booking status ke active
            $booking->update([
                'status' => 'active',
                'admin_notes' => $request->admin_notes,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);
            
            // Pastikan room status occupied
            $booking->room->update(['status' => 'occupied']);
            
            DB::commit();
            
            // TODO: Kirim notifikasi email/WA ke user (opsional)
            
            return redirect()
                ->route('admin.bookings.index')
                ->with('success', "Booking kamar {$booking->room->room_number} oleh {$booking->user->name} berhasil disetujui!");
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Gagal menyetujui booking: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject booking
     */
    public function reject(Request $request, Rent $booking)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ], [
            'admin_notes.required' => 'Alasan penolakan wajib diisi',
        ]);
        
        // Validasi: hanya booking dengan status pending yang bisa di-reject
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }
        
        DB::beginTransaction();
        try {
            // Update booking status ke cancelled
            $booking->update([
                'status' => 'cancelled',
                'admin_notes' => $request->admin_notes,
                'end_date' => now(),
                'approved_by' => Auth::id(),
            ]);
            
            // Kembalikan room status ke available
            $booking->room->update(['status' => 'available']);
            
            DB::commit();
            
            // TODO: Kirim notifikasi email/WA ke user (opsional)
            
            return redirect()
                ->route('admin.bookings.index')
                ->with('success', "Booking kamar {$booking->room->room_number} oleh {$booking->user->name} berhasil ditolak.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Gagal menolak booking: ' . $e->getMessage());
        }
    }
    
    /**
     * Get pending bookings count (for notification badge)
     */
    public function getPendingCount()
    {
        return Rent::where('status', 'pending')->count();
    }
}