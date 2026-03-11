<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets for admin's kos.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Ticket::where('tempat_kos_id', $user->tempat_kos_id)
            ->with(['user', 'room']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Display the specified ticket.
     */
    public function show($id)
    {
        $user = Auth::user();

        $ticket = Ticket::where('tempat_kos_id', $user->tempat_kos_id)
            ->with(['user', 'room'])
            ->findOrFail($id);

        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Update the ticket status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,rejected',
        ]);

        $user = Auth::user();

        $ticket = Ticket::where('tempat_kos_id', $user->tempat_kos_id)
            ->findOrFail($id);

        $ticket->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status komplain berhasil diperbarui.');
    }
}
