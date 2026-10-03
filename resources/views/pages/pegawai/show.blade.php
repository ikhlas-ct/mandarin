@extends('layouts.user.user')

@section('title', 'Detail Pegawai – ' . $pegawai->nama)

@section('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #1269db 0%, #0d4fa3 100%);
        border-radius: 12px 12px 0 0;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }
    .profile-header::before {
        content: ''; position: absolute;
        top: -40px; right: -40px;
        width: 180px; height: 180px;
        border-radius: 50%; background: rgba(255,255,255,.07);
    }
    .profile-header::after {
        content: ''; position: absolute;
        bottom: -60px; left: -30px;
        width: 220px; height: 220px;
        border-radius: 50%; background: rgba(255,255,255,.05);
    }

    .profile-avatar {
        width: 100px; height: 100px; border-radius: 50%;
        object-fit: cover; border: 4px solid rgba(255,255,255,.5);
        background: #e9ecef; flex-shrink: 0;
    }
    .avatar-placeholder-lg {
        width: 100px; height: 100px; border-radius: 50%;
        border: 4px solid rgba(255,255,255,.4);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; font-weight: 700;
        background: rgba(255,255,255,.15); color: #fff; flex-shrink: 0;
    }

    .info-label { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #6c757d; margin-bottom: 2px; }
    .info-value { font-size: .9rem; font-weight: 500; color: #212529; }
    .info-row { border-bottom: 1px solid #f0f0f0; padding: 10px 0; }
    .info-row:last-child { border-bottom: none; }
    .section-label {
        font-size: .8rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: #1269db; padding: 8px 0 4px;
        border-bottom: 2px solid #e9ecef; margin-bottom: 8px;
    }

    /* ── Sosmed Buttons ── */
    .btn-sosmed {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 16px; border-radius: 10px; font-size: .83rem;
        font-weight: 600; text-decoration: none; transition: all .15s;
        border: none;
    }
    .btn-ig   { background: #fdf2f8; color: #be185d; }
    .btn-ig:hover   { background: #be185d; color: #fff; }
    .btn-tw   { background: #eff6ff; color: #1d4ed8; }
    .btn-tw:hover   { background: #1d4ed8; color: #fff; }
    .btn-fb   { background: #eff6ff; color: #1e40af; }
    .btn-fb:hover   { background: #1e40af; color: #fff; }

    /* ── Page Header Card ── */
    .ph-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 14px;
        padding: 16px 20px; display: flex; align-items: center;
        justify-content: space-between; gap: 16px; flex-wrap: wrap;
        margin-bottom: 1.25rem; position: relative; overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before {
        content: ''; position: absolute;
        left: 0; top: 0; bottom: 0; width: 4px;
        border-radius: 14px 0 0 14px; background: #1269db;
    }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon {
        width: 42px; height: 42px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0; background: #e8f3ff; color: #1269db;
    }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb {
        display: flex; align-items: center; gap: 4px; flex-wrap: wrap;
        margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0;
    }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li+li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-eye"></i></div>
            <div>
                <h5 class="ph-title">Detail Pegawai</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('pegawai.index') }}">Pegawai</a></li>
                    <li><span class="bc-active">{{ $pegawai->nama }}</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('pegawai.edit', $pegawai->id_pegawai) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <button class="btn btn-danger btn-sm" id="btn-hapus-detail">
                <i class="fas fa-trash me-1"></i> Hapus
            </button>
            <form id="form-hapus-detail" action="{{ route('pegawai.destroy', $pegawai->id_pegawai) }}" method="POST" class="d-none">
                @csrf @method('DELETE')
            </form>
            <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="page-inner">
        <div class="row g-4">

            {{-- ── Kolom Kiri: Kartu Profil ── --}}
            <div class="col-lg-4">
                <div class="card shadow-sm overflow-hidden mb-4">

                    {{-- Header profil --}}
                    <div class="profile-header text-white">
                        <div class="d-flex align-items-center gap-3 position-relative" style="z-index:1">
                            @if($pegawai->foto_profil)
                                <img src="{{ asset('storage/' . $pegawai->foto_profil) }}"
                                     alt="{{ $pegawai->nama }}" class="profile-avatar">
                            @else
                                <div class="avatar-placeholder-lg">
                                    {{ strtoupper(substr($pegawai->nama, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h5 class="fw-bold mb-1">{{ $pegawai->nama }}</h5>
                                <div class="badge bg-white bg-opacity-25 text-white fw-semibold" style="font-size:.78rem;">
                                    {{ $pegawai->posisi }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Info singkat --}}
                    <div class="card-body">
                        <div class="section-label"><i class="fas fa-address-card me-2"></i>Kontak</div>

                        <div class="info-row">
                            <div class="info-label">No. HP / WA</div>
                            <div class="info-value">
                                @if($pegawai->nohp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$pegawai->nohp) }}"
                                       target="_blank" class="text-decoration-none text-dark">
                                        <i class="fab fa-whatsapp text-success me-1"></i>{{ $pegawai->nohp }}
                                    </a>
                                @else
                                    <span class="text-muted">Belum diisi</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-value">
                                @if($pegawai->email)
                                    <a href="mailto:{{ $pegawai->email }}" class="text-decoration-none text-dark">
                                        <i class="fas fa-envelope text-muted me-1"></i>{{ $pegawai->email }}
                                    </a>
                                @else
                                    <span class="text-muted">Belum diisi</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Alamat</div>
                            <div class="info-value">{{ $pegawai->alamat ?: '-' }}</div>
                        </div>

                        {{-- Sosial Media --}}
                        @if($pegawai->instagram || $pegawai->twitter || $pegawai->facebook)
                        <div class="mt-3">
                            <div class="section-label"><i class="fas fa-share-nodes me-2"></i>Media Sosial</div>
                            <div class="d-flex flex-column gap-2 mt-2">
                                @if($pegawai->instagram)
                                    <a href="{{ $pegawai->instagram }}" target="_blank" class="btn-sosmed btn-ig">
                                        <i class="fab fa-instagram"></i> Instagram
                                    </a>
                                @endif
                                @if($pegawai->twitter)
                                    <a href="{{ $pegawai->twitter }}" target="_blank" class="btn-sosmed btn-tw">
                                        <i class="fab fa-twitter"></i> Twitter / X
                                    </a>
                                @endif
                                @if($pegawai->facebook)
                                    <a href="{{ $pegawai->facebook }}" target="_blank" class="btn-sosmed btn-fb">
                                        <i class="fab fa-facebook"></i> Facebook
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Akun User --}}
                        <div class="mt-3">
                            <div class="section-label"><i class="fas fa-key me-2"></i>Akun Login</div>
                            @if($pegawai->user)
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="badge bg-success-subtle text-success fw-semibold">
                                        <i class="fas fa-check me-1"></i>Terhubung
                                    </span>
                                    <span style="font-size:.8rem;" class="text-muted">{{ $pegawai->user->email }}</span>
                                </div>
                                <div class="text-muted mt-1" style="font-size:.75rem;">
                                    Status: <strong>{{ ucfirst($pegawai->user->status) }}</strong>
                                </div>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary fw-semibold mt-2">
                                    <i class="fas fa-minus me-1"></i>Tidak Ada Akun
                                </span>
                            @endif
                        </div>

                    </div>
                </div>

                {{-- Metadata --}}
                <div class="text-muted small text-center">
                    Ditambahkan: {{ $pegawai->created_at->translatedFormat('d F Y') }}<br>
                    Diperbarui: {{ $pegawai->updated_at->translatedFormat('d F Y, H:i') }}
                </div>
            </div>

            {{-- ── Kolom Kanan: Detail Lengkap ── --}}
            <div class="col-lg-8">

                {{-- Info Jabatan --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-briefcase text-primary me-2"></i>Informasi Jabatan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-label">Nama Lengkap</div>
                                <div class="info-value fw-bold">{{ $pegawai->nama }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Posisi / Jabatan</div>
                                <div class="info-value">
                                    <span class="badge" style="background:#eff6ff;color:#1d4ed8;font-size:.85rem;">
                                        {{ $pegawai->posisi }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kontak Lengkap --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-address-book text-success me-2"></i>Kontak Lengkap</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-label">No. HP / WhatsApp</div>
                                <div class="info-value">
                                    @if($pegawai->nohp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$pegawai->nohp) }}"
                                           target="_blank" class="text-decoration-none text-dark">
                                            <i class="fab fa-whatsapp text-success me-1"></i>{{ $pegawai->nohp }}
                                        </a>
                                    @else -
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Email</div>
                                <div class="info-value">
                                    @if($pegawai->email)
                                        <a href="mailto:{{ $pegawai->email }}" class="text-decoration-none text-dark">
                                            <i class="fas fa-envelope text-muted me-1"></i>{{ $pegawai->email }}
                                        </a>
                                    @else -
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-label">Alamat</div>
                                <div class="info-value">{{ $pegawai->alamat ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi / Bio --}}
                @if($pegawai->deskripsi)
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-align-left text-secondary me-2"></i>Deskripsi / Bio</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted" style="line-height:1.7;">{{ $pegawai->deskripsi }}</p>
                    </div>
                </div>
                @endif

                {{-- Media Sosial --}}
                @if($pegawai->instagram || $pegawai->twitter || $pegawai->facebook)
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-share-nodes text-info me-2"></i>Media Sosial</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @if($pegawai->instagram)
                            <div class="col-md-4">
                                <div class="info-label">Instagram</div>
                                <a href="{{ $pegawai->instagram }}" target="_blank" class="text-decoration-none" style="color:#be185d;font-size:.85rem;">
                                    <i class="fab fa-instagram me-1"></i>{{ Str::limit($pegawai->instagram, 30) }}
                                </a>
                            </div>
                            @endif
                            @if($pegawai->twitter)
                            <div class="col-md-4">
                                <div class="info-label">Twitter / X</div>
                                <a href="{{ $pegawai->twitter }}" target="_blank" class="text-decoration-none" style="color:#1d4ed8;font-size:.85rem;">
                                    <i class="fab fa-twitter me-1"></i>{{ Str::limit($pegawai->twitter, 30) }}
                                </a>
                            </div>
                            @endif
                            @if($pegawai->facebook)
                            <div class="col-md-4">
                                <div class="info-label">Facebook</div>
                                <a href="{{ $pegawai->facebook }}" target="_blank" class="text-decoration-none" style="color:#1e40af;font-size:.85rem;">
                                    <i class="fab fa-facebook me-1"></i>{{ Str::limit($pegawai->facebook, 30) }}
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Akun User --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-user-shield text-warning me-2"></i>Akun Login</h6>
                    </div>
                    <div class="card-body">
                        @if($pegawai->user)
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="info-label">Nama Akun</div>
                                    <div class="info-value">{{ $pegawai->user->name }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-label">Email Akun</div>
                                    <div class="info-value">{{ $pegawai->user->email }}</div>
                                </div>
                                <div class="col-md-2">
                                    <div class="info-label">Role</div>
                                    <div class="info-value">{{ $pegawai->user->role }}</div>
                                </div>
                                <div class="col-md-2">
                                    <div class="info-label">Status</div>
                                    <div class="info-value">
                                        <span class="badge {{ $pegawai->user->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($pegawai->user->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                Pegawai ini belum memiliki akun login.
                                <a href="{{ route('pegawai.edit', $pegawai->id_pegawai) }}" class="ms-2">Tambah sekarang →</a>
                            </p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>{{-- end .page-inner --}}
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('btn-hapus-detail').addEventListener('click', function () {
        swal({
            title: 'Hapus Data?',
            text: 'Data pegawai "{{ $pegawai->nama }}" akan dihapus permanen dan tidak dapat dikembalikan.',
            icon: 'warning',
            buttons: { cancel: 'Batal', confirm: { text: 'Ya, Hapus', className: 'btn-danger' } },
            dangerMode: true,
        }).then(confirmed => {
            if (confirmed) document.getElementById('form-hapus-detail').submit();
        });
    });
</script>
@endsection
