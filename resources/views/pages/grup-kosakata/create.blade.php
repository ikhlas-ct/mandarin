@extends('layouts.user.user')

@section('title', 'Buat Grup Kosakata')

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

    .picker-bar { display: grid; grid-template-columns: minmax(0, 1fr) 170px; gap: 8px; margin-bottom: 10px; }
    @media (max-width: 575.98px) { .picker-bar { grid-template-columns: 1fr; } }
    .picker-tools { display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; margin-bottom: 8px; }
    .picker-tools .btn { padding: 4px 10px; font-size: .76rem; }
    .btn-chip-on { background: #e8f0fe; border-color: var(--gk-blue); color: var(--gk-blue); }

    .tabel-kata-wrap { max-height: 520px; overflow: auto; border: 1.5px solid var(--gk-line); border-radius: 12px; }
    .tabel-kata { margin-bottom: 0; font-size: .85rem; }
    .tabel-kata thead th { position: sticky; top: 0; z-index: 2; background: var(--gk-soft); border-bottom: 1.5px solid var(--gk-line); font-size: .76rem; font-weight: 600; color: var(--gk-faint); padding: 9px 10px; }
    .tabel-kata td { padding: 8px 10px; vertical-align: middle; color: #334155; border-color: #f1f5f9; }
    .tabel-kata tbody tr { cursor: pointer; }
    .tabel-kata tbody tr:hover { background: var(--gk-soft); }
    .tabel-kata tbody tr.terpilih { background: #e8f0fe; }
    .tabel-kata tbody tr.tersembunyi { display: none; }

    .penghitung { font-size: .8rem; font-weight: 600; padding: 6px 12px; border-radius: 999px; background: #e0f2fe; color: #0369a1; display: inline-flex; align-items: center; gap: 6px; }
    .penghitung.kurang { background: #fef3c7; color: #92400e; }
    .sticky-side { position: sticky; top: 16px; }
    @media (max-width: 991.98px) { .sticky-side { position: static; } }
</style>
@endsection

@section('content')
    <div class="container">

        <div class="ph-card create-page">
            <div class="ph-left">
                <div class="ph-icon create"><i class="fas fa-plus"></i></div>
                <div>
                    <h5 class="ph-title">Buat Grup Kosakata</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.grup-kosakata.index') }}">Grup Kosakata</a></li>
                        <li><span class="bc-active">Buat</span></li>
                    </ol>
                </div>
            </div>
            <div class="ph-actions">
                <a href="{{ route('admin.grup-kosakata.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="page-inner">
            <form method="POST" action="{{ route('admin.grup-kosakata.store') }}" novalidate id="form-grup">
                @csrf

                <div class="row g-4">

                    {{-- ===== Kiri: identitas grup ===== --}}
                    <div class="col-lg-4">
                        <div class="sticky-side">
                            <div class="card form-card">
                                <div class="card-body">
                                    <div class="section-divider"><span><i class="fas fa-layer-group me-2"></i>Identitas grup</span></div>

                                    <div class="mb-3">
                                        <label class="lbl" for="nama">Nama grup <span class="required-mark">*</span></label>
                                        <input type="text" id="nama" name="nama" maxlength="150"
                                               class="form-control @error('nama') is-invalid @enderror"
                                               placeholder="mis. Salam &amp; perkenalan"
                                               value="{{ old('nama') }}" required>
                                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="lbl" for="keterangan">Keterangan</label>
                                        <textarea id="keterangan" name="keterangan" rows="3" maxlength="1000"
                                                  class="form-control @error('keterangan') is-invalid @enderror"
                                                  placeholder="Opsional, mis. kosakata pelajaran 1">{{ old('keterangan') }}</textarea>
                                        @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <span class="penghitung kurang" id="penghitung-wrap">
                                            <i class="fas fa-check-square"></i>
                                            <span><span id="jumlah-dipilih">0</span> kata dipilih</span>
                                        </span>
                                        <div class="help-text mb-0" id="hint-minimal">Pilih minimal 2 kata.</div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-save me-1"></i> Simpan grup</button>
                                        <a href="{{ route('admin.grup-kosakata.index') }}" class="btn btn-outline-secondary">Batal</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===== Kanan: pemilih kata ===== --}}
                    <div class="col-lg-8">
                        <div class="card form-card">
                            <div class="card-body">
                                <div class="section-divider"><span><i class="fas fa-list-check me-2"></i>Pilih kata</span></div>

                                @error('kosakata_ids')
                                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                                @enderror

                                <div class="picker-bar">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="search" id="cari-kata" class="form-control" placeholder="Cari hanzi, pinyin, atau arti…" autocomplete="off">
                                    </div>
                                    <select id="filter-level" class="form-select" aria-label="Filter level HSK">
                                        <option value="">Semua level</option>
                                        @foreach ($levels as $level)
                                            <option value="{{ $level->id }}">{{ $level->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="picker-tools">
                                    <div class="help-text mt-0"><span id="jumlah-tampil">{{ $kosakatas->count() }}</span> dari {{ $kosakatas->count() }} kata tampil</div>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <button type="button" class="btn btn-outline-secondary" id="btn-hanya-terpilih"><i class="fas fa-filter me-1"></i>Hanya yang dipilih</button>
                                        <button type="button" class="btn btn-outline-secondary" id="btn-pilih-tampil">Pilih semua yang tampil</button>
                                        <button type="button" class="btn btn-outline-secondary" id="btn-kosongkan">Kosongkan</button>
                                    </div>
                                </div>

                                <div class="tabel-kata-wrap">
                                    <table class="table table-sm tabel-kata">
                                        <thead>
                                            <tr>
                                                <th width="36"><span class="visually-hidden">Pilih</span></th>
                                                <th>Hanzi</th>
                                                <th>Pinyin</th>
                                                <th>Arti</th>
                                                <th>Level</th>
                                            </tr>
                                        </thead>
                                        <tbody id="isi-tabel">
                                            @forelse ($kosakatas as $k)
                                                <tr data-level="{{ $k->level_hsk_id }}"
                                                    data-cari="{{ mb_strtolower($k->hanzi . ' ' . $k->pinyin . ' ' . $k->arti_indonesia) }}">
                                                    <td><input type="checkbox" class="form-check-input cek-kata" name="kosakata_ids[]" value="{{ $k->id }}" aria-label="Pilih {{ $k->hanzi }}" @checked(in_array($k->id, $terpilih))></td>
                                                    <td class="kata-hanzi">{{ $k->hanzi }}</td>
                                                    <td>{{ $k->pinyin }}</td>
                                                    <td>{{ $k->arti_indonesia }}</td>
                                                    <td>
                                                        @if ($k->levelHsk)
                                                            <span class="badge badge-level">{{ $k->levelHsk->nama }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5">
                                                        <div class="empty-state">
                                                            <div class="empty-state-icon"><i class="fas fa-book"></i></div>
                                                            <div class="empty-state-title">Belum ada kosakata</div>
                                                            <div class="empty-state-text">Tambahkan kosakata dulu sebelum membuat grup.</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            <tr id="baris-kosong" class="d-none">
                                                <td colspan="5">
                                                    <div class="empty-state">
                                                        <div class="empty-state-icon"><i class="fas fa-search"></i></div>
                                                        <div class="empty-state-title">Tidak ada kata yang cocok</div>
                                                        <div class="empty-state-text">Ubah kata kunci atau filter level.</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="help-text">Klik baris mana saja untuk memilih. Kata yang sudah dipilih tetap tersimpan walau Anda mengganti pencarian.</div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
@endsection

@section('scripts')
<script>
    (function () {
        const baris       = Array.from(document.querySelectorAll('#isi-tabel tr[data-cari]'));
        const barisKosong = document.getElementById('baris-kosong');
        const inputCari   = document.getElementById('cari-kata');
        const selectLevel = document.getElementById('filter-level');
        const btnTerpilih = document.getElementById('btn-hanya-terpilih');
        const btnPilihTmp = document.getElementById('btn-pilih-tampil');
        const btnKosong   = document.getElementById('btn-kosongkan');
        const penghitung  = document.getElementById('jumlah-dipilih');
        const wrapHitung  = document.getElementById('penghitung-wrap');
        const hint        = document.getElementById('hint-minimal');
        const labelTampil = document.getElementById('jumlah-tampil');
        let hanyaTerpilih = false;

        // Buang tanda nada pinyin supaya "ni" cocok dengan "nǐ".
        const normal = s => s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        baris.forEach(tr => { tr.dataset.cariNormal = normal(tr.dataset.cari); });

        const cek = tr => tr.querySelector('.cek-kata');

        function terapkanFilter() {
            const kata  = normal(inputCari.value.trim());
            const level = selectLevel.value;
            let tampil = 0;

            baris.forEach(tr => {
                const cocok =
                    (!kata  || tr.dataset.cariNormal.includes(kata)) &&
                    (!level || tr.dataset.level === level) &&
                    (!hanyaTerpilih || cek(tr).checked);
                tr.classList.toggle('tersembunyi', !cocok);
                if (cocok) tampil++;
            });

            labelTampil.textContent = tampil;
            if (barisKosong) barisKosong.classList.toggle('d-none', tampil > 0 || baris.length === 0);
        }

        function sinkron() {
            const dipilih = baris.filter(tr => cek(tr).checked).length;
            penghitung.textContent = dipilih;
            baris.forEach(tr => tr.classList.toggle('terpilih', cek(tr).checked));

            const cukup = dipilih >= 2;
            wrapHitung.classList.toggle('kurang', !cukup);
            hint.textContent = cukup ? 'Siap disimpan.' : 'Pilih minimal 2 kata.';
        }

        baris.forEach(tr => {
            cek(tr).addEventListener('change', () => { sinkron(); if (hanyaTerpilih) terapkanFilter(); });
            tr.addEventListener('click', e => {
                if (e.target === cek(tr)) return;
                cek(tr).checked = !cek(tr).checked;
                sinkron();
                if (hanyaTerpilih) terapkanFilter();
            });
        });

        inputCari.addEventListener('input', terapkanFilter);
        selectLevel.addEventListener('change', terapkanFilter);

        btnTerpilih.addEventListener('click', () => {
            hanyaTerpilih = !hanyaTerpilih;
            btnTerpilih.classList.toggle('btn-chip-on', hanyaTerpilih);
            terapkanFilter();
        });

        // Hanya baris yang sedang tampil yang dicentang.
        btnPilihTmp.addEventListener('click', () => {
            baris.filter(tr => !tr.classList.contains('tersembunyi')).forEach(tr => { cek(tr).checked = true; });
            sinkron();
        });

        btnKosong.addEventListener('click', () => {
            baris.forEach(tr => { cek(tr).checked = false; });
            sinkron();
            if (hanyaTerpilih) terapkanFilter();
        });

        // Enter di kolom pencarian tidak boleh mengirim form.
        inputCari.addEventListener('keydown', e => { if (e.key === 'Enter') e.preventDefault(); });

        sinkron();
        terapkanFilter();
    })();
</script>
@endsection
