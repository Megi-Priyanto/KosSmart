<?php
// app/Http/Controllers/Admin/RoomStatusController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomStatusController extends Controller
{
    /**
     * Update room status
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'status' => 'required|in:available,occupied,maintenance'
        ]);
        
        $oldStatus = $room->status;
        $newStatus = $request->status;
        
        // Validasi: tidak bisa set available jika masih ada penyewa aktif
        if ($newStatus === 'available' && $room->currentRent()->exists()) {
            return back()->with('error', 'Kamar tidak dapat diubah ke "Tersedia" karena masih ada penyewa aktif');
        }
        
        $room->update([
            'status' => $newStatus
        ]);
        
        $statusLabels = [
            'available' => 'Tersedia',
            'occupied' => 'Terisi',
            'maintenance' => 'Maintenance'
        ];
        
        return back()->with('success', 
            "Status kamar {$room->room_number} berhasil diubah dari {$statusLabels[$oldStatus]} ke {$statusLabels[$newStatus]}"
        );
    }
    
    /**
     * Bulk update status
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'room_ids' => 'required|array',
            'room_ids.*' => 'exists:rooms,id',
            'status' => 'required|in:available,occupied,maintenance'
        ]);
        
        $rooms = Room::whereIn('id', $request->room_ids)->get();
        $updated = 0;
        
        foreach ($rooms as $room) {
            // Skip jika tidak bisa diupdate
            if ($request->status === 'available' && $room->currentRent()->exists()) {
                continue;
            }
            
            $room->update(['status' => $request->status]);
            $updated++;
        }
        
        return back()->with('success', "{$updated} kamar berhasil diperbarui statusnya");
    }
}