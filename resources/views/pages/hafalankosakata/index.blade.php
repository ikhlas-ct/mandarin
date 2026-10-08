@extends('layouts.user.user')

@section('title', 'Belajar Kosakata')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, .table, .btn, h4, h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

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

    /* ===== STAT CARDS (klik = filter status) ===== */
    a.stat-link { text-decoration: none; display: block; }
    .stat-card { border: 2px solid transparent; border-radius: 16px; padding: 20px; position: relative; overflow: hidden; transition: transform .2s ease, box-shadow .2s ease; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
    .stat-card.aktif { border-color: #1a73e8; }
    .stat-card::after { content: ''; position: absolute; right: -18px; top: -18px; width: 80px; height: 80px; border-radius: 50%; opacity: .12; }
    .stat-card.blue   { background: linear-gradient(135deg, #e8f0fe 0%, #dbeafe 100%); } .stat-card.blue::after   { background: #1a73e8; }
    .stat-card.slate  { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); } .stat-card.slate::after  { background: #64748b; }
    .stat-card.orange { background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); } .stat-card.orange::after { background: #ea580c; }
    .stat-card.green  { background: linear-gradient(135deg, #e6f9f0 0%, #d1fae5 100%); } .stat-card.green::after  { background: #16a34a; }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; color: #fff; }
    .stat-icon.blue { background: #1a73e8; } .stat-icon.slate { background: #64748b; }
    .stat-icon.orange { background: #ea580c; } .stat-icon.green { background: #16a34a; }
    .stat-value { font-size: 1.85rem; font-weight: 800; line-height: 1; color: #1e293b; }
    .stat-label { font-size: .78rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: #64748b; margin-top: 3px; }

    /* ===== CARD & FILTER ===== */
    .filter-card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); overflow: hidden; }
    .filter-card .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; padding: 18px 24px; }
    .filter-card .card-header h5 { font-size: .95rem; font-weight: 700; color: #1e293b; }
    .filter-section { background: #fafbfc; border-bottom: 1px solid #f1f5f9; padding: 16px 24px; }

    .form-control, .form-select { border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: .83rem; padding: 7px 12px; color: #334155; background-color: #f8fafc; transition: border-color .2s, box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color: #1a73e8; background: #fff; box-shadow: 0 0 0 3px rgba(26,115,232,.12); }
    .form-control::placeholder { color: #94a3b8; }
    .input-group .input-group-text { background: #f8fafc; border: 1.5px solid #e2e8f0; border-right: none; border-radius: 10px 0 0 10px; color: #94a3b8; font-size: .8rem; }
    .input-group .form-control { border-left: none; border-radius: 0 10px 10px 0; }

    /* ===== TABLE ===== */
    .table thead th { background: #f8fafc; color: #64748b; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; padding: 12px 16px; border-bottom: 2px solid #e2e8f0; border-top: none; white-space: nowrap; }
    .table tbody td { padding: 13px 16px; vertical-align: middle; font-size: .85rem; color: #334155; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:last-child td { border-bottom: none; }
    .table-hover tbody tr:hover td { background: #f8fafc; }
    .table tbody tr.baris-klik { cursor: pointer; }
    .table tbody tr.terpilih td { background: #eff6ff; }
    .hanzi-text { font-size: 1.25rem; font-weight: 600; color: #1e293b; font-family: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', 'Plus Jakarta Sans', sans-serif; text-decoration: none; }
    .hanzi-text:hover { color: #1a73e8; }
    .form-check-input { width: 1.05em; height: 1.05em; cursor: pointer; }

    /* ===== BADGES ===== */
    .badge { font-size: .7rem; font-weight: 600; padding: 4px 9px; border-radius: 6px; letter-spacing: .2px; }
    .badge-level { background: #e8f0fe; color: #1a73e8; }
    .badge-status-berikutnya       { background: #f1f5f9; color: #64748b; }
    .badge-status-lupa_dan_ingat   { background: #ffedd5; color: #c2410c; }
    .badge-status-ingat_sepenuhnya { background: #dcfce7; color: #15803d; }

    /* ===== TOMBOL PINDAH STATUS PER BARIS ===== */
    .btn-st { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; font-size: .75rem; padding: 0; border: none; background: #f1f5f9; color: #94a3b8; transition: all .15s ease; }
    .btn-st.berikutnya:hover,       .btn-st.berikutnya.aktif       { background: #64748b; color: #fff; }
    .btn-st.lupa_dan_ingat:hover,   .btn-st.lupa_dan_ingat.aktif   { background: #ea580c; color: #fff; }
    .btn-st.ingat_sepenuhnya:hover, .btn-st.ingat_sepenuhnya.aktif { background: #16a34a; color: #fff; }
    .btn-action { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; font-size: .75rem; padding: 0; border: none; background: #e0f2fe; color: #0369a1; transition: all .15s ease; text-decoration: none; }
    .btn-action:hover { background: #0369a1; color: #fff; }

    .btn-primary { background: linear-gradient(135deg, #1a73e8, #1558b0); border: none; border-radius: 10px; font-weight: 600; font-size: .83rem; padding: 8px 18px; box-shadow: 0 2px 8px rgba(26,115,232,.35); transition: all .2s ease; }
    .btn-primary:hover { background: linear-gradient(135deg, #1558b0, #0f3e82); box-shadow: 0 4px 14px rgba(26,115,232,.45); transform: translateY(-1px); }
    .btn-outline-secondary { border-radius: 10px; font-size: .83rem; border-color: #e2e8f0; color: #64748b; padding: 7px 12px; }
    .btn-outline-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }

    /* ===== BAR AKSI PILIH BANYAK ===== */
    .bulk-bar { position: fixed; left: 50%; bottom: 24px; transform: translate(-50%, 160%); z-index: 1050; background: #1e293b; color: #fff; border-radius: 14px; padding: 10px 14px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; justify-content: center; box-shadow: 0 10px 30px rgba(15,23,42,.35); max-width: calc(100vw - 24px); transition: transform .2s ease; }
    .bulk-bar.tampil { transform: translate(-50%, 0); }
    .bulk-info { font-size: .82rem; font-weight: 600; white-space: nowrap; }
    .bulk-btn { border: none; border-radius: 9px; font-size: .78rem; font-weight: 600; padding: 7px 12px; color: #fff; display: inline-flex; align-items: center; gap: 6px; }
    .bulk-btn.berikutnya       { background: #64748b; }
    .bulk-btn.lupa_dan_ingat   { background: #ea580c; }
    .bulk-btn.ingat_sepenuhnya { background: #16a34a; }
    .bulk-btn:hover { filter: brightness(1.12); }
    .bulk-batal { background: transparent; border: 1px solid #475569; color: #cbd5e1; }

    /* ===== EMPTY STATE ===== */
    .empty-state { padding: 60px 20px; }
    .empty-state-icon { width: 72px; height: 72px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.6rem; color: #94a3b8; }

    /* ===== AJAX: PENCARIAN & LOADING ===== */
    #input-cari { padding-right: 34px; }
    .btn-hapus-cari { display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); z-index: 5; border: none; background: transparent; color: #94a3b8; font-size: .9rem; padding: 0; line-height: 1; }
    .btn-hapus-cari:hover { color: #64748b; }
    #daftar-wrap { transition: opacity .15s ease; }
    #daftar-wrap.memuat { opacity: .45; pointer-events: none; }
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

            {{-- ===== STAT CARDS: klik untuk filter status (tanpa reload, lewat AJAX) ===== --}}
            <div class="row g-3 mb-4" id="stat-filter">
                <div class="col-6 col-md-3">
                    <a href="{{ route('pelajar.kosakata.index') }}" class="stat-link" data-status="">
                        <div class="card stat-card blue {{ ! $statusAktif ? 'aktif' : '' }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="stat-icon blue"><i class="fas fa-language"></i></div>
                                <div>
                                    <div class="stat-value">{{ $stats['total'] }}</div>
                                    <div class="stat-label">Semua Kata</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                @php $warna = ['berikutnya' => 'slate', 'lupa_dan_ingat' => 'orange', 'ingat_sepenuhnya' => 'green']; @endphp
                @foreach ($statusList as $kode => $info)
                    <div class="col-6 col-md-3">
                        <a href="{{ route('pelajar.kosakata.index', ['status' => $kode]) }}" class="stat-link" data-status="{{ $kode }}">
                            <div class="card stat-card {{ $warna[$kode] }} {{ $statusAktif === $kode ? 'aktif' : '' }}">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="stat-icon {{ $warna[$kode] }}"><i class="fas {{ $info['ikon'] }}"></i></div>
                                    <div>
                                        <div class="stat-value">{{ $stats[$kode] }}</div>
                                        <div class="stat-label">{{ $info['label'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- ===== FILTER & TABLE CARD ===== --}}
            <div class="card filter-card shadow-sm">

                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="mb-0">
                        <i class="fas fa-list text-primary me-2 opacity-75"></i>Daftar Kosakata
                    </h5>
                    <small class="text-muted">
                        <i class="fas fa-mouse-pointer me-1"></i>Klik kata untuk melihat detail, atau centang beberapa kata untuk dipindahkan sekaligus
                    </small>
                </div>

                {{-- Filter (AJAX: hasil langsung berubah saat mengetik / memilih) --}}
                <div class="filter-section">
                    <form id="form-filter" method="GET" action="{{ route('pelajar.kosakata.index') }}" autocomplete="off">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" id="input-cari" class="form-control"
                                        placeholder="Ketik hanzi / pinyin / arti..." value="{{ request('search') }}">
                                    <button type="button" class="btn-hapus-cari" id="btn-hapus-cari" title="Hapus pencarian" aria-label="Hapus pencarian">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6 col-md-2">
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

                            <div class="col-6 col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    @foreach ($statusList as $kode => $info)
                                        <option value="{{ $kode }}" {{ $statusAktif === $kode ? 'selected' : '' }}>{{ $info['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 col-md-1">
                                <select name="per_page" class="form-select" title="Jumlah per halaman">
                                    @foreach ([25, 50, 100] as $n)
                                        <option value="{{ $n }}" {{ $perPage === $n ? 'selected' : '' }}>{{ $n }}</option>
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

                {{-- Tabel + footer: isinya diganti lewat AJAX --}}
                <div id="daftar-wrap">
                <form id="form-massal" method="POST" action="{{ route('pelajar.kosakata.status') }}">
                    @csrf

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table-hover mb-0 table">
                                <thead>
                                    <tr>
                                        <th width="40"><input type="checkbox" class="form-check-input" id="cek-semua" title="Pilih semua di halaman ini"></th>
                                        <th width="40">#</th>
                                        <th>Hanzi</th>
                                        <th>Pinyin</th>
                                        <th>Arti</th>
                                        <th>Kategori</th>
                                        <th>Level</th>
                                        <th>Status</th>
                                        <th width="150">Pindahkan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kosakatas as $i => $kosakata)
                                        @php
                                            $status = $kosakata->progresHafalans->first()?->status ?? 'berikutnya';
                                        @endphp
                                        <tr class="baris-klik" data-href="{{ route('pelajar.kosakata.show', $kosakata) }}">
                                            <td class="td-cek">
                                                <input type="checkbox" class="form-check-input cek-baris" name="ids[]" value="{{ $kosakata->id }}">
                                            </td>

                                            <td class="text-muted">{{ $kosakatas->firstItem() + $i }}</td>

                                            <td>
                                                <a href="{{ route('pelajar.kosakata.show', $kosakata) }}" class="hanzi-text">{{ $kosakata->hanzi }}</a>
                                            </td>

                                            <td>
                                                {{ $kosakata->pinyin }}
                                                @if ($kosakata->baca_indonesia)
                                                    <div class="text-muted" style="font-size:.75rem;">{{ $kosakata->baca_indonesia }}</div>
                                                @endif
                                            </td>

                                            <td style="max-width:240px;">
                                                {{ $kosakata->arti_indonesia }}
                                                @if ($kosakata->english)
                                                    <div class="text-muted" style="font-size:.75rem;">{{ $kosakata->english }}</div>
                                                @endif
                                            </td>

                                            <td class="text-muted">{{ $kosakata->kategori->nama ?? '-' }}</td>

                                            <td>
                                                @if ($kosakata->levelHsk)
                                                    <span class="badge badge-level">{{ $kosakata->levelHsk->nama }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge badge-status-{{ $status }}">{{ $statusList[$status]['label'] }}</span>
                                            </td>

                                            <td class="td-aksi">
                                                <div class="d-flex gap-1">
                                                    @foreach ($statusList as $kode => $info)
                                                        <button type="button"
                                                            class="btn-st {{ $kode }} {{ $status === $kode ? 'aktif' : '' }}"
                                                            data-id="{{ $kosakata->id }}" data-status="{{ $kode }}"
                                                            title="Pindah ke {{ $info['label'] }}">
                                                            <i class="fas {{ $info['ikon'] }}"></i>
                                                        </button>
                                                    @endforeach
                                                    <a href="{{ route('pelajar.kosakata.show', $kosakata) }}"
                                                        class="btn-action ms-1" title="Lihat detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="p-0 text-center">
                                                <div class="empty-state">
                                                    <div class="empty-state-icon"><i class="fas fa-language"></i></div>
                                                    <div class="fw-semibold text-secondary mb-1">Tidak ada kosakata</div>
                                                    <div class="text-muted" style="font-size:.8rem;">
                                                        Coba ubah kata kunci atau filter pencarian
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
</form>

{{-- Footer: info jumlah hasil selalu tampil (berguna saat mencari), pagination hanya kalau lebih dari satu halaman --}}
@if ($kosakatas->total() > 0)
    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">
            Menampilkan
            <strong>{{ $kosakatas->firstItem() }}</strong>–<strong>{{ $kosakatas->lastItem() }}</strong>
            dari <strong>{{ $kosakatas->total() }}</strong> kata
        </small>
        @if ($kosakatas->hasPages())
            {{ $kosakatas->links() }}
        @endif
    </div>
@endif
                </div>

                    {{-- Bar aksi: muncul kalau ada kata yang dicentang --}}
                    <div class="bulk-bar" id="bulk-bar" role="region" aria-label="Pindahkan kata terpilih">
                        <span class="bulk-info"><span id="bulk-jumlah">0</span> kata dipilih · pindahkan ke:</span>
                        @foreach ($statusList as $kode => $info)
                            <button type="submit" form="form-massal" name="status" value="{{ $kode }}" class="bulk-btn {{ $kode }}">
                                <i class="fas {{ $info['ikon'] }}"></i> {{ $info['label'] }}
                            </button>
                        @endforeach
                        <button type="button" class="bulk-btn bulk-batal" id="bulk-batal">Batal</button>
                    </div>

                {{-- Form tersembunyi untuk tombol pindah status per baris --}}
                <form id="form-satu" method="POST" action="{{ route('pelajar.kosakata.status') }}" class="d-none">
                    @csrf
                    <input type="hidden" name="ids[]" id="satu-id">
                    <input type="hidden" name="status" id="satu-status">
                </form>

            </div>{{-- end .card --}}

        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}
@endsection


@section('scripts')
    <script>
        (function () {
            const wrap     = document.getElementById('daftar-wrap');
            const form     = document.getElementById('form-filter');
            const bar      = document.getElementById('bulk-bar');
            const jumlah   = document.getElementById('bulk-jumlah');
            const inCari   = form.elements['search'];
            const inStatus = form.elements['status'];
            const btnHapus = document.getElementById('btn-hapus-cari');
            const kartuLink = document.querySelectorAll('#stat-filter a[data-status]');
            const cekBaris = () => Array.from(wrap.querySelectorAll('.cek-baris'));

            let timer = null;
            let pengendali = null;

            /* ================= PILIH BANYAK ================= */
            function perbarui() {
                const semua = wrap.querySelector('#cek-semua');
                const daftar = cekBaris();
                const n = daftar.filter(c => c.checked).length;

                jumlah.textContent = n;
                bar.classList.toggle('tampil', n > 0);

                if (semua) {
                    semua.checked = n > 0 && n === daftar.length;
                    semua.indeterminate = n > 0 && n < daftar.length;
                }
                daftar.forEach(c => c.closest('tr').classList.toggle('terpilih', c.checked));
            }

            document.getElementById('bulk-batal').addEventListener('click', () => {
                cekBaris().forEach(c => c.checked = false);
                perbarui();
            });

            /* ================= AJAX: MUAT DAFTAR ================= */
            function bangunUrl() {
                const params = new URLSearchParams();
                new FormData(form).forEach((nilai, kunci) => {
                    if (nilai !== '') params.set(kunci, nilai);
                });
                const query = params.toString();
                return form.getAttribute('action') + (query ? '?' + query : '');
            }

            async function muat(url, gulir = false) {
                if (pengendali) pengendali.abort();           // batalkan permintaan lama yang belum selesai
                const saya = pengendali = new AbortController();
                wrap.classList.add('memuat');

                try {
                    const res = await fetch(url, {
                        headers: { 'Accept': 'text/html' },
                        signal: saya.signal,
                    });
                    if (!res.ok) throw new Error('HTTP ' + res.status);

                    const dok  = new DOMParser().parseFromString(await res.text(), 'text/html');
                    const baru = dok.getElementById('daftar-wrap');
                    if (!baru) throw new Error('respons tidak berisi daftar (sesi habis?)');
                    wrap.innerHTML = baru.innerHTML;
                    history.replaceState(null, '', url);       // URL ikut berubah supaya refresh / tombol back tetap di filter yang sama
                    perbarui();

                    if (gulir) wrap.closest('.card').scrollIntoView({ behavior: 'smooth', block: 'start' });
                } catch (err) {
                    if (err.name === 'AbortError') return;
                    console.error(err);
                    wrap.innerHTML =
                        '<div class="empty-state text-center">' +
                        '<div class="empty-state-icon"><i class="fas fa-exclamation-triangle"></i></div>' +
                        '<div class="fw-semibold text-secondary mb-1">Gagal memuat data</div>' +
                        '<div class="text-muted" style="font-size:.8rem;">Coba muat ulang halaman. (' + (err.message || err) + ')</div></div>';
                } finally {
                    if (pengendali === saya) wrap.classList.remove('memuat');
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

            // Enter di kolom cari: cari langsung tanpa reload halaman
            form.addEventListener('submit', e => {
                e.preventDefault();
                clearTimeout(timer);
                muat(bangunUrl());
            });

            // Kategori / level / jumlah per halaman: langsung terapkan
            form.querySelectorAll('select').forEach(s => {
                s.addEventListener('change', () => {
                    if (s === inStatus) setStatus(inStatus.value);   // sorot kartu yang sesuai
                    muat(bangunUrl());
                });
            });

            /* ================= FILTER STATUS (kartu) ================= */
            function setStatus(status) {
                inStatus.value = status;
                kartuLink.forEach(a => {
                    a.querySelector('.stat-card').classList.toggle('aktif', a.dataset.status === status);
                });
            }

            kartuLink.forEach(a => {
                a.addEventListener('click', e => {
                    e.preventDefault();
                    setStatus(a.dataset.status);
                    muat(bangunUrl());
                });
            });

            document.getElementById('btn-reset').addEventListener('click', () => {
                inCari.value = '';
                form.elements['kategori_id'].value = '';
                form.elements['level_hsk_id'].value = '';
                form.elements['per_page'].value = '25';
                setStatus('');
                tampilkanHapus();
                muat(bangunUrl());
            });

            /* ================= EVENT DI DALAM DAFTAR (delegasi, tetap jalan setelah diganti AJAX) ================= */
            wrap.addEventListener('change', e => {
                if (e.target.id === 'cek-semua') {
                    cekBaris().forEach(c => c.checked = e.target.checked);
                    perbarui();
                } else if (e.target.classList.contains('cek-baris')) {
                    perbarui();
                }
            });

            wrap.addEventListener('click', e => {
                // Pagination lewat AJAX
                const halaman = e.target.closest('.card-footer a[href]');
                if (halaman) {
                    e.preventDefault();
                    muat(halaman.href, true);
                    return;
                }

                // Tombol pindah status per baris
                const btn = e.target.closest('.btn-st');
                if (btn) {
                    if (btn.classList.contains('aktif')) return;
                    document.getElementById('satu-id').value = btn.dataset.id;
                    document.getElementById('satu-status').value = btn.dataset.status;
                    document.getElementById('form-satu').submit();
                    return;
                }

                // Klik baris (di luar checkbox / tombol / link) membuka halaman detail
                const tr = e.target.closest('tr.baris-klik');
                if (tr && !e.target.closest('a, button, input, label, .td-cek')) {
                    window.location.href = tr.dataset.href;
                }
            });

            tampilkanHapus();
            perbarui();
        })();
    </script>
@endsection
