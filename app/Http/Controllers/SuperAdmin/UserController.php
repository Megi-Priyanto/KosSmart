<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TempatKos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with('tempatKos');

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by tempat kos
        if ($request->filled('tempat_kos_id')) {
            $query->where('tempat_kos_id', $request->tempat_kos_id);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        // Data untuk filter
        $tempatKosList = TempatKos::active()->get();

        return view('superadmin.users.index', compact('users', 'tempatKosList'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $tempatKosList = TempatKos::active()->get();
        return view('superadmin.users.create', compact('tempatKosList'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()],
            'role' => ['required', 'in:super_admin,admin,user'],
            'tempat_kos_id' => ['nullable', 'exists:tempat_kos,id'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'tempat_kos_id.exists' => 'Tempat kos tidak valid',
        ]);

        // Validasi: Admin & User harus punya tempat_kos_id
        if (in_array($validated['role'], ['admin', 'user']) && !$validated['tempat_kos_id']) {
            return back()
                ->withInput()
                ->with('error', 'Admin dan User harus terikat dengan tempat kos.');
        }

        // Super admin tidak boleh punya tempat_kos_id
        if ($validated['role'] === 'super_admin') {
            $validated['tempat_kos_id'] = null;
        }

        try {
            $user = User::create([
                'name' => strip_tags($validated['name']),
                'email' => strtolower($validated['email']),
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'tempat_kos_id' => $validated['tempat_kos_id'],
                'email_verified_at' => now(), // Auto verify
            ]);

            $roleLabel = match($user->role) {
                'super_admin' => 'Super Admin',
                'admin' => 'Admin',
                'user' => 'User',
            };

            return redirect()
                ->route('superadmin.users.index')
                ->with('success', "{$roleLabel} {$user->name} berhasil dibuat!");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['tempatKos', 'rents.room', 'billings', 'payments']);

        return view('superadmin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $tempatKosList = TempatKos::active()->get();
        return view('superadmin.users.edit', compact('user', 'tempatKosList'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'role' => ['required', 'in:super_admin,admin,user'],
            'tempat_kos_id' => ['nullable', 'exists:tempat_kos,id'],
            'password' => ['nullable', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()],
        ]);

        // Validasi role & tempat_kos_id
        if (in_array($validated['role'], ['admin', 'user']) && !$validated['tempat_kos_id']) {
            return back()
                ->withInput()
                ->with('error', 'Admin dan User harus terikat dengan tempat kos.');
        }

        if ($validated['role'] === 'super_admin') {
            $validated['tempat_kos_id'] = null;
        }

        try {
            $user->name = strip_tags($validated['name']);
            $user->email = strtolower($validated['email']);
            $user->phone = $validated['phone'] ?? null;
            $user->role = $validated['role'];
            $user->tempat_kos_id = $validated['tempat_kos_id'];

            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return redirect()
                ->route('superadmin.users.show', $user)
                ->with('success', "User {$user->name} berhasil diperbarui!");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Cegah hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        // Cegah hapus super admin lain
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Tidak dapat menghapus Super Admin!');
        }

        try {
            $userName = $user->name;
            $user->delete();

            return redirect()
                ->route('superadmin.users.index')
                ->with('success', "User {$userName} berhasil dihapus!");

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}