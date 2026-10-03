@extends('layouts.user.user')

@section('title', 'Data Kategori')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, .table, .btn, h4, h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ===== PAGE HEADER ===== */
    .ph-card { background: #fff; border: 1px solid #e9ecef; border-radius: 14px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 1.25rem; position: relative; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 14px 0 0 14px; }
    .ph-card.index-page::before { background: #1a73e8; }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
    .ph-icon.index { background: #e8f0fe; color: #1a73e8; }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0; }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li + li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    /* ===== STAT CARDS ===== */
    .stat-card { border: none; border-radius: 16px; padding: 20px; position: relative; overflow: hidden; transition: transform .2s ease, box-shadow .2s ease; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
    .stat-card::after { content: ''; position: absolute; right: -18px; top: -18px; width: 80px; height: 80px; border-radius: 50%; opacity: .12; }
    .stat-card.blue   { background: linear-gradient(135deg, #e8f0fe 0%, #dbeafe 100%); } .stat-card.blue::after   { background: #1a73e8; }
    .stat-card.green  { background: linear-gradient(135deg, #e6f9f0 0%, #d1fae5 100%); } .stat-card.green::after  { background: #16a34a; }
    .stat-card.orange { background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); } .stat-card.orange::after { background: #ea580c; }
    .stat-card.purple { background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); } .stat-card.purple::after { background: #7c3aed; }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; color: #fff; }
    .stat-icon.blue { background: #1a73e8; } .stat-icon.green { background: #16a34a; }
    .stat-icon.orange { background: #ea580c; } .stat-icon.purple { background: #7c3aed; }
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

    .avatar-placeholder { width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: .85rem; font-weight: 700; flex-shrink: 0; background: #e8f0fe; color: #1a73e8; }
    .name-text { font-weight: 600; font-size: .85rem; color: #1e293b; }

    /* ===== BADGES ===== */
    .badge { font-size: .7rem; font-weight: 600; padding: 4px 9px; border-radius: 6px; letter-spacing: .2px; }
    .badge-aktif    { background: #dcfce7; color: #15803d; }
    .badge-nonaktif { background: #f1f5f9; color: #64748b; }

    /* ===== ACTION BUTTONS ===== */
    .btn-action { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; font-size: .75rem; padding: 0; border: none; transition: all .15s ease; }
    .btn-detail { background: #e0f2fe; color: #0369a1; } .btn-detail:hover { background: #0369a1; color: #fff; }
    .btn-edit   { background: #fef9c3; color: #a16207; } .btn-edit:hover   { background: #ca8a04; color: #fff; }
    .btn-hapus  { background: #fee2e2; color: #dc2626; } .btn-hapus:hover  { background: #dc2626; color: #fff; }

    .btn-primary { background: linear-gradient(135deg, #1a73e8, #1558b0); border: none; border-radius: 10px; font-weight: 600; font-size: .83rem; padding: 8px 18px; box-shadow: 0 2px 8px rgba(26,115,232,.35); transition: all .2s ease; }
    .btn-primary:hover { background: linear-gradient(135deg, #1558b0, #0f3e82); box-shadow: 0 4px 14px rgba(26,115,232,.45); transform: translateY(-1px); }
    .btn-outline-secondary { border-radius: 10px; font-size: .83rem; border-color: #e2e8f0; color: #64748b; padding: 7px 12px; }
    .btn-outline-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }

    /* ===== EMPTY STATE ===== */
    .empty-state { padding: 60px 20px; }
    .empty-state-icon { width: 72px; height: 72px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.6rem; color: #94a3b8; }
</style>
@endsection

@section('content')
    <div class="container">

        {{-- Header – di LUAR page-inner, sama seperti index lain --}}
        <div class="ph-card index-page">
            <div class="ph-left">
                <div class="ph-icon index"><i class="fas fa-tags"></i></div>
                <div>
                    <h5 class="ph-title">Daftar Kategori</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><span class="bc-active">Kategori</span></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="page-inner">

            {{-- Alert (X + hilang otomatis 3 detik) --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible alert-flash fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible alert-flash fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            {{-- ===== STAT CARDS ===== --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card stat-card blue">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon blue"><i class="fas fa-tags"></i></div>
                            <div>
                                <div class="stat-value">{{ $stats['total'] }}</div>
                                <div class="stat-label">Total Kategori</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card green">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                            <div>
                                <div class="stat-value">{{ $stats['terpakai'] }}</div>
                                <div class="stat-label">Ada Kosakata</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card orange">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon orange"><i class="fas fa-inbox"></i></div>
                            <div>
                                <div class="stat-value">{{ $stats['kosong'] }}</div>
                                <div class="stat-label">Masih Kosong</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card purple">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon purple"><i class="fas fa-language"></i></div>
                            <div>
                                <div class="stat-value">{{ $stats['tanpa_kategori'] }}</div>
                                <div class="stat-label">Kosakata Tanpa Kategori</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== FILTER & TABLE CARD ===== --}}
            <div class="card filter-card shadow-sm">

                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="fas fa-list text-primary me-2 opacity-75"></i>Daftar Kategori
                    </h5>
                    <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Kategori
                    </a>
                </div>

                {{-- Filter --}}
                <div class="filter-section">
                    <form method="GET" action="{{ route('admin.kategori.index') }}">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama / keterangan..." value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-6 col-sm-3 col-md-3">
                                <select name="isi" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    <option value="ada" {{ request('isi') === 'ada' ? 'selected' : '' }}>Ada kosakata</option>
                                    <option value="kosong" {{ request('isi') === 'kosong' ? 'selected' : '' }}>Masih kosong</option>
                                </select>
                            </div>

                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary btn-sm ms-1"
                                    title="Reset filter">
                                    <i class="fas fa-redo-alt"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table">
                            <thead>
                                <tr>
                                    <th width="40">#</th>
                                    <th>Nama</th>
                                    <th>Keterangan</th>
                                    <th>Kosakata</th>
                                    <th width="110">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategoris as $i => $kategori)
                                    <tr>
                                        <td class="text-muted">{{ $kategoris->firstItem() + $i }}</td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-placeholder">{{ strtoupper(mb_substr($kategori->nama, 0, 1)) }}</div>
                                                <div class="name-text">{{ $kategori->nama }}</div>
                                            </div>
                                        </td>

                                        <td class="text-muted" style="max-width:360px;">
                                            {{ \Illuminate\Support\Str::limit($kategori->keterangan, 80) ?: '-' }}
                                        </td>

                                        <td>
                                            <span class="badge {{ $kategori->kosakatas_count > 0 ? 'badge-aktif' : 'badge-nonaktif' }}">
                                                {{ $kategori->kosakatas_count }} kosakata
                                            </span>
                                        </td>

                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.kategori.show', $kategori) }}"
                                                    class="btn btn-action btn-detail" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.kategori.edit', $kategori) }}"
                                                    class="btn btn-action btn-edit" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <button type="button" class="btn btn-action btn-hapus"
                                                    data-id="{{ $kategori->id }}" data-nama="{{ $kategori->nama }}"
                                                    data-jumlah="{{ $kategori->kosakatas_count }}" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="form-hapus-{{ $kategori->id }}"
                                                    action="{{ route('admin.kategori.destroy', $kategori) }}" method="POST"
                                                    class="d-none">
                                                    @csrf @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-0 text-center">
                                            <div class="empty-state">
                                                <div class="empty-state-icon"><i class="fas fa-tags"></i></div>
                                                <div class="fw-semibold text-secondary mb-1">Belum ada data kategori</div>
                                                <div class="text-muted" style="font-size:.8rem;">
                                                    Coba ubah filter atau tambahkan kategori baru
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if ($kategoris->hasPages())
                    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted">
                            Menampilkan
                            <strong>{{ $kategoris->firstItem() }}</strong>–<strong>{{ $kategoris->lastItem() }}</strong>
                            dari <strong>{{ $kategoris->total() }}</strong> data
                        </small>
                        {{ $kategoris->links() }}
                    </div>
                @endif

            </div>{{-- end .card --}}

        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.btn-hapus').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const nama = this.dataset.nama;
                const jumlah = parseInt(this.dataset.jumlah || 0);

                let teks = `Kategori "${nama}" akan dihapus permanen.`;
                if (jumlah > 0) {
                    teks += ` ${jumlah} kosakata di dalamnya tidak ikut terhapus, tapi menjadi tanpa kategori.`;
                }

                swal({
                    title: 'Hapus Kategori?',
                    text: teks,
                    icon: 'warning',
                    buttons: {
                        cancel: 'Batal',
                        confirm: {
                            text: 'Ya, Hapus!',
                            className: 'btn-danger'
                        }
                    },
                    dangerMode: true,
                }).then(confirmed => {
                    if (confirmed) {
                        document.getElementById('form-hapus-' + id).submit();
                    }
                });
            });
        });
    </script>
@endsection
