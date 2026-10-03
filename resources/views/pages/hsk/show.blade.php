@extends('layouts.user.user')

@section('title', 'Detail Tingkat HSK')

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

    /* ===== TABLE ===== */
    .table thead th { background: #f8fafc; color: #64748b; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; padding: 12px 16px; border-bottom: 2px solid #e2e8f0; border-top: none; white-space: nowrap; }
    .table tbody td { padding: 13px 16px; vertical-align: middle; font-size: .85rem; color: #334155; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:last-child td { border-bottom: none; }
    .table-hover tbody tr:hover td { background: #f8fafc; }
    .hanzi-text { font-size: 1.25rem; font-weight: 600; color: #1e293b; }

    /* ===== BUTTONS ===== */
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

        {{-- Header – di LUAR page-inner --}}
        <div class="ph-card show-page">
            <div class="ph-left">
                <div class="ph-icon show"><i class="fas fa-layer-group"></i></div>
                <div>
                    <h5 class="ph-title">{{ $level->nama }}</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.level-hsk.index') }}">Tingkat HSK</a></li>
                        <li><span class="bc-active">Detail</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.level-hsk.edit', $level) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-pencil-alt me-1"></i> Edit
                </a>
                <a href="{{ route('admin.level-hsk.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="page-inner">
            <div class="row g-4">

                {{-- Info tingkat --}}
                <div class="col-lg-4">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Informasi Tingkat</div>
                            <div class="info-item">
                                <div class="info-label">Tingkat</div>
                                <div class="info-value">{{ $level->tingkat }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Nama</div>
                                <div class="info-value">{{ $level->nama }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Keterangan</div>
                                <div class="info-value">{{ $level->keterangan ?: '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Jumlah Kosakata</div>
                                <div class="info-value">{{ $kosakatas->total() }} kosakata</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Dibuat</div>
                                <div class="info-value">{{ $level->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Terakhir Diubah</div>
                                <div class="info-value">{{ $level->updated_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Daftar kosakata dalam tingkat --}}
                <div class="col-lg-8">
                    <div class="card filter-card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-language text-primary me-2 opacity-75"></i>Kosakata pada Tingkat Ini
                            </h5>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table-hover mb-0 table">
                                    <thead>
                                        <tr>
                                            <th width="40">#</th>
                                            <th>Hanzi</th>
                                            <th>Pinyin</th>
                                            <th>Arti</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kosakatas as $i => $kosakata)
                                            <tr>
                                                <td class="text-muted">{{ $kosakatas->firstItem() + $i }}</td>
                                                <td><span class="hanzi-text">{{ $kosakata->hanzi }}</span></td>
                                                <td>{{ $kosakata->pinyin }}</td>
                                                <td>{{ $kosakata->arti_indonesia }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="p-0 text-center">
                                                    <div class="empty-state">
                                                        <div class="empty-state-icon"><i class="fas fa-language"></i></div>
                                                        <div class="fw-semibold text-secondary mb-1">Belum ada kosakata</div>
                                                        <div class="text-muted" style="font-size:.8rem;">
                                                            Kosakata yang dihubungkan ke tingkat ini akan tampil di sini
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($kosakatas->hasPages())
                            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <small class="text-muted">
                                    Menampilkan
                                    <strong>{{ $kosakatas->firstItem() }}</strong>–<strong>{{ $kosakatas->lastItem() }}</strong>
                                    dari <strong>{{ $kosakatas->total() }}</strong> data
                                </small>
                                {{ $kosakatas->links() }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}
@endsection
