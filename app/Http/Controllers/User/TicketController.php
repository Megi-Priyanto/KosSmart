<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Rent;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $tickets = Ticket::where('user_id', $user->id)
            ->with(['tempatKos', 'room'])
            ->latest()
            ->paginate(10);

        return view('user.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Ensure user has an active room
        $activeRent = Rent::tenant()
            ->where('user_id', $user->id)
            ->whereHas('room', fn($q) => $q->where('status', 'occupied'))
            ->latest()
            ->first();

        if (!$activeRent) {
            return redirect()->route('user.tickets.index')->with('error', 'Anda harus memiliki kamar aktif untuk membuat komplain.');
        }

        return view('user.tickets.create', compact('activeRent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        $activeRent = Rent::tenant()
            ->where('user_id', $user->id)
            ->whereHas('room', fn($q) => $q->where('status', 'occupied'))
            ->latest()
            ->first();

        if (!$activeRent) {
            return redirect()->route('user.tickets.index')->with('error', 'Tidak ada kamar aktif ditemukan.');
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('tickets', 'public');
        }

        Ticket::create([
            'user_id' => $user->id,
            'tempat_kos_id' => $activeRent->room->tempat_kos_id,
            'room_id' => $activeRent->room_id,
            'title' => $request->title,
            'description' => $request->description,
            'photo_path' => $photoPath,
            'status' => 'pending',
        ]);

        return redirect()->route('user.tickets.index')->with('success', 'Komplain berhasil dikirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ticket = Ticket::where('user_id', Auth::id())
            ->with(['tempatKos', 'room'])
            ->findOrFail($id);

        return view('user.tickets.show', compact('ticket'));
    }
}
