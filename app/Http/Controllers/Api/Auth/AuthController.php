<?php
// app/Http/Controllers/Api/Auth/AuthController.php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseApiController
{
    /**
     * Login - Generate Token
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Rate limiting
            $throttleKey = Str::transliterate(Str::lower($request->email) . '|' . $request->ip());
            
            if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
                $seconds = RateLimiter::availableIn($throttleKey);
                return $this->errorResponse(
                    "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
                    null,
                    429
                );
            }

            // Cari user
            $user = User::where('email', $validated['email'])->first();

            // Validasi kredensial
            if (!$user || !Hash::check($validated['password'], $user->password)) {
                RateLimiter::hit($throttleKey, 60);
                return $this->errorResponse('Email atau password salah', null, 401);
            }

            // Cek email verified
            if (is_null($user->email_verified_at)) {
                return $this->errorResponse('Email belum diverifikasi', null, 403);
            }

            // Clear rate limiter
            RateLimiter::clear($throttleKey);

            // Generate token
            $token = $user->createToken('mobile-app')->plainTextToken;

            return $this->successResponse([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'email_verified_at' => $user->email_verified_at,
                ],
                'token' => $token,
            ], 'Login berhasil');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }

    /**
     * Register
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|min:3',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'nullable|string|max:20|regex:/^([0-9\s\-\+\(\)]*)$/',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => strip_tags($validated['name']),
                'email' => strtolower($validated['email']),
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'email_verified_at' => null,
                'role' => 'user',
            ]);

            // TODO: Kirim OTP ke email (gunakan sistem yang sudah ada)

            return $this->successResponse([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ], 'Registrasi berhasil. Silakan verifikasi email Anda.', 201);

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }

    /**
     * Logout - Revoke Token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logout berhasil');
    }

    /**
     * Get Current User
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return $this->successResponse([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'email_verified_at' => $user->email_verified_at,
        ]);
    }

    /**
     * Refresh Token
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        
        // Hapus token lama
        $user->currentAccessToken()->delete();
        
        // Generate token baru
        $token = $user->createToken('mobile-app')->plainTextToken;

        return $this->successResponse([
            'token' => $token,
        ], 'Token berhasil diperbarui');
    }
}