@extends('layouts.public')

@section('title', 'Daftarkan Kos Anda - KosSmart')

@push('head')
<style>
    .step-panel { display: none; }
    .step-panel.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity:0; transform:translateY(10px); }
        to   { opacity:1; transform:translateY(0); }
    }

    .input-field {
        width: 100%;
        padding: 0.65rem 1rem;
        background: #ffffff;
        border: 1px solid rgba(15,23,42,0.12);
        border-radius: 10px;
        color: var(--text);
        font-size: 0.9rem;
        font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .input-field::placeholder { color: #9ca3af; }

    .input-field:focus {
        outline: none;
        border-color: var(--amber2);
        box-shadow: 0 0 0 3px rgba(245,158,11,0.1);
    }

    .input-field.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.08);
    }

    .label {
        display: block;
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.4rem;
    }

    .required { color: #ef4444; margin-left: 2px; }

    .error-msg {
        color: #ef4444;
        font-size: 0.78rem;
        margin-top: 0.3rem;
    }

    .upload-zone {
        border: 2px dashed rgba(15,23,42,0.14);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #fafafa;
    }

    .upload-zone:hover {
        border-color: var(--amber2);
        background: rgba(245,158,11,0.03);
    }

    .upload-zone.has-file {
        border-color: var(--green);
        border-style: solid;
        background: rgba(5,150,105,0.03);
    }

    .upload-zone input[type=file] { display: none; }

    .step-indicator { display:flex; align-items:center; gap:0; }

    .step-dot {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        font-weight: 700;
        flex-shrink: 0;
        border: 2px solid rgba(15,23,42,0.14);
        background: transparent;
        color: var(--muted);
        transition: all 0.3s;
    }

    .step-dot.active {
        background: var(--amber2);
        border-color: var(--amber2);
        color: #fff;
    }

    .step-dot.done {
        background: var(--green);
        border-color: var(--green);
        color: #fff;
    }

    .step-line {
        flex: 1;
        height: 2px;
        background: rgba(15,23,42,0.1);
        transition: background 0.3s;
    }

    .step-line.done { background: var(--green); }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 2rem;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-family: inherit;
        font-size: 0.92rem;
        transition: all 0.2s;
        box-shadow: 0 4px 14px rgba(217,119,6,0.18);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(217,119,6,0.28);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 2rem;
        background: transparent;
        color: var(--muted);
        font-weight: 600;
        border-radius: 10px;
        border: 1px solid rgba(15,23,42,0.14);
        cursor: pointer;
        font-family: inherit;
        font-size: 0.92rem;
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        background: rgba(15,23,42,0.04);
        color: var(--text);
        border-color: rgba(15,23,42,0.22);
    }

    .form-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 1.75rem;
        box-shadow: 0 4px 16px rgba(15,23,42,0.05);
    }

    .section-title {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--amber);
        margin-bottom: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>
@endpush

@section('content')
<section style="padding:3rem 1.5rem 5rem;">
<div class="max-w">

    {{-- HEADER --}}
    <div style="text-align:center;margin-bottom:2.5rem;">
        <div style="display:inline-flex;align-items:center;gap:0.4rem;background:rgba(217,119,6,0.08);border:1px solid rgba(217,119,6,0.22);color:var(--amber);font-size:0.76rem;font-weight:700;padding:0.3rem 0.85rem;border-radius:100px;margin-bottom:1rem;">
            <span class="material-symbols-rounded" style="font-size:14px!important;color:#d97706;">auto_awesome</span>
            Pendaftaran Admin Kos
        </div>
        <h1 style="font-size:2rem;font-weight:800;line-height:1.2;margin-bottom:0.5rem;color:var(--text);">Daftarkan Kos Anda</h1>
        <p style="color:var(--muted);font-size:0.9rem;line-height:1.75;">Isi formulir di bawah ini. Data Anda akan diverifikasi oleh tim KosSmart sebelum akun admin diaktifkan.</p>
    </div>

    {{-- STEP INDICATOR --}}
    <div style="margin-bottom:2rem;">
        <div class="step-indicator" id="stepIndicator">
            <div class="step-dot active" id="dot-1">1</div>
            <div class="step-line" id="line-1"></div>
            <div class="step-dot" id="dot-2">2</div>
            <div class="step-line" id="line-2"></div>
            <div class="step-dot" id="dot-3">3</div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:0.5rem;">
            <span style="font-size:0.75rem;font-weight:600;color:var(--amber);">Data Diri</span>
            <span style="font-size:0.75rem;font-weight:600;color:var(--muted);" id="label-2">Data Kos</span>
            <span style="font-size:0.75rem;font-weight:600;color:var(--muted);" id="label-3">Dokumen</span>
        </div>
    </div>

    {{-- SERVER-SIDE ERRORS --}}
    @if ($errors->any())
        <div style="background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.2);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;">
            <div style="display:flex;align-items:center;gap:0.5rem;color:#dc2626;font-weight:700;margin-bottom:0.5rem;font-size:0.9rem;">
                <span class="material-symbols-rounded" style="font-size:18px!important;">error</span>
                Terdapat kesalahan pada form
            </div>
            <ul style="color:#ef4444;font-size:0.82rem;padding-left:1rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <form action="{{ route('admin.registration.store') }}" method="POST" enctype="multipart/form-data" id="regForm">
        @csrf

        {{-- ══════════ STEP 1: DATA DIRI ══════════ --}}
        <div class="step-panel active" id="step-1">
            <div class="form-card">
                <div class="section-title">
                    <span class="material-symbols-rounded" style="font-size:16px!important;">person</span>
                    Data Diri Pemilik / Pengelola
                </div>

                <div style="display:grid;gap:1.1rem;">
                    <div>
                        <label class="label">Nama Lengkap <span class="required"></span></label>
                        <input type="text" name="nama_lengkap"
                            class="input-field @error('nama_lengkap') error @enderror"
                            value="{{ old('nama_lengkap') }}" placeholder="Sesuai KTP">
                        @error('nama_lengkap')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="label">NIK (Nomor Induk Kependudukan) <span class="required"></span></label>
                        <input type="text" name="nik" class="input-field @error('nik') error @enderror"
                            value="{{ old('nik') }}" placeholder="16 digit angka" maxlength="16"
                            oninput="this.value=this.value.replace(/\D/g,'')">
                        @error('nik')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label class="label">Email <span class="required"></span></label>
                            <input type="email" name="email" class="input-field @error('email') error @enderror"
                                value="{{ old('email') }}" placeholder="email@anda.com">
                            @error('email')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="label">No. HP / WhatsApp <span class="required"></span></label>
                            <input type="tel" name="no_hp" class="input-field @error('no_hp') error @enderror"
                                value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                            @error('no_hp')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label class="label">Password <span class="required"></span></label>
                            <input type="password" name="password" id="password"
                                class="input-field @error('password') error @enderror"
                                placeholder="Min. 8 karakter">
                            @error('password')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="label">Konfirmasi Password <span class="required"></span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="input-field" placeholder="Ulangi password">
                            <p class="error-msg" id="passMatchErr" style="display:none;">Password tidak cocok</p>
                        </div>
                    </div>

                    <div style="background:rgba(245,158,11,0.07);border:1px solid rgba(217,119,6,0.18);border-radius:10px;padding:0.75rem 1rem;font-size:0.8rem;color:#92400e;">
                        <span class="material-symbols-rounded" style="font-size:15px!important;margin-right:4px;color:var(--amber);">info</span>
                        Password harus min. 8 karakter, mengandung huruf besar, huruf kecil, dan angka.
                    </div>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;margin-top:1.25rem;">
                <button type="button" class="btn-primary" onclick="goToStep(2)">
                    Lanjut: Data Kos
                    <span class="material-symbols-rounded" style="font-size:18px!important;margin-left:4px;">arrow_forward</span>
                </button>
            </div>
        </div>

        {{-- ══════════ STEP 2: DATA KOS ══════════ --}}
        <div class="step-panel" id="step-2">
            <div class="form-card">
                <div class="section-title">
                    <span class="material-symbols-rounded" style="font-size:16px!important;">apartment</span>
                    Informasi Kos
                </div>

                <div style="display:grid;gap:1.1rem;">
                    <div>
                        <label class="label">Nama Kos <span class="required"></span></label>
                        <input type="text" name="nama_kos"
                            class="input-field @error('nama_kos') error @enderror" value="{{ old('nama_kos') }}"
                            placeholder="Contoh: Kos Putri Melati">
                        @error('nama_kos')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="label">Alamat Lengkap <span class="required"></span></label>
                        <textarea name="alamat" class="input-field @error('alamat') error @enderror" rows="2"
                            placeholder="Nama jalan, nomor rumah, RT/RW">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label class="label">Kecamatan <span class="required"></span></label>
                            <input type="text" name="kecamatan"
                                class="input-field @error('kecamatan') error @enderror"
                                value="{{ old('kecamatan') }}" placeholder="Nama kecamatan">
                            @error('kecamatan')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="label">Kota / Kabupaten <span class="required"></span></label>
                            <input type="text" name="kota"
                                class="input-field @error('kota') error @enderror" value="{{ old('kota') }}"
                                placeholder="Nama kota">
                            @error('kota')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label class="label">Provinsi <span class="required"></span></label>
                            <input type="text" name="provinsi"
                                class="input-field @error('provinsi') error @enderror"
                                value="{{ old('provinsi') }}" placeholder="Nama provinsi">
                            @error('provinsi')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="label">Kode Pos</label>
                            <input type="text" name="kode_pos"
                                class="input-field @error('kode_pos') error @enderror"
                                value="{{ old('kode_pos') }}" placeholder="5 digit" maxlength="10"
                                oninput="this.value=this.value.replace(/\D/g,'')">
                            @error('kode_pos')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label class="label">Telepon Kos</label>
                            <input type="tel" name="telepon_kos"
                                class="input-field @error('telepon_kos') error @enderror"
                                value="{{ old('telepon_kos') }}" placeholder="Nomor telepon kos">
                            @error('telepon_kos')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="label">Email Kos</label>
                            <input type="email" name="email_kos"
                                class="input-field @error('email_kos') error @enderror"
                                value="{{ old('email_kos') }}" placeholder="email@kos.com (opsional)">
                            @error('email_kos')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;justify-content:space-between;margin-top:1.25rem;">
                <button type="button" class="btn-secondary" onclick="goToStep(1)">
                    <span class="material-symbols-rounded" style="font-size:18px!important;margin-right:4px;">arrow_back</span>
                    Kembali
                </button>
                <button type="button" class="btn-primary" onclick="goToStep(3)">
                    Lanjut: Dokumen
                    <span class="material-symbols-rounded" style="font-size:18px!important;margin-left:4px;">arrow_forward</span>
                </button>
            </div>
        </div>

        {{-- ══════════ STEP 3: DOKUMEN ══════════ --}}
        <div class="step-panel" id="step-3">
            <div class="form-card">
                <div class="section-title">
                    <span class="material-symbols-rounded" style="font-size:16px!important;">badge</span>
                    Dokumen Verifikasi
                </div>

                <div style="display:grid;gap:1.5rem;">

                    {{-- KTP --}}
                    <div>
                        <label class="label">Foto KTP <span class="required"></span></label>
                        <div class="upload-zone @error('ktp_foto') error @enderror" id="zone-ktp"
                            onclick="document.getElementById('ktp_foto').click()">
                            <input type="file" id="ktp_foto" name="ktp_foto" accept="image/*,.pdf"
                                onchange="handleUpload(this,'zone-ktp','preview-ktp','name-ktp')">
                            <span class="material-symbols-rounded"
                                style="font-size:36px!important;color:var(--muted);margin-bottom:0.5rem;display:block;">id_card</span>
                            <p style="font-size:0.85rem;font-weight:600;color:var(--text);">Klik untuk upload foto KTP</p>
                            <p style="font-size:0.75rem;color:var(--muted);margin-top:0.25rem;">JPG, PNG, PDF · Maks. 5MB</p>
                            <p id="name-ktp" style="font-size:0.8rem;color:var(--green);font-weight:600;margin-top:0.5rem;display:none;"></p>
                        </div>
                        @error('ktp_foto')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SELFIE KTP --}}
                    <div>
                        <label class="label">Selfie Memegang KTP <span class="required"></span></label>
                        <div class="upload-zone @error('selfie_ktp') error @enderror" id="zone-selfie"
                            onclick="document.getElementById('selfie_ktp').click()">
                            <input type="file" id="selfie_ktp" name="selfie_ktp" accept="image/*"
                                onchange="handleUpload(this,'zone-selfie','preview-selfie','name-selfie')">
                            <span class="material-symbols-rounded"
                                style="font-size:36px!important;color:var(--muted);margin-bottom:0.5rem;display:block;">face</span>
                            <p style="font-size:0.85rem;font-weight:600;color:var(--text);">Foto wajah sambil memegang KTP</p>
                            <p style="font-size:0.75rem;color:var(--muted);margin-top:0.25rem;">JPG, PNG · Maks. 5MB</p>
                            <p id="name-selfie" style="font-size:0.8rem;color:var(--green);font-weight:600;margin-top:0.5rem;display:none;"></p>
                        </div>
                        @error('selfie_ktp')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- TIPE KEPEMILIKAN --}}
                    <div>
                        <label class="label">Status Kepemilikan Kos <span class="required"></span></label>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                            <label style="cursor:pointer;">
                                <input type="radio" name="tipe_kepemilikan" value="pemilik" id="tipe_pemilik"
                                    {{ old('tipe_kepemilikan') === 'pemilik' ? 'checked' : '' }}
                                    style="display:none;" onchange="toggleBuktiLabel()">
                                <div class="tipe-card" id="card-pemilik"
                                    style="background:var(--card);border:2px solid rgba(15,23,42,0.1);border-radius:12px;padding:1rem;text-align:center;transition:all 0.2s;">
                                    <span class="material-symbols-rounded"
                                        style="font-size:28px!important;color:var(--muted);display:block;margin-bottom:0.35rem;">home_work</span>
                                    <div style="font-size:0.85rem;font-weight:700;color:var(--text);">Pemilik Langsung</div>
                                    <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Saya pemilik kos ini</div>
                                </div>
                            </label>
                            <label style="cursor:pointer;">
                                <input type="radio" name="tipe_kepemilikan" value="penyewa" id="tipe_penyewa"
                                    {{ old('tipe_kepemilikan') === 'penyewa' ? 'checked' : '' }}
                                    style="display:none;" onchange="toggleBuktiLabel()">
                                <div class="tipe-card" id="card-penyewa"
                                    style="background:var(--card);border:2px solid rgba(15,23,42,0.1);border-radius:12px;padding:1rem;text-align:center;transition:all 0.2s;">
                                    <span class="material-symbols-rounded"
                                        style="font-size:28px!important;color:var(--muted);display:block;margin-bottom:0.35rem;">handshake</span>
                                    <div style="font-size:0.85rem;font-weight:700;color:var(--text);">Penyewa / Pengelola</div>
                                    <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Saya menyewa dari pemilik</div>
                                </div>
                            </label>
                        </div>
                        @error('tipe_kepemilikan')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- BUKTI KEPEMILIKAN --}}
                    <div>
                        <label class="label" id="bukti-label">Bukti Kepemilikan / Perjanjian Sewa <span class="required"></span></label>
                        <p style="font-size:0.77rem;color:var(--muted);margin-bottom:0.5rem;" id="bukti-hint">
                            Upload sertifikat/SHM jika pemilik, atau perjanjian sewa jika penyewa
                        </p>
                        <div class="upload-zone @error('bukti_kepemilikan') error @enderror" id="zone-bukti"
                            onclick="document.getElementById('bukti_kepemilikan').click()">
                            <input type="file" id="bukti_kepemilikan" name="bukti_kepemilikan"
                                accept="image/*,.pdf"
                                onchange="handleUpload(this,'zone-bukti','preview-bukti','name-bukti')">
                            <span class="material-symbols-rounded"
                                style="font-size:36px!important;color:var(--muted);margin-bottom:0.5rem;display:block;">description</span>
                            <p style="font-size:0.85rem;font-weight:600;color:var(--text);">Klik untuk upload dokumen</p>
                            <p style="font-size:0.75rem;color:var(--muted);margin-top:0.25rem;">JPG, PNG, PDF · Maks. 10MB</p>
                            <p id="name-bukti" style="font-size:0.8rem;color:var(--green);font-weight:600;margin-top:0.5rem;display:none;"></p>
                        </div>
                        @error('bukti_kepemilikan')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NPWP --}}
                    <div>
                        <label class="label">NPWP <span style="color:var(--muted);font-weight:400;">(Opsional)</span></label>
                        <div class="upload-zone" id="zone-npwp"
                            onclick="document.getElementById('npwp').click()">
                            <input type="file" id="npwp" name="npwp" accept="image/*,.pdf"
                                onchange="handleUpload(this,'zone-npwp','preview-npwp','name-npwp')">
                            <span class="material-symbols-rounded"
                                style="font-size:36px!important;color:var(--muted);margin-bottom:0.5rem;display:block;">receipt</span>
                            <p style="font-size:0.85rem;font-weight:600;color:var(--text);">Klik untuk upload NPWP</p>
                            <p style="font-size:0.75rem;color:var(--muted);margin-top:0.25rem;">JPG, PNG, PDF · Maks. 5MB · Opsional</p>
                            <p id="name-npwp" style="font-size:0.8rem;color:var(--green);font-weight:600;margin-top:0.5rem;display:none;"></p>
                        </div>
                        @error('npwp')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- DISCLAIMER --}}
                    <div style="background:rgba(124,58,237,0.05);border:1px solid rgba(124,58,237,0.15);border-radius:10px;padding:1rem;font-size:0.8rem;color:#5b21b6;line-height:1.7;">
                        <span class="material-symbols-rounded" style="font-size:16px!important;margin-right:4px;color:var(--purple);">security</span>
                        <strong>Keamanan Data:</strong> Semua dokumen yang Anda upload disimpan dengan aman dan
                        hanya dapat diakses oleh tim verifikasi KosSmart. Data Anda tidak akan dibagikan kepada
                        pihak ketiga.
                    </div>
                </div>
            </div>

            <div style="display:flex;justify-content:space-between;margin-top:1.25rem;align-items:center;">
                <button type="button" class="btn-secondary" onclick="goToStep(2)">
                    <span class="material-symbols-rounded" style="font-size:18px!important;margin-right:4px;">arrow_back</span>
                    Kembali
                </button>
                <button type="submit" class="btn-primary" id="submitBtn">
                    <span class="material-symbols-rounded" style="font-size:18px!important;margin-right:4px;">send</span>
                    Kirim Pendaftaran
                </button>
            </div>
        </div>

    </form>
</div>
</section>
@endsection

@push('scripts')
<script>
    let currentStep = 1;

    @if ($errors->hasAny(['nama_lengkap', 'nik', 'email', 'no_hp', 'password']))
        currentStep = 1;
    @elseif ($errors->hasAny(['nama_kos', 'alamat', 'kecamatan', 'kota', 'provinsi', 'kode_pos', 'telepon_kos', 'email_kos']))
        currentStep = 2;
    @elseif ($errors->any())
        currentStep = 3;
    @endif

    function goToStep(step) {
        if (step > currentStep && !validateStep(currentStep)) return;
        document.getElementById('step-' + currentStep).classList.remove('active');
        document.getElementById('step-' + step).classList.add('active');
        for (let i = 1; i <= 3; i++) {
            const dot = document.getElementById('dot-' + i);
            dot.classList.remove('active', 'done');
            if (i < step) dot.classList.add('done');
            else if (i === step) dot.classList.add('active');
        }
        for (let i = 1; i <= 2; i++) {
            document.getElementById('line-' + i).classList.toggle('done', i < step);
        }
        ['label-2', 'label-3'].forEach((id, idx) => {
            const el = document.getElementById(id);
            const s = idx + 2;
            el.style.color = s === step ? 'var(--amber)' : (s < step ? 'var(--green)' : 'var(--muted)');
        });
        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(step) {
        if (step === 1) {
            const fields = ['nama_lengkap', 'nik', 'email', 'no_hp', 'password', 'password_confirmation'];
            let valid = true;
            fields.forEach(f => {
                const el = document.querySelector(`[name="${f}"]`);
                if (el && !el.value.trim()) { el.classList.add('error'); valid = false; }
                else if (el) el.classList.remove('error');
            });
            const nik = document.querySelector('[name="nik"]');
            if (nik && nik.value.length !== 16) { nik.classList.add('error'); valid = false; }
            const p = document.getElementById('password').value;
            const pc = document.getElementById('password_confirmation').value;
            const err = document.getElementById('passMatchErr');
            if (p && pc && p !== pc) { err.style.display = 'block'; valid = false; }
            else err.style.display = 'none';
            return valid;
        }
        if (step === 2) {
            const fields = ['nama_kos', 'alamat', 'kecamatan', 'kota', 'provinsi'];
            let valid = true;
            fields.forEach(f => {
                const el = document.querySelector(`[name="${f}"]`);
                if (el && !el.value.trim()) { el.classList.add('error'); valid = false; }
                else if (el) el.classList.remove('error');
            });
            return valid;
        }
        return true;
    }

    function handleUpload(input, zoneId, previewId, nameId) {
        const zone = document.getElementById(zoneId);
        const nameEl = document.getElementById(nameId);
        if (input.files && input.files[0]) {
            zone.classList.add('has-file');
            nameEl.textContent = '✓ ' + input.files[0].name;
            nameEl.style.display = 'block';
        }
    }

    function toggleBuktiLabel() {
        const pemilik = document.getElementById('tipe_pemilik').checked;
        const hint = document.getElementById('bukti-hint');
        const cardP = document.getElementById('card-pemilik');
        const cardW = document.getElementById('card-penyewa');
        hint.textContent = pemilik ?
            'Upload sertifikat tanah, SHM, atau SHGB sebagai bukti kepemilikan' :
            'Upload perjanjian sewa/kontrak yang menunjukkan Anda berhak mengelola kos ini';
        cardP.style.borderColor = pemilik ? 'var(--amber2)' : 'rgba(15,23,42,0.1)';
        cardP.style.background  = pemilik ? 'rgba(245,158,11,0.06)' : 'var(--card)';
        cardW.style.borderColor = !pemilik ? 'var(--amber2)' : 'rgba(15,23,42,0.1)';
        cardW.style.background  = !pemilik ? 'rgba(245,158,11,0.06)' : 'var(--card)';
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (currentStep > 1) goToStep(currentStep);
        toggleBuktiLabel();
        document.querySelectorAll('[name="tipe_kepemilikan"]').forEach(r => r.addEventListener('change', toggleBuktiLabel));
        document.querySelectorAll('.input-field').forEach(el => el.addEventListener('input', () => el.classList.remove('error')));
    });
</script>
@endpush