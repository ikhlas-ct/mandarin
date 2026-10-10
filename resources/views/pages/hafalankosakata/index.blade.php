@extends('layouts.user.user')

@section('title', 'Belajar Kosakata')

{{-- Cadangan: kalau controller belum mengirim $cari, ambil dari query string --}}
@php $cari = $cari ?? trim((string) request('search')); @endphp

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, .btn, h4, h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ===== PAGE HEADER ===== */
    .ph-card { background: #fff; border: 1px solid #e9ecef; border-radius: 14px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 1.25rem; position: relative; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 14px 0 0 14px; background: #1a73e8; }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; background: #e8f0fe; color: #1a73e8; }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0; }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li + li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    .btn-primary { background: linear-gradient(135deg, #1a73e8, #1558b0); border: none; border-radius: 10px; font-weight: 600; font-size: .83rem; padding: 8px 18px; box-shadow: 0 2px 8px rgba(26,115,232,.35); transition: all .2s ease; }
    .btn-primary:hover { background: linear-gradient(135deg, #1558b0, #0f3e82); transform: translateY(-1px); }
    .btn-outline-secondary { border-radius: 10px; font-size: .83rem; border-color: #e2e8f0; color: #64748b; padding: 7px 12px; }
    .btn-outline-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }

    /* ===== PIL STATUS (klik = filter) ===== */
    .pil-baris { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 1.1rem; }
    a.pil { text-decoration: none; border: 1px solid #e2e8f0; background: #fff; color: #334155; border-radius: 999px; padding: 8px 18px; font-size: .88rem; font-weight: 500; transition: all .15s ease; }
    a.pil:hover { border-color: #1a73e8; color: #1a73e8; }
    a.pil.aktif { background: #e8f0fe; border-color: #1a73e8; color: #1a73e8; font-weight: 600; }

    /* ===== KARTU FILTER ===== */
    .filter-card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); overflow: hidden; }
    .filter-section { background: #fafbfc; border-bottom: 1px solid #f1f5f9; padding: 16px 24px; }
    .form-control, .form-select { border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: .83rem; padding: 7px 12px; color: #334155; background-color: #f8fafc; transition: border-color .2s, box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color: #1a73e8; background: #fff; box-shadow: 0 0 0 3px rgba(26,115,232,.12); }
    .form-control::placeholder { color: #94a3b8; }
    .input-group .input-group-text { background: #f8fafc; border: 1.5px solid #e2e8f0; border-right: none; border-radius: 10px 0 0 10px; color: #94a3b8; font-size: .8rem; }
    .input-group .form-control { border-left: none; border-radius: 0 10px 10px 0; }
    #input-cari { padding-right: 34px; }
    .btn-hapus-cari { display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); z-index: 5; border: none; background: transparent; color: #94a3b8; font-size: .9rem; padding: 0; line-height: 1; }
    .btn-hapus-cari:hover { color: #64748b; }

    /* ===== GRID KARTU ===== */
    #daftar-wrap { padding: 20px 24px 24px; transition: opacity .15s ease; }
    #daftar-wrap.memuat { opacity: .45; pointer-events: none; }
    .info-hasil { font-size: .85rem; color: #64748b; margin-bottom: 14px; }
    .grid-kata { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; }
    .kartu-kata { width: 100%; min-height: 140px; position: relative; background: #fff; border: 1px solid #e2e8f0; border-left: 4px solid #94a3b8; border-radius: 12px; padding: 14px 10px 12px; text-align: center; cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; gap: 2px; font-family: inherit; transition: transform .15s ease, box-shadow .15s ease; }
    .kartu-kata:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(15,23,42,.1); }
    .kartu-kata:focus-visible { outline: 3px solid rgba(26,115,232,.35); outline-offset: 2px; }
    .kartu-kata.st-ingat_sepenuhnya { border-left-color: #16a34a; }
    .kartu-kata.st-lupa_dan_ingat   { border-left-color: #d97706; }
    .kartu-kata.st-berikutnya       { border-left-color: #94a3b8; }
    .kartu-pinyin { color: #1a73e8; font-weight: 600; font-size: .92rem; }
    .kartu-hanzi { font-size: 2rem; font-weight: 600; line-height: 1.25; color: #1e293b; font-family: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', 'Plus Jakarta Sans', sans-serif; }
    .kartu-arti { font-size: .82rem; color: #64748b; line-height: 1.35; }
    .kartu-kat { font-size: .72rem; color: #94a3b8; margin-top: 2px; }
    .kartu-meta { display: flex; flex-wrap: wrap; justify-content: center; gap: 5px; margin-top: 8px; }
    .kartu-meta .lencana { font-size: .64rem; padding: 3px 8px; }
    .kartu-detail { position: absolute; right: 6px; top: 6px; width: 26px; height: 26px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .68rem; color: #94a3b8; background: #f1f5f9; text-decoration: none; opacity: 0; transition: opacity .15s, background .15s, color .15s; }
    .kartu-kata:hover .kartu-detail, .kartu-kata:focus-within .kartu-detail { opacity: 1; }
    .kartu-detail:hover { background: #0369a1; color: #fff; }
    @media (hover: none) { .kartu-detail { opacity: 1; } }
    .lagi-wrap { text-align: center; margin-top: 22px; }

    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-state-icon { width: 72px; height: 72px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.6rem; color: #94a3b8; }

    /* ===== POPUP DETAIL (gabungan index + show) ===== */
    .lencana { font-size: .7rem; font-weight: 600; padding: 4px 10px; border-radius: 6px; display: inline-block; }
    .lencana-kat { background: #f1f5f9; color: #475569; }
    .lencana-lvl { background: #e8f0fe; color: #1a73e8; }
    .lencana-st-berikutnya       { background: #f1f5f9; color: #64748b; }
    .lencana-st-lupa_dan_ingat   { background: #ffedd5; color: #c2410c; }
    .lencana-st-ingat_sepenuhnya { background: #dcfce7; color: #15803d; }

    .modal-kata { position: fixed; inset: 0; z-index: 3000; display: flex; align-items: center; justify-content: center; padding: 16px; }
    .modal-kata[hidden] { display: none; }
    .modal-latar { position: absolute; inset: 0; background: rgba(15,23,42,.55); }
    .modal-kotak { position: relative; width: 100%; max-width: 720px; max-height: calc(100vh - 32px); overflow-y: auto; background: #fff; border-radius: 18px; box-shadow: 0 20px 60px rgba(15,23,42,.35); font-family: 'Plus Jakarta Sans', sans-serif; }
    .mk-tutup { position: absolute; right: 14px; top: 14px; width: 38px; height: 38px; border: 1px solid #e2e8f0; border-radius: 50%; background: #fff; color: #64748b; z-index: 2; }
    .mk-tutup:hover { background: #f1f5f9; color: #1e293b; }
    .mk-isi { padding: 26px 28px 8px; }
    .mk-hanzi { font-size: 4.4rem; font-weight: 600; line-height: 1.15; color: #1e293b; font-family: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', 'Plus Jakarta Sans', sans-serif; padding-right: 50px; }
    .mk-pinyin { color: #1a73e8; font-size: 1.5rem; font-weight: 700; margin-bottom: 16px; }
    .mk-baris { display: grid; grid-template-columns: 150px 1fr; gap: 8px 14px; font-size: .95rem; margin-bottom: 20px; }
    .mk-baris .lbl { color: #94a3b8; }
    .mk-baris .nilai { color: #1e293b; word-break: break-word; white-space: pre-line; }
    .mk-judul { font-size: .78rem; font-weight: 700; color: #475569; margin-bottom: 8px; }
    .mk-status { margin-bottom: 18px; }
    .mk-status-btn { display: flex; gap: 8px; flex-wrap: wrap; }
    .mk-st { border: 1.5px solid #e2e8f0; background: #fff; color: #475569; border-radius: 10px; font-size: .85rem; font-weight: 600; padding: 9px 16px; }
    .mk-st.berikutnya:hover,       .mk-st.berikutnya.aktif       { background: #64748b; border-color: #64748b; color: #fff; }
    .mk-st.lupa_dan_ingat:hover,   .mk-st.lupa_dan_ingat.aktif   { background: #ea580c; border-color: #ea580c; color: #fff; }
    .mk-st.ingat_sepenuhnya:hover, .mk-st.ingat_sepenuhnya.aktif { background: #16a34a; border-color: #16a34a; color: #fff; }
    .mk-pesan { font-size: .75rem; color: #16a34a; min-height: 18px; margin-top: 6px; }
    .mk-pesan.galat { color: #dc2626; }

    .mk-contoh-panel { background: #eaf1fb; border-radius: 14px; padding: 16px 18px; margin-bottom: 18px; }
    .mk-contoh-item { padding: 12px 0; border-top: 1px solid #d6e2f5; }
    .mk-contoh-item:first-of-type { border-top: none; padding-top: 4px; }
    .mk-contoh-hanzi { font-size: 1.4rem; font-weight: 600; color: #1e293b; font-family: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', 'Plus Jakarta Sans', sans-serif; }
    .mk-contoh-pinyin { font-size: .92rem; font-weight: 600; color: #1a73e8; }
    .mk-contoh-arti { font-size: .88rem; color: #64748b; margin-bottom: 6px; }
    .mk-contoh-catatan { font-size: .75rem; color: #94a3b8; font-style: italic; margin-bottom: 6px; }
    .btn-dengar { border: 1px solid #d3dcea; background: #fff; color: #334155; border-radius: 10px; font-size: .85rem; padding: 6px 14px; }
    .btn-dengar:hover { border-color: #1a73e8; color: #1a73e8; }

    .mk-aksi { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 4px; }
    .mk-aksi-btn { border: 1px solid #e2e8f0; background: #fff; color: #334155; border-radius: 10px; font-size: .88rem; font-weight: 500; padding: 10px 18px; }
    .mk-aksi-btn:hover { border-color: #1a73e8; color: #1a73e8; }
    .mk-aksi-btn.primer { background: #1a5fb4; border-color: #1a5fb4; color: #fff; }
    .mk-aksi-btn.primer:hover { background: #154c91; color: #fff; }
    .mk-info { margin-top: 10px; font-size: .75rem; color: #b45309; background: #fffbeb; border-radius: 8px; padding: 8px 12px; }

    .goresan-area { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 14px; }
    .goresan-kotak { width: 160px; height: 160px; border: 1.5px solid #e2e8f0; border-radius: 14px; background: #fff; position: relative; cursor: pointer; transition: border-color .2s, box-shadow .2s; }
    .goresan-kotak:hover { border-color: #1a73e8; box-shadow: 0 2px 10px rgba(26,115,232,.15); }
    .goresan-kotak::before, .goresan-kotak::after { content: ''; position: absolute; background: repeating-linear-gradient(90deg, #e2e8f0 0 4px, transparent 4px 8px); pointer-events: none; }
    .goresan-kotak::before { left: 0; right: 0; top: 50%; height: 1px; }
    .goresan-kotak::after { top: 0; bottom: 0; left: 50%; width: 1px; background: repeating-linear-gradient(0deg, #e2e8f0 0 4px, transparent 4px 8px); }
    .goresan-kotak svg { position: relative; z-index: 1; }
    .goresan-info { font-size: .78rem; color: #64748b; margin: 10px 0 18px; }

    .mk-kaki { display: flex; align-items: center; justify-content: space-between; gap: 8px; padding: 12px 18px; border-top: 1px solid #f1f5f9; background: #fafbfc; position: sticky; bottom: 0; }
    .mk-nav { border: 1.5px solid #e2e8f0; background: #fff; color: #64748b; border-radius: 10px; width: 36px; height: 36px; }
    .mk-nav:hover:not(:disabled) { border-color: #1a73e8; color: #1a73e8; }
    .mk-nav:disabled { opacity: .4; }
    .mk-detail { font-size: .82rem; font-weight: 600; color: #1a73e8; text-decoration: none; text-align: center; }
    .mk-detail:hover { text-decoration: underline; }
    @media (max-width: 576px) {
        .mk-isi { padding: 22px 18px 8px; }
        .mk-baris { grid-template-columns: 110px 1fr; font-size: .88rem; }
        .mk-hanzi { font-size: 3.4rem; }
    }
</style>
@endsection

@section('content')
    <div class="container">

        {{-- Header --}}
        <div class="ph-card">
            <div class="ph-left">
                <div class="ph-icon"><i class="fas fa-language"></i></div>
                <div>
                    <h5 class="ph-title">Belajar Kosakata</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('pelajar.dashboard') }}">Dashboard</a></li>
                        <li><span class="bc-active">Kosakata</span></li>
                    </ol>
                </div>
            </div>
            <a href="{{ route('pelajar.flashcard.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-layer-group me-1"></i> Flashcard
            </a>
        </div>

        <div class="page-inner">

            {{-- Alert --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible alert-flash fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif
            @if (session('error') || $errors->any())
                <div class="alert alert-danger alert-dismissible alert-flash fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') ?? $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            {{-- ===== PIL STATUS: klik untuk memisahkan per status ===== --}}
            <div class="pil-baris" id="pil-status">
                <a href="{{ route('pelajar.kosakata.index') }}" class="pil {{ ! $statusAktif ? 'aktif' : '' }}" data-status="">
                    Semua (<span class="jml">{{ $stats['total'] }}</span>)
                </a>
                @foreach ([\App\Models\ProgresHafalan::INGAT_SEPENUHNYA, \App\Models\ProgresHafalan::LUPA_DAN_INGAT, \App\Models\ProgresHafalan::BERIKUTNYA] as $kode)
                    <a href="{{ route('pelajar.kosakata.index', ['status' => $kode]) }}"
                        class="pil {{ $statusAktif === $kode ? 'aktif' : '' }}" data-status="{{ $kode }}">
                        {{ $statusList[$kode]['label'] }} (<span class="jml">{{ $stats[$kode] }}</span>)
                    </a>
                @endforeach
            </div>

            <div class="card filter-card">

                {{-- Filter (AJAX: hasil langsung berubah saat mengetik / memilih) --}}
                <div class="filter-section">
                    <form id="form-filter" method="GET" action="{{ route('pelajar.kosakata.index') }}" autocomplete="off">
                        <input type="hidden" name="status" value="{{ $statusAktif }}">

                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" id="input-cari" class="form-control"
                                        placeholder="Ketik hanzi / pinyin / arti..." value="{{ $cari }}">
                                    <button type="button" class="btn-hapus-cari" id="btn-hapus-cari" title="Hapus pencarian" aria-label="Hapus pencarian">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <select name="kategori_id" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    <option value="kosong" {{ request('kategori_id') === 'kosong' ? 'selected' : '' }}>Tanpa kategori</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ (string) request('kategori_id') === (string) $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 col-md-2">
                                <select name="level_hsk_id" class="form-select">
                                    <option value="">Semua Level</option>
                                    <option value="kosong" {{ request('level_hsk_id') === 'kosong' ? 'selected' : '' }}>Tanpa level</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level->id }}" {{ (string) request('level_hsk_id') === (string) $level->id ? 'selected' : '' }}>
                                            {{ $level->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-auto">
                                <button type="button" id="btn-reset" class="btn btn-outline-secondary btn-sm" title="Reset filter">
                                    <i class="fas fa-redo-alt"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Daftar kartu: isinya diganti lewat AJAX --}}
                <div id="daftar-wrap">
                    <div class="info-hasil">
                        Menampilkan <strong id="jml-tampil">{{ $kosakatas->count() }}</strong>
                        dari <strong>{{ number_format($kosakatas->total(), 0, ',', '.') }}</strong> kata
                        @if ($cari !== '')
                            untuk “{{ $cari }}” &middot; diurutkan dari yang paling mirip
                        @endif
                    </div>

                    @if ($kosakatas->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon"><i class="fas fa-language"></i></div>
                            <div class="fw-semibold text-secondary mb-1">Tidak ada kosakata</div>
                            <div class="text-muted" style="font-size:.8rem;">Coba ubah kata kunci atau filter pencarian</div>
                        </div>
                    @else
                        <div class="grid-kata" id="grid-kata">
                            @foreach ($kosakatas as $kosakata)
                                @php
                                    $st = $kosakata->progresHafalans->first()?->status ?? 'berikutnya';
                                    $payload = [
                                        'id'       => $kosakata->id,
                                        'hanzi'    => $kosakata->hanzi,
                                        'pinyin'   => $kosakata->pinyin,
                                        'baca'     => $kosakata->baca_indonesia,
                                        'arti'     => $kosakata->arti_indonesia,
                                        'english'  => $kosakata->english,
                                        'kategori' => $kosakata->kategori->nama ?? null,
                                        'level'    => $kosakata->levelHsk->nama ?? null,
                                        'kegunaan' => $kosakata->kegunaan,
                                        'url'      => route('pelajar.kosakata.show', $kosakata),
                                        'contoh'   => $kosakata->contohKalimats->map(fn ($c) => [
                                            'hanzi'   => $c->hanzi,
                                            'pinyin'  => $c->pinyin,
                                            'arti'    => $c->arti_indonesia,
                                            'catatan' => $c->catatan_tata_bahasa,
                                        ])->values(),
                                    ];
                                @endphp
                                <div class="kartu-kata st-{{ $st }}" role="button" tabindex="0" data-status="{{ $st }}"
                                    data-kata="{{ json_encode($payload, JSON_UNESCAPED_UNICODE) }}">
                                    <a href="{{ route('pelajar.kosakata.show', $kosakata) }}" class="kartu-detail"
                                        title="Buka halaman detail" aria-label="Buka halaman detail"><i class="fas fa-eye"></i></a>
                                    <span class="kartu-pinyin">{{ $kosakata->pinyin }}</span>
                                    <span class="kartu-hanzi">{{ $kosakata->hanzi }}</span>
                                    <span class="kartu-arti">{{ $kosakata->arti_indonesia }}</span>
                                    @if ($kosakata->kategori)
                                        <span class="kartu-kat">{{ $kosakata->kategori->nama }}</span>
                                    @endif
                                    <span class="kartu-meta">
                                        @if ($kosakata->levelHsk)
                                            <span class="lencana lencana-lvl">{{ preg_match('/^\d+$/', $kosakata->levelHsk->nama) ? 'HSK ' . $kosakata->levelHsk->nama : $kosakata->levelHsk->nama }}</span>
                                        @endif
                                        <span class="lencana kartu-st lencana-st-{{ $st }}">{{ $statusList[$st]['label'] }}</span>
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        @if ($kosakatas->hasMorePages())
                            <div class="lagi-wrap">
                                <button type="button" id="btn-lagi" class="btn btn-outline-secondary"
                                    data-halaman="{{ $kosakatas->currentPage() + 1 }}">
                                    <i class="fas fa-chevron-down me-1"></i> Muat 50 kata lagi
                                </button>
                            </div>
                        @endif
                    @endif
                </div>

            </div>{{-- end .card --}}

        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}

    {{-- ===== POPUP DETAIL KATA (gabungan index + show) ===== --}}
    <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
    <div class="modal-kata" id="modal-kata" hidden>
        <div class="modal-latar" data-tutup></div>
        <div class="modal-kotak" role="dialog" aria-modal="true" aria-labelledby="mk-hanzi">
            <button type="button" class="mk-tutup" data-tutup aria-label="Tutup"><i class="fas fa-times"></i></button>

            <div class="mk-isi">
                <div class="mk-hanzi" id="mk-hanzi"></div>
                <div class="mk-pinyin" id="mk-pinyin"></div>

                <div class="mk-baris" id="mk-baris"></div>

                <div class="mk-status">
                    <div class="mk-judul">Pindahkan ke:</div>
                    <div class="mk-status-btn" id="mk-status-btn"></div>
                    <div class="mk-pesan" id="mk-pesan"></div>
                </div>

                <div class="mk-contoh-panel" id="mk-blok-contoh">
                    <div class="mk-judul">Contoh pemakaian (<span id="mk-jml">0</span>)</div>
                    <div id="mk-contoh"></div>
                </div>

                <div class="mk-aksi">
                    <button type="button" class="mk-aksi-btn primer" data-laju="0.9"><i class="fas fa-volume-up me-1"></i> Dengar</button>
                    <button type="button" class="mk-aksi-btn" data-laju="0.55"><i class="fas fa-volume-down me-1"></i> Pelan</button>
                    <button type="button" class="mk-aksi-btn" id="btn-animasi"><i class="fas fa-play me-1"></i> Animasi goresan</button>
                    <button type="button" class="mk-aksi-btn" id="btn-latihan"><i class="fas fa-pencil-alt me-1"></i> Latihan menulis</button>
                </div>
                <div class="mk-info d-none" id="mk-info"></div>

                <div class="goresan-area" id="mk-goresan"></div>
                <div class="goresan-info" id="mk-info-goresan"></div>
            </div>

            <div class="mk-kaki">
                <button type="button" class="mk-nav" id="mk-sebelumnya" title="Kata sebelumnya (←)"><i class="fas fa-chevron-left"></i></button>
                <a href="#" class="mk-detail" id="mk-detail"><i class="fas fa-external-link-alt me-1"></i> Buka halaman detail</a>
                <button type="button" class="mk-nav" id="mk-berikutnya" title="Kata berikutnya (→)"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/hanzi-writer@3.7.3/dist/hanzi-writer.min.js"></script>
    <script>
        (function () {
            const URL_STATUS = @json(route('pelajar.kosakata.status'));
            const STATUS     = @json($statusList);

            const wrap     = document.getElementById('daftar-wrap');
            const form     = document.getElementById('form-filter');
            const inCari   = form.elements['search'];
            const inStatus = form.elements['status'];
            const btnHapus = document.getElementById('btn-hapus-cari');
            const pils     = document.querySelectorAll('#pil-status a.pil');
            const modal    = document.getElementById('modal-kata');
            const $        = id => document.getElementById(id);

            let timer = null;
            let pengendali = null;
            let kartuAktif = null;

            /* ================= AJAX: MUAT DAFTAR ================= */
            function bangunUrl(halaman) {
                const params = new URLSearchParams();
                new FormData(form).forEach((nilai, kunci) => {
                    if (nilai !== '') params.set(kunci, nilai);
                });
                if (halaman) params.set('page', halaman);
                const query = params.toString();
                return form.getAttribute('action') + (query ? '?' + query : '');
            }

            async function ambilDokumen(url, sinyal) {
                const res = await fetch(url, { headers: { 'Accept': 'text/html' }, signal: sinyal });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return new DOMParser().parseFromString(await res.text(), 'text/html');
            }

            async function muat(url) {
                if (pengendali) pengendali.abort();           // batalkan permintaan lama yang belum selesai
                const saya = pengendali = new AbortController();
                wrap.classList.add('memuat');

                try {
                    const dok  = await ambilDokumen(url, saya.signal);
                    const baru = dok.getElementById('daftar-wrap');
                    if (!baru) throw new Error('respons tidak berisi daftar (sesi habis?)');
                    wrap.innerHTML = baru.innerHTML;
                    history.replaceState(null, '', url);       // URL ikut berubah supaya refresh / tombol back tetap di filter yang sama
                } catch (err) {
                    if (err.name === 'AbortError') return;
                    console.error(err);
                    wrap.innerHTML =
                        '<div class="empty-state">' +
                        '<div class="empty-state-icon"><i class="fas fa-exclamation-triangle"></i></div>' +
                        '<div class="fw-semibold text-secondary mb-1">Gagal memuat data</div>' +
                        '<div class="text-muted" style="font-size:.8rem;">Coba muat ulang halaman. (' + (err.message || err) + ')</div></div>';
                } finally {
                    if (pengendali === saya) wrap.classList.remove('memuat');
                }
            }

            // "Muat 50 kata lagi": tambahkan kartu halaman berikutnya di bawah kartu yang sudah ada.
            async function muatLagi(btn) {
                const isiAwal = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memuat...';

                try {
                    const dok  = await ambilDokumen(bangunUrl(btn.dataset.halaman));
                    const grid = $('grid-kata');
                    const baru = dok.getElementById('grid-kata');
                    if (!baru) throw new Error('respons tidak berisi kartu');

                    grid.append(...Array.from(baru.children));
                    $('jml-tampil').textContent = grid.children.length;

                    const lagi = dok.getElementById('btn-lagi');
                    if (lagi) {
                        btn.dataset.halaman = lagi.dataset.halaman;
                        btn.innerHTML = isiAwal;
                        btn.disabled = false;
                    } else {
                        btn.closest('.lagi-wrap').remove();
                    }
                } catch (err) {
                    console.error(err);
                    btn.innerHTML = isiAwal;
                    btn.disabled = false;
                }
            }

            /* ================= PENCARIAN (live) ================= */
            function tampilkanHapus() {
                btnHapus.style.display = inCari.value ? 'block' : 'none';
            }

            function jadwalkanCari() {
                tampilkanHapus();
                clearTimeout(timer);
                timer = setTimeout(() => muat(bangunUrl()), 300);   // tunggu 300 ms setelah berhenti mengetik
            }

            inCari.addEventListener('input', e => {
                if (e.isComposing) return;                          // jangan cari saat IME (pinyin/hanzi) masih menyusun
                jadwalkanCari();
            });
            inCari.addEventListener('compositionend', jadwalkanCari);

            btnHapus.addEventListener('click', () => {
                inCari.value = '';
                tampilkanHapus();
                clearTimeout(timer);
                muat(bangunUrl());
                inCari.focus();
            });

            form.addEventListener('submit', e => {                  // Enter di kolom cari
                e.preventDefault();
                clearTimeout(timer);
                muat(bangunUrl());
            });

            form.querySelectorAll('select').forEach(s => {
                s.addEventListener('change', () => muat(bangunUrl()));
            });

            /* ================= FILTER STATUS (pil) ================= */
            function setStatus(status) {
                inStatus.value = status;
                pils.forEach(a => a.classList.toggle('aktif', a.dataset.status === status));
            }

            pils.forEach(a => {
                a.addEventListener('click', e => {
                    e.preventDefault();
                    setStatus(a.dataset.status);
                    muat(bangunUrl());
                });
            });

            $('btn-reset').addEventListener('click', () => {
                inCari.value = '';
                form.elements['kategori_id'].value = '';
                form.elements['level_hsk_id'].value = '';
                setStatus('');
                tampilkanHapus();
                muat(bangunUrl());
            });

            // Hitungan di pil ikut berubah saat status kata dipindah
            function ubahHitungan(kode, delta) {
                const el = document.querySelector('#pil-status a[data-status="' + kode + '"] .jml');
                if (el) el.textContent = Math.max(0, parseInt(el.textContent, 10) + delta);
            }

            /* ================= SUARA ================= */
            const bisaSuara = 'speechSynthesis' in window;

            function pilihSuara() {
                const daftar = window.speechSynthesis.getVoices().filter(v => /^zh/i.test(v.lang));
                let simpan = null;
                try { simpan = localStorage.getItem('suara_mandarin'); } catch (e) {}   // pilihan suara yang sama dengan halaman detail
                return daftar.find(v => v.name === simpan)
                    || daftar.find(v => /^zh[-_]TW$/i.test(v.lang) && /google/i.test(v.name))
                    || daftar.find(v => /^zh[-_](TW|HK)/i.test(v.lang))
                    || daftar[0]
                    || null;
            }

            function ucapkan(teks, laju) {
                const info = $('mk-info');
                info.classList.add('d-none');

                if (!bisaSuara) {
                    info.textContent = 'Browser ini belum mendukung suara. Coba Chrome atau Edge terbaru.';
                    info.classList.remove('d-none');
                    return;
                }
                const suara = pilihSuara();
                if (!suara) {
                    info.textContent = 'Suara Mandarin belum tersedia di perangkat ini. Petunjuk pemasangannya ada di halaman detail kata.';
                    info.classList.remove('d-none');
                    return;
                }
                window.speechSynthesis.cancel();
                const u = new SpeechSynthesisUtterance(teks);
                u.voice = suara;
                u.lang  = suara.lang;
                u.rate  = laju;
                window.speechSynthesis.speak(u);
            }

            if (bisaSuara) window.speechSynthesis.getVoices();   // pancing Chrome memuat daftar suara

            /* ================= POPUP DETAIL ================= */
            const labelLevel = v => /^\d+$/.test(v) ? 'HSK ' + v : v;

            function buat(tag, kelas, teks) {
                const e = document.createElement(tag);
                if (kelas) e.className = kelas;
                if (teks) e.textContent = teks;
                return e;
            }

            function dataAktif() { return JSON.parse(kartuAktif.dataset.kata); }
            function kartuSemua() { return Array.from(document.querySelectorAll('#grid-kata .kartu-kata')); }

            function tombolDengar(teks) {
                const b = buat('button', 'btn-dengar');
                b.type = 'button';
                b.innerHTML = '<i class="fas fa-volume-up me-1"></i> Dengar';
                b.addEventListener('click', () => ucapkan(teks, 0.85));
                return b;
            }

            /* ---- baris info (Arti, Cara baca, Inggris, Kategori, Level, Status, Kegunaan) ---- */
            function gambarBaris() {
                const d = dataAktif();
                const st = kartuAktif.dataset.status;
                const kotak = $('mk-baris');
                kotak.innerHTML = '';

                const tambah = (label, isi) => {
                    if (!isi) return;
                    kotak.append(buat('div', 'lbl', label));
                    const n = buat('div', 'nilai');
                    if (isi instanceof Node) n.append(isi); else n.textContent = isi;
                    kotak.append(n);
                };

                tambah('Arti', d.arti);
                tambah('Cara baca (Indo)', d.baca);
                tambah('Inggris', d.english);
                tambah('Kategori', d.kategori);
                tambah('Level', d.level ? labelLevel(d.level) : '');
                tambah('Status', buat('span', 'lencana lencana-st-' + st, STATUS[st].label));
                tambah('Kegunaan', d.kegunaan);
            }

            function gambarStatus() {
                const sekarang = kartuAktif.dataset.status;
                const area = $('mk-status-btn');
                area.innerHTML = '';

                Object.keys(STATUS).forEach(kode => {
                    const b = buat('button', 'mk-st ' + kode + (kode === sekarang ? ' aktif' : ''));
                    b.type = 'button';
                    b.append(STATUS[kode].label);
                    b.addEventListener('click', () => pindah(kode));
                    area.append(b);
                });
                gambarBaris();
            }

            /* ---- goresan (HanziWriter), sama seperti halaman detail ---- */
            let goresan = [];            // {writer, gagal}
            let latihanAktif = false;
            let sedangAnimasi = false;
            let tokenGoresan = 0;
            const LABEL_LATIHAN = '<i class="fas fa-pencil-alt me-1"></i> Latihan menulis';

            function bersihkanGoresan() {
                tokenGoresan++;                                   // hentikan animasi berurutan yang masih jalan
                goresan.forEach(i => { try { if (!i.gagal) i.writer.cancelQuiz(); } catch (e) {} });
                goresan = [];
                latihanAktif = false;
                sedangAnimasi = false;
                $('mk-goresan').innerHTML = '';
                $('btn-latihan').innerHTML = LABEL_LATIHAN;
            }

            function buatGoresan() {
                bersihkanGoresan();
                const info = $('mk-info-goresan');

                if (typeof HanziWriter === 'undefined') {
                    info.textContent = 'Pustaka goresan belum termuat (cek koneksi internet), lalu muat ulang halaman.';
                    return;
                }

                Array.from(dataAktif().hanzi)
                    .filter(c => /\p{Script=Han}/u.test(c))
                    .forEach(char => {
                        const kotak = document.createElement('div');
                        kotak.className = 'goresan-kotak';
                        $('mk-goresan').appendChild(kotak);

                        const item = { gagal: false };
                        item.writer = HanziWriter.create(kotak, char, {
                            width: 160,
                            height: 160,
                            padding: 14,
                            showOutline: true,
                            strokeColor: '#1e293b',
                            outlineColor: '#e2e8f0',
                            radicalColor: '#1a73e8',
                            drawingColor: '#1a73e8',
                            strokeAnimationSpeed: 1,
                            delayBetweenStrokes: 250,
                            onLoadCharDataError: () => {
                                item.gagal = true;
                                kotak.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted small px-2 text-center">Data goresan "' + char + '" tidak tersedia</div>';
                            },
                        });

                        kotak.addEventListener('click', () => {
                            if (item.gagal || latihanAktif) return;
                            item.writer.animateCharacter();
                        });
                        goresan.push(item);
                    });

                info.textContent = goresan.length
                    ? 'Klik kotak huruf untuk memutar animasi satu karakter.'
                    : 'Tidak ada karakter Han yang bisa ditampilkan goresannya.';
            }

            async function animasiSemua() {
                if (sedangAnimasi) return;
                const token = tokenGoresan;
                sedangAnimasi = true;
                stopLatihan();
                for (const item of goresan) {
                    if (token !== tokenGoresan) return;           // popup sudah ganti kata / ditutup
                    if (item.gagal) continue;
                    await new Promise(selesai => item.writer.animateCharacter({ onComplete: selesai }));
                }
                if (token === tokenGoresan) sedangAnimasi = false;
            }

            function stopLatihan() {
                if (!latihanAktif) return;
                latihanAktif = false;
                goresan.forEach(i => { if (!i.gagal) { i.writer.cancelQuiz(); i.writer.showCharacter(); } });
                $('btn-latihan').innerHTML = LABEL_LATIHAN;
                $('mk-info-goresan').textContent = 'Klik kotak huruf untuk memutar animasi satu karakter.';
            }

            function mulaiLatihan() {
                if (sedangAnimasi || !goresan.length) return;
                latihanAktif = true;
                goresan.forEach(i => { if (!i.gagal) i.writer.quiz({ showHintAfterMisses: 2 }); });
                $('btn-latihan').innerHTML = '<i class="fas fa-times me-1"></i> Selesai latihan';
                $('mk-info-goresan').textContent = 'Tulis tiap goresan dengan mouse atau jari, sesuai urutan. Garis biru = benar.';
            }

            $('btn-animasi').addEventListener('click', animasiSemua);
            $('btn-latihan').addEventListener('click', () => latihanAktif ? stopLatihan() : mulaiLatihan());

            /* ---- isi popup ---- */
            function isiModal() {
                const d = dataAktif();

                $('mk-hanzi').textContent  = d.hanzi;
                $('mk-pinyin').textContent = d.pinyin;

                const contoh = $('mk-contoh');
                contoh.innerHTML = '';
                (d.contoh || []).forEach(c => {
                    const item = buat('div', 'mk-contoh-item');
                    item.append(buat('div', 'mk-contoh-hanzi', c.hanzi), buat('div', 'mk-contoh-pinyin', c.pinyin), buat('div', 'mk-contoh-arti', c.arti));
                    if (c.catatan) item.append(buat('div', 'mk-contoh-catatan', c.catatan));
                    item.append(tombolDengar(c.hanzi));
                    contoh.append(item);
                });
                $('mk-jml').textContent = (d.contoh || []).length;
                $('mk-blok-contoh').classList.toggle('d-none', !(d.contoh || []).length);

                $('mk-detail').href = d.url;
                $('mk-info').classList.add('d-none');
                $('mk-pesan').textContent = '';
                $('mk-pesan').classList.remove('galat');
                gambarStatus();
                buatGoresan();

                const semua = kartuSemua();
                const i = semua.indexOf(kartuAktif);
                $('mk-sebelumnya').disabled = i <= 0;
                $('mk-berikutnya').disabled = i < 0 || i >= semua.length - 1;
                modal.querySelector('.modal-kotak').scrollTop = 0;
            }

            function bukaModal(kartu) {
                kartuAktif = kartu;
                isiModal();
                modal.hidden = false;
                document.body.style.overflow = 'hidden';
            }

            function tutupModal() {
                modal.hidden = true;
                document.body.style.overflow = '';
                bersihkanGoresan();
                if (bisaSuara) window.speechSynthesis.cancel();
                if (kartuAktif) kartuAktif.focus({ preventScroll: true });
            }

            function geser(arah) {
                const semua = kartuSemua();
                const tujuan = semua[semua.indexOf(kartuAktif) + arah];
                if (tujuan) { kartuAktif = tujuan; isiModal(); }
            }

            async function pindah(kode) {
                const lama = kartuAktif.dataset.status;
                if (kode === lama) return;

                const pesan = $('mk-pesan');
                pesan.classList.remove('galat');
                pesan.textContent = 'Menyimpan...';

                try {
                    const fd = new FormData();
                    fd.append('_token', $('csrf-token').value);
                    fd.append('ids[]', dataAktif().id);
                    fd.append('status', kode);

                    const res = await fetch(URL_STATUS, {
                        method: 'POST',
                        body: fd,
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    });
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    await res.json();

                    // kartu di daftar ikut berubah (garis kiri + lencana status)
                    kartuAktif.classList.remove('st-' + lama);
                    kartuAktif.classList.add('st-' + kode);
                    kartuAktif.dataset.status = kode;
                    const lencana = kartuAktif.querySelector('.kartu-st');
                    if (lencana) {
                        lencana.className = 'lencana kartu-st lencana-st-' + kode;
                        lencana.textContent = STATUS[kode].label;
                    }
                    ubahHitungan(lama, -1);
                    ubahHitungan(kode, +1);

                    gambarStatus();
                    pesan.textContent = 'Dipindahkan ke "' + STATUS[kode].label + '"';
                } catch (err) {
                    console.error(err);
                    pesan.classList.add('galat');
                    pesan.textContent = 'Gagal menyimpan (' + (err.message || err) + '). Coba lagi.';
                }
            }

            // Klik kartu / "muat lagi" (delegasi: tetap jalan setelah isi daftar diganti AJAX)
            wrap.addEventListener('click', e => {
                if (e.target.closest('a.kartu-detail')) return;      // tombol detail: biarkan pindah ke halaman show

                const kartu = e.target.closest('.kartu-kata');
                if (kartu) { bukaModal(kartu); return; }

                const lagi = e.target.closest('#btn-lagi');
                if (lagi) muatLagi(lagi);
            });

            wrap.addEventListener('keydown', e => {
                if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('kartu-kata')) {
                    e.preventDefault();
                    bukaModal(e.target);
                }
            });

            modal.addEventListener('click', e => {
                if (e.target.closest('[data-tutup]')) tutupModal();
            });
            modal.querySelectorAll('.mk-aksi-btn[data-laju]').forEach(b => {
                b.addEventListener('click', () => ucapkan(dataAktif().hanzi, parseFloat(b.dataset.laju)));
            });
            $('mk-sebelumnya').addEventListener('click', () => geser(-1));
            $('mk-berikutnya').addEventListener('click', () => geser(+1));

            document.addEventListener('keydown', e => {
                if (modal.hidden) return;
                if (e.key === 'Escape') tutupModal();
                else if (e.key === 'ArrowLeft')  geser(-1);
                else if (e.key === 'ArrowRight') geser(+1);
            });

            tampilkanHapus();
        })();
    </script>
@endsection
