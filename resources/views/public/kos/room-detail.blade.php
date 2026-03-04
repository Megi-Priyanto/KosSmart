@extends('layouts.public')

@section('title', 'Detail Kamar ' . $room->room_number . ' - ' . $tempatKos->nama_kos . ' - KosSmart')

@push('head-scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@push('page-styles')
        /* ── PANELS ── */
        .panel { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; }
        .panel-body { padding: 1.5rem; }

        /* ── QUICK STAT ── */
        .quick-stat { text-align: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 0.5rem; }
        .qs-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.4rem; }

        /* ── BOOKING CARD ── */
        .booking-card { background: #ffffff; border: 2px solid #e2e8f0; border-radius: 18px; padding: 1.5rem; position: sticky; top: 5.5rem; }
        .btn-booking { display: flex; align-items: center; justify-content: center; gap: 8px; background: linear-gradient(135deg, #f59e0b, #d97706); color: #ffffff; font-weight: 800; font-size: 0.9rem; padding: 0.85rem; border-radius: 12px; text-decoration: none; transition: all 0.25s; box-shadow: 0 6px 20px rgba(245,158,11,0.3); }
        .btn-booking:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(245,158,11,0.45); }
        .btn-wa { display: flex; align-items: center; justify-content: center; gap: 8px; background: #16a34a; color: #ffffff; font-weight: 700; font-size: 0.9rem; padding: 0.85rem; border-radius: 12px; text-decoration: none; transition: all 0.25s; }
        .btn-wa:hover { background: #15803d; transform: translateY(-2px); }
        .check-row { display: flex; align-items: center; gap: 8px; font-size: 0.78rem; color: #64748b; }
        .check-row svg { flex-shrink: 0; color: #22c55e; }

        /* ── RELATED CARD ── */
        .related-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; text-decoration: none; transition: all 0.2s; display: block; }
        .related-card:hover { border-color: rgba(245,158,11,0.3); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(15,23,42,0.08); }

        /* ── FACILITY TAG ── */
        .fac-tag { display: flex; align-items: center; gap: 6px; font-size: 0.82rem; color: #475569; }
        .fac-tag svg { color: #22c55e; flex-shrink: 0; }

        /* ── TENTANG KOS ── */
        .kos-info-card {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 16px;
            padding: 1.5rem;
            color: #ffffff;
        }
        .kos-info-label { font-size: 0.75rem; color: rgba(255,255,255,0.8); margin-bottom: 2px; }
        .kos-info-value { font-weight: 600; font-size: 0.9rem; }
        .facility-chip {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 100px;
        }
        .rule-item { display: flex; align-items: flex-start; gap: 8px; font-size: 0.82rem; color: rgba(255,255,255,0.9); line-height: 1.5; }
        .rule-item svg { flex-shrink: 0; margin-top: 1px; color: rgba(255,255,255,0.7); }

        /* ── TIPS BOX ── */
        .tips-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 14px; padding: 1.25rem; }

        /* ── DP BOX ── */
        .dp-box { background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2); border-radius: 12px; padding: 0.85rem; }
@endpush

@section('content')

<div style="max-width:1152px;margin:0 auto;padding:5.5rem 1.5rem 3rem;position:relative;z-index:1;">

    <!-- Breadcrumb -->
    <nav style="display:flex;align-items:center;gap:8px;font-size:0.8rem;color:#64748b;margin-bottom:1.5rem;flex-wrap:wrap;">
        <a href="{{ route('public.kos.index') }}" style="color:#64748b;text-decoration:none;"
           onmouseover="this.style.color='#d97706'" onmouseout="this.style.color='#64748b'">Daftar Kos</a>
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('public.kos.rooms', $tempatKos) }}" style="color:#64748b;text-decoration:none;"
           onmouseover="this.style.color='#d97706'" onmouseout="this.style.color='#64748b'">{{ $tempatKos->nama_kos }}</a>
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span style="color:#1e293b;font-weight:600;">Kamar {{ $room->room_number }}</span>
    </nav>

    <!-- Grid Layout -->
    <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start;">

        <!-- ══ LEFT COLUMN ══ -->
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            <!-- Galeri Foto -->
            <div class="panel">
                @php
                    $images = is_array($room->images) ? $room->images : json_decode($room->images, true);
                @endphp

                @if(!empty($images))
                <div x-data="{ images: {{ json_encode($images) }}, current: 0 }">
                    <div style="aspect-ratio:16/9;background:#f1f5f9;position:relative;" class="group">
                        <img :src="'{{ asset('storage') }}/' + images[current]"
                             alt="Kamar {{ $room->room_number }}"
                             style="width:100%;height:100%;object-fit:cover;">
                        <template x-if="images.length > 1">
                            <div>
                                <button @click="current = (current - 1 + images.length) % images.length"
                                        style="position:absolute;left:12px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,0.55);color:white;padding:8px;border-radius:50%;border:none;cursor:pointer;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <button @click="current = (current + 1) % images.length"
                                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,0.55);color:white;padding:8px;border-radius:50%;border:none;cursor:pointer;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                                <div style="position:absolute;bottom:12px;right:12px;background:rgba(0,0,0,0.6);color:white;font-size:0.72rem;padding:4px 10px;border-radius:100px;">
                                    <span x-text="current + 1"></span> / <span x-text="images.length"></span>
                                </div>
                            </div>
                        </template>
                        <div style="position:absolute;top:12px;left:12px;">
                            <span style="background:#22c55e;color:#ffffff;font-size:0.68rem;font-weight:700;padding:4px 12px;border-radius:100px;">✓ Tersedia</span>
                        </div>
                    </div>
                    <!-- Thumbnails -->
                    <div style="padding:0.75rem;background:#f8fafc;display:flex;gap:8px;overflow-x:auto;">
                        <template x-for="(img, i) in images" :key="i">
                            <div @click="current = i"
                                 style="width:60px;height:60px;flex-shrink:0;border-radius:8px;overflow:hidden;cursor:pointer;transition:all 0.2s;"
                                 :style="current === i ? 'border:2px solid #f59e0b;opacity:1' : 'border:2px solid transparent;opacity:0.55'">
                                <img :src="'{{ asset('storage') }}/' + img" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </template>
                    </div>
                </div>
                @else
                <div style="aspect-ratio:16/9;background:linear-gradient(135deg,#fbbf24,#f97316);display:flex;align-items:center;justify-content:center;">
                    <svg width="72" height="72" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                @endif
            </div>

            <!-- Info Kamar -->
            <div class="panel">
                <div class="panel-body">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.75rem;">
                        <div>
                            <h1 style="font-size:1.5rem;font-weight:800;color:#1e293b;margin-bottom:0.25rem;">Kamar {{ $room->room_number }}</h1>
                            <p style="font-size:0.82rem;color:#64748b;">{{ $room->floor }} • {{ $room->size }} m²</p>
                        </div>
                        <div style="text-align:right;">
                            <p style="font-size:1.6rem;font-weight:800;color:#d97706;">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                            <p style="font-size:0.72rem;color:#94a3b8;">{{ $room->jenis_sewa === 'tahun' ? 'per tahun' : 'per bulan' }}</p>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.75rem;padding:1rem 0;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;margin-bottom:1.25rem;">
                        <div class="quick-stat">
                            <div class="qs-icon" style="background:rgba(245,158,11,0.1);">
                                <svg width="18" height="18" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <p style="font-size:0.68rem;color:#94a3b8;">Kapasitas</p>
                            <p style="font-size:0.85rem;font-weight:700;color:#1e293b;margin-top:2px;">{{ $room->capacity }} Orang</p>
                        </div>
                        <div class="quick-stat">
                            <div class="qs-icon" style="background:rgba(96,165,250,0.1);">
                                <svg width="18" height="18" fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                                </svg>
                            </div>
                            <p style="font-size:0.68rem;color:#94a3b8;">Tipe</p>
                            <p style="font-size:0.85rem;font-weight:700;color:#1e293b;margin-top:2px;">{{ ucfirst($room->type) }}</p>
                        </div>
                        <div class="quick-stat">
                            <div class="qs-icon" style="background:rgba(167,139,250,0.1);">
                                <svg width="18" height="18" fill="none" stroke="#8b5cf6" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p style="font-size:0.68rem;color:#94a3b8;">Sewa</p>
                            <p style="font-size:0.85rem;font-weight:700;color:#1e293b;margin-top:2px;">{{ $room->jenis_sewa_label }}</p>
                        </div>
                        <div class="quick-stat">
                            <div class="qs-icon" style="background:rgba(52,211,153,0.1);">
                                <svg width="18" height="18" fill="none" stroke="#22c55e" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <p style="font-size:0.68rem;color:#94a3b8;">Jendela</p>
                            <p style="font-size:0.85rem;font-weight:700;color:#1e293b;margin-top:2px;">{{ $room->has_window ? 'Ada' : 'Tidak' }}</p>
                        </div>
                    </div>

                    @if($room->description)
                    <div style="margin-bottom:1.25rem;">
                        <h3 style="font-size:0.95rem;font-weight:700;color:#1e293b;margin-bottom:0.5rem;">Deskripsi</h3>
                        <p style="font-size:0.84rem;color:#64748b;line-height:1.75;">{{ $room->description }}</p>
                    </div>
                    @endif

                    @php
                        $facilities = is_array($room->facilities) ? $room->facilities : json_decode($room->facilities, true);
                    @endphp
                    @if(!empty($facilities))
                    <div>
                        <h3 style="font-size:0.95rem;font-weight:700;color:#1e293b;margin-bottom:0.75rem;">Fasilitas Kamar</h3>
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:0.6rem;">
                            @foreach($facilities as $f)
                            <div class="fac-tag">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $f }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ══ TENTANG KOS (sama persis dengan user) ══ -->
            @if($kosInfo && $kosInfo->is_active)
            <div class="kos-info-card">
                <h3 style="font-size:1rem;font-weight:800;margin-bottom:1rem;display:flex;align-items:center;gap:8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Tentang Kos
                </h3>

                {{-- Nama & Alamat --}}
                <div style="margin-bottom:1rem;">
                    <p style="font-size:1.25rem;font-weight:800;margin-bottom:0.2rem;">{{ $kosInfo->name }}</p>
                    <p style="font-size:0.84rem;color:rgba(255,255,255,0.85);">{{ $kosInfo->full_address }}</p>
                </div>

                {{-- Telepon & WhatsApp --}}
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;margin-bottom:1rem;">
                    <div>
                        <p class="kos-info-label">Telepon</p>
                        <p class="kos-info-value">{{ $kosInfo->phone ?? '-' }}</p>
                    </div>
                    @if($kosInfo->whatsapp)
                    <div>
                        <p class="kos-info-label">WhatsApp</p>
                        <p class="kos-info-value">{{ $kosInfo->whatsapp }}</p>
                    </div>
                    @endif
                </div>

                {{-- Fasilitas Umum --}}
                @php
                    $generalFacilities = $kosInfo->general_facilities;
                    if (is_string($generalFacilities)) {
                        $decoded = json_decode($generalFacilities, true);
                        $generalFacilities = json_last_error() === JSON_ERROR_NONE
                            ? $decoded
                            : array_filter(array_map('trim', explode(',', $generalFacilities)));
                    }
                @endphp
                @if(!empty($generalFacilities))
                <div style="padding-top:1rem;border-top:1px solid rgba(255,255,255,0.2);margin-bottom:1rem;">
                    <p style="font-size:0.78rem;font-weight:700;color:rgba(255,255,255,0.8);margin-bottom:0.5rem;">Fasilitas Umum:</p>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                        @foreach($generalFacilities as $fac)
                            <span class="facility-chip">{{ $fac }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Peraturan Kos --}}
                @php
                    $rules = $kosInfo->rules;
                    if (is_string($rules)) {
                        $decoded = json_decode($rules, true);
                        $rules = json_last_error() === JSON_ERROR_NONE
                            ? $decoded
                            : array_filter(array_map('trim', explode(',', $rules)));
                    }
                @endphp
                @if(!empty($rules))
                <div style="padding-top:1rem;border-top:1px solid rgba(255,255,255,0.2);margin-bottom:1rem;">
                    <p style="font-size:0.78rem;font-weight:700;color:rgba(255,255,255,0.8);margin-bottom:0.6rem;">Peraturan Kos:</p>
                    <div style="display:flex;flex-direction:column;gap:0.4rem;">
                        @foreach(array_slice((array)$rules, 0, 10) as $rule)
                        <div class="rule-item">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $rule }}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Check-in & Check-out --}}
                @if($kosInfo->checkin_time || $kosInfo->checkout_time)
                <div style="padding-top:1rem;border-top:1px solid rgba(255,255,255,0.2);display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">
                    @if($kosInfo->checkin_time)
                    <div>
                        <p class="kos-info-label">Check-in</p>
                        <p class="kos-info-value">{{ \Carbon\Carbon::parse($kosInfo->checkin_time)->format('H:i') }} WIB</p>
                    </div>
                    @endif
                    @if($kosInfo->checkout_time)
                    <div>
                        <p class="kos-info-label">Check-out</p>
                        <p class="kos-info-value">{{ \Carbon\Carbon::parse($kosInfo->checkout_time)->format('H:i') }} WIB</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            @endif

            <!-- Kamar Lain yang Tersedia -->
            @if($relatedRooms->count() > 0)
            <div class="panel" style="margin-bottom:1.5rem;">
                <div class="panel-body">
                    <h3 style="font-size:0.95rem;font-weight:700;color:#1e293b;margin-bottom:1rem;">Kamar Lain yang Tersedia</h3>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem;">
                        @foreach($relatedRooms as $r)
                        <a href="{{ route('public.kos.room-detail', $r) }}" class="related-card">
                            @php $ri = is_array($r->images) ? $r->images : json_decode($r->images, true); @endphp
                            @if(!empty($ri))
                                <div style="height:76px;overflow:hidden;">
                                    <img src="{{ asset('storage/' . $ri[0]) }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s;">
                                </div>
                            @else
                                <div style="height:76px;background:linear-gradient(135deg,#fbbf24,#f97316);"></div>
                            @endif
                            <div style="padding:0.6rem;">
                                <p style="font-size:0.8rem;font-weight:700;color:#1e293b;">Kamar {{ $r->room_number }}</p>
                                <p style="font-size:0.72rem;color:#d97706;font-weight:700;">Rp {{ number_format($r->price, 0, ',', '.') }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
        {{-- ═══ END LEFT COLUMN ═══ --}}

        <!-- ══ RIGHT COLUMN ══ -->
        <div>
            <!-- Booking Card -->
            <div class="booking-card">
                <div style="margin-bottom:1.25rem;">
                    <p style="font-size:0.78rem;color:#94a3b8;margin-bottom:0.25rem;">Harga Sewa</p>
                    <p style="font-size:2rem;font-weight:800;color:#d97706;">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                    <p style="font-size:0.72rem;color:#94a3b8;margin-top:2px;">{{ $room->jenis_sewa === 'tahun' ? 'per tahun' : 'per bulan' }}</p>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <div class="dp-box" style="margin-bottom:0.85rem;">
                        <p style="font-size:0.72rem;font-weight:700;color:#b45309;margin-bottom:4px;">DP yang dibutuhkan (50%)</p>
                        <p style="font-size:1.25rem;font-weight:800;color:#d97706;">Rp {{ number_format($room->price * 0.5, 0, ',', '.') }}</p>
                    </div>

                    <a href="{{ route('login') }}?intended={{ urlencode(route('user.rooms.show', $room->id)) }}"
                       class="btn-booking" style="margin-bottom:0.6rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Booking Sekarang
                    </a>

                    @if($kosInfo && $kosInfo->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kosInfo->whatsapp) }}?text={{ urlencode('Halo, saya tertarik dengan Kamar ' . $room->room_number . ' di ' . $tempatKos->nama_kos) }}"
                       target="_blank" class="btn-wa" style="margin-bottom:0.6rem;">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Hubungi via WhatsApp
                    </a>
                    @endif

                    <p style="font-size:0.72rem;text-align:center;color:#94a3b8;">
                        Perlu login untuk booking.
                        <a href="{{ route('register') }}" style="color:#d97706;font-weight:600;text-decoration:none;">Daftar gratis →</a>
                    </p>
                </div>

                <div style="display:flex;flex-direction:column;gap:0.5rem;padding-top:1rem;border-top:1px solid #e2e8f0;">
                    <div class="check-row">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Kamar tersedia untuk booking
                    </div>
                    <div class="check-row">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Booking mudah & cepat
                    </div>
                    <div class="check-row">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Sistem pembayaran fleksibel
                    </div>
                </div>
            </div>

            <!-- Register Prompt -->
            <div style="background:rgba(217,119,6,0.05);border:1px solid rgba(217,119,6,0.2);border-radius:14px;padding:1.1rem;margin-top:0.85rem;">
                <h4 style="font-size:0.86rem;font-weight:700;color:#1e293b;margin-bottom:0.35rem;">Belum punya akun?</h4>
                <p style="font-size:0.75rem;color:#64748b;margin-bottom:0.85rem;">Daftar gratis dan langsung bisa booking kamar favoritmu</p>
                <a href="{{ route('register') }}"
                   style="display:block;text-align:center;font-size:0.84rem;font-weight:700;color:#ffffff;background:linear-gradient(135deg,#f59e0b,#d97706);padding:0.6rem;border-radius:10px;text-decoration:none;">
                    Daftar Sekarang →
                </a>
            </div>

            <!-- Tips Booking -->
            <div class="tips-box" style="margin-top:0.85rem;">
                <h4 style="font-size:0.86rem;font-weight:700;color:#92400e;margin-bottom:0.75rem;display:flex;align-items:center;gap:6px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tips Booking
                </h4>
                <div style="display:flex;flex-direction:column;gap:0.4rem;">
                    <p style="font-size:0.75rem;color:#92400e;">1. Pastikan sudah melihat kondisi kamar secara langsung atau video call</p>
                    <p style="font-size:0.75rem;color:#92400e;">2. Tanyakan detail pembayaran dan deposit yang diperlukan</p>
                    <p style="font-size:0.75rem;color:#92400e;">3. Baca peraturan kos dengan teliti sebelum booking</p>
                    <p style="font-size:0.75rem;color:#92400e;">4. Simpan bukti pembayaran dan perjanjian sewa</p>
                </div>
            </div>

        </div>
        {{-- ═══ END RIGHT COLUMN ═══ --}}

    </div>
</div>

@endsection