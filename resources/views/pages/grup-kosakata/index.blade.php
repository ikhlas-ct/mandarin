@extends('layouts.user.user')

@section('title', 'Grup Kosakata & Generator Soal')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, .table, .btn, .form-control, h4, h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ===== PAGE HEADER ===== */
    .ph-card { background: #fff; border: 1px solid #e9ecef; border-radius: 14px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 1.25rem; position: relative; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 14px 0 0 14px; }
    .ph-card.index-page::before { background: #0369a1; }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
    .ph-icon.index { background: #e0f2fe; color: #0369a1; }
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
    .info-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; }
    .goresan-info { font-size: .78rem; color: #64748b; margin-top: 8px; }

    /* ===== ALERT ===== */
    .alert { border: none; border-radius: 12px; font-size: .85rem; font-weight: 500; padding: 12px 16px; }
    .alert-success { background: #dcfce7; color: #15803d; }
    .alert-danger { background: #fee2e2; color: #b91c1c; }

    /* ===== FORM ===== */
    .form-control { border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: .85rem; }
    .form-control:focus { border-color: #1a73e8; box-shadow: 0 0 0 3px rgba(26,115,232,.12); }
    .input-group-text { border-radius: 10px; border: 1.5px solid #e2e8f0; background: #f8fafc; color: #64748b; font-size: .8rem; font-weight: 600; }
    .input-group > .form-control:not(:last-child), .input-group > .input-group-text:not(:last-child) { border-top-right-radius: 0; border-bottom-right-radius: 0; }
    .input-group > .form-control:not(:first-child), .input-group > .btn:not(:first-child) { border-top-left-radius: 0; border-bottom-left-radius: 0; }
    .input-group > .input-group-text:not(:first-child) { border-top-left-radius: 0; border-bottom-left-radius: 0; }
    .form-check-input:checked { background-color: #1a73e8; border-color: #1a73e8; }

    /* ===== BADGES ===== */
    .badge { font-size: .7rem; font-weight: 600; padding: 4px 9px; border-radius: 6px; letter-spacing: .2px; }
    .badge-level { background: #e8f0fe; color: #1a73e8; }
    .badge-jumlah { background: #dcfce7; color: #15803d; }
    .badge-pilih { background: #e0f2fe; color: #0369a1; }

    /* ===== BUTTONS ===== */
    .btn-primary { background: linear-gradient(135deg, #1a73e8, #1558b0); border: none; border-radius: 10px; font-weight: 600; font-size: .83rem; padding: 8px 18px; box-shadow: 0 2px 8px rgba(26,115,232,.35); transition: all .2s ease; }
    .btn-primary:hover { background: linear-gradient(135deg, #1558b0, #0f3e82); box-shadow: 0 4px 14px rgba(26,115,232,.45); transform: translateY(-1px); }
    .btn-outline-secondary { border-radius: 10px; font-size: .83rem; border-color: #e2e8f0; color: #64748b; padding: 7px 12px; }
    .btn-outline-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }
    .btn-outline-danger { border-radius: 10px; font-size: .8rem; border-color: #fecaca; color: #dc2626; }
    .btn-outline-danger:hover { background: #fee2e2; border-color: #fca5a5; color: #b91c1c; }

    /* ===== TABEL KOSAKATA ===== */
    .tabel-kata-wrap { max-height: 380px; overflow: auto; border: 1.5px solid #e2e8f0; border-radius: 12px; }
    .tabel-kata { margin-bottom: 0; font-size: .85rem; }
    .tabel-kata thead th { position: sticky; top: 0; z-index: 2; background: #f8fafc; border-bottom: 1.5px solid #e2e8f0; font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; padding: 9px 10px; }
    .tabel-kata td { padding: 8px 10px; vertical-align: middle; color: #334155; border-color: #f1f5f9; }
    .tabel-kata tbody tr { cursor: pointer; }
    .tabel-kata tbody tr:hover { background: #f8fafc; }
    .tabel-kata tbody tr.terpilih { background: #e8f0fe; }
    .kata-hanzi { font-size: 1.25rem; font-weight: 600; color: #1e293b; font-family: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', 'Plus Jakarta Sans', sans-serif; }

    /* ===== DAFTAR GRUP ===== */
    .grup-item { background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 14px 16px; margin-bottom: 12px; transition: border-color .2s; }
    .grup-item:last-child { margin-bottom: 0; }
    .grup-item:hover { border-color: #93c5fd; }
    .grup-nama { font-size: .92rem; font-weight: 700; color: #1e293b; line-height: 1.3; }
    .grup-ket { font-size: .75rem; color: #94a3b8; margin-top: 2px; }
    .grup-form { margin-top: 12px; padding-top: 12px; border-top: 1px dashed #cbd5e1; }

    /* ===== PILIHAN TIPE SOAL (chip) ===== */
    .tipe-wrap { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; }
    .tipe-chip { cursor: pointer; margin: 0; }
    .tipe-chip input { position: absolute; opacity: 0; pointer-events: none; }
    .tipe-chip span { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; background: #fff; border: 1.5px solid #e2e8f0; border-radius: 999px; font-size: .75rem; font-weight: 600; color: #64748b; transition: all .15s; }
    .tipe-chip:hover span { border-color: #93c5fd; }
    .tipe-chip input:checked + span { background: #e8f0fe; border-color: #1a73e8; color: #1a73e8; }
    .tipe-chip input:focus-visible + span { box-shadow: 0 0 0 3px rgba(26,115,232,.25); }

    /* ===== EMPTY STATE ===== */
    .empty-state { padding: 40px 20px; text-align: center; }
    .empty-state-icon { width: 72px; height: 72px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.6rem; color: #94a3b8; }
</style>
@endsection

@section('content')
    <div class="container">

        {{-- Header – di LUAR page-inner --}}
        <div class="ph-card index-page">
            <div class="ph-left">
                <div class="ph-icon index"><i class="fas fa-layer-group"></i></div>
                <div>
                    <h5 class="ph-title">Grup Kosakata &amp; Generator Soal</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><span class="bc-active">Grup Kosakata</span></li>
                    </ol>
                </div>
            </div>
            <div>
                <span class="badge badge-jumlah"><i class="fas fa-folder me-1"></i>{{ $grups->count() }} grup</span>
            </div>
        </div>

        <div class="page-inner">

            @if (session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif
            @if (session('error') || $errors->any())
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') ?? $errors->first() }}</div>
            @endif

            <div class="row g-4">

                {{-- ===== Buat grup baru ===== --}}
                <div class="col-lg-7">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-plus-circle me-2"></i>1. Buat Grup Kata</div>

                            <form method="GET" class="input-group mb-3">
                                <input type="text" name="search" class="form-control" placeholder="Cari hanzi / pinyin / arti..." value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary"><i class="fas fa-search me-1"></i> Cari</button>
                            </form>

                            <form method="POST" action="{{ route('admin.grup-kosakata.store') }}">
                                @csrf
                                <div class="row g-2 mb-3">
                                    <div class="col-md-5">
                                        <input type="text" name="nama" class="form-control" placeholder="Nama grup, mis. Salam & Perkenalan" value="{{ old('nama') }}" required>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="keterangan" class="form-control" placeholder="Keterangan (opsional)" value="{{ old('keterangan') }}">
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="info-label">Pilih kata</div>
                                    <span class="badge badge-pilih"><span id="jumlah-dipilih">0</span> kata dipilih</span>
                                </div>

                                <div class="tabel-kata-wrap">
                                    <table class="table table-sm tabel-kata">
                                        <thead>
                                            <tr>
                                                <th width="36"><input type="checkbox" class="form-check-input" id="pilih-semua" title="Pilih semua yang tampil"></th>
                                                <th>Hanzi</th>
                                                <th>Pinyin</th>
                                                <th>Arti</th>
                                                <th>Level</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($kosakatas as $k)
                                                <tr>
                                                    <td><input type="checkbox" class="form-check-input cek-kata" name="kosakata_ids[]" value="{{ $k->id }}" @checked(in_array($k->id, old('kosakata_ids', [])))></td>
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
                                                            <div class="empty-state-icon"><i class="fas fa-search"></i></div>
                                                            <div class="fw-semibold text-secondary mb-1">Kosakata tidak ditemukan</div>
                                                            <div class="text-muted" style="font-size:.8rem;">Coba kata kunci lain</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="goresan-info">Menampilkan maksimal 200 kata; gunakan pencarian untuk mempersempit. Pilih minimal 2 kata.</div>

                                <div class="mt-3">
                                    <button class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan grup</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ===== Daftar grup + generate ===== --}}
                <div class="col-lg-5">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-magic me-2"></i>2. Buat Soal dari Grup</div>

                            @forelse ($grups as $grup)
                                <div class="grup-item">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <div class="grup-nama">{{ $grup->nama }}</div>
                                            @if ($grup->keterangan)
                                                <div class="grup-ket">{{ $grup->keterangan }}</div>
                                            @endif
                                            <span class="badge badge-jumlah mt-2">{{ $grup->kosakatas_count }} kata</span>
                                        </div>
                                        <form method="POST" action="{{ route('admin.grup-kosakata.destroy', $grup) }}" onsubmit="return confirm('Hapus grup ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Hapus grup"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>

                                    <form method="POST" action="{{ route('admin.grup-kosakata.generate', $grup) }}" class="grup-form">
                                        @csrf
                                        <div class="info-label mb-2">Tipe soal</div>
                                        <div class="tipe-wrap">
                                            <label class="tipe-chip"><input type="checkbox" name="tipe[]" value="hanzi_arti" checked><span>Hanzi → arti</span></label>
                                            <label class="tipe-chip"><input type="checkbox" name="tipe[]" value="arti_hanzi" checked><span>Arti → hanzi</span></label>
                                            <label class="tipe-chip"><input type="checkbox" name="tipe[]" value="isian"><span>Isian kalimat</span></label>
                                            <label class="tipe-chip"><input type="checkbox" name="tipe[]" value="listening"><span><i class="fas fa-headphones"></i> Listening</span></label>
                                        </div>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Jumlah soal</span>
                                            <input type="number" name="jumlah" class="form-control" value="{{ max(5, $grup->kosakatas_count) }}" min="1" max="100">
                                            <button class="btn btn-primary">Buat soal</button>
                                        </div>
                                    </form>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fas fa-layer-group"></i></div>
                                    <div class="fw-semibold text-secondary mb-1">Belum ada grup</div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        Buat dulu di sebelah kiri
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}
@endsection

@section('scripts')
    <script>
        (function () {
            const cekSemua = document.getElementById('pilih-semua');
            const cekKata = Array.from(document.querySelectorAll('.cek-kata'));
            const penghitung = document.getElementById('jumlah-dipilih');

            function sinkron() {
                const dipilih = cekKata.filter(c => c.checked).length;
                penghitung.textContent = dipilih;
                cekKata.forEach(c => c.closest('tr').classList.toggle('terpilih', c.checked));
                if (cekSemua) {
                    cekSemua.checked = cekKata.length > 0 && dipilih === cekKata.length;
                    cekSemua.indeterminate = dipilih > 0 && dipilih < cekKata.length;
                }
            }

            cekKata.forEach(c => c.addEventListener('change', sinkron));

            // Klik di mana saja pada baris untuk mencentang.
            cekKata.forEach(c => {
                c.closest('tr').addEventListener('click', e => {
                    if (e.target === c) return;
                    c.checked = !c.checked;
                    sinkron();
                });
            });

            if (cekSemua) {
                cekSemua.addEventListener('change', () => {
                    cekKata.forEach(c => { c.checked = cekSemua.checked; });
                    sinkron();
                });
            }

            sinkron();
        })();
    </script>
@endsection
