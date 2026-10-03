@extends('layouts.user.user')

@section('title', 'Daftar ' . ucfirst($jenis))

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn, .table { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ===== STAT CARDS ===== */
    .stat-card {
        border: none; border-radius: 16px; padding: 18px 20px;
        position: relative; overflow: hidden;
        transition: transform .2s, box-shadow .2s;
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
    .stat-card::after {
        content: ''; position: absolute; right: -16px; top: -16px;
        width: 72px; height: 72px; border-radius: 50%; opacity: .14;
    }
    .stat-card.blue   { background: linear-gradient(135deg,#e8f0fe,#dbeafe); }
    .stat-card.blue::after   { background:#1a73e8; }
    .stat-card.amber  { background: linear-gradient(135deg,#fffbeb,#fef3c7); }
    .stat-card.amber::after  { background:#d97706; }
    .stat-card.green  { background: linear-gradient(135deg,#e6f9f0,#d1fae5); }
    .stat-card.green::after  { background:#16a34a; }
    .stat-card.red    { background: linear-gradient(135deg,#fff1f2,#ffe4e6); }
    .stat-card.red::after    { background:#dc2626; }
    .stat-icon {
        width: 44px; height: 44px; border-radius: 11px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem;
    }
    .stat-icon.blue  { background:#1a73e8; color:#fff; }
    .stat-icon.amber { background:#d97706; color:#fff; }
    .stat-icon.green { background:#16a34a; color:#fff; }
    .stat-icon.red   { background:#dc2626; color:#fff; }
    .stat-value { font-size: 1.7rem; font-weight: 800; color: #1e293b; line-height: 1; }
    .stat-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #64748b; margin-top: 2px; }

    /* ===== PAGE HEADER ===== */
    .ph-card {
        background:#fff; border:1px solid #e9ecef; border-radius:14px;
        padding:16px 20px; display:flex; align-items:center;
        justify-content:space-between; gap:16px; flex-wrap:wrap;
        margin-bottom:1.25rem; position:relative; overflow:hidden;
        box-shadow:0 1px 6px rgba(0,0,0,.05);
    }
    @php $accentColor = $jenis === 'berita' ? '#1a73e8' : '#16a34a'; @endphp
    .ph-card::before {
        content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
        border-radius:14px 0 0 14px;
        background: {{ $jenis === 'berita' ? '#1a73e8' : '#16a34a' }};
    }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon {
        width:42px; height:42px; border-radius:10px;
        display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0;
        background: {{ $jenis === 'berita' ? '#e8f0fe' : '#dcfce7' }};
        color: {{ $jenis === 'berita' ? '#1a73e8' : '#16a34a' }};
    }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; letter-spacing:-.2px; line-height:1.2; margin:0; }
    .ph-breadcrumb {
        display:flex; align-items:center; gap:4px; flex-wrap:wrap;
        margin-top:4px; list-style:none; padding:0; margin-bottom:0;
    }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ===== MAIN CARD ===== */
    .main-card { border:none; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; }
    .main-card .card-header { background:#fff; border-bottom:1px solid #f1f5f9; padding:16px 22px; }
    .main-card .card-header h5 { font-size:.93rem; font-weight:700; color:#1e293b; }
    .filter-section { background:#fafbfc; border-bottom:1px solid #f1f5f9; padding:14px 22px; }

    /* ===== FORM CONTROLS ===== */
    .form-control, .form-select {
        border-radius:10px; border:1.5px solid #e2e8f0; font-size:.82rem;
        padding:7px 11px; color:#334155; background:#f8fafc;
        transition:border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color:{{ $jenis === 'berita' ? '#1a73e8' : '#16a34a' }};
        background:#fff; box-shadow:0 0 0 3px {{ $jenis === 'berita' ? 'rgba(26,115,232,.12)' : 'rgba(22,163,74,.12)' }};
    }
    .form-control::placeholder { color:#94a3b8; }
    .input-group-text { background:#f8fafc; border:1.5px solid #e2e8f0; border-right:none; border-radius:10px 0 0 10px; font-size:.8rem; color:#94a3b8; }
    .input-group .form-control { border-left:none; border-radius:0 10px 10px 0; }

    /* ===== TABLE ===== */
    .table { font-size:.82rem; margin-bottom:0; }
    .table thead th {
        background:#f8fafc; color:#475569; font-weight:700;
        font-size:.7rem; text-transform:uppercase; letter-spacing:.5px;
        border-bottom:2px solid #e2e8f0; padding:11px 14px; white-space:nowrap; border-top:none;
    }
    .table tbody td { padding:12px 14px; vertical-align:middle; border-bottom:1px solid #f1f5f9; color:#334155; }
    .table tbody tr:hover td { background:#f8fafc; }
    .table tbody tr:last-child td { border-bottom:none; }

    /* ===== THUMBNAIL ===== */
    .thumb { width:68px; height:50px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0; flex-shrink:0; }
    .thumb-placeholder {
        width:68px; height:50px; border-radius:8px; border:1px solid #e2e8f0;
        background:#f1f5f9; display:flex; align-items:center; justify-content:center;
        color:#94a3b8; font-size:.7rem;
    }

    /* ===== BADGES ===== */
    .badge { font-size:.7rem; font-weight:600; padding:4px 9px; border-radius:20px; }
    .badge-berita    { background:#dbeafe; color:#1d4ed8; }
    .badge-kegiatan  { background:#dcfce7; color:#15803d; }
    .badge-direncanakan { background:#fffbeb; color:#92400e; }
    .badge-berlangsung  { background:#dbeafe; color:#1e40af; }
    .badge-selesai      { background:#dcfce7; color:#15803d; }
    .badge-dibatalkan   { background:#fee2e2; color:#991b1b; }

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        width:30px; height:30px; padding:0; border-radius:8px; border:none;
        display:inline-flex; align-items:center; justify-content:center;
        font-size:.72rem; transition:all .15s;
    }
    .btn-detail { background:#e0f2fe; color:#0369a1; }
    .btn-detail:hover { background:#0369a1; color:#fff; }
    .btn-edit   { background:#fef9c3; color:#a16207; }
    .btn-edit:hover   { background:#ca8a04; color:#fff; }
    .btn-hapus  { background:#fee2e2; color:#dc2626; }
    .btn-hapus:hover  { background:#dc2626; color:#fff; }

    /* ===== BTN PRIMARY ===== */
    .btn-primary {
        background: {{ $jenis === 'berita' ? 'linear-gradient(135deg,#1a73e8,#1558b0)' : 'linear-gradient(135deg,#16a34a,#15803d)' }};
        border:none; border-radius:10px; font-weight:600; font-size:.83rem;
        padding:8px 18px; transition:all .2s;
        box-shadow: {{ $jenis === 'berita' ? '0 2px 8px rgba(26,115,232,.35)' : '0 2px 8px rgba(22,163,74,.35)' }};
    }
    .btn-primary:hover { transform:translateY(-1px); filter:brightness(1.07); }
    .btn-outline-secondary { border-radius:10px; font-size:.82rem; border-color:#e2e8f0; color:#64748b; padding:7px 12px; }

    /* ===== EMPTY STATE ===== */
    .empty-state { padding:48px 24px; text-align:center; }
    .empty-icon { width:60px; height:60px; border-radius:14px; background:#f1f5f9; color:#94a3b8; display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin:0 auto 14px; }

    /* ===== ALERTS ===== */
    .alert { border:none; border-radius:12px; font-size:.84rem; }
    .alert-success { background:#dcfce7; color:#166534; }
    .alert-danger  { background:#fee2e2; color:#991b1b; }

    /* ===== VIEWER BADGE ===== */
    .viewer-badge { display:inline-flex; align-items:center; gap:4px; font-size:.75rem; color:#64748b; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon">
                <i class="fas fa-{{ $jenis === 'berita' ? 'newspaper' : 'calendar-check' }}"></i>
            </div>
            <div>
                <h5 class="ph-title">Daftar {{ ucfirst($jenis) }}</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><span class="bc-active">{{ ucfirst($jenis) }}</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('konten.create', $jenis) }}" class="btn btn-primary btn-sm px-3">
            <i class="fas fa-plus me-1"></i> Tambah {{ ucfirst($jenis) }}
        </a>
    </div>

    {{-- ── Flash ── --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- ── Stat Cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card blue">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon blue">
                        <i class="fas fa-{{ $jenis === 'berita' ? 'newspaper' : 'calendar-alt' }}"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total {{ ucfirst($jenis) }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if($jenis === 'kegiatan')
        <div class="col-6 col-md-3">
            <div class="stat-card amber">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon amber"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['direncanakan'] }}</div>
                        <div class="stat-label">Direncanakan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card blue">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon blue"><i class="fas fa-spinner"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['berlangsung'] }}</div>
                        <div class="stat-label">Berlangsung</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card green">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['selesai'] }}</div>
                        <div class="stat-label">Selesai</div>
                    </div>
                </div>
            </div>
        </div>

        @else
        {{-- Berita: viewer total --}}
        <div class="col-6 col-md-3">
            <div class="stat-card green">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon green"><i class="fas fa-eye"></i></div>
                    <div>
                        <div class="stat-value">
                            {{ \App\Models\Konten::berita()
                                ->when(auth()->user()->isAdminPanti(), fn($q) => $q->where('panti_asuhan_id', auth()->user()->pengurus?->panti_asuhan_id))
                                ->sum('viewer') }}
                        </div>
                        <div class="stat-label">Total Viewer</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Main Card ── --}}
    <div class="card main-card">

        {{-- Header --}}
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="mb-0">
                <i class="fas fa-list me-2" style="color:{{ $jenis === 'berita' ? '#1a73e8' : '#16a34a' }}"></i>
                Daftar {{ ucfirst($jenis) }}
            </h5>
            <span class="text-muted" style="font-size:.77rem;">{{ $konten->total() }} data</span>
        </div>

        {{-- Filter --}}
        <div class="filter-section">
            <form method="GET" action="{{ route('konten.index', $jenis) }}" class="row g-2 align-items-end">
                <div class="col-md-5 col-sm-7">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-search" style="font-size:.7rem;"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Cari judul, ringkasan…"
                               value="{{ request('search') }}">
                    </div>
                </div>

                @if($jenis === 'kegiatan')
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">-- Semua Status --</option>
                        @foreach(['direncanakan','berlangsung','selesai','dibatalkan'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if(auth()->user()->isAdminDinsos() && $jenis === 'kegiatan' && $daftarPanti->isNotEmpty())
                <div class="col-md-3">
                    <select name="panti_id" class="form-select form-select-sm">
                        <option value="">-- Semua Panti --</option>
                        @foreach($daftarPanti as $p)
                            <option value="{{ $p->id }}" {{ request('panti_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_panti }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search','status','panti_id']))
                        <a href="{{ route('konten.index', $jenis) }}" class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fas fa-times me-1"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th width="80">Sampul</th>
                        <th>Judul & Penulis</th>
                        @if($jenis === 'kegiatan')
                            <th>Panti</th>
                            <th>Tgl Mulai</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                        @else
                            <th>Ringkasan</th>
                            <th>Kategori</th>
                            <th>Viewer</th>
                            <th>Publikasi</th>
                        @endif
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($konten as $i => $item)
                    <tr>
                        <td class="text-muted">{{ $konten->firstItem() + $i }}</td>

                        {{-- Sampul --}}
                        <td>
                            @if($item->gambar)
                                    @php
                                        $imgSrc = $item->gambar;
                                        if ($imgSrc && !preg_match('/^(http:|https:|\/\/)/', $imgSrc)) {
                                            $imgSrc = \Illuminate\Support\Facades\Storage::url($imgSrc);
                                        }
                                    @endphp
                                    <img src="{{ $imgSrc }}" alt="{{ $item->judul }}" class="thumb">
                                @else
                                <div class="thumb-placeholder"><i class="fas fa-image"></i></div>
                            @endif
                        </td>

                        {{-- Judul --}}
                        <td>
                            <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;max-width:220px;">
                                {{ Str::limit($item->judul, 55) }}
                            </div>
                            <div style="font-size:.73rem;color:#94a3b8;margin-top:2px;">
                                <i class="fas fa-user me-1"></i>
                                {{ $item->user?->username ?? '-' }}
                            </div>
                        </td>

                        @if($jenis === 'kegiatan')
                        {{-- Panti --}}
                        <td>
                            @if($item->pantiAsuhan)
                                <span style="font-size:.8rem;">{{ Str::limit($item->pantiAsuhan->nama_panti, 30) }}</span>
                            @else
                                <span class="text-muted" style="font-size:.78rem;">Dinsos</span>
                            @endif
                        </td>
                        {{-- Tgl Mulai --}}
                        <td style="white-space:nowrap;">
                            {{ $item->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}
                        </td>
                        {{-- Lokasi --}}
                        <td>{{ Str::limit($item->lokasi, 28) ?: '-' }}</td>
                        {{-- Status --}}
                        <td>
                            <span class="badge badge-{{ $item->status }}">
                                {{ ucfirst($item->status ?? '-') }}
                            </span>
                        </td>

                        @else
                        {{-- Ringkasan --}}
                        <td>
                            <span style="font-size:.8rem;color:#64748b;">
                                {{ Str::limit(strip_tags($item->ringkasan), 70) ?: '-' }}
                            </span>
                        </td>
                        {{-- Kategori --}}
                        <td>
                            @if($item->kategori)
                                <span class="badge badge-berita">{{ $item->kategori->nama_kategori }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        {{-- Viewer --}}
                        <td>
                            <span class="viewer-badge">
                                <i class="fas fa-eye"></i> {{ number_format($item->viewer) }}
                            </span>
                        </td>
                        {{-- Publikasi --}}
                        <td style="white-space:nowrap;">
                            {{ $item->tanggal_publikasi?->translatedFormat('d M Y') ?? '-' }}
                        </td>
                        @endif

                        {{-- Aksi --}}
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('berita.detail', [$item->jenis_konten, $item->slug]) }}"
                                   class="btn btn-action btn-detail" title="Lihat" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('konten.edit', ['jenis' => $jenis, 'slug' => $item->slug]) }}"
                                   class="btn btn-action btn-edit" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <button class="btn btn-action btn-hapus btn-confirm-hapus"
                                        data-id="{{ $item->id_konten }}"
                                        data-judul="{{ $item->judul }}" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <form id="form-hapus-{{ $item->id_konten }}"
                                      action="{{ route('konten.destroy', ['jenis' => $jenis, 'id_konten' => $item->id_konten]) }}"
                                      method="POST" class="d-none">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-0">
                            <div class="empty-state">
                                <div class="empty-icon mx-auto">
                                    <i class="fas fa-{{ $jenis === 'berita' ? 'newspaper' : 'calendar-times' }}"></i>
                                </div>
                                <div class="fw-semibold text-secondary mb-1">Belum ada {{ $jenis }}</div>
                                <div class="text-muted" style="font-size:.8rem;">Coba ubah filter atau tambahkan data baru</div>
                                <a href="{{ route('konten.create', $jenis) }}" class="btn btn-primary btn-sm mt-3">
                                    <i class="fas fa-plus me-1"></i> Tambah Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($konten->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2"
             style="background:#fff;border-top:1px solid #f1f5f9;">
            <small class="text-muted">
                Menampilkan <strong>{{ $konten->firstItem() }}</strong>–<strong>{{ $konten->lastItem() }}</strong>
                dari <strong>{{ $konten->total() }}</strong> data
            </small>
            {{ $konten->links() }}
        </div>
        @endif

    </div>{{-- end .card --}}
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.btn-confirm-hapus').forEach(btn => {
        btn.addEventListener('click', function () {
            const id    = this.dataset.id;
            const judul = this.dataset.judul;
            swal({
                title: 'Hapus {{ ucfirst($jenis) }}?',
                text: `"${judul}" akan dihapus permanen beserta gambar sampulnya.`,
                icon: 'warning',
                buttons: { cancel: 'Batal', confirm: { text: 'Ya, Hapus!', className: 'btn-danger' } },
                dangerMode: true,
            }).then(ok => { if (ok) document.getElementById('form-hapus-' + id).submit(); });
        });
    });
</script>
@endsection
