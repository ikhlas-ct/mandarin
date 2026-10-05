@extends('layouts.user.user')

@section('title', 'Detail Paragraf')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, .table, .btn, h4, h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ===== PAGE HEADER ===== */
    .ph-card { background: #fff; border: 1px solid #e9ecef; border-radius: 14px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 1.25rem; position: relative; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 14px 0 0 14px; }
    .ph-card.show-page::before { background: #0369a1; }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
    .ph-icon.show { background: #e0f2fe; color: #0369a1; }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0; }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li + li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    /* ===== CARD ===== */
    .section-divider { background: #f8f9fa; border-left: 4px solid #1269db; padding: 8px 14px; border-radius: 0 6px 6px 0; font-weight: 600; font-size: .9rem; color: #1269db; margin-bottom: 1rem; }
    .form-card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .info-item { padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
    .info-item:last-child { border-bottom: none; padding-bottom: 0; }
    .info-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; }
    .info-value { font-size: .88rem; font-weight: 500; color: #1e293b; margin-top: 2px; word-break: break-word; }
    .blok-teks { font-size: .92rem; color: #334155; line-height: 1.75; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 12px 14px; height: 100%; }
    .blok-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; margin-bottom: 6px; }

    /* ===== BADGES ===== */
    .badge { font-size: .7rem; font-weight: 600; padding: 4px 9px; border-radius: 6px; letter-spacing: .2px; }
    .badge-aktif    { background: #dcfce7; color: #15803d; }
    .badge-nonaktif { background: #f1f5f9; color: #64748b; }

    /* ===== BUTTONS ===== */
    .btn-primary { background: linear-gradient(135deg, #1a73e8, #1558b0); border: none; border-radius: 10px; font-weight: 600; font-size: .83rem; padding: 8px 18px; box-shadow: 0 2px 8px rgba(26,115,232,.35); transition: all .2s ease; }
    .btn-primary:hover { background: linear-gradient(135deg, #1558b0, #0f3e82); box-shadow: 0 4px 14px rgba(26,115,232,.45); transform: translateY(-1px); }
    .btn-outline-secondary { border-radius: 10px; font-size: .83rem; border-color: #e2e8f0; color: #64748b; padding: 7px 12px; }
    .btn-outline-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }

    /* ===== TEKS HANZI PER KALIMAT ===== */
    .hanzi-kalimat { display: flex; flex-wrap: wrap; gap: 8px; }
    .kalimat-chip { display: inline-flex; align-items: center; gap: 4px; padding: 6px 8px 6px 14px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; cursor: pointer; transition: border-color .2s, background .2s, box-shadow .2s; }
    .kalimat-chip:hover { border-color: #93c5fd; }
    .kalimat-chip.terpilih { border-color: #1a73e8; background: #e8f0fe; }
    .kalimat-chip.sedang-dibaca { box-shadow: 0 0 0 3px rgba(22,163,74,.25); border-color: #16a34a; }
    .kalimat-teks { font-size: 1.3rem; font-weight: 600; color: #1e293b; font-family: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', 'Plus Jakarta Sans', sans-serif; }

    /* ===== PEMUTAR PARAGRAF UTUH ===== */
    .player-utuh { background: #f0f7ff; border: 1.5px solid #dbeafe; border-radius: 14px; padding: 14px 16px; }
    .player-utuh .form-select { border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: .8rem; background-color: #fff; }
    .putar-track { height: 8px; background: #dbeafe; border-radius: 999px; overflow: hidden; margin-top: 12px; }
    .putar-bar { height: 100%; width: 0; background: linear-gradient(90deg, #1a73e8, #16a34a); border-radius: 999px; transition: width .35s ease; }

    /* ===== GORESAN & SUARA ===== */
    .goresan-area { display: flex; flex-wrap: wrap; gap: 12px; }
    .goresan-kotak { width: 110px; height: 110px; border: 1.5px solid #e2e8f0; border-radius: 14px; background: #fff; position: relative; cursor: pointer; transition: border-color .2s, box-shadow .2s; }
    .goresan-kotak:hover { border-color: #1a73e8; box-shadow: 0 2px 10px rgba(26,115,232,.15); }
    .goresan-kotak::before, .goresan-kotak::after { content: ''; position: absolute; background: repeating-linear-gradient(90deg, #e2e8f0 0 4px, transparent 4px 8px); pointer-events: none; }
    .goresan-kotak::before { left: 0; right: 0; top: 50%; height: 1px; }
    .goresan-kotak::after { top: 0; bottom: 0; left: 50%; width: 1px; background: repeating-linear-gradient(0deg, #e2e8f0 0 4px, transparent 4px 8px); }
    .goresan-kotak svg { position: relative; z-index: 1; }
    .goresan-grup { margin-bottom: 18px; }
    .goresan-grup:last-child { margin-bottom: 0; }
    .goresan-grup-label { font-size: .75rem; font-weight: 600; color: #64748b; margin-bottom: 8px; display: flex; align-items: baseline; gap: 8px; flex-wrap: wrap; }
    .goresan-grup-label .no { background: #e8f0fe; color: #1a73e8; border-radius: 6px; padding: 1px 8px; font-size: .7rem; }
    .goresan-grup-label .teks { font-size: .9rem; font-weight: 600; color: #334155; font-family: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', 'Plus Jakarta Sans', sans-serif; }
    .goresan-kotak.aktif { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.2); }
    .mode-seg { display: inline-flex; background: #f1f5f9; border-radius: 12px; padding: 3px; gap: 2px; }
    .mode-btn { border: none; background: transparent; color: #64748b; font-size: .8rem; font-weight: 600; padding: 6px 14px; border-radius: 10px; transition: all .15s; }
    .mode-btn:hover { color: #1e293b; }
    .mode-btn.aktif { background: #fff; color: #1a73e8; box-shadow: 0 1px 4px rgba(0,0,0,.1); }
    .goresan-info { font-size: .78rem; color: #64748b; margin-top: 10px; }
    .goresan-info.galat { color: #b45309; }
    .btn-speak { border: none; background: #e8f0fe; color: #1a73e8; width: 26px; height: 26px; border-radius: 50%; font-size: .7rem; flex-shrink: 0; transition: all .15s; }
    .btn-speak:hover { background: #1a73e8; color: #fff; }

    /* ===== ISI PENJELASAN (HTML dari Summernote) ===== */
    .penjelasan { font-size: .92rem; color: #334155; line-height: 1.8; word-break: break-word; }
    .penjelasan h1, .penjelasan h2, .penjelasan h3, .penjelasan h4 { color: #1e293b; font-weight: 700; margin: 1.1em 0 .5em; }
    .penjelasan p { margin-bottom: .8em; }
    .penjelasan img { max-width: 100%; height: auto; border-radius: 12px; }
    .penjelasan iframe, .penjelasan video { display: block; width: 100%; max-width: 100%; height: auto; aspect-ratio: 16 / 9; border: 0; border-radius: 12px; margin: .5em 0; }
    .penjelasan table { width: 100%; border-collapse: collapse; margin-bottom: 1em; }
    .penjelasan table td, .penjelasan table th { border: 1px solid #e2e8f0; padding: 8px 10px; }
    .penjelasan blockquote { border-left: 4px solid #cbd5e1; padding-left: 14px; color: #64748b; }

    /* ===== EMPTY STATE ===== */
    .empty-state { padding: 40px 20px; text-align: center; }
    .empty-state-icon { width: 72px; height: 72px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.6rem; color: #94a3b8; }
</style>
@endsection

@section('content')
    @php
        preg_match_all('/\p{Han}/u', $paragraf->hanzi, $han);
        $jumlahHan = count($han[0]);
        $ada       = filled($paragraf->penjelasan_tata_bahasa);
    @endphp

    <div class="container">

        {{-- Header – di LUAR page-inner --}}
        <div class="ph-card show-page">
            <div class="ph-left">
                <div class="ph-icon show"><i class="fas fa-paragraph"></i></div>
                <div>
                    <h5 class="ph-title">{{ $paragraf->judul }}</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.paragraf.index') }}">Paragraf</a></li>
                        <li><span class="bc-active">Detail</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.paragraf.edit', $paragraf) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-pencil-alt me-1"></i> Edit
                </a>
                <a href="{{ route('admin.paragraf.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="page-inner">

            {{-- ===== Teks paragraf ===== --}}
            <div class="card form-card mb-4">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-align-left me-2"></i>Teks Paragraf</div>

                    <div class="blok-label">Hanzi</div>
                    <div id="hanzi-kalimat" class="hanzi-kalimat mb-1"></div>
                    <div class="goresan-info mb-3">Klik sebuah kalimat untuk melihat goresannya, atau klik <i class="fas fa-volume-up"></i> untuk mendengar kalimat itu saja.</div>

                    {{-- Pemutar suara paragraf utuh --}}
                    <div class="player-utuh mb-3">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-putar">
                                <i class="fas fa-play me-1"></i> Putar paragraf utuh
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-jeda" disabled>
                                <i class="fas fa-pause me-1"></i> Jeda
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-stop" disabled>
                                <i class="fas fa-stop me-1"></i> Stop
                            </button>
                            <select id="pilih-laju" class="form-select form-select-sm" style="width:auto;" title="Kecepatan">
                                <option value="0.9">Kecepatan normal</option>
                                <option value="0.7">Agak pelan</option>
                                <option value="0.55">Pelan</option>
                            </select>
                            <select id="pilih-suara" class="form-select form-select-sm d-none" style="width:auto;max-width:270px;" title="Pilih suara"></select>
                        </div>
                        <div class="putar-track"><div id="bar-putar" class="putar-bar"></div></div>
                        <div id="label-putar" class="goresan-info mb-0">Siap diputar. Seluruh paragraf dibaca berurutan, kalimat yang sedang dibaca akan disorot.</div>
                        <div id="info-suara" class="goresan-info galat d-none"></div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="blok-label">Pinyin</div>
                            <div class="blok-teks">{!! nl2br(e($paragraf->pinyin)) !!}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="blok-label">Arti Indonesia</div>
                            <div class="blok-teks">{!! nl2br(e($paragraf->arti_indonesia)) !!}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== Goresan & suara ===== --}}
            <div class="card form-card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <div class="section-divider mb-0"><i class="fas fa-pen-nib me-2"></i>Urutan Goresan &amp; Suara</div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-animasi">
                                <i class="fas fa-play me-1"></i> Animasi
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-latihan">
                                <i class="fas fa-pencil-alt me-1"></i> Latihan menulis
                            </button>
                        </div>
                    </div>

                    <div class="mode-seg mb-3" role="group" aria-label="Mode tampilan goresan">
                        <button type="button" class="mode-btn aktif" data-mode="kalimat">Per kalimat</button>
                        <button type="button" class="mode-btn" data-mode="semua">Seluruh paragraf</button>
                    </div>

                    <div id="area-goresan"></div>
                    <div id="info-goresan" class="goresan-info"></div>
                </div>
            </div>

            <div class="row g-4">

                {{-- Penjelasan tata bahasa --}}
                <div class="col-lg-8">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div class="section-divider mb-0"><i class="fas fa-book-open me-2"></i>Penjelasan Tata Bahasa</div>
                                <a href="{{ route('admin.paragraf.edit', $paragraf) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-pencil-alt me-1"></i> Kelola
                                </a>
                            </div>

                            @if ($ada)
                                {{-- HTML dari Summernote (sudah dibersihkan di controller saat disimpan) --}}
                                <div class="penjelasan">{!! $paragraf->penjelasan_tata_bahasa !!}</div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fas fa-book-open"></i></div>
                                    <div class="fw-semibold text-secondary mb-1">Belum ada penjelasan</div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        Tambahkan lewat tombol Edit atau import dari Excel
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Info --}}
                <div class="col-lg-4">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Informasi</div>

                            <div class="info-item">
                                <div class="info-label">Judul</div>
                                <div class="info-value">{{ $paragraf->judul }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Jumlah Karakter Han</div>
                                <div class="info-value">{{ $jumlahHan }} karakter</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Penjelasan</div>
                                <div class="info-value">
                                    <span class="badge {{ $ada ? 'badge-aktif' : 'badge-nonaktif' }}">{{ $ada ? 'Ada' : 'Belum ada' }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Dibuat</div>
                                <div class="info-value">{{ $paragraf->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Terakhir Diubah</div>
                                <div class="info-value">{{ $paragraf->updated_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/hanzi-writer@3.7.3/dist/hanzi-writer.min.js"></script>
    <script>
        (function () {
            const SEMUA = @json($paragraf->hanzi);

            // Pecah paragraf menjadi kalimat (akhiran 。！？；atau baris baru).
            const kalimat = (SEMUA.match(/[^。！？!?；;\n]+[。！？!?；;]?/g) || [SEMUA])
                .map(s => s.trim())
                .filter(Boolean);

            const wadahKalimat = document.getElementById('hanzi-kalimat');
            const chips = [];

            /* =============== GORESAN =============== */
            const area = document.getElementById('area-goresan');
            const infoGoresan = document.getElementById('info-goresan');
            const btnLatihan = document.getElementById('btn-latihan');
            const tombolMode = document.querySelectorAll('.mode-btn');

            let daftar = [];            // {writer, kotak, gagal}
            let terpilih = 0;           // kalimat yang tampil di mode "per kalimat"
            let mode = 'kalimat';       // 'kalimat' | 'semua'
            let sesi = 0;               // naik tiap tampilan berganti, untuk menghentikan animasi lama
            let latihanAktif = false;
            let sedangAnimasi = false;

            const hurufHan = teks => Array.from(teks).filter(c => /\p{Script=Han}/u.test(c));

            function infoAwal() {
                return mode === 'semua'
                    ? 'Menampilkan ' + daftar.length + ' karakter dari seluruh paragraf, dikelompokkan per kalimat. Klik kotak huruf untuk memutar animasi satu karakter.'
                    : 'Klik kotak huruf untuk memutar animasi satu karakter.';
            }

            function stopLatihan() {
                if (!latihanAktif) return;
                latihanAktif = false;
                daftar.forEach(i => { if (!i.gagal) { i.writer.cancelQuiz(); i.writer.showCharacter(); } });
                btnLatihan.innerHTML = '<i class="fas fa-pencil-alt me-1"></i> Latihan menulis';
                infoGoresan.textContent = infoAwal();
            }

            function buatKotak(wadah, char) {
                const kotak = document.createElement('div');
                kotak.className = 'goresan-kotak';
                wadah.appendChild(kotak);

                const item = { gagal: false, kotak: kotak };
                item.writer = HanziWriter.create(kotak, char, {
                    width: 110,
                    height: 110,
                    padding: 10,
                    showOutline: true,
                    strokeColor: '#1e293b',
                    outlineColor: '#e2e8f0',
                    radicalColor: '#1a73e8',
                    drawingColor: '#1a73e8',
                    strokeAnimationSpeed: 1,
                    delayBetweenStrokes: 250,
                    onLoadCharDataError: () => {
                        item.gagal = true;
                        kotak.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted px-2 text-center" style="font-size:.7rem;">Data goresan "' + char + '" tidak tersedia</div>';
                    },
                });

                kotak.addEventListener('click', () => {
                    if (item.gagal || latihanAktif) return;
                    item.writer.animateCharacter();
                });

                return item;
            }

            // Menggambar ulang area goresan sesuai mode: satu kalimat, atau seluruh paragraf.
            function tampilkan() {
                stopLatihan();
                sesi++;
                sedangAnimasi = false;
                daftar.forEach(i => { try { i.writer.pauseAnimation(); } catch (e) {} });
                daftar = [];
                area.innerHTML = '';

                const indeks = mode === 'semua' ? kalimat.map((_, i) => i) : [terpilih];

                indeks.forEach(n => {
                    const huruf = hurufHan(kalimat[n]);
                    if (huruf.length === 0) return;

                    const grup = document.createElement('div');
                    grup.className = 'goresan-grup';

                    if (mode === 'semua') {
                        const label = document.createElement('div');
                        label.className = 'goresan-grup-label';
                        const no = document.createElement('span');
                        no.className = 'no';
                        no.textContent = 'Kalimat ' + (n + 1);
                        const teks = document.createElement('span');
                        teks.className = 'teks';
                        teks.textContent = kalimat[n];
                        label.append(no, teks);
                        grup.appendChild(label);
                    }

                    const baris = document.createElement('div');
                    baris.className = 'goresan-area';
                    grup.appendChild(baris);
                    area.appendChild(grup);

                    huruf.forEach(char => daftar.push(buatKotak(baris, char)));
                });

                if (daftar.length === 0) {
                    infoGoresan.textContent = mode === 'semua'
                        ? 'Paragraf ini tidak punya karakter Han yang bisa ditampilkan goresannya.'
                        : 'Kalimat ini tidak punya karakter Han yang bisa ditampilkan goresannya.';
                    return;
                }
                infoGoresan.textContent = infoAwal();
            }

            function sinkronMode() {
                tombolMode.forEach(b => b.classList.toggle('aktif', b.dataset.mode === mode));
                chips.forEach((c, i) => c.classList.toggle('terpilih', mode === 'kalimat' && i === terpilih));
            }

            function pilihKalimat(n) {
                terpilih = n;
                mode = 'kalimat';
                sinkronMode();
                tampilkan();
            }

            function ubahMode(m) {
                if (m === mode) return;
                mode = m;
                sinkronMode();
                tampilkan();
            }

            async function animasiSemua() {
                if (sedangAnimasi || daftar.length === 0) return;
                sedangAnimasi = true;
                stopLatihan();
                const milikSesi = sesi;
                for (const item of daftar) {
                    if (milikSesi !== sesi) return; // tampilan diganti di tengah animasi
                    if (item.gagal) continue;
                    item.kotak.classList.add('aktif');
                    item.kotak.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                    await new Promise(selesai => item.writer.animateCharacter({ onComplete: selesai }));
                    item.kotak.classList.remove('aktif');
                }
                if (milikSesi === sesi) sedangAnimasi = false;
            }

            function mulaiLatihan() {
                if (sedangAnimasi || daftar.length === 0) return;
                latihanAktif = true;
                daftar.forEach(i => { if (!i.gagal) i.writer.quiz({ showHintAfterMisses: 2 }); });
                btnLatihan.innerHTML = '<i class="fas fa-times me-1"></i> Selesai latihan';
                infoGoresan.textContent = 'Tulis tiap goresan dengan mouse atau jari, sesuai urutan. Garis biru = benar.';
            }

            document.getElementById('btn-animasi').addEventListener('click', animasiSemua);
            btnLatihan.addEventListener('click', () => latihanAktif ? stopLatihan() : mulaiLatihan());
            tombolMode.forEach(b => b.addEventListener('click', () => ubahMode(b.dataset.mode)));

            /* =============== SUARA =============== */
            const info = document.getElementById('info-suara');
            const pilihan = document.getElementById('pilih-suara');
            const pilihLaju = document.getElementById('pilih-laju');
            const btnPutar = document.getElementById('btn-putar');
            const btnJeda = document.getElementById('btn-jeda');
            const btnStop = document.getElementById('btn-stop');
            const barPutar = document.getElementById('bar-putar');
            const labelPutar = document.getElementById('label-putar');
            const LABEL_AWAL = labelPutar.textContent;
            const KUNCI_SUARA = 'suara_mandarin';
            const bisaSuara = 'speechSynthesis' in window;
            let suara = null;
            let daftarMandarin = [];

            // Default: Google 國語（臺灣）(zh-TW), lalu suara Taiwan/HK lain, lalu suara Mandarin apa saja.
            function suaraDefault(daftar) {
                return daftar.find(v => /^zh[-_]TW$/i.test(v.lang) && /google/i.test(v.name))
                    || daftar.find(v => /^zh[-_](TW|HK)/i.test(v.lang))
                    || daftar[0]
                    || null;
            }

            function isiPilihan() {
                daftarMandarin = window.speechSynthesis.getVoices().filter(v => /^zh/i.test(v.lang));

                pilihan.innerHTML = '';
                if (daftarMandarin.length === 0) {
                    pilihan.classList.add('d-none');
                    suara = null;
                    return;
                }

                daftarMandarin.forEach(v => {
                    const opsi = document.createElement('option');
                    opsi.value = v.name;
                    opsi.textContent = v.name;
                    pilihan.appendChild(opsi);
                });

                let tersimpan = null;
                try { tersimpan = localStorage.getItem(KUNCI_SUARA); } catch (e) {}

                suara = daftarMandarin.find(v => v.name === tersimpan) || suaraDefault(daftarMandarin);
                pilihan.value = suara.name;
                pilihan.classList.remove('d-none');
            }

            if (bisaSuara) {
                isiPilihan();
                // Chrome memuat daftar suara belakangan, jadi isi ulang saat sudah siap.
                window.speechSynthesis.onvoiceschanged = isiPilihan;
            }

            function tandaiDibaca(n) {
                chips.forEach((c, i) => c.classList.toggle('sedang-dibaca', i === n));
            }

            // Mengembalikan true kalau suara siap dipakai; kalau tidak, tampilkan pesan.
            function suaraSiap() {
                info.classList.add('d-none');

                if (!bisaSuara) {
                    info.textContent = 'Browser ini belum mendukung suara. Coba Chrome atau Edge terbaru.';
                    info.classList.remove('d-none');
                    return false;
                }
                if (!suara) {
                    info.textContent = 'Suara Mandarin belum tersedia di perangkat ini. Di Windows: Settings > Time & language > Language & region, tambahkan bahasa Chinese, lalu restart browser. Chrome yang sedang online biasanya sudah punya suara Google bahasa Mandarin.';
                    info.classList.remove('d-none');
                    return false;
                }
                return true;
            }

            /* ---- Pemutar paragraf utuh ----
               Dibaca kalimat demi kalimat dalam satu alur (teks panjang yang dibaca sekaligus
               sering terpotong di Chrome). Jeda = berhenti di kalimat itu, Lanjutkan = ulang
               kalimat tersebut lalu terus ke kalimat berikutnya. */
            let status = 'diam';   // diam | putar | jeda
            let posisi = 0;        // indeks kalimat yang sedang / akan dibaca
            let token = 0;         // naik tiap berhenti, supaya callback lama diabaikan

            function perbaruiTombol() {
                btnPutar.disabled = status === 'putar';
                btnJeda.disabled = status !== 'putar';
                btnStop.disabled = status === 'diam';
                btnPutar.innerHTML = status === 'putar'
                    ? '<i class="fas fa-volume-up me-1"></i> Sedang diputar…'
                    : status === 'jeda'
                        ? '<i class="fas fa-play me-1"></i> Lanjutkan'
                        : '<i class="fas fa-play me-1"></i> Putar paragraf utuh';
            }

            function isiProgress(persen, teks) {
                barPutar.style.width = persen + '%';
                labelPutar.textContent = teks;
            }

            function hentiSuara() {
                token++;
                if (bisaSuara) window.speechSynthesis.cancel();
                tandaiDibaca(-1);
            }

            function setelahSelesai() {
                status = 'diam';
                posisi = 0;
                tandaiDibaca(-1);
                isiProgress(100, 'Selesai. Klik “Putar paragraf utuh” untuk mengulang.');
                perbaruiTombol();
            }

            function bacaKalimat(n, milik) {
                if (milik !== token) return;
                if (n >= kalimat.length) { setelahSelesai(); return; }

                posisi = n;
                const u = new SpeechSynthesisUtterance(kalimat[n]);
                u.voice = suara;
                u.lang = suara.lang;
                u.rate = parseFloat(pilihLaju.value);
                u.onstart = () => {
                    if (milik !== token) return;
                    tandaiDibaca(n);
                    isiProgress(Math.round((n / kalimat.length) * 100), 'Kalimat ' + (n + 1) + ' dari ' + kalimat.length);
                };
                u.onend = () => bacaKalimat(n + 1, milik);
                u.onerror = (e) => {
                    if (milik !== token) return;
                    if (e.error === 'interrupted' || e.error === 'canceled') return;
                    hentiSuara();
                    status = 'diam';
                    isiProgress(0, 'Suara berhenti karena ada masalah di browser (' + e.error + '). Coba putar lagi.');
                    perbaruiTombol();
                };
                window.speechSynthesis.speak(u);
            }

            btnPutar.addEventListener('click', () => {
                if (status === 'putar' || !suaraSiap()) return;
                hentiSuara();
                status = 'putar';
                perbaruiTombol();
                bacaKalimat(posisi, token);
            });

            btnJeda.addEventListener('click', () => {
                if (status !== 'putar') return;
                hentiSuara();
                status = 'jeda';
                isiProgress(Math.round((posisi / kalimat.length) * 100), 'Dijeda di kalimat ' + (posisi + 1) + ' dari ' + kalimat.length);
                perbaruiTombol();
            });

            btnStop.addEventListener('click', () => {
                hentiSuara();
                status = 'diam';
                posisi = 0;
                isiProgress(0, LABEL_AWAL);
                perbaruiTombol();
            });

            // Dengar satu kalimat saja (tombol speaker di tiap kalimat). Menghentikan pemutar utuh.
            function ucapkanSatu(teks, n) {
                if (!suaraSiap()) return;
                hentiSuara();
                if (status !== 'diam') {
                    status = 'diam';
                    posisi = 0;
                    isiProgress(0, LABEL_AWAL);
                    perbaruiTombol();
                }
                const u = new SpeechSynthesisUtterance(teks);
                u.voice = suara;
                u.lang = suara.lang;
                u.rate = 0.85;
                u.onstart = () => tandaiDibaca(n);
                u.onend = () => tandaiDibaca(-1);
                window.speechSynthesis.speak(u);
            }

            pilihan.addEventListener('change', () => {
                suara = daftarMandarin.find(v => v.name === pilihan.value) || suara;
                try { localStorage.setItem(KUNCI_SUARA, pilihan.value); } catch (e) {}
                // Suara baru dipakai mulai kalimat berikutnya; kalau sedang tidak memutar, beri contoh.
                if (status === 'diam') ucapkanSatu(kalimat[0], 0);
            });

            window.addEventListener('beforeunload', () => { if (bisaSuara) window.speechSynthesis.cancel(); });

            /* =============== DAFTAR KALIMAT =============== */
            kalimat.forEach((teks, n) => {
                const chip = document.createElement('div');
                chip.className = 'kalimat-chip';

                const span = document.createElement('span');
                span.className = 'kalimat-teks';
                span.textContent = teks;

                const speak = document.createElement('button');
                speak.type = 'button';
                speak.className = 'btn-speak';
                speak.title = 'Dengar kalimat';
                speak.innerHTML = '<i class="fas fa-volume-up"></i>';
                speak.addEventListener('click', e => {
                    e.stopPropagation();
                    ucapkanSatu(teks, n);
                });

                chip.append(span, speak);
                chip.addEventListener('click', () => pilihKalimat(n));

                wadahKalimat.appendChild(chip);
                chips.push(chip);
            });

            if (kalimat.length) pilihKalimat(0);
        })();
    </script>
@endsection
