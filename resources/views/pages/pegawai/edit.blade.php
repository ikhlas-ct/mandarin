@extends('layouts.user.user')

@section('title', 'Edit Pegawai – ' . $pegawai->nama)

@section('styles')
<style>
    .section-divider {
        background: #f8f9fa;
        border-left: 4px solid #e96c1a;
        padding: 8px 14px;
        border-radius: 0 6px 6px 0;
        font-weight: 600;
        font-size: .9rem;
        color: #e96c1a;
        margin-bottom: 1rem;
    }

    .foto-preview-wrap {
        width: 130px; height: 130px;
        border: 2px dashed #ced4da; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; overflow: hidden; transition: border-color .2s;
    }
    .foto-preview-wrap:hover { border-color: #e96c1a; }
    .foto-preview-wrap img { width: 100%; height: 100%; object-fit: cover; }
    .required-mark { color: #dc3545; }
    label { font-size: .875rem; font-weight: 500; }

    .form-control, .form-select {
        border-radius: 10px; border: 1.5px solid #e2e8f0;
        font-size: .85rem; padding: 8px 12px; color: #334155;
        background-color: #f8fafc; transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #e96c1a; background: #fff;
        box-shadow: 0 0 0 3px rgba(233,108,26,.12);
    }
    .form-control::placeholder { color: #94a3b8; }

    /* ── Page Header Card ── */
    .ph-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 14px;
        padding: 16px 20px; display: flex; align-items: center;
        justify-content: space-between; gap: 16px; flex-wrap: wrap;
        margin-bottom: 1.25rem; position: relative; overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before {
        content: ''; position: absolute;
        left: 0; top: 0; bottom: 0; width: 4px;
        border-radius: 14px 0 0 14px; background: #e96c1a;
    }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon {
        width: 42px; height: 42px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0; background: #fff4ed; color: #e96c1a;
    }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb {
        display: flex; align-items: center; gap: 4px; flex-wrap: wrap;
        margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0;
    }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li+li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    .input-group-text { background: #f8fafc; border: 1.5px solid #e2e8f0; border-right: none; font-size: .85rem; }
    .input-group .form-control { border-left: none; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-pen-to-square"></i></div>
            <div>
                <h5 class="ph-title">Edit Pegawai</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('pegawai.index') }}">Pegawai</a></li>
                    <li><a href="{{ route('pegawai.show', $pegawai->id_pegawai) }}">{{ $pegawai->nama }}</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pegawai.show', $pegawai->id_pegawai) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-eye me-1"></i> Detail
            </a>
            <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="page-inner">
        <form action="{{ route('pegawai.update', $pegawai->id_pegawai) }}" method="POST"
              enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-4">

                {{-- ── Kolom Kiri: Form ── --}}
                <div class="col-lg-8">

                    {{-- Data Diri --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-id-card me-2"></i>Data Diri</div>
                            <div class="row g-3">

                                <div class="col-md-8">
                                    <label class="form-label">Nama Lengkap <span class="required-mark">*</span></label>
                                    <input type="text" name="nama"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama', $pegawai->nama) }}" required>
                                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Posisi / Jabatan <span class="required-mark">*</span></label>
                                    <input type="text" name="posisi"
                                        class="form-control @error('posisi') is-invalid @enderror"
                                        value="{{ old('posisi', $pegawai->posisi) }}" required>
                                    @error('posisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $pegawai->email) }}">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">No. HP / WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" name="nohp"
                                            class="form-control @error('nohp') is-invalid @enderror"
                                            value="{{ old('nohp', $pegawai->nohp) }}">
                                        @error('nohp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Alamat</label>
                                    <input type="text" name="alamat"
                                        class="form-control @error('alamat') is-invalid @enderror"
                                        value="{{ old('alamat', $pegawai->alamat) }}">
                                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Deskripsi / Bio</label>
                                    <textarea name="deskripsi" rows="3"
                                        class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $pegawai->deskripsi) }}</textarea>
                                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Media Sosial --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-share-nodes me-2"></i>Media Sosial</div>
                            <div class="row g-3">

                                <div class="col-md-4">
                                    <label class="form-label">Instagram</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background:#fdf2f8;color:#be185d;">
                                            <i class="fab fa-instagram"></i>
                                        </span>
                                        <input type="url" name="instagram"
                                            class="form-control @error('instagram') is-invalid @enderror"
                                            value="{{ old('instagram', $pegawai->instagram) }}">
                                        @error('instagram')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Twitter / X</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background:#eff6ff;color:#1d4ed8;">
                                            <i class="fab fa-twitter"></i>
                                        </span>
                                        <input type="url" name="twitter"
                                            class="form-control @error('twitter') is-invalid @enderror"
                                            value="{{ old('twitter', $pegawai->twitter) }}">
                                        @error('twitter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Facebook</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background:#eff6ff;color:#1e40af;">
                                            <i class="fab fa-facebook"></i>
                                        </span>
                                        <input type="url" name="facebook"
                                            class="form-control @error('facebook') is-invalid @enderror"
                                            value="{{ old('facebook', $pegawai->facebook) }}">
                                        @error('facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Hubungkan / Ganti Akun --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-key me-2"></i>Akun Login</div>
                            <label class="form-label">Hubungkan ke Akun User</label>
                            <select name="id_user" class="form-select @error('id_user') is-invalid @enderror">
                                <option value="">-- Tidak dihubungkan --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}"
                                        {{ old('id_user', $pegawai->id_user) == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Hanya akun <code>admin_dinsos</code> yang belum terhubung ke profil lain.</div>
                        </div>
                    </div>

                </div>

                {{-- ── Kolom Kanan: Foto & Simpan ── --}}
                <div class="col-lg-4">

                    {{-- Foto Profil --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body text-center">
                            <div class="section-divider text-start"><i class="fas fa-camera me-2"></i>Foto Profil</div>
                            <div class="foto-preview-wrap mx-auto mb-3"
                                 onclick="document.getElementById('foto-input').click()">
                                <img id="foto-preview"
                                    src="{{ $pegawai->foto_profil
                                        ? asset('storage/' . $pegawai->foto_profil)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($pegawai->nama) . '&background=e9ecef&color=6c757d&size=130' }}"
                                    alt="{{ $pegawai->nama }}">
                            </div>
                            <input type="file" name="foto_profil" id="foto-input" accept="image/*" class="d-none">
                            <button type="button" class="btn btn-outline-warning btn-sm w-100"
                                onclick="document.getElementById('foto-input').click()">
                                <i class="fas fa-sync me-1"></i> Ganti Foto
                            </button>
                            <div class="text-muted small mt-2">Kosongkan jika tidak ingin mengubah foto</div>
                            @error('foto_profil')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Tombol Simpan --}}
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <button type="submit" class="btn btn-warning w-100 mb-2">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('pegawai.show', $pegawai->id_pegawai) }}"
                               class="btn btn-outline-secondary w-100">
                                Batal
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </form>
    </div>{{-- end .page-inner --}}

</div>
@endsection

@section('scripts')
<script>
    document.getElementById('foto-input').addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('foto-preview').src = e.target.result;
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection
