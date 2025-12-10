<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpVerificationMail;


class EmailVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Generate OTP 6 digit
     */
    public static function generateOtp(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Buat OTP baru untuk email
     */
    public static function createOtp(string $email): self
    {
        // Hapus OTP lama untuk email ini
        self::where('email', $email)->delete();

        // Buat OTP baru
        $otp = self::generateOtp();
        
        $verification = self::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addSeconds(60), // 60 detik
        ]);

        // Kirim email OTP
        Mail::to($email)->send(new OtpVerificationMail($otp));

        return $verification;
    }

    /**
     * Verifikasi OTP
     */
    public static function verifyOtp(string $email, string $otp): bool
    {
        $verification = self::where('email', $email)
            ->where('otp', $otp)
            ->first();

        if (!$verification) {
            return false;
        }

        // Cek apakah expired
        if ($verification->expires_at < now()) {
            return false;
        }

        // OTP valid, hapus dari database
        $verification->delete();
        
        return true;
    }

    /**
     * Cek apakah OTP masih valid
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    /**
     * Cek apakah email memiliki OTP aktif
     */
    public static function hasActiveOtp(string $email): bool
    {
        return self::where('email', $email)
            ->where('expires_at', '>', now())
            ->exists();
    }
}