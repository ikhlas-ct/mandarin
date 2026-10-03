@extends('layouts.user.user')

@section('title', 'Edit Pelajar')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, .table, .btn, h4, h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ===== PAGE HEADER ===== */
    .ph-card { background: #fff; border: 1px solid #e9ecef; border-radius: 14px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 1.25rem; position: relative; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 14px 0 0 14px; }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0; }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li + li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }
    .ph-card.edit-page::before { background: #e96c1a; }
    .ph-icon.edit { background: #fff4ed; color: #e96c1a; }

    /* ===== FORM ===== */
    label { font-size: .875rem; font-weight: 500; }
    .required-mark { color: #dc3545; }
    .section-divider { background: #f8f9fa; border-left: 4px solid #1269db; padding: 8px 14px; border-radius: 0 6px 6px 0; font-weight: 600; font-size: .9rem; color: #1269db; margin-bottom: 1rem; }
    .form-card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .form-control, .form-select { border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: .83rem; padding: 7px 12px; color: #334155; background-color: #f8fafc; transition: border-color .2s, box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color: #1a73e8; background: #fff; box-shadow: 0 0 0 3px rgba(26,115,232,.12); }
    .form-control::placeholder { color: #94a3b8; }
    .foto-preview { width: 120px; height: 120px; border-radius: 16px; object-fit: cover; border: 1px solid #e2e8f0; background: #f1f5f9; }

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
                    <h5 class="ph-title">Edit Pelajar</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.pelajar.index') }}">Pelajar</a></li>
                        <li><a href="{{ route('admin.pelajar.show', $pelajar) }}">{{ $pelajar->nama }}</a></li>
                        <li><span class="bc-active">Edit</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.pelajar.show', $pelajar) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye me-1"></i> Lihat Detail
                </a>
                <a href="{{ route('admin.pelajar.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="page-inner">
            <form action="{{ route('admin.pelajar.update', $pelajar) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- Kolom kiri --}}
                    <div class="col-lg-8">

                        {{-- Data pelajar --}}
                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="section-divider"><i class="fas fa-user-graduate me-2"></i>Data Pelajar</div>

                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label for="nama" class="form-label">Nama Lengkap <span class="required-mark">*</span></label>
                                        <input type="text" id="nama" name="nama" maxlength="50"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            value="{{ old('nama', $pelajar->nama) }}" placeholder="Nama pelajar" required autofocus>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-5">
                                        <label for="no_telp" class="form-label">No. Telepon</label>
                                        <input type="text" id="no_telp" name="no_telp" maxlength="20"
                                            class="form-control @error('no_telp') is-invalid @enderror"
                                            value="{{ old('no_telp', $pelajar->no_telp) }}" placeholder="08xxxxxxxxxx">
                                        @error('no_telp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea id="alamat" name="alamat" rows="3" maxlength="1000"
                                            class="form-control @error('alamat') is-invalid @enderror"
                                            placeholder="Alamat pelajar (opsional)">{{ old('alamat', $pelajar->alamat) }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <textarea id="keterangan" name="keterangan" rows="3" maxlength="1000"
                                            class="form-control @error('keterangan') is-invalid @enderror"
                                            placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $pelajar->keterangan) }}</textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Akun login --}}
                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="section-divider"><i class="fas fa-key me-2"></i>Akun Login</div>

                                @unless ($pelajar->user)
                                    <div class="alert alert-warning py-2" style="font-size:.8rem;">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Pelajar ini belum punya akun login. Isi username, email, dan password untuk membuatkannya
                                        (opsional).
                                    </div>
                                @endunless

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="username" class="form-label">Username @if ($pelajar->user)<span class="required-mark">*</span>@endif</label>
                                        <input type="text" id="username" name="username" maxlength="100" autocomplete="off"
                                            class="form-control @error('username') is-invalid @enderror"
                                            value="{{ old('username', $pelajar->user?->username) }}" placeholder="Username untuk login">
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email @if ($pelajar->user)<span class="required-mark">*</span>@endif</label>
                                        <input type="email" id="email" name="email" maxlength="100" autocomplete="off"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $pelajar->user?->email) }}" placeholder="nama@email.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Password </label>
                                        <input type="password" id="password" name="password" autocomplete="new-password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="********">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Kosongkan jika tidak ingin mengganti password.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password </label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            autocomplete="new-password" class="form-control" placeholder="Ulangi password">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Kolom kanan --}}
                    <div class="col-lg-4">

                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="section-divider"><i class="fas fa-image me-2"></i>Foto &amp; Status</div>

                                <div class="text-center mb-3">
                                    <img id="foto-preview" src="{{ $pelajar->foto_url }}" alt="Foto pelajar" class="foto-preview">
                                </div>

                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" id="foto" name="foto" accept="image/png,image/jpeg,image/webp"
                                    class="form-control @error('foto') is-invalid @enderror">
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">JPG, PNG, atau WEBP. Maksimal 2 MB.</div>

                                @if ($pelajar->foto)
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="hapus_foto" value="1" id="hapus_foto"
                                            {{ old('hapus_foto') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="hapus_foto" style="font-size:.8rem;">Hapus foto saat ini</label>
                                    </div>
                                @endif

                                <div class="mt-3">
                                    <label for="status" class="form-label">Status <span class="required-mark">*</span></label>
                                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="aktif" {{ old('status', $pelajar->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ old('status', $pelajar->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Pelajar nonaktif tidak bisa login.</div>
                                </div>
                            </div>
                        </div>

                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Informasi</div>
                                <div class="info-item">
                                    <div class="info-label">Progres Hafalan</div>
                                    <div class="info-value">{{ $pelajar->progres_hafalans_count }} kata</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Dibuat</div>
                                    <div class="info-value">{{ $pelajar->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Terakhir Diubah</div>
                                    <div class="info-value">{{ $pelajar->updated_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.pelajar.show', $pelajar) }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}
@endsection

@section('scripts')
    <script>
        // Pratinjau foto sebelum diunggah
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = ev => document.getElementById('foto-preview').src = ev.target.result;
            reader.readAsDataURL(file);
        });
    </script>
@endsection
