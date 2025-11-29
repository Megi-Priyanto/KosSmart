<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of users with search and filter
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by role (FIXED)
        if ($request->role !== null && $request->role !== '') {
            $query->where('role', $request->role);
        }
        
        // Paginate results
       $users = $query->orderBy('id', 'asc')->paginate(10)->withQueryString(); 
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()],
            'role' => ['required', 'in:admin,user'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.min' => 'Nama lengkap minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
        ]);

        try {
            // Buat user baru
            $user = User::create([
                'name' => strip_tags($validated['name']),
                'email' => strtolower($validated['email']),
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'email_verified_at' => now(), // Auto verify untuk user yang dibuat admin
            ]);

            return redirect()
                ->route('admin.users.index')
                ->with('success', "User {$user->name} berhasil ditambahkan!");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan user. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, User $user)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'role' => ['required', 'in:admin,user'],
            'password' => ['nullable', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()],
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.min' => 'Nama lengkap minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan user lain',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
        ]);

        try {
            // Update data user
            $user->name = strip_tags($validated['name']);
            $user->email = strtolower($validated['email']);
            $user->phone = $validated['phone'] ?? null;
            $user->role = $validated['role'];
            
            // Update password jika diisi
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }
            
            $user->save();

            return redirect()
                ->route('admin.users.index')
                ->with('success', "User {$user->name} berhasil diperbarui!");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui user. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        // Cegah admin menghapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        try {
            $userName = $user->name;
            $user->delete();

            return redirect()
                ->route('admin.users.index')
                ->with('success', "User {$userName} berhasil dihapus!");

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus user. Silakan coba lagi.');
        }
    }
}