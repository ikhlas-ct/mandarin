@extends('layouts.user.user')
@section('title', 'Dashboard Admin Dinsos')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    *, .card, .table, .btn, h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── WELCOME BANNER ── */
    .welcome-banner {
        background: linear-gradient(135deg, #1269db 0%, #7c3aed 100%);
        border-radius: 20px; padding: 2rem 2.5rem;
        position: relative; overflow: hidden;
        box-shadow: 0 8px 32px rgba(18,105,219,.25);
        margin-bottom: 1.75rem;
    }
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
    .stat-value.sm { font-size:1.25rem; }
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
    .dash-card .card-footer { background:#f8fafc; border-top:1px solid #f1f5f9; padding:12px 22px; }

    /* ── TABLE ── */
    .dash-table thead th { background:#f8fafc; color:#64748b; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.6px; padding:11px 16px; border-bottom:2px solid #e2e8f0; border-top:none; white-space:nowrap; }
    .dash-table tbody td { padding:12px 16px; vertical-align:middle; font-size:.84rem; color:#334155; border-bottom:1px solid #f1f5f9; }
    .dash-table tbody tr:last-child td { border-bottom:none; }
    .dash-table tbody tr:hover td { background:#f8fafc; }

    /* ── BADGES ── */
    .badge { font-size:.68rem; font-weight:600; padding:4px 9px; border-radius:6px; letter-spacing:.2px; }
    .bdg-pending  { background:#fffbeb; color:#92400e; }
    .bdg-diterima { background:#dcfce7; color:#15803d; }
    .bdg-ditolak  { background:#fee2e2; color:#dc2626; }
    .bdg-uang     { background:#e0f2fe; color:#0369a1; }
    .bdg-barang   { background:#f0fdf4; color:#15803d; }
    .bdg-online   { background:#f5f3ff; color:#7c3aed; }
    .bdg-kunjungan{ background:#fff7ed; color:#c2410c; }
    .bdg-pemasukan  { background:#dcfce7; color:#15803d; }
    .bdg-pengeluaran{ background:#fee2e2; color:#dc2626; }

    /* ── PANTI LIST ── */
    .panti-item { display:flex; align-items:center; gap:14px; padding:12px 0; border-bottom:1px solid #f1f5f9; }
    .panti-item:last-child { border-bottom:none; padding-bottom:0; }
    .panti-rank { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; flex-shrink:0; }
    .rank-1 { background:#fef9c3; color:#ca8a04; }
    .rank-2 { background:#f1f5f9; color:#64748b; }
    .rank-3 { background:#fff7ed; color:#c2410c; }
    .rank-n { background:#f8fafc; color:#94a3b8; }

    /* ── KEUANGAN ITEM ── */
    .keu-item { display:flex; align-items:center; gap:12px; padding:11px 0; border-bottom:1px solid #f1f5f9; }
    .keu-item:last-child { border-bottom:none; }
    .keu-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.85rem; flex-shrink:0; }
    .keu-icon.masuk { background:#dcfce7; color:#15803d; }
    .keu-icon.keluar{ background:#fee2e2; color:#dc2626; }

    /* ── DONATUR ITEM ── */
    .donatur-item { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #f1f5f9; }
    .donatur-item:last-child { border-bottom:none; }
    .donatur-avatar { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.85rem; font-weight:700; flex-shrink:0; background:#eff6ff; color:#1a73e8; }

    /* ── PENDING ALERT ── */
    .pending-alert { background:linear-gradient(135deg,#fffbeb,#fef9c3); border:1.5px solid #fde68a; border-radius:14px; padding:18px 22px; margin-bottom:1.75rem; }

    /* ── QUICK LINK ── */
    .quick-link { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:18px 10px; border-radius:14px; text-decoration:none; transition:all .2s; border:1.5px solid transparent; gap:8px; }
    .quick-link:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(0,0,0,.1); border-color:rgba(255,255,255,.3); }
    .quick-link i { font-size:1.4rem; }
    .quick-link span { font-size:.78rem; font-weight:700; }
    .ql-blue   { background:linear-gradient(135deg,#1a73e8,#1558b0); color:#fff; }
    .ql-green  { background:linear-gradient(135deg,#16a34a,#15803d); color:#fff; }
    .ql-purple { background:linear-gradient(135deg,#7c3aed,#6d28d9); color:#fff; }
    .ql-orange { background:linear-gradient(135deg,#ea580c,#c2410c); color:#fff; }
    .ql-teal   { background:linear-gradient(135deg,#0d9488,#0f766e); color:#fff; }
    .ql-indigo { background:linear-gradient(135deg,#4f46e5,#4338ca); color:#fff; }

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
                <h4 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->pegawai?->nama ?? Auth::user()->username }}! 👋</h4>
                <p class="mb-0" style="font-size:.88rem;opacity:.85;">
                    Dashboard Admin Dinas Sosial &bull; Pantau donasi, keuangan, dan panti asuhan dalam satu halaman.
                </p>
            </div>
            <div class="text-white text-end" style="font-size:.82rem;opacity:.8;">
                <div><i class="fas fa-clock me-1"></i> {{ now()->format('H:i') }} WIB</div>
                <div class="mt-1"><i class="fas fa-user-shield me-1"></i> Admin Dinsos</div>
            </div>
        </div>
    </div>

    {{-- ── ALERT DONASI PENDING ── --}}
    @if($stats['donasi_pending'] > 0)
    <div class="pending-alert d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div style="width:42px;height:42px;background:#fde68a;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-clock" style="color:#92400e;font-size:1.1rem;"></i>
            </div>
            <div>
                <div class="fw-bold" style="color:#92400e;font-size:.92rem;">
                    Ada <strong>{{ $stats['donasi_pending'] }} donasi</strong> menunggu verifikasi!
                </div>
                <div style="font-size:.78rem;color:#a16207;">Segera verifikasi agar donasi dapat diproses.</div>
            </div>
        </div>
        <a href="{{ route('donasi.index', ['status' => 'pending']) }}" class="btn btn-sm px-4 fw-600"
           style="background:#ca8a04;color:#fff;border-radius:10px;font-size:.82rem;font-weight:600;">
            <i class="fas fa-arrow-right me-1"></i> Lihat Sekarang
        </a>
    </div>
    @endif

    {{-- ── STAT CARDS ROW 1 – Donasi & Keuangan ── --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-blue">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-blue"><i class="fas fa-hand-holding-heart"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_donasi'] }}</div>
                        <div class="stat-label">Total Donasi</div>
                        <div class="stat-sub">{{ $stats['donasi_diterima'] }} diterima</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-yellow">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-yellow"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['donasi_pending'] }}</div>
                        <div class="stat-label">Pending</div>
                        <div class="stat-sub">Perlu verifikasi</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-green">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-green"><i class="fas fa-coins"></i></div>
                    <div>
                        <div class="stat-value sm">Rp {{ number_format($stats['total_pemasukan']/1000000, 1) }}Jt</div>
                        <div class="stat-label">Total Pemasukan</div>
                        <div class="stat-sub">Seluruh panti</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-red">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-red"><i class="fas fa-money-bill-wave"></i></div>
                    <div>
                        <div class="stat-value sm">Rp {{ number_format($stats['total_pengeluaran']/1000000, 1) }}Jt</div>
                        <div class="stat-label">Total Pengeluaran</div>
                        <div class="stat-sub">Seluruh panti</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card sc-teal">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-teal"><i class="fas fa-wallet"></i></div>
                    <div>
                        <div class="stat-value sm" style="{{ $stats['saldo'] >= 0 ? 'color:#0d9488' : 'color:#dc2626' }}">
                            Rp {{ number_format($stats['saldo']/1000000, 1) }}Jt
                        </div>
                        <div class="stat-label">Saldo Bersih</div>
                        <div class="stat-sub">Pemasukan - Pengeluaran</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── STAT CARDS ROW 2 – Entitas ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card sc-purple">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-purple"><i class="fas fa-home"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_panti'] }}</div>
                        <div class="stat-label">Panti Asuhan</div>
                        <div class="stat-sub">{{ $stats['panti_aktif'] }} aktif</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card sc-orange">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-orange"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_donatur'] }}</div>
                        <div class="stat-label">Donatur</div>
                        <div class="stat-sub">{{ $stats['donatur_aktif'] }} aktif</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card sc-indigo">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-indigo"><i class="fas fa-user-tie"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_pegawai'] }}</div>
                        <div class="stat-label">Pegawai Dinsos</div>
                        <div class="stat-sub">Terdaftar</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card sc-teal">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon si-teal"><i class="fas fa-user-friends"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_pengurus'] }}</div>
                        <div class="stat-label">Pengurus Panti</div>
                        <div class="stat-sub">Seluruh panti</div>
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
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('donasi.create') }}" class="quick-link ql-blue">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Donasi</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('donasi.index', ['status'=>'pending']) }}" class="quick-link ql-orange">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Verifikasi Donasi</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('panti-asuhan.index') }}" class="quick-link ql-purple">
                        <i class="fas fa-home"></i>
                        <span>Panti Asuhan</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('pegawai.index') }}" class="quick-link ql-indigo">
                        <i class="fas fa-users-cog"></i>
                        <span>Kelola Pegawai</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('donasi.index') }}" class="quick-link ql-green">
                        <i class="fas fa-hand-holding-heart"></i>
                        <span>Semua Donasi</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('pegawai.profil') }}" class="quick-link ql-teal">
                        <i class="fas fa-user-circle"></i>
                        <span>Profil Saya</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── ROW: Grafik + Distribusi ── --}}
    <div class="row g-4 mb-4">

        {{-- Grafik Donasi Bulanan --}}
        <div class="col-lg-8">
            <div class="card dash-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#1a73e8;"></span>
                        Donasi Diterima per Bulan (12 Bulan Terakhir)
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="chartDonasiBulanan" height="110"></canvas>
                </div>
            </div>
        </div>

        {{-- Distribusi --}}
        <div class="col-lg-4">
            <div class="card dash-card mb-4">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#7c3aed;"></span>
                        Jenis Donasi
                    </div>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="chartJenis" height="180"></canvas>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#ea580c;"></span>
                        Metode Donasi
                    </div>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="chartMetode" height="180"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- ── ROW: Donasi Terbaru + Pending ── --}}
    <div class="row g-4 mb-4">

        {{-- Donasi Terbaru --}}
        <div class="col-lg-7">
            <div class="card dash-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#1a73e8;"></span>
                        Donasi Terbaru
                    </div>
                    <a href="{{ route('donasi.index') }}" class="btn-sm-link">Lihat Semua →</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table dash-table mb-0">
                            <thead><tr>
                                <th>Donatur</th>
                                <th>Panti</th>
                                <th>Jenis</th>
                                <th>Nominal / Barang</th>
                                <th>Status</th>
                            </tr></thead>
                            <tbody>
                            @forelse($donasi_terbaru as $d)
                            <tr style="cursor:pointer;" onclick="window.location='{{ route('donasi.show', $d) }}'">
                                <td>
                                    <div class="fw-semibold" style="color:#1e293b;">{{ $d->donatur->nama ?? '-' }}</div>
                                    <div style="font-size:.72rem;color:#94a3b8;">{{ $d->tanggal_donasi?->format('d/m/Y') }}</div>
                                </td>
                                <td style="font-size:.82rem;">{{ $d->pantiAsuhan->nama_panti ?? '-' }}</td>
                                <td>
                                    <span class="badge bdg-{{ $d->jenis_donasi }}">
                                        {{ ucfirst($d->jenis_donasi) }}
                                    </span>
                                </td>
                                <td>
                                    @if($d->jenis_donasi === 'uang')
                                        <span class="fw-semibold text-success" style="font-size:.83rem;">
                                            Rp {{ number_format($d->nominal,0,',','.') }}
                                        </span>
                                    @else
                                        <span style="font-size:.82rem;">{{ $d->barang->count() }} jenis</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bdg-{{ $d->status }}">{{ ucfirst($d->status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5"><div class="empty-sm"><i class="fas fa-inbox mb-2" style="display:block;font-size:1.5rem;"></i>Belum ada donasi</div></td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Donasi Pending --}}
        <div class="col-lg-5">
            <div class="card dash-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#ca8a04;"></span>
                        Perlu Verifikasi
                        @if($stats['donasi_pending'] > 0)
                            <span class="badge" style="background:#ca8a04;color:#fff;font-size:.7rem;margin-left:4px;">{{ $stats['donasi_pending'] }}</span>
                        @endif
                    </div>
                    <a href="{{ route('donasi.index', ['status'=>'pending']) }}" class="btn-sm-link">Lihat Semua →</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table dash-table mb-0">
                            <thead><tr>
                                <th>Donatur</th>
                                <th>Jenis</th>
                                <th>Tgl</th>
                                <th width="70">Aksi</th>
                            </tr></thead>
                            <tbody>
                            @forelse($donasi_pending as $d)
                            <tr>
                                <td>
                                    <div class="fw-semibold" style="color:#1e293b;font-size:.83rem;">{{ $d->donatur->nama ?? '-' }}</div>
                                    <div style="font-size:.71rem;color:#94a3b8;">{{ $d->pantiAsuhan->nama_panti ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge bdg-{{ $d->jenis_donasi }}">{{ ucfirst($d->jenis_donasi) }}</span>
                                </td>
                                <td style="font-size:.78rem;color:#64748b;">{{ $d->tanggal_donasi?->format('d/m') }}</td>
                                <td>
                                    <a href="{{ route('donasi.show', $d) }}"
                                       class="btn btn-sm px-2 py-1"
                                       style="background:#e0f2fe;color:#0369a1;border-radius:8px;font-size:.72rem;font-weight:600;border:none;">
                                        <i class="fas fa-eye me-1"></i>Review
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4">
                                <div class="empty-sm">
                                    <i class="fas fa-check-circle mb-2" style="display:block;font-size:1.5rem;color:#16a34a;"></i>
                                    Semua donasi sudah diverifikasi!
                                </div>
                            </td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── ROW: Panti Top + Donatur + Keuangan ── --}}
    <div class="row g-4 mb-4">

        {{-- Panti Top Donasi --}}
        <div class="col-lg-4">
            <div class="card dash-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#7c3aed;"></span>
                        Panti Penerima Terbanyak
                    </div>
                    <a href="{{ route('panti-asuhan.index') }}" class="btn-sm-link">Semua →</a>
                </div>
                <div class="card-body">
                    @forelse($panti_top as $i => $panti)
                    <div class="panti-item">
                        <div class="panti-rank {{ $i===0?'rank-1':($i===1?'rank-2':($i===2?'rank-3':'rank-n')) }}">
                            {{ $i + 1 }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $panti->nama_panti }}
                            </div>
                            <div style="font-size:.72rem;color:#94a3b8;">
                                {{ $panti->donasi_count }} donasi diterima
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success" style="font-size:.8rem;">
                                Rp {{ number_format(($panti->total_pemasukan ?? 0)/1000000, 1) }}Jt
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-sm">Belum ada data</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Donatur Terbaru --}}
        <div class="col-lg-4">
            <div class="card dash-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#ea580c;"></span>
                        Donatur Terbaru
                    </div>
                    <a href="{{ route('donasi.index') }}" class="btn-sm-link">Semua →</a>
                </div>
                <div class="card-body">
                    @forelse($donatur_terbaru as $donatur)
                    <div class="donatur-item">
                        <div class="donatur-avatar">
                            {{ strtoupper(substr($donatur->nama, 0, 1)) }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $donatur->nama }}
                            </div>
                            <div style="font-size:.72rem;color:#94a3b8;">
                                {{ ucfirst($donatur->jenis_donatur) }}
                            </div>
                        </div>
                        <div>
                            <span class="badge" style="background:#f1f5f9;color:#64748b;font-size:.67rem;">
                                {{ $donatur->donasi_count }}x donasi
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="empty-sm">Belum ada donatur</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Keuangan Terbaru --}}
        <div class="col-lg-4">
            <div class="card dash-card h-100">
                <div class="card-header">
                    <div class="sec-title">
                        <span class="sec-title-dot" style="background:#0d9488;"></span>
                        Transaksi Keuangan Terbaru
                    </div>
                </div>
                <div class="card-body">
                    @forelse($keuangan_terbaru as $k)
                    <div class="keu-item">
                        <div class="keu-icon {{ $k->jenis==='pemasukan'?'masuk':'keluar' }}">
                            <i class="fas fa-{{ $k->jenis==='pemasukan'?'arrow-down':'arrow-up' }}"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.82rem;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $k->keterangan ?? $k->kategori ?? '-' }}
                            </div>
                            <div style="font-size:.72rem;color:#94a3b8;">
                                {{ $k->pantiAsuhan->nama_panti ?? '-' }} &bull; {{ $k->tanggal?->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="fw-bold {{ $k->jenis==='pemasukan'?'text-success':'text-danger' }}" style="font-size:.8rem;white-space:nowrap;">
                            {{ $k->jenis==='pemasukan'?'+':'-' }}Rp {{ number_format($k->nominal/1000, 0, ',', '.') }}K
                        </div>
                    </div>
                    @empty
                    <div class="empty-sm">Belum ada transaksi</div>
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
const donasiPerBulan = @json($donasi_per_bulan);
const distribusiJenis = @json($distribusi_jenis);
const distribusiMetode = @json($distribusi_metode);

// ── CHART 1: Donasi Bulanan (Line + Bar) ──────────────────────────
(function() {
    // Buat array 12 bulan terakhir
    const labels = [];
    const dataTotalDonasi = [];
    const dataNominalUang = [];

    for (let i = 11; i >= 0; i--) {
        const d = new Date();
        d.setMonth(d.getMonth() - i);
        const bln = d.getMonth() + 1;
        const thn = d.getFullYear();
        labels.push(d.toLocaleString('id-ID', { month: 'short', year: '2-digit' }));

        const found = donasiPerBulan.find(x => x.bulan == bln && x.tahun == thn);
        dataTotalDonasi.push(found ? found.total : 0);
        dataNominalUang.push(found ? parseFloat(found.total_uang) / 1000000 : 0);
    }

    new Chart(document.getElementById('chartDonasiBulanan'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Jumlah Donasi',
                    data: dataTotalDonasi,
                    backgroundColor: 'rgba(26,115,232,.15)',
                    borderColor: '#1a73e8',
                    borderWidth: 2,
                    borderRadius: 6,
                    yAxisID: 'y1',
                },
                {
                    label: 'Nominal Uang (Juta)',
                    data: dataNominalUang,
                    type: 'line',
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22,163,74,.08)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#16a34a',
                    pointRadius: 4,
                    yAxisID: 'y2',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top', labels: { font: { family: 'Plus Jakarta Sans', size: 11 }, padding: 16 } } },
            scales: {
                y1: { type:'linear', position:'left',  beginAtZero:true, ticks: { stepSize:1, font:{size:10} }, grid: { color:'#f1f5f9' } },
                y2: { type:'linear', position:'right', beginAtZero:true, ticks: { callback: v => 'Rp'+v+'Jt', font:{size:10} }, grid: { drawOnChartArea:false } },
                x:  { ticks: { font: { size:10 } }, grid: { color:'#f8fafc' } }
            }
        }
    });
})();

// ── CHART 2: Jenis Donasi (Doughnut) ─────────────────────────────
new Chart(document.getElementById('chartJenis'), {
    type: 'doughnut',
    data: {
        labels: ['Uang', 'Barang'],
        datasets: [{
            data: [distribusiJenis.uang || 0, distribusiJenis.barang || 0],
            backgroundColor: ['#1a73e8', '#16a34a'],
            hoverOffset: 6,
            borderWidth: 3,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { font: { family:'Plus Jakarta Sans', size:11 }, padding:12 } }
        }
    }
});

// ── CHART 3: Metode Donasi (Doughnut) ────────────────────────────
new Chart(document.getElementById('chartMetode'), {
    type: 'doughnut',
    data: {
        labels: ['Online', 'Kunjungan'],
        datasets: [{
            data: [distribusiMetode.online || 0, distribusiMetode.kunjungan || 0],
            backgroundColor: ['#7c3aed', '#ea580c'],
            hoverOffset: 6,
            borderWidth: 3,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { font: { family:'Plus Jakarta Sans', size:11 }, padding:12 } }
        }
    }
});
</script>
@endsection
