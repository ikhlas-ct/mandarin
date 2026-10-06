@extends('layouts.user.user')

@section('title', 'Hafalan Saya')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, .table, .btn, h4, h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ===== PAGE HEADER ===== */
    .ph-card { background: #fff; border: 1px solid #e9ecef; border-radius: 14px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 1.25rem; position: relative; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 14px 0 0 14px; background: #0369a1; }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; background: #e0f2fe; color: #0369a1; }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0; }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li + li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    /* ===== CARD ===== */
    .form-card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .section-divider { background: #f8f9fa; border-left: 4px solid #1269db; padding: 8px 14px; border-radius: 0 6px 6px 0; font-weight: 600; font-size: .9rem; color: #1269db; margin-bottom: 1rem; }
    .filter-card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); overflow: hidden; }
    .filter-card .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; padding: 18px 24px; }
    .filter-card .card-header h5 { font-size: .95rem; font-weight: 700; color: #1e293b; }

    /* ===== FORM ===== */
    .form-control, .form-select { border-radius: 10px; font-size: .85rem; border-color: #e2e8f0; }
    .form-control:focus, .form-select:focus { border-color: #1a73e8; box-shadow: 0 0 0 3px rgba(26,115,232,.12); }

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
    .hanzi-text { font-size: 1.15rem; font-weight: 600; color: #1e293b; font-family: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', 'Plus Jakarta Sans', sans-serif; }

    /* ===== BADGE ===== */
    .badge { font-size: .7rem; font-weight: 600; padding: 4px 9px; border-radius: 6px; letter-spacing: .2px; }
    .badge-aktif { background: #dcfce7; color: #15803d; }
    .badge-info-soft { background: #e8f0fe; color: #1a73e8; }
    .badge-nonaktif { background: #f1f5f9; color: #64748b; }
    .badge-warn { background: #fef3c7; color: #b45309; }
    .badge-danger-soft { background: #fee2e2; color: #b91c1c; }
    .badge-st-ingat_sepenuhnya { background: #dcfce7; color: #15803d; }
    .badge-st-lupa_dan_ingat { background: #fef3c7; color: #b45309; }
    .badge-st-berikutnya { background: #f1f5f9; color: #64748b; }

    /* ===== BUTTONS ===== */
    .btn-primary { background: linear-gradient(135deg, #1a73e8, #1558b0); border: none; border-radius: 10px; font-weight: 600; font-size: .83rem; padding: 8px 18px; box-shadow: 0 2px 8px rgba(26,115,232,.35); transition: all .2s ease; }
    .btn-primary:hover { background: linear-gradient(135deg, #1558b0, #0f3e82); box-shadow: 0 4px 14px rgba(26,115,232,.45); transform: translateY(-1px); }
    .btn-primary:disabled { opacity: .55; transform: none; box-shadow: none; }
    .btn-outline-secondary { border-radius: 10px; font-size: .83rem; border-color: #e2e8f0; color: #64748b; padding: 7px 12px; }
    .btn-outline-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }

    /* ===== EMPTY STATE ===== */
    .empty-state { padding: 60px 20px; }
    .empty-state-icon { width: 72px; height: 72px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.6rem; color: #94a3b8; }

    /* ===== STAT ===== */
    .stat-link { display: block; text-decoration: none; }
    .stat-card { border: 1.5px solid transparent; transition: transform .15s, border-color .15s, box-shadow .15s; }
    .stat-link:hover .stat-card { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,.09); }
    .stat-card.aktif { border-color: var(--c); }
    .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: var(--bg); color: var(--c); font-size: 1.05rem; flex-shrink: 0; }
    .stat-angka { font-size: 1.6rem; font-weight: 800; color: #1e293b; line-height: 1.1; }
    .stat-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; }

    .bar-hafalan { height: 10px; border-radius: 6px; overflow: hidden; background: #e9ecef; display: flex; }
    .legenda { display: flex; flex-wrap: wrap; gap: 14px; font-size: .78rem; color: #64748b; margin-top: 10px; }
    .legenda i { display: inline-block; width: 9px; height: 9px; border-radius: 50%; margin-right: 6px; }

    .riwayat-item { padding: 12px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .riwayat-item:last-child { border-bottom: none; }
</style>
@endsection

@section('content')
    @php
        $kartu = [
            ['kunci' => 'ingat_sepenuhnya', 'judul' => 'Ingat Sepenuhnya', 'jumlah' => $ingat,      'c' => '#15803d', 'bg' => '#dcfce7', 'ikon' => 'fa-check-circle'],
            ['kunci' => 'lupa_dan_ingat',   'judul' => 'Lupa & Ingat',      'jumlah' => $lupa,       'c' => '#b45309', 'bg' => '#fef3c7', 'ikon' => 'fa-redo'],
            ['kunci' => 'berikutnya',       'judul' => 'Berikutnya',        'jumlah' => $berikutnya, 'c' => '#64748b', 'bg' => '#f1f5f9', 'ikon' => 'fa-forward'],
            ['kunci' => 'belum',            'judul' => 'Belum Disentuh',    'jumlah' => $belum,      'c' => '#0369a1', 'bg' => '#e0f2fe', 'ikon' => 'fa-hourglass-start'],
        ];
    @endphp

    <div class="container">

        {{-- Header – di LUAR page-inner --}}
        <div class="ph-card">
            <div class="ph-left">
                <div class="ph-icon"><i class="fas fa-chart-pie"></i></div>
                <div>
                    <h5 class="ph-title">Hafalan Saya</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('pelajar.dashboard') }}">Dashboard</a></li>
                        <li><span class="bc-active">Hafalan Saya</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('pelajar.latihan.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-tasks me-1"></i> Kerjakan Latihan
                </a>
            </div>
        </div>

        <div class="page-inner">

            {{-- Kartu ringkasan (klik untuk memfilter daftar) --}}
            <div class="row g-3 mb-4">
                @foreach ($kartu as $k)
                    <div class="col-6 col-lg-3">
                        <a class="stat-link"
                           href="{{ route('pelajar.hafalan.index', ['status' => $status === $k['kunci'] ? null : $k['kunci'], 'search' => $cari ?: null]) }}">
                            <div class="card form-card stat-card h-100 {{ $status === $k['kunci'] ? 'aktif' : '' }}"
                                 style="--c: {{ $k['c'] }}; --bg: {{ $k['bg'] }}">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="stat-icon"><i class="fas {{ $k['ikon'] }}"></i></div>
                                    <div>
                                        <div class="stat-angka">{{ $k['jumlah'] }}</div>
                                        <div class="stat-label">{{ $k['judul'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Komposisi --}}
            <div class="card form-card mb-4">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-chart-bar me-2"></i>Komposisi Hafalan</div>

                    @if ($totalKosakata > 0)
                        <div class="bar-hafalan">
                            <div style="width: {{ $ingat / $totalKosakata * 100 }}%; background:#22c55e"></div>
                            <div style="width: {{ $lupa / $totalKosakata * 100 }}%; background:#f59e0b"></div>
                            <div style="width: {{ $berikutnya / $totalKosakata * 100 }}%; background:#94a3b8"></div>
                        </div>
                        <div class="legenda">
                            <span><i style="background:#22c55e"></i>Ingat sepenuhnya</span>
                            <span><i style="background:#f59e0b"></i>Lupa &amp; ingat</span>
                            <span><i style="background:#94a3b8"></i>Berikutnya</span>
                            <span><i style="background:#e9ecef"></i>Belum disentuh</span>
                        </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-md-4 info-item">
                            <div class="info-label">Total Kosakata</div>
                            <div class="info-value">{{ $totalKosakata }} kata</div>
                        </div>
                        <div class="col-md-4 info-item">
                            <div class="info-label">Perlu Direview Sekarang</div>
                            <div class="info-value">{{ $jatuhTempo }} kata</div>
                        </div>
                        <div class="col-md-4 info-item">
                            <div class="info-label">Akurasi Review</div>
                            <div class="info-value">
                                {{ $akurasi !== null ? $akurasi . '%' : '-' }}
                                @if ($totalReview > 0)
                                    <span class="text-muted fw-normal">({{ $totalReview }} kali review)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">

                {{-- Daftar kata --}}
                <div class="col-lg-8">
                    <div class="card filter-card shadow-sm">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">
                                <i class="fas fa-language text-primary me-2 opacity-75"></i>Daftar Kata
                                <span class="badge badge-info-soft ms-1">{{ $kosakatas->total() }}</span>
                            </h5>
                        </div>

                        <div class="card-body border-bottom">
                            <form method="GET" class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" name="search" class="form-control" placeholder="Cari hanzi / pinyin / arti..." value="{{ $cari }}">
                                </div>
                                <div class="col-md-4">
                                    <select name="status" class="form-select">
                                        <option value="">Semua status</option>
                                        <option value="ingat_sepenuhnya" @selected($status === 'ingat_sepenuhnya')>Ingat sepenuhnya</option>
                                        <option value="lupa_dan_ingat"   @selected($status === 'lupa_dan_ingat')>Lupa &amp; ingat</option>
                                        <option value="berikutnya"       @selected($status === 'berikutnya')>Berikutnya</option>
                                        <option value="belum"            @selected($status === 'belum')>Belum disentuh</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-grid">
                                    <button class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i> Cari</button>
                                </div>
                            </form>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table-hover mb-0 table">
                                    <thead>
                                        <tr>
                                            <th>Kata</th>
                                            <th>Arti</th>
                                            <th>Status</th>
                                            <th class="text-center">Diulang</th>
                                            <th>Review Berikutnya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($kosakatas as $k)
                                            @php $p = $k->progresHafalans->first(); @endphp
                                            <tr>
                                                <td>
                                                    <div class="hanzi-text">{{ $k->hanzi }}</div>
                                                    <div class="text-muted" style="font-size:.78rem;">{{ $k->pinyin }}</div>
                                                </td>
                                                <td>
                                                    {{ $k->arti_indonesia }}
                                                    <div class="text-muted" style="font-size:.78rem;">{{ $k->levelHsk->nama ?? '' }}</div>
                                                </td>
                                                <td>
                                                    @if ($p)
                                                        <span class="badge badge-st-{{ $p->status }}">{{ $p->status_label }}</span>
                                                    @else
                                                        <span class="badge badge-nonaktif">Belum disentuh</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{ $p->jumlah_ulang ?? 0 }}
                                                    @if ($p && $p->benar_beruntun > 1)
                                                        <div class="text-success" style="font-size:.72rem;">{{ $p->benar_beruntun }}× beruntun</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($p && $p->review_berikutnya)
                                                        {{ $p->review_berikutnya->translatedFormat('d M Y') }}
                                                        @if ($p->review_berikutnya->isPast())
                                                            <span class="badge badge-danger-soft ms-1">Jatuh tempo</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="p-0 text-center">
                                                    <div class="empty-state">
                                                        <div class="empty-state-icon"><i class="fas fa-search"></i></div>
                                                        <div class="fw-semibold text-secondary mb-1">Tidak ada kata yang cocok</div>
                                                        <div class="text-muted" style="font-size:.8rem;">Coba ubah kata kunci atau filter status</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($kosakatas->hasPages())
                            <div class="card-footer bg-white border-top px-4 py-3">{{ $kosakatas->links() }}</div>
                        @endif
                    </div>
                </div>

                {{-- Review terbaru --}}
                <div class="col-lg-4">
                    <div class="card filter-card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-history text-primary me-2 opacity-75"></i>Review Terbaru</h5>
                        </div>
                        <div class="card-body p-0">
                            @forelse ($riwayatTerbaru as $r)
                                <div class="riwayat-item">
                                    <div>
                                        <div class="hanzi-text">{{ $r->kosakata->hanzi ?? '?' }}</div>
                                        <div class="text-muted" style="font-size:.74rem;">
                                            {{ $r->direview_pada->diffForHumans() }} · {{ $r->sumber === 'soal' ? 'dari soal' : 'flashcard' }}
                                        </div>
                                    </div>
                                    <span class="badge {{ $r->benar ? 'badge-aktif' : 'badge-warn' }}">{{ $r->benar ? 'Ingat' : 'Lupa' }}</span>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fas fa-history"></i></div>
                                    <div class="fw-semibold text-secondary mb-1">Belum ada review</div>
                                    <div class="text-muted" style="font-size:.8rem;">Kerjakan latihan untuk memulai</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}
@endsection
