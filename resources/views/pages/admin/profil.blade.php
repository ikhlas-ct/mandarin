@extends('layouts.user.user')

@section('title', 'Profil Saya')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, h6, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Hero ── */
    .profile-hero { background: linear-gradient(135deg, #1269db 0%, #7c3aed 100%); border-radius: 20px; padding: 2.5rem 2rem 5rem; position: relative; overflow: hidden; margin-bottom: -60px; box-shadow: 0 8px 32px rgba(18,105,219,.25); }
    .profile-hero::before { content:''; position:absolute; top:-60px; right:-60px; width:240px; height:240px; border-radius:50%; background:rgba(255,255,255,.07); }
    .profile-hero::after  { content:''; position:absolute; bottom:-80px; left:-40px; width:280px; height:280px; border-radius:50%; background:rgba(255,255,255,.05); }
    .hero-content { position: relative; z-index: 1; }


    /* ── Avatar ── */
    .avatar-wrap { position: relative; display: inline-block; }
    .avatar-lg { width: 110px; height: 110px; border-radius: 50%; object-fit: cover; border: 4px solid rgba(255,255,255,.6); box-shadow: 0 4px 20px rgba(0,0,0,.2); background: #e9ecef; }
    .avatar-edit-btn { position: absolute; bottom: 4px; right: 4px; width: 30px; height: 30px; border-radius: 50%; background: #fff; color: #1269db; display: flex; align-items: center; justify-content: center; font-size: .75rem; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,.2); border: 2px solid #fff; transition: all .2s; }
    .avatar-edit-btn:hover { background: #1269db; color: #fff; }
    .foto-preview { width: 80px; height: 80px; border-radius: 14px; object-fit: cover; border: 2px solid #e2e8f0; }

    /* ── Tabs ── */
    .profile-tabs { border: none; gap: 4px; padding: 0 8px; }
    .profile-tabs .nav-link { border: none !important; border-radius: 10px !important; font-size: .83rem; font-weight: 600; color: #64748b; padding: 8px 18px; transition: all .2s; }
    .profile-tabs .nav-link:hover { background: #f1f5f9; color: #1e293b; }
    .profile-tabs .nav-link.active { background: #1269db !important; color: #fff !important; box-shadow: 0 4px 12px rgba(18,105,219,.3); }

    /* ── Cards ── */
    .profile-card { border: none; border-radius: 16px; box-shadow: 0 2px 14px rgba(0,0,0,.07); }
    .profile-card .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; border-radius: 16px 16px 0 0 !important; padding: 16px 24px; font-weight: 700; font-size: .9rem; color: #1e293b; }
    .tabs-card { border: none; border-radius: 16px; box-shadow: 0 2px 14px rgba(0,0,0,.07); overflow: hidden; }
    .tabs-header { background: #fff; border-bottom: 1px solid #f1f5f9; padding: 12px 16px; }
    .tab-content { background: #fff; border-radius: 0 0 16px 16px; }
    .tab-pane { padding: 24px; }

    /* ── Info rows ── */
    .info-item { display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #f8fafc; gap: 12px; }
    .info-item:last-child { border-bottom: none; }
    .info-icon { width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: .8rem; }
    .info-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; }
    .info-value { font-size: .88rem; font-weight: 500; color: #1e293b; margin-top: 2px; word-break: break-word; }

    .section-divider { background: #f8f9fa; border-left: 4px solid #1269db; padding: 7px 13px; border-radius: 0 6px 6px 0; font-weight: 700; font-size: .82rem; color: #1269db; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 8px; }

    /* ── Form ── */
    .form-control, .form-select { border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: .85rem; padding: 9px 13px; color: #334155; background: #f8fafc; transition: border-color .2s, box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color: #1269db; background: #fff; box-shadow: 0 0 0 3px rgba(18,105,219,.12); }
    .form-control::placeholder { color: #b0bec5; }
    label { font-size: .83rem; font-weight: 600; color: #475569; }
    .required-mark { color: #dc3545; }
    .input-group .form-control { border-left: none; border-radius: 0 10px 10px 0; }


    /* ── Password strength ── */
    .strength-bar { height: 4px; border-radius: 2px; background: #e2e8f0; overflow: hidden; margin-top: 6px; }
    .strength-fill { height: 100%; border-radius: 2px; width: 0; transition: width .3s, background .3s; }

    /* ── Sosmed ── */

    .alert { border:none; border-radius: 12px; font-size: .84rem; }
    .alert-success { background:#dcfce7; color:#166534; }
    .alert-danger  { background:#fee2e2; color:#991b1b; }

    /* ── Page header ── */
    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:1.25rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:#1269db; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; background:#e8f3ff; color:#1269db; }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; letter-spacing:-.2px; line-height:1.2; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; flex-wrap:wrap; margin-top:4px; list-style:none; padding:0; margin-bottom:0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    .stat-mini { text-align: center; padding: 14px 10px; border-radius: 12px; }
    .stat-mini-value { font-size: 1.5rem; font-weight: 800; line-height: 1; color: #1e293b; }
    .stat-mini-label { font-size: .7rem; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; color: #64748b; margin-top: 3px; }
</style>
@endsection

@section('content')
@php
    $sudahAda   = $admin->exists;
    $formAction = $sudahAda ? route('admin.profil.update') : route('admin.profil.store');

    $editErrors = ['nama', 'jabatan', 'no_telp', 'alamat', 'keterangan', 'foto'];
    $passErrors = ['current_password', 'password'];
    $hasEditErr = $errors->hasAny($editErrors);
    $hasPassErr = $errors->hasAny($passErrors);

    // Tab aktif: error validasi > session > query ?tab= > default.
    // Jika profil belum pernah diisi, langsung buka tab edit.
    $tabAktif = match (true) {
        $hasEditErr             => 'edit',
        $hasPassErr             => 'password',
        session('tab') !== null => session('tab'),
        request('tab') !== null => request('tab'),
        ! $sudahAda             => 'edit',
        default                 => 'profil',
    };
    $tabActive = fn ($tab) => $tabAktif === $tab;
@endphp

<div class="container">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-user-circle"></i></div>
            <div>
                <h5 class="ph-title">Profil Saya</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><span class="bc-active">Profil Saya</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="page-inner">

    {{-- ── Flash ── --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-check-circle fs-5"></i><span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-exclamation-circle fs-5"></i><span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- ── Hero ── --}}
    <div class="profile-hero">
        <div class="hero-content d-flex align-items-center gap-4 flex-wrap">

            <div class="avatar-wrap">
                <img src="{{ $admin->foto_url }}" alt="{{ $admin->nama ?? $user->username }}" class="avatar-lg">
                @if($sudahAda)
                    <label for="quick-foto-input" class="avatar-edit-btn" title="Ganti foto">
                        <i class="fas fa-camera"></i>
                    </label>
                @endif
            </div>

            <div class="text-white">
                <h3 class="fw-bold mb-1" style="letter-spacing:-.3px;">{{ $admin->nama ?? $user->username }}</h3>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge bg-white bg-opacity-20 text-white fw-semibold" style="font-size:.8rem;">
                        <i class="fas fa-briefcase me-1"></i>{{ $admin->jabatan ?? 'Administrator' }}
                    </span>
                    <span class="badge {{ $user->isAktif() ? 'bg-success' : 'bg-secondary' }} fw-semibold" style="font-size:.8rem;">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
                <div class="mt-2 d-flex align-items-center gap-3 flex-wrap" style="font-size:.82rem;opacity:.85;">
                    <span><i class="fas fa-envelope me-1"></i>{{ $user->email }}</span>
                    @if($admin->no_telp)<span><i class="fas fa-phone me-1"></i>{{ $admin->no_telp }}</span>@endif
                    @if($admin->alamat)<span><i class="fas fa-map-marker-alt me-1"></i>{{ \Illuminate\Support\Str::limit($admin->alamat, 40) }}</span>@endif
                </div>
            </div>
        </div>
    </div>

    {{-- Quick foto upload (hanya jika profil sudah ada) --}}
    @if($sudahAda)
    <form id="quick-foto-form" action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data" class="d-none">
        @csrf @method('PUT')
        <input type="hidden" name="nama" value="{{ $admin->nama }}">
        <input type="file" name="foto" id="quick-foto-input" accept="image/*">
    </form>
    @endif

    <div class="row g-4 mt-1">

        {{-- ── Kolom Kiri ── --}}
        <div class="col-lg-4">

            <div class="card profile-card mb-4">
                <div class="card-body">
                    <div class="row g-2 text-center">
                        <div class="col-6">
                            <div class="stat-mini" style="background:#eff6ff;">
                                <div class="stat-mini-value text-primary">
                                    {{ $admin->created_at ? (int) $admin->created_at->diffInDays(now()) : '-' }}
                                </div>
                                <div class="stat-mini-label">Hari Bergabung</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-mini" style="background:#fdf4ff;">
                                <div class="stat-mini-value" style="color:#7c3aed;">{{ $kelengkapan['persen'] }}%</div>
                                <div class="stat-mini-label">Info Terisi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card profile-card mb-4">
                <div class="card-header"><i class="fas fa-address-card me-2 text-primary"></i>Kontak & Info</div>
                <div class="card-body">
                    <div class="info-item">
                        <div class="info-icon" style="background:#eff6ff;"><i class="fas fa-briefcase text-primary"></i></div>
                        <div>
                            <div class="info-label">Jabatan</div>
                            <div class="info-value">{{ $admin->jabatan ?: '-' }}</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon" style="background:#f0fdf4;"><i class="fas fa-envelope text-success"></i></div>
                        <div>
                            <div class="info-label">Email</div>
                            <div class="info-value">
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none text-dark">{{ $user->email }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon" style="background:#f0fdf4;"><i class="fab fa-whatsapp text-success"></i></div>
                        <div>
                            <div class="info-label">No. Telepon / WhatsApp</div>
                            <div class="info-value">
                                @if($admin->no_telp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $admin->no_telp) }}" target="_blank" class="text-decoration-none text-dark">{{ $admin->no_telp }}</a>
                                @else <span class="text-muted">-</span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon" style="background:#fef2f2;"><i class="fas fa-map-marker-alt text-danger"></i></div>
                        <div>
                            <div class="info-label">Alamat</div>
                            <div class="info-value">{{ $admin->alamat ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Kolom Kanan ── --}}
        <div class="col-lg-8">
            <div class="tabs-card">

                <div class="tabs-header">
                    <ul class="nav profile-tabs" id="profileTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link {{ $tabActive('profil') ? 'active' : '' }}" id="tab-profil"
                                    data-bs-toggle="tab" data-bs-target="#pane-profil" type="button" role="tab">
                                <i class="fas fa-user me-1"></i> Profil
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link {{ $tabActive('edit') ? 'active' : '' }}" id="tab-edit"
                                    data-bs-toggle="tab" data-bs-target="#pane-edit" type="button" role="tab">
                                <i class="fas fa-pen me-1"></i> {{ $sudahAda ? 'Edit Data' : 'Isi Profil' }}
                                @if($hasEditErr)<span class="badge ms-1" style="background:#dc2626;font-size:.65rem;padding:2px 6px;border-radius:6px;">!</span>@endif
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link {{ $tabActive('password') ? 'active' : '' }}" id="tab-pass"
                                    data-bs-toggle="tab" data-bs-target="#pane-pass" type="button" role="tab">
                                <i class="fas fa-lock me-1"></i> Ubah Password
                                @if($hasPassErr)<span class="badge ms-1" style="background:#dc2626;font-size:.65rem;padding:2px 6px;border-radius:6px;">!</span>@endif
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">

                    {{-- ══ TAB 1 – PROFIL ══ --}}
                    <div class="tab-pane fade {{ $tabActive('profil') ? 'show active' : '' }}" id="pane-profil" role="tabpanel">

                        @if(! $sudahAda)
                            <div class="text-center py-5">
                                <i class="fas fa-user-circle mb-3" style="font-size:2.5rem;color:#cbd5e1;"></i>
                                <div class="fw-semibold mb-1" style="color:#1e293b;">Profil Anda belum diisi</div>
                                <div class="text-muted small mb-3">Lengkapi data diri agar profil admin terisi.</div>
                                <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('tab-edit').click()">
                                    <i class="fas fa-pen me-1"></i> Isi Profil Sekarang
                                </button>
                            </div>
                        @else
                            @if($admin->keterangan)
                            <div class="mb-4">
                                <div class="section-divider"><i class="fas fa-align-left"></i> Bio / Keterangan</div>
                                <p class="text-muted" style="line-height:1.75;font-size:.88rem;white-space:pre-line;">{{ $admin->keterangan }}</p>
                            </div>
                            @endif

                            <div class="section-divider"><i class="fas fa-id-card"></i> Data Diri Lengkap</div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6"><div class="info-label">Nama Lengkap</div><div class="info-value">{{ $admin->nama }}</div></div>
                                <div class="col-md-6"><div class="info-label">Jabatan</div><div class="info-value">{{ $admin->jabatan ?: '-' }}</div></div>
                                <div class="col-md-6"><div class="info-label">No. Telepon</div><div class="info-value">{{ $admin->no_telp ?: '-' }}</div></div>
                                <div class="col-md-6"><div class="info-label">Alamat</div><div class="info-value">{{ $admin->alamat ?: '-' }}</div></div>
                            </div>

                            <div class="section-divider"><i class="fas fa-user-shield"></i> Informasi Akun</div>
                            <div class="row g-3">
                                <div class="col-md-4"><div class="info-label">Username</div><div class="info-value">{{ $user->username }}</div></div>
                                <div class="col-md-4"><div class="info-label">Email</div><div class="info-value">{{ $user->email }}</div></div>
                                <div class="col-md-4"><div class="info-label">Role</div><div class="info-value">{{ ucfirst($user->role) }}</div></div>
                            </div>
                        @endif
                    </div>

                    {{-- ══ TAB 2 – EDIT / ISI ══ --}}
                    <div class="tab-pane fade {{ $tabActive('edit') ? 'show active' : '' }}" id="pane-edit" role="tabpanel">

                        @if($hasEditErr)
                            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Terdapat kesalahan pada form. Periksa kembali isian Anda.</span>
                            </div>
                        @endif

                        <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            @if($sudahAda) @method('PUT') @endif

                            {{-- Foto --}}
                            <div class="section-divider"><i class="fas fa-camera"></i> Foto Profil</div>
                            <div class="d-flex align-items-center gap-4 mb-4">
                                <img id="edit-foto-preview" src="{{ $admin->foto_url }}" class="foto-preview" alt="foto">
                                <div>
                                    <input type="file" name="foto" id="edit-foto-input" accept="image/*" class="d-none">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('edit-foto-input').click()">
                                        <i class="fas fa-upload me-1"></i> Pilih Foto Baru
                                    </button>
                                    <div class="text-muted small mt-1">JPG / PNG / WEBP, maks. 2 MB. Kosongkan jika tidak ingin mengubah.</div>
                                    @error('foto')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Data diri --}}
                            <div class="section-divider"><i class="fas fa-id-card"></i> Data Diri</div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap <span class="required-mark">*</span></label>
                                    <input type="text" name="nama" value="{{ old('nama', $admin->nama) }}"
                                           class="form-control @error('nama') is-invalid @enderror" placeholder="Nama lengkap" maxlength="50">
                                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jabatan</label>
                                    <input type="text" name="jabatan" value="{{ old('jabatan', $admin->jabatan) }}"
                                           class="form-control @error('jabatan') is-invalid @enderror" placeholder="mis. Administrator" maxlength="50">
                                    @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="text" value="{{ $user->email }}" class="form-control" disabled>
                                    <div class="text-muted small mt-1">Email mengikuti data akun login.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No. Telepon / WhatsApp</label>
                                    <input type="text" name="no_telp" value="{{ old('no_telp', $admin->no_telp) }}"
                                           class="form-control @error('no_telp') is-invalid @enderror" placeholder="08xxxxxxxxxx" maxlength="20">
                                    @error('no_telp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror"
                                              placeholder="Alamat lengkap">{{ old('alamat', $admin->alamat) }}</textarea>
                                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Bio / Keterangan</label>
                                    <textarea name="keterangan" rows="4" class="form-control @error('keterangan') is-invalid @enderror"
                                              placeholder="Ceritakan singkat tentang Anda">{{ old('keterangan', $admin->keterangan) }}</textarea>
                                    @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> {{ $sudahAda ? 'Simpan Perubahan' : 'Simpan Profil' }}
                            </button>
                        </form>
                    </div>

                    {{-- ══ TAB 3 – PASSWORD ══ --}}
                    <div class="tab-pane fade {{ $tabActive('password') ? 'show active' : '' }}" id="pane-pass" role="tabpanel">

                        @if($hasPassErr)
                            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $errors->first('current_password') ?: $errors->first('password') }}</span>
                            </div>
                        @endif

                        <div class="section-divider"><i class="fas fa-shield-halved"></i> Keamanan Akun</div>

                        <form action="{{ route('admin.profil.password') }}" method="POST" novalidate>
                            @csrf @method('PUT')
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Password Saat Ini <span class="required-mark">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="current_password" id="cur-pass" class="form-control @error('current_password') is-invalid @enderror"
                                               placeholder="Masukkan password lama" style="border-radius:10px 0 0 10px;">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleField('cur-pass','cur-icon')" style="border-radius:0 10px 10px 0;">
                                            <i class="fas fa-eye" id="cur-icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Password Baru <span class="required-mark">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="new-pass" class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Min. 8 karakter" style="border-radius:10px 0 0 10px;" oninput="checkStrength(this.value)">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleField('new-pass','new-icon')" style="border-radius:0 10px 10px 0;">
                                            <i class="fas fa-eye" id="new-icon"></i>
                                        </button>
                                    </div>
                                    <div class="strength-bar"><div class="strength-fill" id="strength-fill"></div></div>
                                    <div id="strength-label" class="text-muted" style="font-size:.72rem;margin-top:3px;"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Konfirmasi Password <span class="required-mark">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="conf-pass" class="form-control"
                                               placeholder="Ulangi password baru" style="border-radius:10px 0 0 10px;">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleField('conf-pass','conf-icon')" style="border-radius:0 10px 10px 0;">
                                            <i class="fas fa-eye" id="conf-icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background:#fffbeb;border:1px solid #fde68a;">
                                        <div class="fw-semibold mb-1" style="font-size:.82rem;color:#92400e;"><i class="fas fa-lightbulb me-1"></i>Syarat Password</div>
                                        <ul class="mb-0 ps-3" style="font-size:.78rem;color:#78350f;line-height:1.8;">
                                            <li>Minimal 8 karakter</li>
                                            <li>Kombinasi huruf besar & kecil</li>
                                            <li>Minimal 1 angka</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-lock me-1"></i> Ganti Password</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>{{-- end tab-content --}}
            </div>{{-- end tabs-card --}}
        </div>

    </div>{{-- end row --}}
    </div>{{-- end page-inner --}}
</div>
@endsection

@section('scripts')
<script>
    const quickInput = document.getElementById('quick-foto-input');
    if (quickInput) {
        quickInput.addEventListener('change', function () {
            if (this.files && this.files[0]) document.getElementById('quick-foto-form').submit();
        });
    }

    document.getElementById('edit-foto-input').addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('edit-foto-preview').src = e.target.result;
            reader.readAsDataURL(this.files[0]);
        }
    });

    function toggleField(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') { input.type = 'text'; icon.classList.replace('fa-eye', 'fa-eye-slash'); }
        else { input.type = 'password'; icon.classList.replace('fa-eye-slash', 'fa-eye'); }
    }

    function checkStrength(val) {
        const fill = document.getElementById('strength-fill');
        const label = document.getElementById('strength-label');
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^a-zA-Z0-9]/.test(val)) score++;
        const levels = [
            { pct: '20%', color: '#dc2626', text: 'Sangat Lemah' },
            { pct: '40%', color: '#ea580c', text: 'Lemah' },
            { pct: '60%', color: '#ca8a04', text: 'Sedang' },
            { pct: '80%', color: '#16a34a', text: 'Kuat' },
            { pct: '100%', color: '#15803d', text: 'Sangat Kuat' },
        ];
        const lv = levels[Math.max(0, score - 1)] || levels[0];
        fill.style.width = val.length ? lv.pct : '0';
        fill.style.background = lv.color;
        label.textContent = val.length ? lv.text : '';
        label.style.color = lv.color;
    }

    @if($errors->any())
        document.addEventListener('DOMContentLoaded', () => {
            const el = document.querySelector('.is-invalid, .invalid-feedback, .alert-danger');
            if (el) setTimeout(() => el.scrollIntoView({ behavior: 'smooth', block: 'center' }), 150);
        });
    @endif
</script>
@endsection
