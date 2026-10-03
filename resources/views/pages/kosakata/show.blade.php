@extends('layouts.user.user')

@section('title', 'Detail Kosakata')

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
    .form-card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .section-divider { background: #f8f9fa; border-left: 4px solid #1269db; padding: 8px 14px; border-radius: 0 6px 6px 0; font-weight: 600; font-size: .9rem; color: #1269db; margin-bottom: 1rem; }
    .filter-card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); overflow: hidden; }
    .filter-card .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; padding: 18px 24px; }
    .filter-card .card-header h5 { font-size: .95rem; font-weight: 700; color: #1e293b; }

    /* ===== INFO ITEM ===== */
    .info-item { padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
    .info-item:last-child { border-bottom: none; padding-bottom: 0; }
    .info-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; }
    .info-value { font-size: .88rem; font-weight: 500; color: #1e293b; margin-top: 2px; word-break: break-word; }
    .hanzi-besar { font-size: 2.6rem; font-weight: 600; color: #1e293b; line-height: 1.2; font-family: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', 'Plus Jakarta Sans', sans-serif; }

    /* ===== TABLE ===== */
    .table thead th { background: #f8fafc; color: #64748b; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; padding: 12px 16px; border-bottom: 2px solid #e2e8f0; border-top: none; white-space: nowrap; }
    .table tbody td { padding: 13px 16px; vertical-align: middle; font-size: .85rem; color: #334155; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:last-child td { border-bottom: none; }
    .table-hover tbody tr:hover td { background: #f8fafc; }
    .hanzi-text { font-size: 1.15rem; font-weight: 600; color: #1e293b; font-family: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', 'Plus Jakarta Sans', sans-serif; }

    .badge { font-size: .7rem; font-weight: 600; padding: 4px 9px; border-radius: 6px; letter-spacing: .2px; }
    .badge-aktif { background: #dcfce7; color: #15803d; }
    .badge-info-soft { background: #e8f0fe; color: #1a73e8; }
    .badge-nonaktif { background: #f1f5f9; color: #64748b; }

    /* ===== BUTTONS ===== */
    .btn-primary { background: linear-gradient(135deg, #1a73e8, #1558b0); border: none; border-radius: 10px; font-weight: 600; font-size: .83rem; padding: 8px 18px; box-shadow: 0 2px 8px rgba(26,115,232,.35); transition: all .2s ease; }
    .btn-primary:hover { background: linear-gradient(135deg, #1558b0, #0f3e82); box-shadow: 0 4px 14px rgba(26,115,232,.45); transform: translateY(-1px); }
    .btn-outline-secondary { border-radius: 10px; font-size: .83rem; border-color: #e2e8f0; color: #64748b; padding: 7px 12px; }
    .btn-outline-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }


    /* ===== GORESAN & SUARA ===== */
    .goresan-area { display: flex; flex-wrap: wrap; gap: 14px; }
    .goresan-kotak { width: 160px; height: 160px; border: 1.5px solid #e2e8f0; border-radius: 14px; background: #fff; position: relative; cursor: pointer; transition: border-color .2s, box-shadow .2s; }
    .goresan-kotak:hover { border-color: #1a73e8; box-shadow: 0 2px 10px rgba(26,115,232,.15); }
    .goresan-kotak::before, .goresan-kotak::after { content: ''; position: absolute; background: repeating-linear-gradient(90deg, #e2e8f0 0 4px, transparent 4px 8px); pointer-events: none; }
    .goresan-kotak::before { left: 0; right: 0; top: 50%; height: 1px; }
    .goresan-kotak::after { top: 0; bottom: 0; left: 50%; width: 1px; background: repeating-linear-gradient(0deg, #e2e8f0 0 4px, transparent 4px 8px); }
    .goresan-kotak svg { position: relative; z-index: 1; }
    .goresan-info { font-size: .78rem; color: #64748b; margin-top: 10px; }
    .goresan-info.galat { color: #b45309; }
    .btn-speak { border: none; background: #e8f0fe; color: #1a73e8; width: 26px; height: 26px; border-radius: 50%; font-size: .7rem; margin-left: 6px; vertical-align: middle; transition: all .15s; }
    .btn-speak:hover { background: #1a73e8; color: #fff; }

    /* ===== EMPTY STATE ===== */
    .empty-state { padding: 60px 20px; }
    .empty-state-icon { width: 72px; height: 72px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.6rem; color: #94a3b8; }
</style>
@endsection

@section('content')
    <div class="container">

        {{-- Header – di LUAR page-inner --}}
        <div class="ph-card show-page">
            <div class="ph-left">
                <div class="ph-icon show"><i class="fas fa-language"></i></div>
                <div>
                    <h5 class="ph-title">{{ $kosakata->hanzi }} &nbsp;<span class="text-muted fw-normal">{{ $kosakata->pinyin }}</span></h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.kosakata.index') }}">Kosakata</a></li>
                        <li><span class="bc-active">Detail</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.kosakata.edit', $kosakata) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-pencil-alt me-1"></i> Edit
                </a>
                <a href="{{ route('admin.kosakata.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="page-inner">

            {{-- Goresan & suara --}}
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
                            <button type="button" class="btn btn-outline-secondary btn-sm btn-speak-utama" data-laju="0.9">
                                <i class="fas fa-volume-up me-1"></i> Dengar
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm btn-speak-utama" data-laju="0.55">
                                <i class="fas fa-volume-down me-1"></i> Pelan
                            </button>
                            <select id="pilih-suara" class="form-select form-select-sm d-none" style="width:auto;max-width:270px;border-radius:10px;font-size:.8rem;" title="Pilih suara"></select>
                        </div>
                    </div>

                    <div id="area-goresan" class="goresan-area"></div>
                    <div id="info-goresan" class="goresan-info">Klik kotak huruf untuk memutar animasi satu karakter.</div>
                    <div id="info-suara" class="goresan-info galat d-none"></div>
                </div>
            </div>

            <div class="row g-4">

                {{-- Info kosakata --}}
                <div class="col-lg-4">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Informasi Kosakata</div>

                            <div class="info-item">
                                <div class="hanzi-besar">{{ $kosakata->hanzi }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Pinyin</div>
                                <div class="info-value">{{ $kosakata->pinyin }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Cara Baca Indonesia</div>
                                <div class="info-value">{{ $kosakata->baca_indonesia ?: '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Arti Indonesia</div>
                                <div class="info-value">{{ $kosakata->arti_indonesia }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Arti Inggris</div>
                                <div class="info-value">{{ $kosakata->english ?: '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Kategori</div>
                                <div class="info-value">
                                    @if ($kosakata->kategori)
                                        <a href="{{ route('admin.kategori.show', $kosakata->kategori) }}" class="text-decoration-none">
                                            {{ $kosakata->kategori->nama }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Level HSK</div>
                                <div class="info-value">{{ $kosakata->levelHsk->nama ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Urutan</div>
                                <div class="info-value">{{ $kosakata->urutan }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Dibuat</div>
                                <div class="info-value">{{ $kosakata->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Terakhir Diubah</div>
                                <div class="info-value">{{ $kosakata->updated_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Daftar contoh kalimat --}}
                <div class="col-lg-8">
                    <div class="card filter-card shadow-sm">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">
                                <i class="fas fa-comment-dots text-primary me-2 opacity-75"></i>Contoh Kalimat
                                <span class="badge {{ $kosakata->contohKalimats->count() ? 'badge-aktif' : 'badge-nonaktif' }} ms-1">
                                    {{ $kosakata->contohKalimats->count() }}
                                </span>
                            </h5>
                            <a href="{{ route('admin.kosakata.edit', $kosakata) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-plus me-1"></i> Kelola
                            </a>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table-hover mb-0 table">
                                    <thead>
                                        <tr>
                                            <th width="40">#</th>
                                            <th>Kalimat</th>
                                            <th>Arti</th>
                                            <th>Catatan Tata Bahasa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kosakata->contohKalimats as $i => $contoh)
                                            <tr>
                                                <td class="text-muted">{{ $i + 1 }}</td>
                                                <td>
                                                    <div class="hanzi-text">{{ $contoh->hanzi }}<button type="button" class="btn-speak" data-teks="{{ $contoh->hanzi }}" title="Dengar kalimat"><i class="fas fa-volume-up"></i></button></div>
                                                    <div class="text-muted" style="font-size:.78rem;">{{ $contoh->pinyin }}</div>
                                                </td>
                                                <td>{{ $contoh->arti_indonesia }}</td>
                                                <td class="text-muted">{{ $contoh->catatan_tata_bahasa ?: '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="p-0 text-center">
                                                    <div class="empty-state">
                                                        <div class="empty-state-icon"><i class="fas fa-comment-dots"></i></div>
                                                        <div class="fw-semibold text-secondary mb-1">Belum ada contoh kalimat</div>
                                                        <div class="text-muted" style="font-size:.8rem;">
                                                            Tambahkan lewat tombol Edit atau import dari Excel
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
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
    <script src="https://cdn.jsdelivr.net/npm/hanzi-writer@3.7.3/dist/hanzi-writer.min.js"></script>
    <script>
        (function () {
            const KATA = @json($kosakata->hanzi);

            /* =============== GORESAN =============== */
            const area = document.getElementById('area-goresan');
            const infoGoresan = document.getElementById('info-goresan');
            const daftar = []; // {writer, gagal}
            let latihanAktif = false;

            Array.from(KATA)
                .filter(c => /\p{Script=Han}/u.test(c))
                .forEach(char => {
                    const kotak = document.createElement('div');
                    kotak.className = 'goresan-kotak';
                    area.appendChild(kotak);

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

                    daftar.push(item);
                });

            if (daftar.length === 0) {
                infoGoresan.textContent = 'Tidak ada karakter Han yang bisa ditampilkan goresannya.';
            }

            let sedangAnimasi = false;
            async function animasiSemua() {
                if (sedangAnimasi) return;
                sedangAnimasi = true;
                stopLatihan();
                for (const item of daftar) {
                    if (item.gagal) continue;
                    await new Promise(selesai => item.writer.animateCharacter({ onComplete: selesai }));
                }
                sedangAnimasi = false;
            }

            const btnLatihan = document.getElementById('btn-latihan');

            function stopLatihan() {
                if (!latihanAktif) return;
                latihanAktif = false;
                daftar.forEach(i => { if (!i.gagal) { i.writer.cancelQuiz(); i.writer.showCharacter(); } });
                btnLatihan.innerHTML = '<i class="fas fa-pencil-alt me-1"></i> Latihan menulis';
                infoGoresan.textContent = 'Klik kotak huruf untuk memutar animasi satu karakter.';
            }

            function mulaiLatihan() {
                if (sedangAnimasi) return;
                latihanAktif = true;
                daftar.forEach(i => { if (!i.gagal) i.writer.quiz({ showHintAfterMisses: 2 }); });
                btnLatihan.innerHTML = '<i class="fas fa-times me-1"></i> Selesai latihan';
                infoGoresan.textContent = 'Tulis tiap goresan dengan mouse atau jari, sesuai urutan. Garis biru = benar.';
            }

            document.getElementById('btn-animasi').addEventListener('click', animasiSemua);
            btnLatihan.addEventListener('click', () => latihanAktif ? stopLatihan() : mulaiLatihan());

            /* =============== SUARA =============== */
            const info = document.getElementById('info-suara');
            const pilihan = document.getElementById('pilih-suara');
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

            pilihan.addEventListener('change', () => {
                suara = daftarMandarin.find(v => v.name === pilihan.value) || suara;
                try { localStorage.setItem(KUNCI_SUARA, pilihan.value); } catch (e) {}
                ucapkan(KATA, 0.9);
            });

            function ucapkan(teks, laju) {
                info.classList.add('d-none');

                if (!bisaSuara) {
                    info.textContent = 'Browser ini belum mendukung suara. Coba Chrome atau Edge terbaru.';
                    info.classList.remove('d-none');
                    return;
                }
                if (!suara) {
                    info.textContent = 'Suara Mandarin belum tersedia di perangkat ini. Di Windows: Settings > Time & language > Language & region, tambahkan bahasa Chinese, lalu restart browser. Chrome yang sedang online biasanya sudah punya suara Google bahasa Mandarin.';
                    info.classList.remove('d-none');
                    return;
                }

                window.speechSynthesis.cancel();
                const u = new SpeechSynthesisUtterance(teks);
                u.voice = suara;
                u.lang = suara.lang;
                u.rate = laju;
                window.speechSynthesis.speak(u);
            }

            document.querySelectorAll('.btn-speak-utama').forEach(btn => {
                btn.addEventListener('click', () => ucapkan(KATA, parseFloat(btn.dataset.laju)));
            });
            document.querySelectorAll('.btn-speak').forEach(btn => {
                btn.addEventListener('click', () => ucapkan(btn.dataset.teks, 0.85));
            });
        })();
    </script>
@endsection
