<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminRegistration extends Model
{
    protected $fillable = [
        'nama_lengkap',
        'nik',
        'email',
        'no_hp',
        'password',
        'nama_kos',
        'alamat',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'telepon_kos',
        'email_kos',
        'ktp_foto',
        'selfie_ktp',
        'tipe_kepemilikan',
        'bukti_kepemilikan',
        'npwp',
        'status',
        'catatan',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    // ── Relasi ─────────────────────────────────────

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ── Helper ─────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getAlamatLengkapAttribute(): string
    {
        return collect([
            $this->alamat,
            'Kec. ' . $this->kecamatan,
            $this->kota,
            $this->provinsi,
            $this->kode_pos,
        ])->filter()->implode(', ');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'Menunggu Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => '-',
        };
    }

    public function getTipeKepemilikanLabelAttribute(): string
    {
        return match ($this->tipe_kepemilikan) {
            'pemilik' => 'Pemilik Langsung',
            'penyewa' => 'Penyewa / Pengelola',
            default   => '-',
        };
    }

    // ── Scope ──────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}