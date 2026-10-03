@extends('layouts.user.user')
@section('title', 'Dashboard Admin')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    *, .card, .table, .btn, h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── WELCOME BANNER ── */
    .welcome-banner { background: linear-gradient(135deg, #1269db 0%, #7c3aed 100%); border-radius: 20px; padding: 2rem 2.5rem; position: relative; overflow: hidden; box-shadow: 0 8px 32px rgba(18,105,219,.25); margin-bottom: 1.75rem; }
    .welcome-banner::before { content:''; position:absolute; top:-60px; right:-60px; width:260px; height:260px; border-radius:50%; background:rgba(255,255,255,.07); }
    .welcome-banner::after  { content:''; position:absolute; bottom:-80px; left:-40px; width:300px; height:300px; border-radius:50%; background:rgba(255,255,255,.05); }
    .welcome-banner .z1 { position:relative; z-index:1; }

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
    .bdg-aktif    { background:#dcfce7; color:#15803d; }
    .bdg-nonaktif { background:#f1f5f9; color:#64748b; }
    .bdg-benar    { background:#dcfce7; color:#15803d; }
    .bdg-salah    { background:#fee2e2; color:#dc2626; }
    .bdg-popup    { background:#f5f3ff; color:#7c3aed; }
    .bdg-soal     { background:#fff7ed; color:#c2410c; }

    /* ── LIST ITEMS ── */
    .rank-item { display:flex; align-items:center; gap:14px; padding:12px 0; border-bottom:1px solid #f1f5f9; }
    .rank-item:last-child { border-bottom:none; padding-bottom:0; }
    .rank-no { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; flex-shrink:0; }
    .rank-1 { background:#fef9c3; color:#ca8a04; }
    .rank-2 { background:#f1f5f9; color:#64748b; }
    .rank-3 { background:#fff7ed; color:#c2410c; }
    .rank-n { background:#f8fafc; color:#94a3b8; }

    .vis-item { display:flex; align-items:center; gap:12px; padding:11px 0; border-bottom:1px solid #f1f5f9; }
    .vis-item:last-child { border-bottom:none; }
    .vis-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.85rem; flex-shrink:0; background:#ccfbf1; color:#0d9488; }

    .avatar-mini { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.85rem; font-weight:700; flex-shrink:0; background:#eff6ff; color:#1a73e8; }

    /* ── PROFIL ALERT ── */
    .pending-alert { background:linear-gradient(135deg,#fffbeb,#fef9c3); border:1.5px solid #fde68a; border-radius:14px; padding:18px 22px; margin-bottom:1.75rem; }
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
<div class="container-fluid px-4">

    {{-- ── WELCOME BANNER ── --}}
    <div class="welcome-banner">
        <div class="z1 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="text-white">
                <div style="font-size:.83rem;opacity:.8;margin-bottom:4px;">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
                <h4 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->admin?->nama ?? Auth::user()->username }}! 👋</h4>
                <p class="mb-0" style="font-size:.88rem;opacity:.85;">
                    Dashboard Admin {{ $setting?->nama ?? 'Belajar Mandarin' }} &bull; Pantau pelajar, kosakata, dan pengunjung dalam satu halaman.
                </p>
            </div>
            <div class="text-white text-end" style="font-size:.82rem;opacity:.8;">
                <div><i class="fas fa-clock me-1"></i> {{ now()->format('H:i') }} WIB</div>
                <div class="mt-1"><i class="fas fa-user-shield me-1"></i> Administrator</div>
            </div>
        </div>
    </div>

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
                <div style="font-size:.78rem;color:#a16207;">Lengkapi data diri Anda agar profil admin terisi penuh.</div>
                <div class="progress-slim"><div style="width:{{ $kelengkapan['persen'] }}%"></div></div>
            </div>
        </div>
        <a href="{{ route('admin.profil', ['tab' => 'edit']) }}" class="btn btn-sm px-4"
           style="background:#ca8a04;color:#fff;border-radius:10px;font-size:.82rem;font-weight:600;">
            <i class="fas fa-arrow-right me-1"></i> Lengkapi Sekarang
        </a>
    </div>
    @endif

    {{-- ── STAT CARDS ROW 1 – Pelajar & Pengunjung ── --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-blue">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-blue"><i class="fas fa-user-graduate"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_pelajar'] }}</div>
                        <div class="stat-label">Total Pelajar</div>
                        <div class="stat-sub">{{ $stats['pelajar_aktif'] }} aktif</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-purple">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-purple"><i class="fas fa-language"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_kosakata'] }}</div>
                        <div class="stat-label">Kosakata</div>
                        <div class="stat-sub">{{ $stats['total_kalimat'] }} contoh kalimat</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-orange">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-orange"><i class="fas fa-book-open"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_paragraf'] }}</div>
                        <div class="stat-label">Paragraf</div>
                        <div class="stat-sub">Bahan bacaan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-teal">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-teal"><i class="fas fa-eye"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_visitor'] }}</div>
                        <div class="stat-label">Total Pengunjung</div>
                        <div class="stat-sub">{{ $stats['visitor_hari_ini'] }} hari ini</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-green">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-green"><i class="fas fa-check-double"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['review_hari_ini'] }}</div>
                        <div class="stat-label">Review Hari Ini</div>
                        <div class="stat-sub">Akurasi {{ $stats['akurasi_hari_ini'] }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── STAT CARDS ROW 2 – Master data ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card sc-indigo">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-indigo"><i class="fas fa-layer-group"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_level'] }}</div>
                        <div class="stat-label">Level HSK</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card sc-yellow">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-yellow"><i class="fas fa-tags"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_kategori'] }}</div>
                        <div class="stat-label">Kategori</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card sc-red">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-red"><i class="fas fa-comment-dots"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_kalimat'] }}</div>
                        <div class="stat-label">Contoh Kalimat</div>
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
                    <a href="{{ route('admin.profil') }}" class="quick-link ql-blue">
                        <i class="fas fa-id-card"></i><span>Profil Saya</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.profil', ['tab' => 'edit']) }}" class="quick-link ql-green">
                        <i class="fas fa-pen-to-square"></i><span>Edit Profil</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.profil', ['tab' => 'password']) }}" class="quick-link ql-purple">
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

    {{-- ── ROW: Grafik + Distribusi ── --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card dash-card h-100">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#1a73e8;"></span>
                        Pengunjung per Bulan (12 Bulan Terakhir)
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="chartVisitorBulanan" height="110"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card dash-card mb-4">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#7c3aed;"></span>
                        Kosakata per Level HSK
                    </div>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="chartLevel" height="180"></canvas>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#ea580c;"></span>
                        Status Hafalan Pelajar
                    </div>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="chartStatus" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ── ROW: Pelajar Terbaru + Review Terbaru ── --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card dash-card">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#1a73e8;"></span>
                        Pelajar Terbaru
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table dash-table mb-0">
                            <thead><tr>
                                <th>Pelajar</th>
                                <th>Kontak</th>
                                <th>Status</th>
                                <th>Terdaftar</th>
                            </tr></thead>
                            <tbody>
                            @forelse($pelajar_terbaru as $p)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-mini" style="width:32px;height:32px;">{{ strtoupper(substr($p->nama, 0, 1)) }}</div>
                                        <div>
                                            <div class="fw-semibold" style="color:#1e293b;">{{ $p->nama }}</div>
                                            <div style="font-size:.72rem;color:#94a3b8;">{{ $p->user->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:.82rem;">{{ $p->no_telp ?? '-' }}</td>
                                <td><span class="badge bdg-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                                <td style="font-size:.78rem;color:#64748b;">{{ $p->created_at?->format('d/m/Y') ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4"><div class="empty-sm"><i class="fas fa-inbox mb-2" style="display:block;font-size:1.5rem;"></i>Belum ada pelajar</div></td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card dash-card">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#16a34a;"></span>
                        Aktivitas Review Terbaru
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table dash-table mb-0">
                            <thead><tr>
                                <th>Pelajar</th>
                                <th>Kosakata</th>
                                <th>Sumber</th>
                                <th>Hasil</th>
                                <th>Waktu</th>
                            </tr></thead>
                            <tbody>
                            @forelse($review_terbaru as $r)
                            <tr>
                                <td class="fw-semibold" style="color:#1e293b;">{{ $r->pelajar->nama ?? '-' }}</td>
                                <td>
                                    <span style="font-size:1rem;">{{ $r->kosakata->hanzi ?? '-' }}</span>
                                    <div style="font-size:.71rem;color:#94a3b8;">{{ $r->kosakata->pinyin ?? '' }}</div>
                                </td>
                                <td><span class="badge bdg-{{ $r->sumber }}">{{ ucfirst($r->sumber) }}</span></td>
                                <td>
                                    <span class="badge {{ $r->benar ? 'bdg-benar' : 'bdg-salah' }}">{{ $r->benar ? 'Benar' : 'Salah' }}</span>
                                </td>
                                <td style="font-size:.76rem;color:#64748b;">{{ $r->direview_pada?->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5"><div class="empty-sm"><i class="fas fa-inbox mb-2" style="display:block;font-size:1.5rem;"></i>Belum ada aktivitas review</div></td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── ROW: Kategori + Pelajar Teraktif + Pengunjung ── --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-4">
            <div class="card dash-card h-100">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#7c3aed;"></span>
                        Kategori Kosakata Terbanyak
                    </div>
                </div>
                <div class="card-body">
                    @forelse($kategori_top as $i => $k)
                    <div class="rank-item">
                        <div class="rank-no {{ $i===0?'rank-1':($i===1?'rank-2':($i===2?'rank-3':'rank-n')) }}">{{ $i + 1 }}</div>
                        <div style="flex:1;min-width:0;">
                            <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $k->nama }}</div>
                        </div>
                        <span class="badge" style="background:#f5f3ff;color:#7c3aed;">{{ $k->kosakatas_count }} kata</span>
                    </div>
                    @empty
                    <div class="empty-sm">Belum ada kategori</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card dash-card h-100">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#ea580c;"></span>
                        Pelajar Teraktif
                    </div>
                </div>
                <div class="card-body">
                    @forelse($pelajar_teraktif as $i => $p)
                    <div class="rank-item">
                        <div class="rank-no {{ $i===0?'rank-1':($i===1?'rank-2':($i===2?'rank-3':'rank-n')) }}">{{ $i + 1 }}</div>
                        <div style="flex:1;min-width:0;">
                            <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $p->nama }}</div>
                        </div>
                        <span class="badge" style="background:#f1f5f9;color:#64748b;">{{ $p->riwayat_reviews_count }}x review</span>
                    </div>
                    @empty
                    <div class="empty-sm">Belum ada data</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card dash-card h-100">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#0d9488;"></span>
                        Pengunjung Terbaru
                    </div>
                </div>
                <div class="card-body">
                    @forelse($visitor_terbaru as $v)
                    <div class="vis-item">
                        <div class="vis-icon"><i class="fas fa-globe"></i></div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.82rem;font-weight:600;color:#1e293b;">{{ $v->ip_address }}</div>
                            <div style="font-size:.72rem;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ \Illuminate\Support\Str::limit($v->user_agent ?? '-', 40) }}</div>
                        </div>
                        <div style="font-size:.72rem;color:#64748b;white-space:nowrap;">{{ $v->created_at?->diffForHumans() }}</div>
                    </div>
                    @empty
                    <div class="empty-sm">Belum ada pengunjung</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// ── Data dari Laravel ──────────────────────────────────────────────
const visitorPerBulan  = @json($visitor_per_bulan);
const distribusiLevel  = @json($distribusi_level);
const distribusiStatus = @json($distribusi_status);

const fontOpt = { family: 'Plus Jakarta Sans', size: 11 };

// ── CHART 1: Pengunjung Bulanan (Bar + Line) ──────────────────────
(function () {
    const labels = [], dataTotal = [], dataUnik = [];

    for (let i = 11; i >= 0; i--) {
        const d = new Date();
        d.setDate(1);                       // hindari bug akhir bulan (mis. 31 → bulan 30 hari)
        d.setMonth(d.getMonth() - i);
        const bln = d.getMonth() + 1, thn = d.getFullYear();
        labels.push(d.toLocaleString('id-ID', { month: 'short', year: '2-digit' }));

        const found = visitorPerBulan.find(x => x.bulan == bln && x.tahun == thn);
        dataTotal.push(found ? found.total : 0);
        dataUnik.push(found ? found.unik : 0);
    }

    new Chart(document.getElementById('chartVisitorBulanan'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: 'Total Kunjungan', data: dataTotal, backgroundColor: 'rgba(26,115,232,.15)', borderColor: '#1a73e8', borderWidth: 2, borderRadius: 6 },
                { label: 'Pengunjung Unik (IP)', data: dataUnik, type: 'line', borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,.08)', fill: true, tension: .4, pointBackgroundColor: '#16a34a', pointRadius: 4 }
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
})();

// ── CHART 2: Kosakata per Level HSK (Doughnut) ────────────────────
new Chart(document.getElementById('chartLevel'), {
    type: 'doughnut',
    data: {
        labels: distribusiLevel.map(x => x.label),
        datasets: [{
            data: distribusiLevel.map(x => x.total),
            backgroundColor: ['#1a73e8', '#16a34a', '#7c3aed', '#ea580c', '#ca8a04', '#dc2626', '#94a3b8'],
            hoverOffset: 6, borderWidth: 3, borderColor: '#fff'
        }]
    },
    options: { responsive: true, cutout: '65%', plugins: { legend: { position: 'bottom', labels: { font: fontOpt, padding: 12 } } } }
});

// ── CHART 3: Status Hafalan (Doughnut) ────────────────────────────
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
