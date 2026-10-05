@extends('layouts.user.user')
@section('title', 'Dashboard Pelajar')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Noto+Sans+TC:wght@400;500;700&display=swap');
    *, .card, .table, .btn, h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* Hanzi tradisional (zh-TW) */
    .hanzi { font-family: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', sans-serif; }

    /* ── WELCOME BANNER ── */
    .welcome-banner { background: linear-gradient(135deg, #1269db 0%, #7c3aed 100%); border-radius: 20px; padding: 2rem 2.5rem; position: relative; overflow: hidden; box-shadow: 0 8px 32px rgba(18,105,219,.25); margin-bottom: 1.75rem; }
    .welcome-banner::before { content:''; position:absolute; top:-60px; right:-60px; width:260px; height:260px; border-radius:50%; background:rgba(255,255,255,.07); }
    .welcome-banner::after  { content:''; position:absolute; bottom:-80px; left:-40px; width:300px; height:300px; border-radius:50%; background:rgba(255,255,255,.05); }
    .welcome-banner .z1 { position:relative; z-index:1; }
    .streak-chip { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.18); border-radius:10px; padding:5px 12px; font-size:.8rem; font-weight:700; }

    /* ── STAT CARDS ── */
    .stat-card { border:none; border-radius:16px; padding:22px; position:relative; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,.07); transition:transform .2s, box-shadow .2s; }
    .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.12); }
    .stat-card::after { content:''; position:absolute; right:-18px; top:-18px; width:90px; height:90px; border-radius:50%; opacity:.1; }
    .sc-blue   { background:linear-gradient(135deg,#e8f0fe,#dbeafe); } .sc-blue::after   { background:#1a73e8; }
    .sc-green  { background:linear-gradient(135deg,#e6f9f0,#d1fae5); } .sc-green::after  { background:#16a34a; }
    .sc-purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); } .sc-purple::after { background:#7c3aed; }
    .sc-orange { background:linear-gradient(135deg,#fff7ed,#ffedd5); } .sc-orange::after { background:#ea580c; }
    .sc-yellow { background:linear-gradient(135deg,#fffbeb,#fef9c3); } .sc-yellow::after { background:#ca8a04; }
    .sc-red    { background:linear-gradient(135deg,#fef2f2,#fee2e2); } .sc-red::after    { background:#dc2626; }
    .sc-teal   { background:linear-gradient(135deg,#f0fdfa,#ccfbf1); } .sc-teal::after   { background:#0d9488; }
    .sc-indigo { background:linear-gradient(135deg,#eef2ff,#e0e7ff); } .sc-indigo::after { background:#4f46e5; }

    .stat-icon { width:50px; height:50px; border-radius:13px; display:inline-flex; align-items:center; justify-content:center; font-size:1.25rem; flex-shrink:0; }
    .si-blue   { background:#1a73e8; color:#fff; } .si-green  { background:#16a34a; color:#fff; }
    .si-purple { background:#7c3aed; color:#fff; } .si-orange { background:#ea580c; color:#fff; }
    .si-yellow { background:#ca8a04; color:#fff; } .si-red    { background:#dc2626; color:#fff; }
    .si-teal   { background:#0d9488; color:#fff; } .si-indigo { background:#4f46e5; color:#fff; }

    .stat-value { font-size:1.9rem; font-weight:800; line-height:1; color:#1e293b; }
    .stat-label { font-size:.73rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#64748b; margin-top:4px; }
    .stat-sub   { font-size:.75rem; color:#94a3b8; margin-top:3px; }

    /* ── SECTION HEADER ── */
    .sec-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.1rem; }
    .sec-title { font-size:.95rem; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px; }
    .sec-title-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }

    /* ── CARDS ── */
    .dash-card { border:none; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; }
    .dash-card .card-header { background:#fff; border-bottom:1px solid #f1f5f9; padding:16px 22px; }
    .dash-card .card-body { padding:22px; }

    /* ── TABLE ── */
    .dash-table thead th { background:#f8fafc; color:#64748b; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.6px; padding:11px 16px; border-bottom:2px solid #e2e8f0; border-top:none; white-space:nowrap; }
    .dash-table tbody td { padding:12px 16px; vertical-align:middle; font-size:.84rem; color:#334155; border-bottom:1px solid #f1f5f9; }
    .dash-table tbody tr:last-child td { border-bottom:none; }
    .dash-table tbody tr:hover td { background:#f8fafc; }

    /* ── BADGES ── */
    .badge { font-size:.68rem; font-weight:600; padding:4px 9px; border-radius:6px; letter-spacing:.2px; }
    .bdg-benar    { background:#dcfce7; color:#15803d; }
    .bdg-salah    { background:#fee2e2; color:#dc2626; }
    .bdg-popup    { background:#f5f3ff; color:#7c3aed; }
    .bdg-soal     { background:#fff7ed; color:#c2410c; }
    .bdg-lulus    { background:#dcfce7; color:#15803d; }
    .bdg-gagal    { background:#fee2e2; color:#dc2626; }
    .bdg-netral   { background:#f1f5f9; color:#64748b; }
    .bdg-ingat_sepenuhnya { background:#dcfce7; color:#15803d; }
    .bdg-lupa_dan_ingat   { background:#fef9c3; color:#a16207; }
    .bdg-berikutnya       { background:#f1f5f9; color:#64748b; }

    /* ── LIST ITEMS ── */
    .rank-item { display:flex; align-items:center; gap:14px; padding:12px 0; border-bottom:1px solid #f1f5f9; }
    .rank-item:last-child { border-bottom:none; padding-bottom:0; }
    .rank-no { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; flex-shrink:0; }
    .rank-1 { background:#fef9c3; color:#ca8a04; }
    .rank-2 { background:#f1f5f9; color:#64748b; }
    .rank-3 { background:#fff7ed; color:#c2410c; }
    .rank-n { background:#f8fafc; color:#94a3b8; }

    .word-box { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; font-weight:500; flex-shrink:0; background:#eff6ff; color:#1a73e8; }

    /* ── PROGRES LEVEL ── */
    .level-row { padding:12px 0; border-bottom:1px solid #f1f5f9; }
    .level-row:last-child { border-bottom:none; padding-bottom:0; }
    .level-bar { height:8px; border-radius:8px; background:#e2e8f0; overflow:hidden; display:flex; margin-top:7px; }
    .level-bar .lb-ingat { background:#16a34a; }
    .level-bar .lb-lupa  { background:#ca8a04; }

    /* ── ALERT ── */
    .pending-alert { background:linear-gradient(135deg,#fffbeb,#fef9c3); border:1.5px solid #fde68a; border-radius:14px; padding:18px 22px; margin-bottom:1.75rem; }
    .review-alert  { background:linear-gradient(135deg,#e6f9f0,#d1fae5); border:1.5px solid #a7f3d0; border-radius:14px; padding:18px 22px; margin-bottom:1.75rem; }
    .progress-slim { height:6px; border-radius:6px; background:#fde68a; overflow:hidden; width:220px; max-width:100%; margin-top:8px; }
    .progress-slim > div { height:100%; background:#ca8a04; border-radius:6px; }

    /* ── QUICK LINK ── */
    .quick-link { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:18px 10px; border-radius:14px; text-decoration:none; transition:all .2s; border:1.5px solid transparent; gap:8px; }
    .quick-link:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(0,0,0,.1); border-color:rgba(255,255,255,.3); color:#fff; }
    .quick-link i { font-size:1.4rem; }
    .quick-link span { font-size:.78rem; font-weight:700; }
    .ql-blue   { background:linear-gradient(135deg,#1a73e8,#1558b0); color:#fff; }
    .ql-green  { background:linear-gradient(135deg,#16a34a,#15803d); color:#fff; }
    .ql-purple { background:linear-gradient(135deg,#7c3aed,#6d28d9); color:#fff; }
    .ql-teal   { background:linear-gradient(135deg,#0d9488,#0f766e); color:#fff; }

    .btn-sm-link { font-size:.75rem; color:#1a73e8; text-decoration:none; font-weight:600; }
    .btn-sm-link:hover { text-decoration:underline; }
    .empty-sm { text-align:center; padding:30px 16px; color:#94a3b8; font-size:.82rem; }
</style>
@endsection

@section('content')
@php
    // Link memakai Route::has() supaya halaman tidak error kalau nama route belum dibuat.
    $link = fn (string $nama, array $param = [], string $cadangan = '#') =>
        \Illuminate\Support\Facades\Route::has($nama) ? route($nama, $param) : $cadangan;

    $linkProfil   = $link('pelajar.profil');
    $linkEdit     = $link('pelajar.profil', ['tab' => 'edit']);
    $linkPassword = $link('pelajar.profil', ['tab' => 'password']);
    $linkReview   = $link('pelajar.review', [], url('/'));

    $labelStatus = [
        'ingat_sepenuhnya' => 'Ingat Sepenuhnya',
        'lupa_dan_ingat'   => 'Lupa & Ingat',
        'berikutnya'       => 'Berikutnya',
    ];
@endphp

<div class="container">
<div class="page-inner">

    {{-- ── WELCOME BANNER ── --}}
    <div class="welcome-banner">
        <div class="z1 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="text-white">
                <div style="font-size:.83rem;opacity:.8;margin-bottom:4px;">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
                <h4 class="fw-bold mb-1">Selamat Datang, {{ $pelajar->nama ?? Auth::user()->username }}! 👋</h4>
                <p class="mb-0" style="font-size:.88rem;opacity:.85;">
                    Dashboard Pelajar {{ $setting?->nama ?? 'Belajar Mandarin' }} &bull; Lihat progres hafalan, jadwal review, dan hasil belajarmu.
                </p>
            </div>
            <div class="text-white text-end" style="font-size:.82rem;opacity:.9;">
                <div class="streak-chip mb-2">
                    <i class="fas fa-fire"></i>
                    {{ $stats['streak'] > 0 ? $stats['streak'].' hari beruntun' : 'Mulai streak hari ini' }}
                </div>
                <div style="opacity:.85;"><i class="fas fa-clock me-1"></i> {{ now()->format('H:i') }} WIB</div>
                <div class="mt-1" style="opacity:.85;"><i class="fas fa-user-graduate me-1"></i> Pelajar</div>
            </div>
        </div>
    </div>

    {{-- ── ALERT REVIEW JATUH TEMPO ── --}}
    @if($stats['jatuh_tempo'] > 0)
    <div class="review-alert d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div style="width:42px;height:42px;background:#a7f3d0;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-bell" style="color:#047857;font-size:1.1rem;"></i>
            </div>
            <div>
                <div class="fw-bold" style="color:#047857;font-size:.92rem;">
                    {{ $stats['jatuh_tempo'] }} kata siap direview hari ini.
                </div>
                <div style="font-size:.78rem;color:#059669;">Review sekarang supaya hafalanmu tidak hilang.</div>
            </div>
        </div>
        <a href="{{ $linkReview }}" class="btn btn-sm px-4"
           style="background:#16a34a;color:#fff;border-radius:10px;font-size:.82rem;font-weight:600;">
            <i class="fas fa-arrow-right me-1"></i> Mulai Review
        </a>
    </div>
    @endif

    {{-- ── ALERT PROFIL BELUM LENGKAP ── --}}
    @if($kelengkapan['persen'] < 100)
    <div class="pending-alert d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div style="width:42px;height:42px;background:#fde68a;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-id-card" style="color:#92400e;font-size:1.1rem;"></i>
            </div>
            <div>
                <div class="fw-bold" style="color:#92400e;font-size:.92rem;">
                    Profil Anda baru terisi <strong>{{ $kelengkapan['persen'] }}%</strong>
                    ({{ $kelengkapan['terisi'] }} dari {{ $kelengkapan['total'] }} data).
                </div>
                <div style="font-size:.78rem;color:#a16207;">Lengkapi data diri Anda agar profil pelajar terisi penuh.</div>
                <div class="progress-slim"><div style="width:{{ $kelengkapan['persen'] }}%"></div></div>
            </div>
        </div>
        <a href="{{ $linkEdit }}" class="btn btn-sm px-4"
           style="background:#ca8a04;color:#fff;border-radius:10px;font-size:.82rem;font-weight:600;">
            <i class="fas fa-arrow-right me-1"></i> Lengkapi Sekarang
        </a>
    </div>
    @endif

    {{-- ── STAT CARDS ROW 1 – Hafalan ── --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-green">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-green"><i class="fas fa-circle-check"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['ingat'] }}</div>
                        <div class="stat-label">Sudah Hafal</div>
                        <div class="stat-sub">dari {{ $stats['total_kosakata'] }} kosakata</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-yellow">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-yellow"><i class="fas fa-rotate"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['lupa'] }}</div>
                        <div class="stat-label">Lupa &amp; Ingat</div>
                        <div class="stat-sub">perlu diulang</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-blue">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-blue"><i class="fas fa-forward"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['berikutnya'] }}</div>
                        <div class="stat-label">Berikutnya</div>
                        <div class="stat-sub">antrean belajar</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-teal">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-teal"><i class="fas fa-check-double"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['review_hari_ini'] }}</div>
                        <div class="stat-label">Review Hari Ini</div>
                        <div class="stat-sub">Akurasi {{ $stats['akurasi_hari_ini'] }}%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-red">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-red"><i class="fas fa-bell"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['jatuh_tempo'] }}</div>
                        <div class="stat-label">Siap Direview</div>
                        <div class="stat-sub">jadwal sudah tiba</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── STAT CARDS ROW 2 – Ringkasan belajar ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card sc-orange">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-orange"><i class="fas fa-fire"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['streak'] }}</div>
                        <div class="stat-label">Hari Beruntun</div>
                        <div class="stat-sub">belajar tiap hari</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card sc-indigo">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-indigo"><i class="fas fa-repeat"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_review'] }}</div>
                        <div class="stat-label">Total Review</div>
                        <div class="stat-sub">Akurasi {{ $stats['akurasi_total'] }}%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card sc-purple">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-purple"><i class="fas fa-file-pen"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_ujian'] }}</div>
                        <div class="stat-label">Latihan &amp; Ujian</div>
                        <div class="stat-sub">Rata-rata skor {{ $stats['rata_skor'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card sc-blue">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-blue"><i class="fas fa-percent"></i></div>
                    <div>
                        <div class="stat-value">{{ $kelengkapan['persen'] }}%</div>
                        <div class="stat-label">Profil Terisi</div>
                        <div class="stat-sub">{{ $kelengkapan['terisi'] }}/{{ $kelengkapan['total'] }} data</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── QUICK LINKS ── --}}
    <div class="card dash-card mb-4">
        <div class="card-body p-4">
            <div class="sec-header mb-3">
                <div class="sec-title">
                    <span class="sec-title-dot" style="background:#1a73e8;"></span>
                    Akses Cepat
                </div>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <a href="{{ $linkProfil }}" class="quick-link ql-blue">
                        <i class="fas fa-id-card"></i><span>Profil Saya</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ $linkEdit }}" class="quick-link ql-green">
                        <i class="fas fa-pen-to-square"></i><span>Edit Profil</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ $linkPassword }}" class="quick-link ql-purple">
                        <i class="fas fa-lock"></i><span>Ubah Password</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ url('/') }}" target="_blank" class="quick-link ql-teal">
                        <i class="fas fa-up-right-from-square"></i><span>Buka Website</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── ROW: Grafik aktivitas + Status hafalan ── --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card dash-card h-100">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#1a73e8;"></span>
                        Aktivitas Review (14 Hari Terakhir)
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="chartAktivitas" height="110"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card dash-card h-100">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#ea580c;"></span>
                        Status Hafalan Saya
                    </div>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="chartStatus" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ── ROW: Progres Level HSK + Kata Siap Review ── --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card dash-card h-100">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#7c3aed;"></span>
                        Progres per Level HSK
                    </div>
                </div>
                <div class="card-body">
                    @forelse($progres_level as $lv)
                    <div class="level-row">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;">{{ $lv['nama'] }}</div>
                            <div style="font-size:.75rem;color:#64748b;">
                                {{ $lv['ingat'] }}/{{ $lv['total'] }} kata &bull; <strong style="color:#15803d;">{{ $lv['persen'] }}%</strong>
                            </div>
                        </div>
                        <div class="level-bar">
                            <div class="lb-ingat" style="width:{{ $lv['persen'] }}%"></div>
                            <div class="lb-lupa"  style="width:{{ $lv['persen_lupa'] }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-sm">Belum ada level HSK</div>
                    @endforelse

                    @if(count($progres_level))
                    <div class="d-flex gap-3 mt-3" style="font-size:.72rem;color:#64748b;">
                        <span><i class="fas fa-square me-1" style="color:#16a34a;"></i>Ingat sepenuhnya</span>
                        <span><i class="fas fa-square me-1" style="color:#ca8a04;"></i>Lupa &amp; ingat</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card dash-card h-100">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="sec-title">
                            <span class="sec-title-dot" style="background:#dc2626;"></span>
                            Kata Siap Direview
                        </div>
                        @if($stats['jatuh_tempo'] > 0)
                        <a href="{{ $linkReview }}" class="btn-sm-link">Mulai review</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @forelse($kata_siap_review as $p)
                    <div class="rank-item">
                        <div class="word-box hanzi" lang="zh-TW">{{ $p->kosakata->hanzi ?? '-' }}</div>
                        <div style="flex:1;min-width:0;">
                            <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;">{{ $p->kosakata->pinyin ?? '-' }}</div>
                            <div style="font-size:.74rem;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $p->kosakata->arti_indonesia ?? '' }}</div>
                        </div>
                        <span class="badge bdg-{{ $p->status }}">{{ $labelStatus[$p->status] ?? $p->status }}</span>
                    </div>
                    @empty
                    <div class="empty-sm"><i class="fas fa-circle-check mb-2" style="display:block;font-size:1.5rem;"></i>Tidak ada kata yang perlu direview sekarang</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ── ROW: Review Terbaru + Kata Sering Salah ── --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card dash-card h-100">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#16a34a;"></span>
                        Riwayat Review Terbaru
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table dash-table mb-0">
                            <thead><tr>
                                <th>Kosakata</th>
                                <th>Arti</th>
                                <th>Sumber</th>
                                <th>Hasil</th>
                                <th>Waktu</th>
                            </tr></thead>
                            <tbody>
                            @forelse($review_terbaru as $r)
                            <tr>
                                <td>
                                    <span class="hanzi" lang="zh-TW" style="font-size:1rem;">{{ $r->kosakata->hanzi ?? '-' }}</span>
                                    <div style="font-size:.71rem;color:#94a3b8;">{{ $r->kosakata->pinyin ?? '' }}</div>
                                </td>
                                <td style="font-size:.82rem;">{{ \Illuminate\Support\Str::limit($r->kosakata->arti_indonesia ?? '-', 28) }}</td>
                                <td><span class="badge bdg-{{ $r->sumber }}">{{ ucfirst($r->sumber) }}</span></td>
                                <td>
                                    <span class="badge {{ $r->benar ? 'bdg-benar' : 'bdg-salah' }}">{{ $r->benar ? 'Benar' : 'Salah' }}</span>
                                </td>
                                <td style="font-size:.76rem;color:#64748b;">{{ $r->direview_pada?->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5"><div class="empty-sm"><i class="fas fa-inbox mb-2" style="display:block;font-size:1.5rem;"></i>Belum ada riwayat review</div></td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card dash-card h-100">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#ea580c;"></span>
                        Kata yang Sering Salah
                    </div>
                </div>
                <div class="card-body">
                    @forelse($kata_sulit as $i => $k)
                    <div class="rank-item">
                        <div class="rank-no {{ $i===0?'rank-1':($i===1?'rank-2':($i===2?'rank-3':'rank-n')) }}">{{ $i + 1 }}</div>
                        <div style="flex:1;min-width:0;">
                            <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                <span class="hanzi" lang="zh-TW">{{ $k->kosakata->hanzi ?? '-' }}</span>
                                <span style="font-weight:500;color:#94a3b8;font-size:.76rem;">&nbsp;{{ $k->kosakata->pinyin ?? '' }}</span>
                            </div>
                            <div style="font-size:.72rem;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $k->kosakata->arti_indonesia ?? '' }}</div>
                        </div>
                        <span class="badge bdg-salah">{{ $k->salah }}x salah</span>
                    </div>
                    @empty
                    <div class="empty-sm">Belum ada jawaban yang salah</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ── ROW: Hasil Latihan & Ujian ── --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card dash-card">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#7c3aed;"></span>
                        Hasil Latihan &amp; Ujian Terbaru
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table dash-table mb-0">
                            <thead><tr>
                                <th>Judul</th>
                                <th>Jenis</th>
                                <th>Benar</th>
                                <th>Skor</th>
                                <th>Hasil</th>
                                <th>Dikerjakan</th>
                            </tr></thead>
                            <tbody>
                            @forelse($hasil_ujian_terbaru as $h)
                            <tr>
                                <td class="fw-semibold" style="color:#1e293b;">{{ $h->grupSoal->judul ?? '-' }}</td>
                                <td><span class="badge {{ ($h->grupSoal?->jenis) === 'ujian' ? 'bdg-soal' : 'bdg-popup' }}">{{ ucfirst($h->grupSoal->jenis ?? '-') }}</span></td>
                                <td>{{ $h->jumlah_benar }}/{{ $h->jumlah_soal }}</td>
                                <td class="fw-semibold" style="color:#1e293b;">{{ rtrim(rtrim(number_format((float) $h->skor, 2), '0'), '.') }}</td>
                                <td>
                                    @if($h->lulus === null)
                                        <span class="badge bdg-netral">Selesai</span>
                                    @elseif($h->lulus)
                                        <span class="badge bdg-lulus">Lulus</span>
                                    @else
                                        <span class="badge bdg-gagal">Belum lulus</span>
                                    @endif
                                </td>
                                <td style="font-size:.76rem;color:#64748b;">{{ $h->mulai_pada?->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6"><div class="empty-sm"><i class="fas fa-inbox mb-2" style="display:block;font-size:1.5rem;"></i>Belum ada latihan atau ujian yang dikerjakan</div></td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- end .page-inner --}}
</div>{{-- end .container --}}
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// ── Data dari Laravel ──────────────────────────────────────────────
const aktivitasReview  = @json($aktivitas_review);
const distribusiStatus = @json($distribusi_status);

const fontOpt = { family: 'Plus Jakarta Sans', size: 11 };

// ── CHART 1: Aktivitas Review Harian (Bar + Line) ─────────────────
new Chart(document.getElementById('chartAktivitas'), {
    type: 'bar',
    data: {
        labels: aktivitasReview.map(x => x.label),
        datasets: [
            { label: 'Total Review', data: aktivitasReview.map(x => x.total), backgroundColor: 'rgba(26,115,232,.15)', borderColor: '#1a73e8', borderWidth: 2, borderRadius: 6 },
            { label: 'Jawaban Benar', data: aktivitasReview.map(x => x.benar), type: 'line', borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,.08)', fill: true, tension: .4, pointBackgroundColor: '#16a34a', pointRadius: 4 }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'top', labels: { font: fontOpt, padding: 16 } } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0, font: { size: 10 } }, grid: { color: '#f1f5f9' } },
            x: { ticks: { font: { size: 10 } }, grid: { color: '#f8fafc' } }
        }
    }
});

// ── CHART 2: Status Hafalan (Doughnut) ────────────────────────────
new Chart(document.getElementById('chartStatus'), {
    type: 'doughnut',
    data: {
        labels: ['Ingat Sepenuhnya', 'Lupa & Ingat', 'Berikutnya'],
        datasets: [{
            data: [
                distribusiStatus.ingat_sepenuhnya || 0,
                distribusiStatus.lupa_dan_ingat   || 0,
                distribusiStatus.berikutnya       || 0
            ],
            backgroundColor: ['#16a34a', '#ca8a04', '#94a3b8'],
            hoverOffset: 6, borderWidth: 3, borderColor: '#fff'
        }]
    },
    options: { responsive: true, cutout: '65%', plugins: { legend: { position: 'bottom', labels: { font: fontOpt, padding: 12 } } } }
});
</script>
@endsection
