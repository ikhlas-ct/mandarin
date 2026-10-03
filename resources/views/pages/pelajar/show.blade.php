@extends('layouts.user.user')

@section('title', 'Detail Pelajar')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, .table, .btn, h4, h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ===== PAGE HEADER ===== */
    .ph-card { background: #fff; border: 1px solid #e9ecef; border-radius: 14px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 1.25rem; position: relative; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 14px 0 0 14px; }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0; }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li + li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }
    .ph-card.show-page::before { background: #0369a1; }
    .ph-icon.show { background: #e0f2fe; color: #0369a1; }

    /* ===== STAT CARDS ===== */
    .stat-card { border: none; border-radius: 16px; padding: 20px; position: relative; overflow: hidden; transition: transform .2s ease, box-shadow .2s ease; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
    .stat-card::after { content: ''; position: absolute; right: -18px; top: -18px; width: 80px; height: 80px; border-radius: 50%; opacity: .12; }
    .stat-card.blue   { background: linear-gradient(135deg, #e8f0fe 0%, #dbeafe 100%); } .stat-card.blue::after   { background: #1a73e8; }
    .stat-card.green  { background: linear-gradient(135deg, #e6f9f0 0%, #d1fae5 100%); } .stat-card.green::after  { background: #16a34a; }
    .stat-card.orange { background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); } .stat-card.orange::after { background: #ea580c; }
    .stat-card.purple { background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); } .stat-card.purple::after { background: #7c3aed; }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; color: #fff; }
    .stat-icon.blue { background: #1a73e8; } .stat-icon.green { background: #16a34a; }
    .stat-icon.orange { background: #ea580c; } .stat-icon.purple { background: #7c3aed; }
    .stat-value { font-size: 1.85rem; font-weight: 800; line-height: 1; color: #1e293b; }
    .stat-label { font-size: .78rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: #64748b; margin-top: 3px; }

    /* ===== CARD & FILTER ===== */
    .filter-card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); overflow: hidden; }
    .filter-card .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; padding: 18px 24px; }
    .filter-card .card-header h5 { font-size: .95rem; font-weight: 700; color: #1e293b; }
    .filter-section { background: #fafbfc; border-bottom: 1px solid #f1f5f9; padding: 16px 24px; }
    .input-group .input-group-text { background: #f8fafc; border: 1.5px solid #e2e8f0; border-right: none; border-radius: 10px 0 0 10px; color: #94a3b8; font-size: .8rem; }
    .input-group .form-control { border-left: none; border-radius: 0 10px 10px 0; }

    /* ===== FORM ===== */
    label { font-size: .875rem; font-weight: 500; }
    .required-mark { color: #dc3545; }
    .section-divider { background: #f8f9fa; border-left: 4px solid #1269db; padding: 8px 14px; border-radius: 0 6px 6px 0; font-weight: 600; font-size: .9rem; color: #1269db; margin-bottom: 1rem; }
    .form-card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .form-control, .form-select { border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: .83rem; padding: 7px 12px; color: #334155; background-color: #f8fafc; transition: border-color .2s, box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color: #1a73e8; background: #fff; box-shadow: 0 0 0 3px rgba(26,115,232,.12); }
    .form-control::placeholder { color: #94a3b8; }
    .foto-preview { width: 120px; height: 120px; border-radius: 16px; object-fit: cover; border: 1px solid #e2e8f0; background: #f1f5f9; }

    /* ===== INFO ITEM ===== */
    .info-item { padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
    .info-item:last-child { border-bottom: none; padding-bottom: 0; }
    .info-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; }
    .info-value { font-size: .88rem; font-weight: 500; color: #1e293b; margin-top: 2px; word-break: break-word; }

    /* ===== TABLE ===== */
    .table thead th { background: #f8fafc; color: #64748b; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; padding: 12px 16px; border-bottom: 2px solid #e2e8f0; border-top: none; white-space: nowrap; }
    .table tbody td { padding: 13px 16px; vertical-align: middle; font-size: .85rem; color: #334155; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:last-child td { border-bottom: none; }
    .table-hover tbody tr:hover td { background: #f8fafc; }
    .avatar-img { width: 38px; height: 38px; border-radius: 10px; object-fit: cover; flex-shrink: 0; background: #e8f0fe; }
    .name-text { font-weight: 600; font-size: .85rem; color: #1e293b; }
    .sub-text { font-size: .75rem; color: #94a3b8; }
    .hanzi-text { font-size: 1.25rem; font-weight: 600; color: #1e293b; }

    /* ===== BADGES ===== */
    .badge { font-size: .7rem; font-weight: 600; padding: 4px 9px; border-radius: 6px; letter-spacing: .2px; }
    .badge-aktif    { background: #dcfce7; color: #15803d; }
    .badge-nonaktif { background: #f1f5f9; color: #64748b; }
    .badge-ingat    { background: #dcfce7; color: #15803d; }
    .badge-lupa     { background: #ffedd5; color: #c2410c; }
    .badge-next     { background: #e8f0fe; color: #1a73e8; }

    /* ===== BUTTONS ===== */
    .btn-primary { background: linear-gradient(135deg, #1a73e8, #1558b0); border: none; border-radius: 10px; font-weight: 600; font-size: .83rem; padding: 8px 18px; box-shadow: 0 2px 8px rgba(26,115,232,.35); transition: all .2s ease; }
    .btn-primary:hover { background: linear-gradient(135deg, #1558b0, #0f3e82); box-shadow: 0 4px 14px rgba(26,115,232,.45); transform: translateY(-1px); }
    .btn-outline-secondary { border-radius: 10px; font-size: .83rem; border-color: #e2e8f0; color: #64748b; padding: 7px 12px; }
    .btn-outline-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }

    /* ===== EMPTY STATE ===== */
    .empty-state { padding: 60px 20px; }
    .empty-state-icon { width: 72px; height: 72px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.6rem; color: #94a3b8; }
</style>
@endsection

@section('content')
@php
    $labelStatus = [
        'ingat_sepenuhnya' => ['Ingat Sepenuhnya', 'badge-ingat'],
        'lupa_dan_ingat'   => ['Lupa & Ingat', 'badge-lupa'],
        'berikutnya'       => ['Berikutnya', 'badge-next'],
    ];
@endphp
    <div class="container">

        {{-- Header – di LUAR page-inner --}}
        <div class="ph-card show-page">
            <div class="ph-left">
                <div class="ph-icon show"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <h5 class="ph-title">{{ $pelajar->nama }}</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.pelajar.index') }}">Pelajar</a></li>
                        <li><span class="bc-active">Detail</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.pelajar.edit', $pelajar) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-pencil-alt me-1"></i> Edit
                </a>
                <a href="{{ route('admin.pelajar.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="page-inner">
            <div class="row g-4">

                {{-- Info pelajar --}}
                <div class="col-lg-4">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <img src="{{ $pelajar->foto_url }}" alt="{{ $pelajar->nama }}" class="foto-preview">
                                <div class="mt-2">
                                    <span class="badge {{ $pelajar->status === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                                        {{ ucfirst($pelajar->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Informasi Pelajar</div>
                            <div class="info-item">
                                <div class="info-label">Nama</div>
                                <div class="info-value">{{ $pelajar->nama }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">No. Telepon</div>
                                <div class="info-value">{{ $pelajar->no_telp ?: '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Alamat</div>
                                <div class="info-value">{{ $pelajar->alamat ?: '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Keterangan</div>
                                <div class="info-value">{{ $pelajar->keterangan ?: '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Dibuat</div>
                                <div class="info-value">{{ $pelajar->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Terakhir Diubah</div>
                                <div class="info-value">{{ $pelajar->updated_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-key me-2"></i>Akun Login</div>
                            @if ($pelajar->user)
                                <div class="info-item">
                                    <div class="info-label">Username</div>
                                    <div class="info-value">{{ $pelajar->user->username }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Email</div>
                                    <div class="info-value">{{ $pelajar->user->email }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Status Akun</div>
                                    <div class="info-value">{{ ucfirst($pelajar->user->status) }}</div>
                                </div>
                            @else
                                <p class="text-muted mb-0" style="font-size:.83rem;">
                                    Pelajar ini belum punya akun login. Buat lewat halaman Edit.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Progres hafalan --}}
                <div class="col-lg-8">

                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="card stat-card green">
                                <div class="stat-value">{{ $ringkasan['ingat_sepenuhnya'] ?? 0 }}</div>
                                <div class="stat-label">Ingat Sepenuhnya</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card stat-card orange">
                                <div class="stat-value">{{ $ringkasan['lupa_dan_ingat'] ?? 0 }}</div>
                                <div class="stat-label">Lupa &amp; Ingat</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card stat-card blue">
                                <div class="stat-value">{{ $ringkasan['berikutnya'] ?? 0 }}</div>
                                <div class="stat-label">Berikutnya</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card stat-card purple">
                                <div class="stat-value">{{ $totalReview }}</div>
                                <div class="stat-label">Total Review</div>
                            </div>
                        </div>
                    </div>

                    <div class="card filter-card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-language text-primary me-2 opacity-75"></i>Progres Hafalan Kosakata
                            </h5>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table-hover mb-0 table">
                                    <thead>
                                        <tr>
                                            <th width="40">#</th>
                                            <th>Hanzi</th>
                                            <th>Arti</th>
                                            <th>Status</th>
                                            <th>Diulang</th>
                                            <th>Review Berikutnya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($progres as $i => $p)
                                            @php [$label, $kelas] = $labelStatus[$p->status] ?? [$p->status, 'badge-nonaktif']; @endphp
                                            <tr>
                                                <td class="text-muted">{{ $progres->firstItem() + $i }}</td>
                                                <td>
                                                    <span class="hanzi-text">{{ $p->kosakata?->hanzi ?? '-' }}</span>
                                                    <div class="sub-text">{{ $p->kosakata?->pinyin }}</div>
                                                </td>
                                                <td>{{ $p->kosakata?->arti_indonesia ?? '-' }}</td>
                                                <td><span class="badge {{ $kelas }}">{{ $label }}</span></td>
                                                <td>{{ $p->jumlah_ulang }}x
                                                    <div class="sub-text">benar beruntun {{ $p->benar_beruntun }}</div>
                                                </td>
                                                <td class="text-muted">
                                                    {{ $p->review_berikutnya?->translatedFormat('d M Y, H:i') ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="p-0 text-center">
                                                    <div class="empty-state">
                                                        <div class="empty-state-icon"><i class="fas fa-language"></i></div>
                                                        <div class="fw-semibold text-secondary mb-1">Belum ada progres hafalan</div>
                                                        <div class="text-muted" style="font-size:.8rem;">
                                                            Kosakata yang sudah dipelajari pelajar ini akan tampil di sini
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($progres->hasPages())
                            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <small class="text-muted">
                                    Menampilkan
                                    <strong>{{ $progres->firstItem() }}</strong>–<strong>{{ $progres->lastItem() }}</strong>
                                    dari <strong>{{ $progres->total() }}</strong> data
                                </small>
                                {{ $progres->links() }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}
@endsection
