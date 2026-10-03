@extends('layouts.user.user')

@section('title', 'Tambah Pegawai')

@section('styles')
<style>
    .section-divider {
        background: #f8f9fa;
        border-left: 4px solid #16a34a;
        padding: 8px 14px;
        border-radius: 0 6px 6px 0;
        font-weight: 600;
        font-size: .9rem;
        color: #16a34a;
        margin-bottom: 1rem;
    }

    .foto-preview-wrap {
        width: 130px; height: 130px;
        border: 2px dashed #ced4da;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; overflow: hidden;
        transition: border-color .2s;
    }
    .foto-preview-wrap:hover { border-color: #16a34a; }
    .foto-preview-wrap img { width: 100%; height: 100%; object-fit: cover; }
    .required-mark { color: #dc3545; }
    label { font-size: .875rem; font-weight: 500; }

    .form-control, .form-select {
        border-radius: 10px; border: 1.5px solid #e2e8f0;
        font-size: .85rem; padding: 8px 12px; color: #334155;
        background-color: #f8fafc; transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #16a34a; background: #fff;
        box-shadow: 0 0 0 3px rgba(22,163,74,.12);
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
        border-radius: 14px 0 0 14px; background: #16a34a;
    }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon {
        width: 42px; height: 42px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
        background: #dcfce7; color: #16a34a;
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

    /* ── Akun Toggle ── */
    .akun-box {
        border: 1.5px dashed #e2e8f0; border-radius: 12px;
        padding: 16px; background: #fafbfc;
        transition: border-color .2s, background .2s;
    }
    .akun-box.active { border-color: #16a34a; background: #f0fdf4; }

    /* ── Sosmed Input group ── */
    .input-group-text { background: #f8fafc; border: 1.5px solid #e2e8f0; border-right: none; font-size: .85rem; }
    .input-group .form-control { border-left: none; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-user-plus"></i></div>
            <div>
                <h5 class="ph-title">Tambah Pegawai</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('pegawai.index') }}">Pegawai</a></li>
                    <li><span class="bc-active">Tambah</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="page-inner">
        <form action="{{ route('pegawai.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

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
                                        value="{{ old('nama') }}" placeholder="Nama lengkap pegawai" required>
                                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Posisi / Jabatan <span class="required-mark">*</span></label>
                                    <input type="text" name="posisi"
                                        class="form-control @error('posisi') is-invalid @enderror"
                                        value="{{ old('posisi') }}" placeholder="cth. Bendahara" required>
                                    @error('posisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>



                                <div class="col-md-12">
                                    <label class="form-label">No. HP / WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-number"><i class="fas fa-phone"></i></span>
                                        <input type="number" name="nohp"
                                            class="form-control @error('nohp') is-invalid @enderror"
                                            value="{{ old('nohp') }}" placeholder="08xxxxxxxxxx">
                                        @error('nohp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Alamat</label>
                                    <input type="text" name="alamat"
                                        class="form-control @error('alamat') is-invalid @enderror"
                                        value="{{ old('alamat') }}" placeholder="Alamat lengkap">
                                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Deskripsi / Bio</label>
                                    <textarea name="deskripsi" rows="3"
                                        class="form-control @error('deskripsi') is-invalid @enderror"
                                        placeholder="Deskripsi singkat atau bio pegawai…">{{ old('deskripsi') }}</textarea>
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
                                            value="{{ old('instagram') }}" placeholder="https://instagram.com/…">
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
                                            value="{{ old('twitter') }}" placeholder="https://twitter.com/…">
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
                                            value="{{ old('facebook') }}" placeholder="https://facebook.com/…">
                                        @error('facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Akun User (Opsional) --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-key me-2"></i>Akun Login (Opsional)</div>

                            {{-- Pilih akun yang sudah ada --}}
                            <div class="mb-3">
                                <label class="form-label">Hubungkan ke Akun Existing</label>
                                <select name="id_user" class="form-select @error('id_user') is-invalid @enderror">
                                    <option value="">-- Tidak dihubungkan --</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ old('id_user') == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }} ({{ $u->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">Hanya akun <code>admin_dinsos</code> yang belum punya profil pegawai.</div>
                            </div>

                            <div class="text-center text-muted my-2" style="font-size:.8rem;">— atau —</div>

                            {{-- Buat akun baru --}}
                            <div class="akun-box" id="akun-box">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="buat_akun"
                                        id="buat-akun-check" value="1" {{ old('buat_akun') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="buat-akun-check">
                                        Buat akun baru sekaligus
                                    </label>
                                </div>
                                <div id="akun-fields" style="{{ old('buat_akun') ? '' : 'display:none' }}">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Email Akun <span class="required-mark">*</span></label>
                                            <input type="email" name="akun_email"
                                                class="form-control @error('akun_email') is-invalid @enderror"
                                                value="{{ old('akun_email') }}" placeholder="email@domain.com">
                                            @error('akun_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Password <span class="required-mark">*</span></label>
                                            <div class="input-group">
                                                <input type="password" name="akun_password" id="akun-pass"
                                                    class="form-control @error('akun_password') is-invalid @enderror"
                                                    placeholder="Min. 8 karakter">
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                    onclick="togglePass()" style="border-radius:0 10px 10px 0;">
                                                    <i class="fas fa-eye" id="pass-icon"></i>
                                                </button>
                                                @error('akun_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- ── Kolom Kanan: Foto & Simpan ── --}}
                <div class="col-lg-4">

                    {{-- Foto Profil --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body text-center">
                            <div class="section-divider text-start"><i class="fas fa-camera me-2"></i>Foto Profil</div>
                            <div class="foto-preview-wrap mx-auto mb-3" id="foto-wrap"
                                 onclick="document.getElementById('foto-input').click()">
                                <img id="foto-preview"
                                    src="{{ asset('user/img/default-avatar.png') }}"
                                    onerror="this.src='https://ui-avatars.com/api/?name=Foto&background=e9ecef&color=6c757d&size=130'"
                                    alt="Preview Foto">
                            </div>
                            <input type="file" name="foto_profil" id="foto-input" accept="image/*" class="d-none">
                            <button type="button" class="btn btn-outline-success btn-sm w-100"
                                onclick="document.getElementById('foto-input').click()">
                                <i class="fas fa-upload me-1"></i> Upload Foto
                            </button>
                            <div class="text-muted small mt-2">JPG/PNG/WEBP, maks. 2 MB</div>
                            @error('foto_profil')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Tombol Simpan --}}
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-save me-2"></i> Simpan Pegawai
                            </button>
                            <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary w-100">
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
    // ── Preview foto ──────────────────────────────────────────────────
    document.getElementById('foto-input').addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('foto-preview').src = e.target.result;
            reader.readAsDataURL(this.files[0]);
        }
    });

    // ── Toggle akun baru ──────────────────────────────────────────────
    const buatAkunCheck = document.getElementById('buat-akun-check');
    const akunFields    = document.getElementById('akun-fields');
    const akunBox       = document.getElementById('akun-box');

    buatAkunCheck.addEventListener('change', function () {
        akunFields.style.display = this.checked ? '' : 'none';
        akunBox.classList.toggle('active', this.checked);
    });

    if (buatAkunCheck.checked) akunBox.classList.add('active');

    // ── Toggle password visibility ────────────────────────────────────
    function togglePass() {
        const input = document.getElementById('akun-pass');
        const icon  = document.getElementById('pass-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endsection
