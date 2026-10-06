@extends('layouts.user.user')

@section('title', 'Detail Latihan')

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

    /* ===== MINI STAT ===== */
    .mini-stat { text-align: center; padding: 12px 6px; border-radius: 12px; background: #f8fafc; }
    .mini-stat .angka { font-size: 1.5rem; font-weight: 800; line-height: 1.1; }
    .mini-stat .label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; margin-top: 2px; }
</style>
@endsection

@section('content')
    <div class="container">

        {{-- Header – di LUAR page-inner --}}
        <div class="ph-card">
            <div class="ph-left">
                <div class="ph-icon"><i class="fas fa-tasks"></i></div>
                <div>
                    <h5 class="ph-title">{{ $grupSoal->judul }}</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('pelajar.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('pelajar.latihan.index') }}">Latihan &amp; Ujian</a></li>
                        <li><span class="bc-active">Detail</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('pelajar.latihan.mulai', $grupSoal) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm" @disabled($sisa === 0 && ! $belumSelesai)>
                        <i class="fas fa-play me-1"></i> {{ $belumSelesai ? 'Lanjutkan' : 'Mulai Mengerjakan' }}
                    </button>
                </form>
                <a href="{{ route('pelajar.latihan.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="page-inner">

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($belumSelesai)
                <div class="alert alert-info">
                    Ada pengerjaan yang belum selesai (dimulai {{ $belumSelesai->mulai_pada?->diffForHumans() }}). Klik <strong>Lanjutkan</strong> untuk meneruskan.
                </div>
            @endif

            <div class="row g-4">

                {{-- Info latihan + hafalan --}}
                <div class="col-lg-4">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Informasi Latihan</div>

                            @if ($grupSoal->deskripsi)
                                <div class="info-item">
                                    <div class="info-label">Deskripsi</div>
                                    <div class="info-value">{{ $grupSoal->deskripsi }}</div>
                                </div>
                            @endif
                            <div class="info-item">
                                <div class="info-label">Jenis</div>
                                <div class="info-value">
                                    <span class="badge {{ $grupSoal->isUjian() ? 'badge-danger-soft' : 'badge-info-soft' }}">{{ ucfirst($grupSoal->jenis) }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Level HSK</div>
                                <div class="info-value">{{ $grupSoal->levelHsk->nama ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Jumlah Soal</div>
                                <div class="info-value">{{ $grupSoal->soals->count() }} soal ({{ $grupSoal->total_poin }} poin)</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Durasi</div>
                                <div class="info-value">{{ $grupSoal->durasi_menit ? $grupSoal->durasi_menit . ' menit' : 'Tanpa batas waktu' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Nilai Lulus</div>
                                <div class="info-value">{{ $grupSoal->nilai_lulus ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Sisa Percobaan</div>
                                <div class="info-value">{{ $sisa === null ? 'Tanpa batas' : $sisa }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-brain me-2"></i>Hafalan Kata di Latihan Ini</div>

                            @if ($ringkasan['total'] > 0)
                                <div class="row g-2">
                                    <div class="col-4"><div class="mini-stat"><div class="angka" style="color:#15803d">{{ $ringkasan['ingat'] }}</div><div class="label">Ingat</div></div></div>
                                    <div class="col-4"><div class="mini-stat"><div class="angka" style="color:#b45309">{{ $ringkasan['lupa'] }}</div><div class="label">Lupa</div></div></div>
                                    <div class="col-4"><div class="mini-stat"><div class="angka" style="color:#64748b">{{ $ringkasan['belum'] }}</div><div class="label">Belum</div></div></div>
                                </div>
                                <div class="text-muted mt-3" style="font-size:.78rem;">
                                    {{ $ringkasan['total'] }} kata diuji. Hafalan diperbarui otomatis setiap kali kamu mengumpulkan jawaban.
                                </div>
                            @else
                                <div class="text-muted" style="font-size:.82rem;">
                                    Soal di latihan ini belum terhubung ke kosakata, jadi hasilnya tidak memengaruhi hafalan.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Riwayat pengerjaan --}}
                <div class="col-lg-8">
                    <div class="card filter-card shadow-sm">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">
                                <i class="fas fa-history text-primary me-2 opacity-75"></i>Riwayat Pengerjaan
                                <span class="badge {{ $riwayat->count() ? 'badge-aktif' : 'badge-nonaktif' }} ms-1">{{ $riwayat->count() }}</span>
                            </h5>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table-hover mb-0 table">
                                    <thead>
                                        <tr>
                                            <th width="40">#</th>
                                            <th>Selesai</th>
                                            <th>Benar</th>
                                            <th>Skor</th>
                                            <th>Status</th>
                                            <th width="90"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($riwayat as $i => $h)
                                            <tr>
                                                <td class="text-muted">{{ $i + 1 }}</td>
                                                <td>{{ $h->selesai_pada->translatedFormat('d M Y, H:i') }}</td>
                                                <td>{{ $h->jumlah_benar }} / {{ $h->jumlah_soal }}</td>
                                                <td class="fw-semibold">{{ rtrim(rtrim(number_format($h->skor, 2), '0'), '.') }}</td>
                                                <td>
                                                    @if ($h->lulus === null)
                                                        <span class="text-muted">-</span>
                                                    @else
                                                        <span class="badge {{ $h->lulus ? 'badge-aktif' : 'badge-danger-soft' }}">{{ $h->lulus ? 'Lulus' : 'Belum lulus' }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('pelajar.pengerjaan.hasil', $h) }}" class="btn btn-outline-secondary btn-sm">Lihat</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="p-0 text-center">
                                                    <div class="empty-state">
                                                        <div class="empty-state-icon"><i class="fas fa-history"></i></div>
                                                        <div class="fw-semibold text-secondary mb-1">Belum pernah dikerjakan</div>
                                                        <div class="text-muted" style="font-size:.8rem;">Klik Mulai Mengerjakan untuk mencoba</div>
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
