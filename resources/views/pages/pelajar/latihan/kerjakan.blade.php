@extends('layouts.user.user')

@section('title', 'Mengerjakan Latihan')

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

    /* ===== SOAL ===== */
    .pertanyaan { font-size: .95rem; font-weight: 600; color: #1e293b; margin-bottom: 12px; }
    .kalimat-isian { font-size: 1.5rem; font-weight: 600; color: #1e293b; background: #f8fafc; border-radius: 12px; padding: 14px 18px; margin-bottom: 14px; font-family: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', 'Plus Jakarta Sans', sans-serif; }
    .pilihan { display: flex; align-items: center; gap: 10px; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 10px 14px; margin-bottom: 8px; cursor: pointer; font-size: .88rem; color: #334155; transition: border-color .15s, background .15s; }
    .pilihan:hover { background: #f8fafc; border-color: #cbd5e1; }
    .pilihan input { margin: 0; flex-shrink: 0; }
    .pilihan .huruf { width: 26px; height: 26px; border-radius: 8px; background: #f1f5f9; color: #64748b; font-weight: 700; font-size: .75rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .pilihan:has(input:checked) { border-color: #1a73e8; background: #e8f0fe; }
    .pilihan:has(input:checked) .huruf { background: #1a73e8; color: #fff; }

    /* ===== SUARA TTS ===== */
    .btn-suara { border: 1.5px solid #bfdbfe; background: #f0f7ff; color: #1a73e8; border-radius: 10px; font-size: .83rem; font-weight: 600; padding: 7px 14px; transition: all .15s; }
    .btn-suara:hover { background: #e8f0fe; border-color: #93c5fd; }
    .btn-suara.sedang-putar { background: #dcfce7; border-color: #16a34a; color: #15803d; }

    /* ===== STATUS MENGAMBANG ===== */
    .float-status { position: fixed; right: 20px; bottom: 20px; z-index: 50; background: #1e293b; color: #fff; border-radius: 999px; padding: 10px 18px; font-size: .82rem; box-shadow: 0 6px 20px rgba(0,0,0,.25); display: flex; align-items: center; gap: 12px; }
    .float-status .sep { width: 1px; height: 16px; background: rgba(255,255,255,.25); }
    .float-status.waktu-kritis #timer-teks { color: #fca5a5; }
</style>
@endsection

@section('content')
    @php $totalSoal = $grupSoal->soals->count(); @endphp

    <div class="container">

        {{-- Header – di LUAR page-inner --}}
        <div class="ph-card">
            <div class="ph-left">
                <div class="ph-icon"><i class="fas fa-pen"></i></div>
                <div>
                    <h5 class="ph-title">{{ $grupSoal->judul }}</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('pelajar.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('pelajar.latihan.index') }}">Latihan &amp; Ujian</a></li>
                        <li><a href="{{ route('pelajar.latihan.show', $grupSoal) }}">Detail</a></li>
                        <li><span class="bc-active">Mengerjakan</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge badge-info-soft">{{ $totalSoal }} soal</span>
                <span class="badge badge-nonaktif">{{ $grupSoal->durasi_menit ? $grupSoal->durasi_menit . ' menit' : 'Tanpa batas waktu' }}</span>
            </div>
        </div>

        <div class="page-inner">

            <form method="POST" action="{{ route('pelajar.pengerjaan.kumpulkan', $hasilUjian) }}" id="form-soal">
                @csrf

                <div id="info-suara" class="alert alert-warning py-2 d-none" style="font-size:.82rem;"></div>

                @foreach ($grupSoal->soals as $i => $soal)
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div class="section-divider mb-0">
                                    <i class="fas fa-question-circle me-2"></i>Soal {{ $i + 1 }}
                                </div>
                                <span class="badge {{ $soal->bagian === 'listening' ? 'badge-warn' : 'badge-info-soft' }}">
                                    {{ $soal->bagian === 'listening' ? 'Listening' : 'Reading' }}
                                </span>
                            </div>

                            <div class="pertanyaan">{{ $soal->pertanyaan }}</div>

                            @if ($soal->paragraf)
                                <div class="kalimat-isian">{!! nl2br(e($soal->paragraf)) !!}</div>
                            @endif

                            @if ($soal->audio_url)
                                <audio controls preload="none" class="mb-3 w-100" src="{{ $soal->audio_url }}"></audio>
                            @elseif ($soal->teks_suara)
                                <div class="d-flex gap-2 flex-wrap mb-3">
                                    <button type="button" class="btn-suara" data-teks="{{ $soal->teks_suara }}" data-laju="0.85">
                                        <i class="fas fa-volume-up me-1"></i> Putar suara
                                    </button>
                                    <button type="button" class="btn-suara" data-teks="{{ $soal->teks_suara }}" data-laju="0.55">
                                        <i class="fas fa-volume-down me-1"></i> Pelan
                                    </button>
                                </div>
                            @elseif ($soal->bagian === 'listening')
                                <div class="alert alert-secondary py-2" style="font-size:.82rem;">Audio untuk soal ini belum tersedia.</div>
                            @endif

                            @foreach ($soal->pilihan as $huruf => $teks)
                                <label class="pilihan">
                                    <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $huruf }}" class="form-check-input">
                                    <span class="huruf">{{ $huruf }}</span>
                                    <span>{{ $teks }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-end gap-2 mb-5 pb-4">
                    <a href="{{ route('pelajar.latihan.show', $grupSoal) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Keluar (lanjut nanti)
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Kumpulkan Jawaban
                    </button>
                </div>
            </form>

        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}

    {{-- Status mengambang: jumlah terjawab + sisa waktu --}}
    <div class="float-status" id="float-status">
        <span><i class="fas fa-check-circle me-1"></i> Terjawab <strong id="terjawab">0</strong>/{{ $totalSoal }}</span>
        @if ($sisaDetik !== null)
            <span class="sep"></span>
            <span><i class="fas fa-clock me-1"></i> <strong id="timer-teks">--:--</strong></span>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        (function () {
            const form  = document.getElementById('form-soal');
            const total = {{ $totalSoal }};
            const label = document.getElementById('terjawab');
            let kirim   = false;

            const hitung = () => form.querySelectorAll('input[type=radio]:checked').length;

            form.addEventListener('change', () => { label.textContent = hitung(); });

            form.addEventListener('submit', (e) => {
                if (kirim) return;
                const sisa = total - hitung();
                if (sisa > 0 && !confirm('Masih ada ' + sisa + ' soal belum dijawab. Kumpulkan sekarang?')) {
                    e.preventDefault();
                    return;
                }
                kirim = true;
            });

            @if ($sisaDetik !== null)
            let detik = {{ (int) $sisaDetik }};
            const teks = document.getElementById('timer-teks');
            const kotak = document.getElementById('float-status');

            function tampil() {
                const m = Math.floor(detik / 60), s = detik % 60;
                teks.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                kotak.classList.toggle('waktu-kritis', detik <= 60);
            }

            tampil();
            const jalan = setInterval(() => {
                detik--;
                if (detik <= 0) {
                    clearInterval(jalan);
                    detik = 0;
                    tampil();
                    kirim = true;   // waktu habis: kumpulkan tanpa konfirmasi
                    form.submit();
                    return;
                }
                tampil();
            }, 1000);
            @endif

            /* ===== Suara TTS untuk soal listening tanpa file audio ===== */
            const bisaSuara   = 'speechSynthesis' in window;
            const KUNCI_SUARA = 'suara_mandarin';   // sama dengan halaman show paragraf
            const infoSuara   = document.getElementById('info-suara');
            let suara = null;

            function pilihSuara() {
                if (!bisaSuara) return null;
                const daftar = window.speechSynthesis.getVoices().filter(v => /^zh/i.test(v.lang));
                if (!daftar.length) return null;

                let tersimpan = null;
                try { tersimpan = localStorage.getItem(KUNCI_SUARA); } catch (e) {}

                return daftar.find(v => v.name === tersimpan)
                    || daftar.find(v => /^zh[-_]TW$/i.test(v.lang) && /google/i.test(v.name))
                    || daftar.find(v => /^zh[-_](TW|HK)/i.test(v.lang))
                    || daftar[0];
            }

            if (bisaSuara) {
                suara = pilihSuara();
                // Chrome memuat daftar suara belakangan.
                window.speechSynthesis.onvoiceschanged = () => { suara = pilihSuara(); };
            }

            function tampilInfoSuara(pesan) {
                infoSuara.textContent = pesan;
                infoSuara.classList.remove('d-none');
            }

            function hentiSuara() {
                if (bisaSuara) window.speechSynthesis.cancel();
                document.querySelectorAll('.btn-suara.sedang-putar').forEach(b => b.classList.remove('sedang-putar'));
            }

            document.querySelectorAll('.btn-suara').forEach(btn => {
                btn.addEventListener('click', () => {
                    infoSuara.classList.add('d-none');

                    if (!bisaSuara) {
                        tampilInfoSuara('Browser ini belum mendukung suara. Coba Chrome atau Edge terbaru.');
                        return;
                    }
                    if (!suara) suara = pilihSuara();
                    if (!suara) {
                        tampilInfoSuara('Suara Mandarin belum tersedia di perangkat ini. Di Windows: Settings > Time & language > Language & region, tambahkan bahasa Chinese, lalu restart browser.');
                        return;
                    }

                    const sedangPutar = btn.classList.contains('sedang-putar');
                    hentiSuara();
                    if (sedangPutar) return;   // klik lagi = berhenti

                    const u = new SpeechSynthesisUtterance(btn.dataset.teks);
                    u.voice = suara;
                    u.lang  = suara.lang;
                    u.rate  = parseFloat(btn.dataset.laju);
                    u.onend = u.onerror = () => btn.classList.remove('sedang-putar');

                    btn.classList.add('sedang-putar');
                    window.speechSynthesis.speak(u);
                });
            });

            window.addEventListener('beforeunload', hentiSuara);

            label.textContent = hitung();
        })();
    </script>
@endsection
