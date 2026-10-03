@extends('layouts.user.user')

@section('title', 'Edit Tingkat HSK')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, .btn, h4, h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ===== PAGE HEADER ===== */
    .ph-card { background: #fff; border: 1px solid #e9ecef; border-radius: 14px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 1.25rem; position: relative; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 14px 0 0 14px; }
    .ph-card.edit-page::before { background: #e96c1a; }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
    .ph-icon.edit { background: #fff4ed; color: #e96c1a; }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0; }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li + li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    /* ===== FORM ===== */
    label { font-size: .875rem; font-weight: 500; }
    .required-mark { color: #dc3545; }
    .section-divider { background: #f8f9fa; border-left: 4px solid #1269db; padding: 8px 14px; border-radius: 0 6px 6px 0; font-weight: 600; font-size: .9rem; color: #1269db; margin-bottom: 1rem; }
    .form-card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .form-control { border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: .83rem; padding: 7px 12px; color: #334155; background-color: #f8fafc; transition: border-color .2s, box-shadow .2s; }
    .form-control:focus { border-color: #1a73e8; background: #fff; box-shadow: 0 0 0 3px rgba(26,115,232,.12); }
    .form-control::placeholder { color: #94a3b8; }

    /* ===== INFO ITEM ===== */
    .info-item { padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
    .info-item:last-child { border-bottom: none; padding-bottom: 0; }
    .info-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; }
    .info-value { font-size: .88rem; font-weight: 500; color: #1e293b; margin-top: 2px; word-break: break-word; }

    /* ===== BUTTONS ===== */
    .btn-primary { background: linear-gradient(135deg, #1a73e8, #1558b0); border: none; border-radius: 10px; font-weight: 600; font-size: .83rem; padding: 8px 18px; box-shadow: 0 2px 8px rgba(26,115,232,.35); transition: all .2s ease; }
    .btn-primary:hover { background: linear-gradient(135deg, #1558b0, #0f3e82); box-shadow: 0 4px 14px rgba(26,115,232,.45); transform: translateY(-1px); }
    .btn-outline-secondary { border-radius: 10px; font-size: .83rem; border-color: #e2e8f0; color: #64748b; padding: 7px 12px; }
    .btn-outline-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }
</style>
@endsection

@section('content')
    <div class="container">

        {{-- Header – di LUAR page-inner --}}
        <div class="ph-card edit-page">
            <div class="ph-left">
                <div class="ph-icon edit"><i class="fas fa-pencil-alt"></i></div>
                <div>
                    <h5 class="ph-title">Edit Tingkat HSK</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.level-hsk.index') }}">Tingkat HSK</a></li>
                        <li><a href="{{ route('admin.level-hsk.show', $level) }}">{{ $level->nama }}</a></li>
                        <li><span class="bc-active">Edit</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.level-hsk.show', $level) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye me-1"></i> Lihat Detail
                </a>
                <a href="{{ route('admin.level-hsk.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="page-inner">
            <form action="{{ route('admin.level-hsk.update', $level) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- Kolom kiri: form --}}
                    <div class="col-lg-8">
                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="section-divider"><i class="fas fa-layer-group me-2"></i>Data Tingkat</div>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="tingkat" class="form-label">Tingkat <span class="required-mark">*</span></label>
                                        <input type="number" id="tingkat" name="tingkat" min="1" max="9"
                                            class="form-control @error('tingkat') is-invalid @enderror"
                                            value="{{ old('tingkat', $level->tingkat) }}" placeholder="Contoh: 1" required autofocus>
                                        @error('tingkat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Angka 1–9, tidak boleh sama dengan tingkat lain.</div>
                                    </div>

                                    <div class="col-md-8">
                                        <label for="nama" class="form-label">Nama Tingkat <span class="required-mark">*</span></label>
                                        <input type="text" id="nama" name="nama" maxlength="50"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            value="{{ old('nama', $level->nama) }}" placeholder="Contoh: HSK 1" required>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Maksimal 50 karakter dan tidak boleh sama dengan tingkat lain.</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <textarea id="keterangan" name="keterangan" rows="5" maxlength="1000"
                                            class="form-control @error('keterangan') is-invalid @enderror"
                                            placeholder="Penjelasan singkat tentang tingkat ini (opsional)">{{ old('keterangan', $level->keterangan) }}</textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom kanan: info & tombol --}}
                    <div class="col-lg-4">
                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Informasi</div>
                                <div class="info-item">
                                    <div class="info-label">Jumlah Kosakata</div>
                                    <div class="info-value">{{ $level->kosakatas_count }} kosakata</div>
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

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.level-hsk.show', $level) }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}
@endsection
