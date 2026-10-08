@extends('layouts.user.user')

@section('title', $grup->nama)

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
    .badge-ujian { background: #fee2e2; color: #b91c1c; }
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

    .grup-ket-box { background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 12px 16px; font-size: .85rem; color: var(--gk-muted); margin-bottom: 1.25rem; }
    .sticky-side { position: sticky; top: 16px; }
    @media (max-width: 991.98px) { .sticky-side { position: static; } }

    /* ===== LATIHAN ===== */
    .latihan { border: 1.5px solid var(--gk-line); border-radius: 14px; margin-bottom: 14px; background: #fff; overflow: hidden; }
    .latihan:last-child { margin-bottom: 0; }
    .latihan.aktif { border-color: #86efac; }
    .latihan-head { padding: 14px 16px; display: flex; gap: 12px; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; }
    .latihan-judul { font-size: .95rem; font-weight: 700; color: var(--gk-ink); margin: 0 0 6px; line-height: 1.3; }
    .latihan-meta { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
    .latihan-tgl { font-size: .74rem; color: var(--gk-faint); margin-left: 4px; }
    .latihan-set { display: flex; flex-wrap: wrap; gap: 4px 14px; margin-top: 8px; font-size: .76rem; color: var(--gk-muted); }
    .latihan-set i { color: var(--gk-faint); margin-right: 4px; }
    .tautkan-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; flex-wrap: wrap; }
    .tautkan-row:last-child { border-bottom: none; padding-bottom: 0; }
    .tautkan-row .jd { font-size: .86rem; font-weight: 600; color: var(--gk-ink); }
    .tautkan-row .mt { font-size: .74rem; color: var(--gk-faint); margin-top: 2px; }
    .latihan-aksi { display: flex; gap: 6px; }
    .latihan-aksi .btn { padding: 5px 11px; }

    details.daftar-soal { border-top: 1px solid #eef2f6; background: var(--gk-soft); }
    details.daftar-soal > summary { cursor: pointer; padding: 10px 16px; font-size: .82rem; font-weight: 600; color: var(--gk-blue); list-style: none; display: flex; align-items: center; gap: 8px; }
    details.daftar-soal > summary::-webkit-details-marker { display: none; }
    details.daftar-soal > summary .caret { transition: transform .2s; font-size: .7rem; }
    details.daftar-soal[open] > summary .caret { transform: rotate(90deg); }
    details.daftar-soal > summary:focus-visible { outline: 3px solid rgba(26,115,232,.35); outline-offset: -3px; }

    .soal-list { list-style: none; margin: 0; padding: 4px 16px 16px; }
    .soal-item { background: #fff; border: 1px solid #e8edf3; border-radius: 12px; padding: 12px 14px; margin-top: 10px; }
    .soal-head { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 8px; }
    .soal-no { width: 24px; height: 24px; border-radius: 50%; background: #e2e8f0; color: #475569; font-size: .72rem; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .soal-poin { margin-left: auto; font-size: .74rem; color: var(--gk-faint); }
    .soal-paragraf { font-family: var(--gk-hanzi); font-size: .95rem; color: #475569; background: var(--gk-soft); border-radius: 8px; padding: 8px 10px; margin-bottom: 8px; }
    .soal-tanya { font-family: var(--gk-hanzi); font-size: .95rem; font-weight: 600; color: var(--gk-ink); margin-bottom: 10px; line-height: 1.5; }
    .soal-audio { margin-bottom: 10px; font-size: .78rem; color: var(--gk-muted); }
    .soal-audio audio { width: 100%; height: 34px; }

    .opsi-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
    @media (max-width: 575.98px) { .opsi-grid { grid-template-columns: 1fr; } }
    .opsi { display: flex; gap: 8px; align-items: flex-start; border: 1.5px solid var(--gk-line); border-radius: 10px; padding: 6px 10px; font-size: .84rem; color: #334155; font-family: var(--gk-hanzi); }
    .opsi .huruf { font-weight: 700; color: var(--gk-faint); flex-shrink: 0; width: 14px; }
    .opsi.benar { background: #dcfce7; border-color: #4ade80; color: #14532d; }
    .opsi.benar .huruf { color: #15803d; }
    .opsi .tanda { margin-left: auto; color: #15803d; flex-shrink: 0; }
    .soal-jelas { margin-top: 8px; font-size: .78rem; color: var(--gk-muted); }

    /* ===== GENERATOR ===== */
    .tipe-wrap { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px; }
    .tipe-chip { cursor: pointer; margin: 0; position: relative; }
    .tipe-chip input { position: absolute; opacity: 0; pointer-events: none; }
    .tipe-chip span { display: inline-flex; align-items: center; gap: 5px; padding: 6px 13px; background: #fff; border: 1.5px solid var(--gk-line); border-radius: 999px; font-size: .78rem; font-weight: 600; color: var(--gk-muted); transition: all .15s; }
    .tipe-chip:hover span { border-color: #93c5fd; }
    .tipe-chip input:checked + span { background: #e8f0fe; border-color: var(--gk-blue); color: var(--gk-blue); }

    /* ===== DAFTAR KATA ===== */
    .kata-scroll { max-height: 420px; overflow: auto; margin: 0 -4px; padding: 0 4px; }
    .kata-row { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
    .kata-row:last-child { border-bottom: none; }
    .kata-row .hz { font-family: var(--gk-hanzi); font-size: 1.2rem; font-weight: 600; color: var(--gk-ink); min-width: 52px; }
    .kata-row .tx { min-width: 0; flex: 1; }
    .kata-row .py { font-size: .8rem; color: #334155; line-height: 1.2; }
    .kata-row .ar { font-size: .74rem; color: var(--gk-faint); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .kata-row .jml { font-size: .7rem; font-weight: 600; padding: 3px 8px; border-radius: 6px; background: #fef3c7; color: #92400e; flex-shrink: 0; }
    .kata-row .jml.ada { background: #dcfce7; color: #15803d; }
</style>
@endsection

@section('content')
    <div class="container">

        <div class="ph-card show-page">
            <div class="ph-left">
                <div class="ph-icon show"><i class="fas fa-layer-group"></i></div>
                <div style="min-width:0;">
                    <h5 class="ph-title">{{ $grup->nama }}</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.grup-kosakata.index') }}">Grup Kosakata</a></li>
                        <li><span class="bc-active">{{ \Illuminate\Support\Str::limit($grup->nama, 30) }}</span></li>
                    </ol>
                </div>
            </div>
            <div class="ph-actions">
                <a href="{{ route('admin.grup-kosakata.edit', $grup) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-pen me-1"></i> Edit grup
                </a>
                <a href="{{ route('admin.grup-kosakata.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
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

            @if ($grup->keterangan)
                <div class="grup-ket-box">{{ $grup->keterangan }}</div>
            @endif

            <div class="stat-row">
                <div class="stat-box">
                    <div class="stat-num">{{ $ringkasan['kata'] }}</div>
                    <div class="stat-cap">Kata dalam grup</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">{{ $ringkasan['latihan'] }}</div>
                    <div class="stat-cap">Latihan dibuat</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">{{ $ringkasan['latihan_aktif'] }}</div>
                    <div class="stat-cap">Latihan aktif untuk pelajar</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">{{ $ringkasan['soal'] }}</div>
                    <div class="stat-cap">Total soal</div>
                </div>
            </div>

            <div class="row g-4">

                {{-- ===== Kiri: soal yang sudah dibuat ===== --}}
                <div class="col-lg-8">
                    <div class="card form-card">
                        <div class="card-body">
                            <div class="section-divider"><span><i class="fas fa-clipboard-list me-2"></i>Soal yang sudah dibuat</span></div>

                            @forelse ($latihans as $latihan)
                                <div class="latihan {{ $latihan->aktif ? 'aktif' : '' }}">
                                    <div class="latihan-head">
                                        <div style="min-width:0;">
                                            <h6 class="latihan-judul">{{ $latihan->judul }}</h6>
                                            <div class="latihan-meta">
                                                @if ($latihan->aktif)
                                                    <span class="badge badge-aktif"><i class="fas fa-eye me-1"></i>Aktif</span>
                                                @else
                                                    <span class="badge badge-draf"><i class="fas fa-eye-slash me-1"></i>Belum aktif</span>
                                                @endif
                                                <span class="badge {{ $latihan->isUjian() ? 'badge-ujian' : 'badge-latihan' }}">{{ $latihan->isUjian() ? 'Ujian' : 'Latihan' }}</span>
                                                <span class="badge badge-pilih">{{ $latihan->soals_count }} soal</span>
                                                <span class="badge badge-level">{{ $latihan->total_poin }} poin</span>
                                                <span class="latihan-tgl">{{ $latihan->created_at?->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="latihan-set">
                                                <span><i class="fas fa-clock"></i>{{ $latihan->durasi_menit ? $latihan->durasi_menit . ' menit' : 'Tanpa batas waktu' }}</span>
                                                <span><i class="fas fa-bullseye"></i>{{ $latihan->nilai_lulus !== null ? 'Nilai lulus ' . $latihan->nilai_lulus : 'Tanpa nilai lulus' }}</span>
                                                <span><i class="fas fa-redo"></i>{{ $latihan->maks_percobaan ? 'Maks ' . $latihan->maks_percobaan . ' percobaan' : 'Percobaan tanpa batas' }}</span>
                                                @if ($latihan->levelHsk)
                                                    <span><i class="fas fa-layer-group"></i>{{ $latihan->levelHsk->nama }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="latihan-aksi">
                                            <a href="{{ route('admin.grup-kosakata.latihan.edit', [$grup, $latihan]) }}" class="btn btn-outline-secondary" title="Edit pengaturan latihan">
                                                <i class="fas fa-pen me-1"></i>Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.grup-kosakata.latihan.toggle', [$grup, $latihan]) }}">
                                                @csrf @method('PATCH')
                                                @if ($latihan->aktif)
                                                    <button class="btn btn-outline-warning"><i class="fas fa-eye-slash me-1"></i>Nonaktifkan</button>
                                                @else
                                                    <button class="btn btn-success"><i class="fas fa-check me-1"></i>Aktifkan</button>
                                                @endif
                                            </form>
                                            <form method="POST" action="{{ route('admin.grup-kosakata.latihan.destroy', [$grup, $latihan]) }}"
                                                  onsubmit="return confirm('Hapus latihan ini beserta {{ $latihan->soals_count }} soalnya?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-outline-danger" title="Hapus latihan" aria-label="Hapus latihan {{ $latihan->judul }}"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </div>
                                    </div>

                                    <details class="daftar-soal">
                                        <summary><i class="fas fa-chevron-right caret"></i> Lihat {{ $latihan->soals_count }} soal</summary>
                                        <ol class="soal-list">
                                            @foreach ($latihan->soals as $soal)
                                                <li class="soal-item">
                                                    <div class="soal-head">
                                                        <span class="soal-no">{{ $loop->iteration }}</span>
                                                        @if ($soal->bagian === 'listening')
                                                            <span class="badge badge-listening"><i class="fas fa-headphones me-1"></i>Listening</span>
                                                        @else
                                                            <span class="badge badge-reading"><i class="fas fa-book-open me-1"></i>Reading</span>
                                                        @endif
                                                        @if ($soal->kosakata)
                                                            <span class="hanzi-chip">{{ $soal->kosakata->hanzi }}</span>
                                                            <span class="text-muted" style="font-size:.76rem;">{{ $soal->kosakata->pinyin }}</span>
                                                        @endif
                                                        <span class="soal-poin">{{ $soal->poin }} poin</span>
                                                    </div>

                                                    @if ($soal->paragraf)
                                                        <div class="soal-paragraf">{{ \Illuminate\Support\Str::limit(strip_tags($soal->paragraf), 300) }}</div>
                                                    @endif

                                                    @if ($soal->bagian === 'listening')
                                                        <div class="soal-audio">
                                                            @if ($soal->audio_url)
                                                                <audio controls preload="none" src="{{ $soal->audio_url }}"></audio>
                                                            @elseif ($soal->teks_suara)
                                                                <i class="fas fa-volume-up me-1"></i>Tidak ada file audio; suara dibacakan browser: <strong>{{ $soal->teks_suara }}</strong>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    <div class="soal-tanya">{{ $soal->pertanyaan }}</div>

                                                    <div class="opsi-grid">
                                                        @foreach ($soal->pilihan as $huruf => $teks)
                                                            <div class="opsi {{ $huruf === $soal->jawaban_benar ? 'benar' : '' }}">
                                                                <span class="huruf">{{ $huruf }}</span>
                                                                <span>{{ $teks }}</span>
                                                                @if ($huruf === $soal->jawaban_benar)
                                                                    <i class="fas fa-check-circle tanda" title="Jawaban benar"></i>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    @if ($soal->penjelasan)
                                                        <div class="soal-jelas"><i class="fas fa-lightbulb me-1"></i>{{ \Illuminate\Support\Str::limit(strip_tags($soal->penjelasan), 250) }}</div>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ol>
                                    </details>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fas fa-clipboard-list"></i></div>
                                    <div class="empty-state-title">Belum ada soal dari grup ini</div>
                                    <div class="empty-state-text">Pilih tipe soal di kanan, lalu klik "Buat soal".@if ($belumTertaut->isNotEmpty()) Atau tautkan latihan lama di bawah.@endif</div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if ($belumTertaut->isNotEmpty())
                        <div class="card form-card mt-4">
                            <div class="card-body">
                                <div class="section-divider">
                                    <span><i class="fas fa-link me-2"></i>Latihan lama yang belum tertaut ke grup</span>
                                    <span class="badge badge-latihan">{{ $belumTertaut->count() }}</span>
                                </div>
                                <p class="help-text mt-0 mb-2">Latihan ini belum punya grup asal, jadi belum bisa diedit dari halaman grup. Tautkan ke grup ini bila memang dibuat dari kata-kata di sini.</p>

                                @foreach ($belumTertaut as $lama)
                                    <div class="tautkan-row">
                                        <div style="min-width:0;">
                                            <div class="jd">{{ $lama->judul }}</div>
                                            <div class="mt">
                                                {{ $lama->isUjian() ? 'Ujian' : 'Latihan' }},
                                                {{ $lama->soals_count }} soal,
                                                {{ $lama->created_at?->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('admin.grup-kosakata.latihan.tautkan', [$grup, $lama]) }}">
                                            @csrf
                                            <button class="btn btn-outline-secondary btn-sm"><i class="fas fa-link me-1"></i>Tautkan ke grup ini</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ===== Kanan: generator + daftar kata ===== --}}
                <div class="col-lg-4">
                    <div class="sticky-side">

                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="section-divider"><span><i class="fas fa-magic me-2"></i>Buat soal baru</span></div>

                                <form method="POST" action="{{ route('admin.grup-kosakata.generate', $grup) }}">
                                    @csrf
                                    <div class="info-label mb-2">Tipe soal</div>
                                    <div class="tipe-wrap">
                                        @foreach ($labelTipe as $nilai => $label)
                                            <label class="tipe-chip">
                                                <input type="checkbox" name="tipe[]" value="{{ $nilai }}" @checked(in_array($nilai, old('tipe', ['hanzi_arti', 'arti_hanzi'])))>
                                                <span>
                                                    @if ($nilai === 'listening')<i class="fas fa-headphones"></i>@endif
                                                    {{ $label }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>

                                    <label class="lbl" for="jumlah">Jumlah soal</label>
                                    <input type="number" id="jumlah" name="jumlah" class="form-control mb-3"
                                           value="{{ old('jumlah', max(5, $ringkasan['kata'])) }}" min="1" max="100">

                                    <button class="btn btn-primary w-100"><i class="fas fa-magic me-1"></i> Buat soal</button>
                                    <div class="help-text">Soal baru dibuat dengan status belum aktif. Periksa dulu, lalu aktifkan agar tampil untuk pelajar.</div>
                                </form>
                            </div>
                        </div>

                        <div class="card form-card">
                            <div class="card-body">
                                <div class="section-divider">
                                    <span><i class="fas fa-font me-2"></i>Kata dalam grup</span>
                                    <span class="badge badge-jumlah">{{ $ringkasan['kata'] }}</span>
                                </div>

                                <div class="kata-scroll">
                                    @foreach ($grup->kosakatas as $k)
                                        @php $jml = $soalPerKata[$k->id] ?? 0; @endphp
                                        <div class="kata-row">
                                            <div class="hz">{{ $k->hanzi }}</div>
                                            <div class="tx">
                                                <div class="py">{{ $k->pinyin }}</div>
                                                <div class="ar" title="{{ $k->arti_indonesia }}">{{ $k->arti_indonesia }}</div>
                                            </div>
                                            @if ($ringkasan['latihan'] > 0)
                                                <span class="jml {{ $jml > 0 ? 'ada' : '' }}" title="Jumlah soal yang menguji kata ini">
                                                    {{ $jml > 0 ? $jml . ' soal' : 'belum diuji' }}
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
