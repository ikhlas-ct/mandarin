@extends('layouts.user.user')

@section('title', 'Flashcard Kosakata')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, .btn, h4, h5, label, select { font-family: 'Plus Jakarta Sans', sans-serif; }

    .ph-card { background: #fff; border: 1px solid #e9ecef; border-radius: 14px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 1.25rem; position: relative; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 14px 0 0 14px; background: #0369a1; }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; background: #e0f2fe; color: #0369a1; }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0; }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li + li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    .form-card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .section-divider { background: #f8f9fa; border-left: 4px solid #1269db; padding: 8px 14px; border-radius: 0 6px 6px 0; font-weight: 600; font-size: .9rem; color: #1269db; margin-bottom: 1rem; }

    .review-hero { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); position: relative; overflow: hidden; }
    .review-hero::after { content: ''; position: absolute; right: -30px; top: -30px; width: 130px; height: 130px; border-radius: 50%; background: #ea580c; opacity: .1; }
    .review-hero.kosong { background: linear-gradient(135deg, #e6f9f0 0%, #d1fae5 100%); }
    .review-hero.kosong::after { background: #16a34a; }
    .review-angka { font-size: 2.6rem; font-weight: 800; line-height: 1; color: #1e293b; }
    .review-ket { font-size: .85rem; color: #64748b; }

    .form-label { font-size: .78rem; font-weight: 600; color: #64748b; margin-bottom: 4px; }
    .form-select { border-radius: 10px; font-size: .85rem; border-color: #e2e8f0; }
    .btn-primary { background: linear-gradient(135deg, #1a73e8, #1558b0); border: none; border-radius: 10px; font-weight: 600; font-size: .85rem; padding: 9px 20px; box-shadow: 0 2px 8px rgba(26,115,232,.35); }
    .btn-primary:hover { background: linear-gradient(135deg, #1558b0, #0f3e82); }
    .btn-orange { background: linear-gradient(135deg, #f97316, #ea580c); border: none; color: #fff; border-radius: 10px; font-weight: 600; font-size: .85rem; padding: 9px 20px; box-shadow: 0 2px 8px rgba(234,88,12,.35); }
    .btn-orange:hover { color: #fff; background: linear-gradient(135deg, #ea580c, #c2410c); }
</style>
@endsection

@section('content')
    <div class="container">

        <div class="ph-card">
            <div class="ph-left">
                <div class="ph-icon"><i class="fas fa-layer-group"></i></div>
                <div>
                    <h5 class="ph-title">Flashcard Kosakata</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('pelajar.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('pelajar.kosakata.index') }}">Kosakata</a></li>
                        <li><span class="bc-active">Flashcard</span></li>
                    </ol>
                </div>
            </div>
            <a href="{{ route('pelajar.kosakata.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="page-inner">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif
            @if (session('error') || $errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') ?? $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            {{-- Review harian --}}
            <div class="card review-hero mb-4 {{ $jatuhTempo === 0 ? 'kosong' : '' }}">
                <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="review-angka">{{ $jatuhTempo }}</div>
                        <div>
                            <div class="fw-bold" style="color:#1e293b;">
                                @if ($jatuhTempo > 0)
                                    kata siap direview hari ini
                                @else
                                    <i class="fas fa-check-circle text-success me-1"></i> Semua review hari ini sudah beres
                                @endif
                            </div>
                            <div class="review-ket">
                                @if ($jatuhTempo > $batasReview)
                                    Satu sesi memuat maksimal {{ $batasReview }} kata, sisanya bisa dilanjutkan setelahnya.
                                @elseif ($jatuhTempo > 0)
                                    Kata berstatus Lupa &amp; Ingat dan Ingat Sepenuhnya yang jadwalnya sudah tiba.
                                @else
                                    Kata baru akan muncul lagi sesuai jadwal. Mau lanjut, pakai latihan bebas di bawah.
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($jatuhTempo > 0)
                        <form method="GET" action="{{ route('pelajar.flashcard.mulai') }}">
                            <input type="hidden" name="mode" value="review">
                            <button type="submit" class="btn btn-orange">
                                <i class="fas fa-play me-1"></i> Mulai review
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Latihan bebas --}}
            <div class="card form-card">
                <div class="card-body p-4">
                    <div class="section-divider"><i class="fas fa-sliders-h me-2"></i>Latihan Bebas</div>

                    <form method="GET" action="{{ route('pelajar.flashcard.mulai') }}">
                        <input type="hidden" name="mode" value="bebas">

                        <div class="row g-3">
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label" for="f-status">Status kata</label>
                                <select class="form-select" id="f-status" name="status">
                                    <option value="berikutnya" selected>{{ $statusList['berikutnya']['label'] }} (belum dipelajari)</option>
                                    <option value="lupa_dan_ingat">{{ $statusList['lupa_dan_ingat']['label'] }}</option>
                                    <option value="ingat_sepenuhnya">{{ $statusList['ingat_sepenuhnya']['label'] }}</option>
                                    <option value="">Semua status</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label" for="f-kategori">Kategori</label>
                                <select class="form-select" id="f-kategori" name="kategori_id">
                                    <option value="">Semua kategori</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label" for="f-level">Level HSK</label>
                                <select class="form-select" id="f-level" name="level_hsk_id">
                                    <option value="">Semua level</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level->id }}">{{ $level->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label" for="f-jumlah">Jumlah kartu</label>
                                <select class="form-select" id="f-jumlah" name="jumlah">
                                    @foreach ($pilihanJumlah as $n)
                                        <option value="{{ $n }}" {{ $n === 20 ? 'selected' : '' }}>{{ $n }} kata</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mt-4">
                            <div class="form-check">
                                <input type="hidden" name="acak" value="0">
                                <input class="form-check-input" type="checkbox" name="acak" value="1" id="f-acak" checked>
                                <label class="form-check-label" for="f-acak" style="font-size:.85rem;">Acak urutan kartu</label>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-play me-1"></i> Mulai latihan
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 pt-3" style="border-top:1px solid #f1f5f9; font-size:.78rem; color:#64748b; line-height:1.6;">
                        <i class="fas fa-info-circle me-1"></i>
                        Tombol <b>Ingat</b> memindahkan kata ke <b>Ingat Sepenuhnya</b> (review lagi 7 hari kemudian).
                        Tombol <b>Lupa</b> memindahkannya ke <b>Lupa &amp; Ingat</b> (review lagi besok).
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
