@extends('layouts.user.user')

@section('title', 'Grup Kosakata')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, .table, .btn, .form-control, .form-select, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root {
        --gk-blue: #1a73e8;
        --gk-blue-dark: #1558b0;
        --gk-ink: #1e293b;
        --gk-muted: #64748b;
        --gk-faint: #94a3b8;
        --gk-line: #e2e8f0;
        --gk-soft: #f8fafc;
        --gk-hanzi: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', 'Plus Jakarta Sans', sans-serif;
    }

    /* ===== PAGE HEADER ===== */
    .ph-card { background: #fff; border: 1px solid #e9ecef; border-radius: 14px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 1.25rem; position: relative; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 14px 0 0 14px; }
    .ph-card.index-page::before, .ph-card.show-page::before { background: #0369a1; }
    .ph-card.create-page::before { background: #16a34a; }
    .ph-card.edit-page::before { background: #e96c1a; }
    .ph-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .ph-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
    .ph-icon.index, .ph-icon.show { background: #e0f2fe; color: #0369a1; }
    .ph-icon.create { background: #dcfce7; color: #16a34a; }
    .ph-icon.edit { background: #fff4ed; color: #e96c1a; }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: var(--gk-ink); letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0; }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li + li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: var(--gk-blue); text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: var(--gk-faint); }
    .ph-actions { display: flex; gap: 8px; flex-wrap: wrap; }

    /* ===== CARD ===== */
    .form-card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .section-divider { background: #f8f9fa; border-left: 4px solid #1269db; padding: 8px 14px; border-radius: 0 6px 6px 0; font-weight: 600; font-size: .9rem; color: #1269db; margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .info-label { font-size: .72rem; font-weight: 600; color: var(--gk-faint); letter-spacing: .02em; }
    .help-text { font-size: .78rem; color: var(--gk-muted); margin-top: 8px; }
    .required-mark { color: #dc3545; }
    label.lbl { font-size: .85rem; font-weight: 600; color: #334155; margin-bottom: 4px; }

    /* ===== ALERT ===== */
    .alert { border: none; border-radius: 12px; font-size: .85rem; font-weight: 500; padding: 12px 16px; }
    .alert-success { background: #dcfce7; color: #15803d; }
    .alert-danger { background: #fee2e2; color: #b91c1c; }
    .alert-warning { background: #fef3c7; color: #92400e; }

    /* ===== FORM ===== */
    .form-control, .form-select { border-radius: 10px; border: 1.5px solid var(--gk-line); font-size: .85rem; color: #334155; background-color: var(--gk-soft); transition: border-color .2s, box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color: var(--gk-blue); background: #fff; box-shadow: 0 0 0 3px rgba(26,115,232,.12); }
    .form-control::placeholder { color: var(--gk-faint); }
    .form-control.is-invalid { border-color: #f87171; }
    .invalid-feedback { font-size: .76rem; }
    .input-group-text { border-radius: 10px; border: 1.5px solid var(--gk-line); background: #f1f5f9; color: var(--gk-muted); font-size: .8rem; font-weight: 600; }
    .input-group > .form-control:not(:last-child), .input-group > .input-group-text:not(:last-child) { border-top-right-radius: 0; border-bottom-right-radius: 0; }
    .input-group > .form-control:not(:first-child), .input-group > .btn:not(:first-child), .input-group > .input-group-text:not(:first-child) { border-top-left-radius: 0; border-bottom-left-radius: 0; }
    .form-check-input:checked { background-color: var(--gk-blue); border-color: var(--gk-blue); }

    /* ===== BADGES ===== */
    .badge { font-size: .7rem; font-weight: 600; padding: 4px 9px; border-radius: 6px; letter-spacing: .2px; }
    .badge-level { background: #e8f0fe; color: var(--gk-blue); }
    .badge-jumlah { background: #dcfce7; color: #15803d; }
    .badge-pilih { background: #e0f2fe; color: #0369a1; }
    .badge-latihan { background: #fef3c7; color: #92400e; }
    .badge-aktif { background: #dcfce7; color: #15803d; }
    .badge-draf { background: #f1f5f9; color: var(--gk-muted); }
    .badge-listening { background: #ede9fe; color: #6d28d9; }
    .badge-reading { background: #e0f2fe; color: #0369a1; }

    /* ===== BUTTONS ===== */
    .btn-primary { background: linear-gradient(135deg, var(--gk-blue), var(--gk-blue-dark)); border: none; border-radius: 10px; font-weight: 600; font-size: .83rem; padding: 8px 18px; box-shadow: 0 2px 8px rgba(26,115,232,.35); transition: all .2s ease; }
    .btn-primary:hover { background: linear-gradient(135deg, var(--gk-blue-dark), #0f3e82); box-shadow: 0 4px 14px rgba(26,115,232,.45); transform: translateY(-1px); }
    .btn-success { border-radius: 10px; font-weight: 600; font-size: .83rem; padding: 8px 18px; }
    .btn-outline-secondary { border-radius: 10px; font-size: .83rem; border-color: var(--gk-line); color: var(--gk-muted); padding: 7px 12px; }
    .btn-outline-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }
    .btn-outline-danger { border-radius: 10px; font-size: .8rem; border-color: #fecaca; color: #dc2626; }
    .btn-outline-danger:hover { background: #fee2e2; border-color: #fca5a5; color: #b91c1c; }
    .btn-outline-warning { border-radius: 10px; font-size: .8rem; }
    .btn:focus-visible, .tipe-chip input:focus-visible + span { outline: 3px solid rgba(26,115,232,.35); outline-offset: 2px; }

    /* ===== RINGKASAN ANGKA ===== */
    .stat-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; margin-bottom: 1.25rem; }
    .stat-box { background: #fff; border-radius: 14px; padding: 14px 16px; box-shadow: 0 1px 6px rgba(0,0,0,.06); border: 1px solid #eef2f6; }
    .stat-num { font-size: 1.55rem; font-weight: 800; color: var(--gk-ink); line-height: 1.1; letter-spacing: -.5px; }
    .stat-cap { font-size: .76rem; color: var(--gk-muted); margin-top: 2px; }

    /* ===== HANZI ===== */
    .kata-hanzi { font-size: 1.25rem; font-weight: 600; color: var(--gk-ink); font-family: var(--gk-hanzi); }
    .hanzi-chip { display: inline-block; font-family: var(--gk-hanzi); font-size: 1rem; font-weight: 600; color: var(--gk-ink); background: #f1f5f9; border-radius: 8px; padding: 2px 9px; }

    /* ===== EMPTY STATE ===== */
    .empty-state { padding: 36px 20px; text-align: center; }
    .empty-state-icon { width: 68px; height: 68px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; font-size: 1.5rem; color: var(--gk-faint); }
    .empty-state-title { font-weight: 600; color: var(--gk-muted); margin-bottom: 4px; }
    .empty-state-text { font-size: .82rem; color: var(--gk-faint); }

    @media (prefers-reduced-motion: reduce) {
        .btn-primary, .grup-card, .form-control { transition: none; }
        .btn-primary:hover { transform: none; }
    }

    .toolbar { display: flex; gap: 10px; align-items: center; justify-content: space-between; flex-wrap: wrap; margin-bottom: 1rem; }
    .toolbar form { flex: 1 1 320px; max-width: 460px; }

    .grup-card { background: #fff; border: 1.5px solid #eef2f6; border-radius: 16px; padding: 18px; height: 100%; display: flex; flex-direction: column; box-shadow: 0 1px 6px rgba(0,0,0,.05); transition: border-color .2s, box-shadow .2s; }
    .grup-card:hover { border-color: #93c5fd; box-shadow: 0 4px 16px rgba(26,115,232,.1); }
    .grup-nama { font-size: .98rem; font-weight: 700; color: var(--gk-ink); line-height: 1.3; margin: 0; }
    .grup-nama a { color: inherit; text-decoration: none; }
    .grup-nama a:hover { color: var(--gk-blue); }
    .grup-ket { font-size: .8rem; color: var(--gk-muted); margin-top: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .grup-badges { display: flex; gap: 6px; flex-wrap: wrap; margin: 12px 0; }
    .grup-preview { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px; min-height: 30px; }
    .grup-preview .sisa { font-size: .75rem; color: var(--gk-faint); align-self: center; }
    .grup-aksi { margin-top: auto; display: flex; gap: 6px; padding-top: 12px; border-top: 1px dashed #dbe3ec; }
    .grup-aksi .btn { padding: 6px 12px; }
    .grup-aksi .btn-lihat { flex: 1; }
    .pagination { justify-content: center; margin-top: 1.25rem; }
</style>
@endsection

@section('content')
    <div class="container">

        <div class="ph-card index-page">
            <div class="ph-left">
                <div class="ph-icon index"><i class="fas fa-layer-group"></i></div>
                <div>
                    <h5 class="ph-title">Grup Kosakata</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><span class="bc-active">Grup Kosakata</span></li>
                    </ol>
                </div>
            </div>
            <div class="ph-actions">
                <a href="{{ route('admin.grup-kosakata.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Buat grup
                </a>
            </div>
        </div>

        <div class="page-inner">

            @if (session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif
            @if (session('error') || $errors->any())
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') ?? $errors->first() }}</div>
            @endif

            <div class="stat-row">
                <div class="stat-box">
                    <div class="stat-num">{{ $ringkasan['grup'] }}</div>
                    <div class="stat-cap">Grup kosakata</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">{{ $ringkasan['latihan'] }}</div>
                    <div class="stat-cap">Latihan hasil generate</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">{{ $ringkasan['soal'] }}</div>
                    <div class="stat-cap">Soal yang sudah dibuat</div>
                </div>
            </div>

            <div class="toolbar">
                <form method="GET" class="input-group">
                    <input type="search" name="search" class="form-control" placeholder="Cari nama grup atau kata di dalamnya…" value="{{ $cari }}">
                    <button class="btn btn-outline-secondary"><i class="fas fa-search me-1"></i> Cari</button>
                    @if ($cari !== '')
                        <a href="{{ route('admin.grup-kosakata.index') }}" class="btn btn-outline-secondary" title="Hapus pencarian"><i class="fas fa-times"></i></a>
                    @endif
                </form>
                <div class="help-text mt-0">{{ $grups->total() }} grup{{ $cari !== '' ? ' ditemukan' : '' }}</div>
            </div>

            @if ($grups->isEmpty())
                <div class="card form-card">
                    <div class="card-body">
                        <div class="empty-state">
                            <div class="empty-state-icon"><i class="fas fa-layer-group"></i></div>
                            @if ($cari !== '')
                                <div class="empty-state-title">Tidak ada grup yang cocok dengan "{{ $cari }}"</div>
                                <div class="empty-state-text">Coba kata kunci lain.</div>
                            @else
                                <div class="empty-state-title">Belum ada grup kosakata</div>
                                <div class="empty-state-text mb-3">Kumpulkan beberapa kata, lalu buat soal latihan darinya.</div>
                                <a href="{{ route('admin.grup-kosakata.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Buat grup pertama</a>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="row g-3">
                    @foreach ($grups as $grup)
                        @php $sisa = $grup->kosakatas_count - 6; @endphp
                        <div class="col-md-6 col-xl-4">
                            <div class="grup-card">
                                <h6 class="grup-nama">
                                    <a href="{{ route('admin.grup-kosakata.show', $grup) }}">{{ $grup->nama }}</a>
                                </h6>
                                @if ($grup->keterangan)
                                    <div class="grup-ket">{{ $grup->keterangan }}</div>
                                @endif

                                <div class="grup-badges">
                                    <span class="badge badge-jumlah"><i class="fas fa-font me-1"></i>{{ $grup->kosakatas_count }} kata</span>
                                    @if ($grup->grup_soals_count > 0)
                                        <span class="badge badge-latihan"><i class="fas fa-clipboard-list me-1"></i>{{ $grup->grup_soals_count }} latihan</span>
                                    @else
                                        <span class="badge badge-draf">Belum ada soal</span>
                                    @endif
                                </div>

                                <div class="grup-preview">
                                    @foreach ($grup->kosakatas->take(6) as $k)
                                        <span class="hanzi-chip">{{ $k->hanzi }}</span>
                                    @endforeach
                                    @if ($sisa > 0)
                                        <span class="sisa">+{{ $sisa }} lagi</span>
                                    @endif
                                </div>

                                <div class="grup-aksi">
                                    <a href="{{ route('admin.grup-kosakata.show', $grup) }}" class="btn btn-primary btn-lihat">
                                        <i class="fas fa-eye me-1"></i> Lihat &amp; buat soal
                                    </a>
                                    <a href="{{ route('admin.grup-kosakata.edit', $grup) }}" class="btn btn-outline-secondary" title="Edit grup" aria-label="Edit {{ $grup->nama }}">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.grup-kosakata.destroy', $grup) }}"
                                          onsubmit="return confirm('Hapus grup &quot;{{ addslashes($grup->nama) }}&quot;? Latihan yang pernah dibuat darinya tetap tersimpan.')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Hapus grup" aria-label="Hapus {{ $grup->nama }}"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{ $grups->links() }}
            @endif

        </div>
    </div>
@endsection
