@extends('layouts.user.user')

@section('title', 'Detail - ' . $anakAsuh->nama)

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
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
    }
    .profile-header::after {
        content: '';
        position: absolute;
        bottom: -60px; left: -30px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
    }
    .profile-avatar {
        width: 100px; height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,.5);
        background: #e9ecef;
        flex-shrink: 0;
    }
    .avatar-placeholder-lg {
        width: 100px; height: 100px;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,.4);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; font-weight: 700;
        background: rgba(255,255,255,.15);
        color: #fff;
        flex-shrink: 0;
    }
    .info-label {
        font-size: .75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #6c757d;
        margin-bottom: 2px;
    }
    .info-value {
        font-size: .9rem;
        font-weight: 500;
        color: #212529;
    }
    .info-row { border-bottom: 1px solid #f0f0f0; padding: 10px 0; }
    .info-row:last-child { border-bottom: none; }
    .section-label {
        font-size: .8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #1269db;
        padding: 8px 0 4px;
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 8px;
    }

    /* ===== PAGE HEADER CARD ===== */
    .ph-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        border-radius: 14px 0 0 14px;
    }
    .ph-card.show-page::before { background: #1269db; }

    .ph-left { display: flex; align-items: center; gap: 12px; }

    .ph-icon {
        width: 42px; height: 42px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .ph-icon.show { background: #e8f3ff; color: #1269db; }

    .ph-title {
        font-size: 1.05rem; font-weight: 700;
        color: #1e293b; letter-spacing: -.2px; line-height: 1.2;
        margin: 0;
    }
    .ph-breadcrumb {
        display: flex; align-items: center; gap: 4px;
        flex-wrap: wrap; margin-top: 4px;
        list-style: none; padding: 0; margin-bottom: 0;
    }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li + li::before {
        content: '›';
        color: #cbd5e1;
        font-size: .7rem;
        margin: 0 4px;
    }
    .ph-breadcrumb a          { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover    { text-decoration: underline; }
    .ph-breadcrumb .bc-active  { font-size: .75rem; color: #94a3b8; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Header – di LUAR page-inner, sama seperti index --}}
<div class="ph-card show-page">
    <div class="ph-left">
        <div class="ph-icon show"><i class="fas fa-eye"></i></div>
        <div>
            <h5 class="ph-title">Detail Anak Asuh</h5>
            <ol class="ph-breadcrumb" aria-label="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('anak-asuh.index') }}">Anak Asuh</a></li>
                <li><span class="bc-active">{{ $anakAsuh->nama }}</span></li>
            </ol>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('anak-asuh.edit', ['anakAsuh' => $anakAsuh->id]) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <button class="btn btn-danger btn-sm" id="btn-hapus-detail">
            <i class="fas fa-trash me-1"></i> Hapus
        </button>
        <form id="form-hapus-detail" action="{{ route('anak-asuh.destroy', ['anakAsuh' => $anakAsuh->id]) }}" method="POST" class="d-none">
            @csrf @method('DELETE')
        </form>
        <a href="{{ route('anak-asuh.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

    <div class="page-inner">
        <div class="row g-4">

            {{-- Kolom Kiri: Profil Singkat --}}
            <div class="col-lg-4">
                <div class="card shadow-sm overflow-hidden mb-4">
                    {{-- Header Profil --}}
                    <div class="profile-header text-white">
                        <div class="d-flex align-items-center gap-3 position-relative" style="z-index:1">
                            @if($anakAsuh->foto)
                                <img src="{{ asset('storage/' . $anakAsuh->foto) }}"
                                    alt="{{ $anakAsuh->nama }}" class="profile-avatar">
                            @else
                                <div class="avatar-placeholder-lg">
                                    {{ strtoupper(substr($anakAsuh->nama, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h5 class="fw-bold mb-1">{{ $anakAsuh->nama }}</h5>
                                <div class="opacity-75 small">{{ $anakAsuh->jenis_kelamin_label }}</div>
                                @php
                                    $statusColors = ['aktif' => 'bg-success', 'nonaktif' => 'bg-secondary', 'keluar' => 'bg-danger'];
                                @endphp
                                <span class="badge {{ $statusColors[$anakAsuh->status] ?? 'bg-secondary' }} mt-1">
                                    {{ ucfirst($anakAsuh->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Info Singkat --}}
                    <div class="card-body">
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">Usia</span>
                            <span class="info-value">{{ $anakAsuh->usia ?? '-' }} tahun</span>
                        </div>
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">Panti Asuhan</span>
                            <span class="info-value text-end" style="max-width:60%">{{ $anakAsuh->pantiAsuhan?->nama_panti ?? '-' }}</span>
                        </div>
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">Jenis Tinggal</span>
                            <span class="badge {{ $anakAsuh->jenis_tinggal === 'dalam' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($anakAsuh->jenis_tinggal) }}
                            </span>
                        </div>
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">Status Yatim</span>
                            <span class="info-value">{{ ucwords(str_replace('_', ' ', $anakAsuh->status_yatim)) }}</span>
                        </div>
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">Tanggal Masuk</span>
                            <span class="info-value">{{ $anakAsuh->tanggal_masuk?->translatedFormat('d M Y') ?? '-' }}</span>
                        </div>
                        @if($anakAsuh->tanggal_keluar)
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">Tanggal Keluar</span>
                            <span class="info-value">{{ $anakAsuh->tanggal_keluar->translatedFormat('d M Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Pendidikan Card --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-label"><i class="fas fa-graduation-cap me-2"></i>Pendidikan</div>
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">Jenjang</span>
                            <span class="info-value">{{ $anakAsuh->jenjang_pendidikan ?? '-' }}</span>
                        </div>
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">Nama Sekolah</span>
                            <span class="info-value text-end" style="max-width:60%">{{ $anakAsuh->nama_sekolah ?? '-' }}</span>
                        </div>
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">Kelas</span>
                            <span class="info-value">{{ $anakAsuh->kelas ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Detail Lengkap --}}
            <div class="col-lg-8">

                {{-- Data Diri --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-id-card text-primary me-2"></i>Data Diri Lengkap</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-label">NIK</div>
                                <div class="info-value">{{ $anakAsuh->nik ?? '<span class="text-muted">Belum diisi</span>' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">No. KK</div>
                                <div class="info-value">{{ $anakAsuh->no_kk ?? '<span class="text-muted">Belum diisi</span>' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Tempat, Tanggal Lahir</div>
                                <div class="info-value">
                                    {{ $anakAsuh->tempat_lahir ?? '-' }},
                                    {{ $anakAsuh->tanggal_lahir?->translatedFormat('d F Y') ?? '-' }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Agama</div>
                                <div class="info-value">{{ $anakAsuh->agama ?? '-' }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Jenis Kelamin</div>
                                <div class="info-value">{{ $anakAsuh->jenis_kelamin_label }}</div>
                            </div>
                            <div class="col-md-8">
                                <div class="info-label">Alamat Asal</div>
                                <div class="info-value">{{ $anakAsuh->alamat_asal ?? '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Asal Daerah</div>
                                <div class="info-value">{{ $anakAsuh->asal_daerah ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Data Orang Tua --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-users text-success me-2"></i>Data Orang Tua / Wali</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-label">Nama Ayah</div>
                                <div class="info-value">{{ $anakAsuh->nama_ayah ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Nama Ibu</div>
                                <div class="info-value">{{ $anakAsuh->nama_ibu ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Pekerjaan Orang Tua / Wali</div>
                                <div class="info-value">{{ $anakAsuh->pekerjaan_ortu ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">No. Telepon Wali</div>
                                <div class="info-value">
                                    @if($anakAsuh->no_telp_wali)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $anakAsuh->no_telp_wali) }}" target="_blank">
                                            <i class="fab fa-whatsapp text-success me-1"></i>{{ $anakAsuh->no_telp_wali }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Penerimaan --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-clipboard-check text-warning me-2"></i>Status Penerimaan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="info-label">Status Saat Ini</div>
                                <div class="info-value">
                                    @php
                                        $statusBadge = ['aktif' => 'bg-success', 'nonaktif' => 'bg-secondary', 'keluar' => 'bg-danger'];
                                    @endphp
                                    <span class="badge {{ $statusBadge[$anakAsuh->status] ?? 'bg-secondary' }} fs-6">
                                        {{ ucfirst($anakAsuh->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Jenis Tinggal</div>
                                <div class="info-value">
                                    <span class="badge {{ $anakAsuh->jenis_tinggal === 'dalam' ? 'bg-primary' : 'bg-secondary' }} fs-6">
                                        {{ ucfirst($anakAsuh->jenis_tinggal) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Status Yatim</div>
                                <div class="info-value">{{ ucwords(str_replace('_', ' ', $anakAsuh->status_yatim)) }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Tanggal Masuk</div>
                                <div class="info-value">{{ $anakAsuh->tanggal_masuk?->translatedFormat('d F Y') ?? '-' }}</div>
                            </div>
                            @if($anakAsuh->tanggal_keluar)
                            <div class="col-md-4">
                                <div class="info-label">Tanggal Keluar</div>
                                <div class="info-value">{{ $anakAsuh->tanggal_keluar->translatedFormat('d F Y') }}</div>
                            </div>
                            @endif
                            @if($anakAsuh->alasan_keluar)
                            <div class="col-12">
                                <div class="info-label">Alasan Keluar</div>
                                <div class="info-value">{{ $anakAsuh->alasan_keluar }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($anakAsuh->keterangan)
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-sticky-note text-secondary me-2"></i>Keterangan Tambahan</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted">{{ $anakAsuh->keterangan }}</p>
                    </div>
                </div>
                @endif

                {{-- Metadata --}}
                <div class="text-muted small text-end">
                    Ditambahkan: {{ $anakAsuh->created_at->translatedFormat('d F Y, H:i') }} &nbsp;·&nbsp;
                    Diperbarui: {{ $anakAsuh->updated_at->translatedFormat('d F Y, H:i') }}
                </div>

            </div>
        </div>

    </div>
    </div>{{-- end .page-inner --}}
</div>{{-- end .container --}}
@endsection

@section('scripts')
<script>
    document.getElementById('btn-hapus-detail').addEventListener('click', function () {
        swal({
            title: 'Hapus Data?',
            text: 'Data anak asuh "{{ $anakAsuh->nama }}" akan dihapus permanen dan tidak dapat dikembalikan.',
            icon: 'warning',
            buttons: { cancel: 'Batal', confirm: { text: 'Ya, Hapus', className: 'btn-danger' } },
            dangerMode: true,
        }).then(confirmed => {
            if (confirmed) document.getElementById('form-hapus-detail').submit();
        });
    });
</script>
@endsection
