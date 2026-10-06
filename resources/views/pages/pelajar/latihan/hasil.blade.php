@extends('layouts.user.user')

@section('title', 'Hasil Latihan')

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

    /* ===== SKOR ===== */
    .skor-wrap { text-align: center; padding: 8px 0 18px; border-bottom: 1px solid #f1f5f9; margin-bottom: 4px; }
    .skor-besar { font-size: 3.4rem; font-weight: 800; line-height: 1; letter-spacing: -1px; }
    .skor-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; margin-bottom: 6px; }
</style>
@endsection

@section('content')
    @php
        $grup  = $hasilUjian->grupSoal;
        $skor  = rtrim(rtrim(number_format($hasilUjian->skor, 2), '0'), '.');
        $lulus = $hasilUjian->lulus;
    @endphp

    <div class="container">

        {{-- Header – di LUAR page-inner --}}
        <div class="ph-card">
            <div class="ph-left">
                <div class="ph-icon"><i class="fas fa-poll"></i></div>
                <div>
                    <h5 class="ph-title">Hasil: {{ $grup->judul }}</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('pelajar.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('pelajar.latihan.index') }}">Latihan &amp; Ujian</a></li>
                        <li><a href="{{ route('pelajar.latihan.show', $grup) }}">Detail</a></li>
                        <li><span class="bc-active">Hasil</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('pelajar.hafalan.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-chart-pie me-1"></i> Lihat Hafalan
                </a>
                <a href="{{ route('pelajar.latihan.show', $grup) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="page-inner">
            <div class="row g-4">

                {{-- Skor --}}
                <div class="col-lg-4">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-award me-2"></i>Skor</div>

                            <div class="skor-wrap">
                                <div class="skor-label">Skor kamu</div>
                                <div class="skor-besar" style="color: {{ $lulus === false ? '#b91c1c' : '#15803d' }}">{{ $skor }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Jawaban Benar</div>
                                <div class="info-value">{{ $hasilUjian->jumlah_benar }} dari {{ $hasilUjian->jumlah_soal }} soal</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Status</div>
                                <div class="info-value">
                                    @if ($lulus === null)
                                        -
                                    @else
                                        <span class="badge {{ $lulus ? 'badge-aktif' : 'badge-danger-soft' }}">
                                            {{ $lulus ? 'Lulus' : 'Belum lulus' }} (batas {{ $grup->nilai_lulus }})
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Dimulai</div>
                                <div class="info-value">{{ $hasilUjian->mulai_pada?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Selesai</div>
                                <div class="info-value">{{ $hasilUjian->selesai_pada->translatedFormat('d F Y, H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">

                    {{-- Dampak ke hafalan --}}
                    <div class="card filter-card shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">
                                <i class="fas fa-brain text-primary me-2 opacity-75"></i>Dampak ke Hafalan
                                <span class="badge {{ $dampak->count() ? 'badge-aktif' : 'badge-nonaktif' }} ms-1">{{ $dampak->count() }}</span>
                            </h5>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table-hover mb-0 table">
                                    <thead>
                                        <tr>
                                            <th>Kata</th>
                                            <th>Arti</th>
                                            <th>Hasil</th>
                                            <th>Review Berikutnya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($dampak as $d)
                                            <tr>
                                                <td>
                                                    <div class="hanzi-text">{{ $d['kosakata']->hanzi }}</div>
                                                    <div class="text-muted" style="font-size:.78rem;">{{ $d['kosakata']->pinyin }}</div>
                                                </td>
                                                <td>{{ $d['kosakata']->arti_indonesia }}</td>
                                                <td>
                                                    <span class="badge {{ $d['benar'] ? 'badge-aktif' : 'badge-warn' }}">{{ $d['benar'] ? 'Ingat' : 'Lupa' }}</span>
                                                </td>
                                                <td>
                                                    {{ $d['progres']?->review_berikutnya?->translatedFormat('d M Y') ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="p-0 text-center">
                                                    <div class="empty-state">
                                                        <div class="empty-state-icon"><i class="fas fa-brain"></i></div>
                                                        <div class="fw-semibold text-secondary mb-1">Tidak ada kata yang dinilai</div>
                                                        <div class="text-muted" style="font-size:.8rem;">
                                                            Soal belum terhubung ke kosakata, atau tidak ada yang dijawab
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($dampak->isNotEmpty())
                            <div class="card-footer bg-white border-top px-4 py-3 text-muted" style="font-size:.78rem;">
                                Semua soal sebuah kata benar &rarr; ingat sepenuhnya (review 7 hari lagi).
                                Ada yang salah &rarr; lupa &amp; ingat (review besok).
                                Soal yang tidak dijawab tidak mengubah hafalan.
                            </div>
                        @endif
                    </div>

                    {{-- Pembahasan --}}
                    <div class="card filter-card shadow-sm">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">
                                <i class="fas fa-list-ol text-primary me-2 opacity-75"></i>Pembahasan
                                <span class="badge badge-info-soft ms-1">{{ $jawabans->count() }}</span>
                            </h5>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table-hover mb-0 table">
                                    <thead>
                                        <tr>
                                            <th width="40">#</th>
                                            <th>Soal</th>
                                            <th>Jawabanmu</th>
                                            <th>Jawaban Benar</th>
                                            <th>Hasil</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jawabans as $i => $j)
                                            @php $soal = $j->soal; @endphp
                                            <tr>
                                                <td class="text-muted">{{ $i + 1 }}</td>
                                                <td>
                                                    <div>{{ $soal->pertanyaan }}</div>
                                                    @if ($soal->paragraf)
                                                        <div class="hanzi-text mt-1">{{ $soal->paragraf }}</div>
                                                    @endif
                                                    @if ($soal->penjelasan)
                                                        <div class="text-muted mt-1" style="font-size:.76rem;">{{ $soal->penjelasan }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($j->jawaban === null)
                                                        <span class="text-muted">Tidak dijawab</span>
                                                    @else
                                                        <span class="{{ $j->benar ? 'text-success' : 'text-danger' }} fw-semibold">
                                                            {{ $j->jawaban }}. {{ $soal->pilihan[$j->jawaban] ?? '' }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-success fw-semibold">
                                                    {{ $soal->jawaban_benar }}. {{ $soal->pilihan[$soal->jawaban_benar] ?? '' }}
                                                </td>
                                                <td>
                                                    @if ($j->jawaban === null)
                                                        <span class="badge badge-nonaktif">Kosong</span>
                                                    @elseif ($j->benar)
                                                        <span class="badge badge-aktif">Benar</span>
                                                    @else
                                                        <span class="badge badge-danger-soft">Salah</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
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
