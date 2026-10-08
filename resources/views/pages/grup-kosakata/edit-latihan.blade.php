@extends('layouts.user.user')

@section('title', 'Edit Latihan')

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

    .badge-ujian { background: #fee2e2; color: #b91c1c; }
    .sticky-side { position: sticky; top: 16px; }
    @media (max-width: 991.98px) { .sticky-side { position: static; } }

    /* ===== PILIHAN JENIS ===== */
    .opsi-jenis { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    @media (max-width: 575.98px) { .opsi-jenis { grid-template-columns: 1fr; } }
    .jenis-card { position: relative; margin: 0; cursor: pointer; }
    .jenis-card input { position: absolute; opacity: 0; pointer-events: none; }
    .jenis-card .isi { display: flex; gap: 12px; align-items: flex-start; border: 1.5px solid var(--gk-line); border-radius: 12px; padding: 12px 14px; background: var(--gk-soft); transition: border-color .15s, background .15s; height: 100%; }
    .jenis-card:hover .isi { border-color: #93c5fd; }
    .jenis-card input:checked + .isi { border-color: var(--gk-blue); background: #e8f0fe; }
    .jenis-card input:focus-visible + .isi { outline: 3px solid rgba(26,115,232,.35); outline-offset: 2px; }
    .jenis-card .ikon { width: 34px; height: 34px; border-radius: 9px; background: #fff; color: var(--gk-muted); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .jenis-card input:checked + .isi .ikon { color: var(--gk-blue); }
    .jenis-card .nama { font-weight: 700; font-size: .88rem; color: var(--gk-ink); }
    .jenis-card .ket { font-size: .76rem; color: var(--gk-muted); margin-top: 2px; }

    /* ===== PENGATURAN ANGKA ===== */
    .set-item { margin-bottom: 16px; }
    .set-item .help-text { margin-top: 4px; }
    .form-switch .form-check-input { width: 2.6em; height: 1.35em; cursor: pointer; }
    .form-switch .form-check-label { font-size: .85rem; font-weight: 600; color: #334155; padding-left: 6px; }

    .ringkas-item { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: .83rem; color: var(--gk-muted); }
    .ringkas-item:last-child { border-bottom: none; padding-bottom: 0; }
    .ringkas-item strong { color: var(--gk-ink); }
</style>
@endsection

@section('content')
    <div class="container">

        <div class="ph-card edit-page">
            <div class="ph-left">
                <div class="ph-icon edit"><i class="fas fa-sliders-h"></i></div>
                <div style="min-width:0;">
                    <h5 class="ph-title">Edit Latihan</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.grup-kosakata.index') }}">Grup Kosakata</a></li>
                        <li><a href="{{ route('admin.grup-kosakata.show', $grup) }}">{{ \Illuminate\Support\Str::limit($grup->nama, 30) }}</a></li>
                        <li><span class="bc-active">Edit latihan</span></li>
                    </ol>
                </div>
            </div>
            <div class="ph-actions">
                <a href="{{ route('admin.grup-kosakata.show', $grup) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke detail
                </a>
            </div>
        </div>

        <div class="page-inner">

            @if (session('error') || $errors->any())
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') ?? $errors->first() }}</div>
            @endif

            @if ($jumlahPengerjaan > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Latihan ini sudah dikerjakan pelajar {{ $jumlahPengerjaan }} kali. Hati-hati saat mengubah jenis, nilai lulus, dan batas percobaan.
                </div>
            @endif

            <form method="POST" action="{{ route('admin.grup-kosakata.latihan.update', [$grup, $latihan]) }}" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- ===== Kiri: identitas latihan ===== --}}
                    <div class="col-lg-8">
                        <div class="card form-card">
                            <div class="card-body">
                                <div class="section-divider"><span><i class="fas fa-clipboard-list me-2"></i>Informasi latihan</span></div>

                                <div class="mb-3">
                                    <label class="lbl" for="judul">Judul <span class="required-mark">*</span></label>
                                    <input type="text" id="judul" name="judul" maxlength="150"
                                           class="form-control @error('judul') is-invalid @enderror"
                                           value="{{ old('judul', $latihan->judul) }}" required>
                                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="lbl" for="deskripsi">Deskripsi</label>
                                    <textarea id="deskripsi" name="deskripsi" rows="3" maxlength="2000"
                                              class="form-control @error('deskripsi') is-invalid @enderror"
                                              placeholder="Opsional, tampil untuk pelajar sebelum mulai mengerjakan">{{ old('deskripsi', $latihan->deskripsi) }}</textarea>
                                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="lbl">Jenis <span class="required-mark">*</span></div>
                                    @php $jenis = old('jenis', $latihan->jenis); @endphp
                                    <div class="opsi-jenis">
                                        <label class="jenis-card">
                                            <input type="radio" name="jenis" value="latihan" @checked($jenis === 'latihan')>
                                            <span class="isi">
                                                <span class="ikon"><i class="fas fa-pencil-alt"></i></span>
                                                <span>
                                                    <div class="nama">Latihan</div>
                                                    <div class="ket">Untuk berlatih, bisa diulang.</div>
                                                </span>
                                            </span>
                                        </label>
                                        <label class="jenis-card">
                                            <input type="radio" name="jenis" value="ujian" @checked($jenis === 'ujian')>
                                            <span class="isi">
                                                <span class="ikon"><i class="fas fa-file-alt"></i></span>
                                                <span>
                                                    <div class="nama">Ujian</div>
                                                    <div class="ket">Untuk menilai, biasanya dengan batas waktu dan percobaan.</div>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    @error('jenis') <div class="text-danger mt-1" style="font-size:.76rem;">{{ $message }}</div> @enderror
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="lbl" for="grup_kosakata_id">Grup kosakata asal <span class="required-mark">*</span></label>
                                        <select id="grup_kosakata_id" name="grup_kosakata_id" class="form-select @error('grup_kosakata_id') is-invalid @enderror">
                                            @foreach ($grups as $g)
                                                <option value="{{ $g->id }}" @selected((int) old('grup_kosakata_id', $latihan->grup_kosakata_id) === (int) $g->id)>{{ $g->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('grup_kosakata_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <div class="help-text">Mengganti grup memindahkan latihan ini ke halaman grup tersebut.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="lbl" for="level_hsk_id">Level HSK</label>
                                        <select id="level_hsk_id" name="level_hsk_id" class="form-select @error('level_hsk_id') is-invalid @enderror">
                                            <option value="">Tanpa level</option>
                                            @foreach ($levels as $level)
                                                <option value="{{ $level->id }}" @selected((int) old('level_hsk_id', $latihan->level_hsk_id) === (int) $level->id)>{{ $level->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('level_hsk_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===== Kanan: pengaturan pengerjaan ===== --}}
                    <div class="col-lg-4">
                        <div class="sticky-side">
                            <div class="card form-card mb-4">
                                <div class="card-body">
                                    <div class="section-divider"><span><i class="fas fa-sliders-h me-2"></i>Pengaturan pengerjaan</span></div>

                                    <div class="set-item">
                                        <label class="lbl" for="durasi_menit">Durasi (menit)</label>
                                        <input type="number" id="durasi_menit" name="durasi_menit" min="1" max="65535"
                                               class="form-control @error('durasi_menit') is-invalid @enderror"
                                               value="{{ old('durasi_menit', $latihan->durasi_menit) }}" placeholder="Tanpa batas waktu">
                                        @error('durasi_menit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <div class="help-text">Kosongkan jika tidak ada batas waktu.</div>
                                    </div>

                                    <div class="set-item">
                                        <label class="lbl" for="nilai_lulus">Nilai lulus</label>
                                        <input type="number" id="nilai_lulus" name="nilai_lulus" min="0" max="100"
                                               class="form-control @error('nilai_lulus') is-invalid @enderror"
                                               value="{{ old('nilai_lulus', $latihan->nilai_lulus) }}" placeholder="Tanpa nilai lulus">
                                        @error('nilai_lulus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <div class="help-text">Isi 0 sampai 100. Kosongkan jika tidak ada.</div>
                                    </div>

                                    <div class="set-item">
                                        <label class="lbl" for="maks_percobaan">Maks percobaan</label>
                                        <input type="number" id="maks_percobaan" name="maks_percobaan" min="1" max="255"
                                               class="form-control @error('maks_percobaan') is-invalid @enderror"
                                               value="{{ old('maks_percobaan', $latihan->maks_percobaan) }}" placeholder="Tanpa batas">
                                        @error('maks_percobaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <div class="help-text">Kosongkan agar pelajar bisa mengulang tanpa batas.</div>
                                    </div>

                                    <div class="form-check form-switch mb-1">
                                        <input class="form-check-input" type="checkbox" role="switch" id="aktif" name="aktif" value="1" @checked($errors->any() ? old('aktif') : $latihan->aktif)>
                                        <label class="form-check-label" for="aktif">Aktif (tampil untuk pelajar)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="card form-card">
                                <div class="card-body">
                                    <div class="ringkas-item"><span>Jumlah soal</span><strong>{{ $latihan->soals_count }}</strong></div>
                                    <div class="ringkas-item"><span>Sudah dikerjakan</span><strong>{{ $jumlahPengerjaan }} kali</strong></div>
                                    <div class="ringkas-item"><span>Dibuat</span><strong>{{ $latihan->created_at?->format('d/m/Y H:i') }}</strong></div>

                                    <div class="d-flex gap-2 mt-3">
                                        <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-save me-1"></i> Simpan perubahan</button>
                                        <a href="{{ route('admin.grup-kosakata.show', $grup) }}" class="btn btn-outline-secondary">Batal</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
