@extends('layouts.user.user')

@section('title', 'Pengaturan Website')

@section('styles')
    <style>
        /* ===== IMPORT FONT ===== */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body,
        .card,
        .btn,
        h4,
        h5 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ===== PAGE HEADER ===== */
        .ph-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .05);
        }

        .ph-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 14px 0 0 14px;
        }

        .ph-card.index-page::before {
            background: #1a73e8;
        }

        .ph-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ph-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .ph-icon.index {
            background: #e8f0fe;
            color: #1a73e8;
        }

        .ph-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -.2px;
            line-height: 1.2;
            margin: 0;
        }

        .ph-breadcrumb {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
            margin-top: 4px;
            list-style: none;
            padding: 0;
            margin-bottom: 0;
        }

        .ph-breadcrumb li {
            display: flex;
            align-items: center;
        }

        .ph-breadcrumb li+li::before {
            content: '›';
            color: #cbd5e1;
            font-size: .7rem;
            margin: 0 4px;
        }

        .ph-breadcrumb a {
            font-size: .75rem;
            color: #1a73e8;
            text-decoration: none;
        }

        .ph-breadcrumb a:hover {
            text-decoration: underline;
        }

        .ph-breadcrumb .bc-active {
            font-size: .75rem;
            color: #94a3b8;
        }

        /* ===== CARD ===== */
        .filter-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
            overflow: hidden;
        }

        .filter-card .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            padding: 18px 24px;
        }

        .filter-card .card-header h5 {
            font-size: .95rem;
            font-weight: 700;
            color: #1e293b;
        }

        .filter-card .card-body {
            padding: 24px;
        }

        /* ===== FORM ===== */
        .form-label {
            font-size: .78rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: .83rem;
            padding: 8px 12px;
            color: #334155;
            background-color: #f8fafc;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #1a73e8;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, .12);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .input-group .input-group-text {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: #94a3b8;
            font-size: .8rem;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .form-hint {
            font-size: .72rem;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* ===== PREVIEW GAMBAR ===== */
        .img-preview-box {
            width: 100%;
            min-height: 120px;
            border: 1.5px dashed #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .img-preview-box img {
            max-width: 100%;
            max-height: 160px;
            object-fit: contain;
            border-radius: 8px;
        }

        .img-preview-box .placeholder-text {
            font-size: .78rem;
            color: #94a3b8;
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: linear-gradient(135deg, #1a73e8, #1558b0);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: .83rem;
            padding: 8px 18px;
            box-shadow: 0 2px 8px rgba(26, 115, 232, .35);
            transition: all .2s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1558b0, #0f3e82);
            box-shadow: 0 4px 14px rgba(26, 115, 232, .45);
            transform: translateY(-1px);
        }

        .btn-outline-danger {
            border-radius: 10px;
            font-size: .83rem;
            font-weight: 600;
            padding: 7px 14px;
        }

        .btn-outline-secondary {
            border-radius: 10px;
            font-size: .83rem;
            border-color: #e2e8f0;
            color: #64748b;
            padding: 7px 12px;
        }

        .btn-outline-secondary:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #334155;
        }
    </style>
@endsection

@section('content')
    @php
        $pr = $pengaturan;

        $sosial = [
            'social_facebook' => ['Facebook', 'fab fa-facebook-f', 'https://facebook.com/namaakun'],
            'social_instagram' => ['Instagram', 'fab fa-instagram', 'https://instagram.com/namaakun'],
            'social_twitter' => ['Twitter / X', 'fab fa-twitter', 'https://x.com/namaakun'],
            'social_youtube' => ['YouTube', 'fab fa-youtube', 'https://youtube.com/@namakanal'],
        ];
    @endphp

    <div class="container">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="ph-card index-page">
            <div class="ph-left">
                <div class="ph-icon index"><i class="fas fa-cogs"></i></div>
                <div>
                    <h5 class="ph-title">Pengaturan Website</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><span class="bc-active">Pengaturan Website</span></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="page-inner">

            {{-- Alert --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>Ada isian yang perlu diperbaiki, cek kolom bertanda merah.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ===== FORM UTAMA ===== --}}
            <form id="form-pengaturan" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.pengaturan.update') }}">
                @csrf
                @method('PUT')

                {{-- ---------- Identitas ---------- --}}
                <div class="card filter-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-id-card text-primary me-2 opacity-75"></i>Identitas Website</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label" for="nama">Nama Website</label>
                                    <input type="text" id="nama" name="nama"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama', $pr->nama) }}" placeholder="Contoh: Belajar Mandarin">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label" for="slogan">Slogan</label>
                                    <input type="text" id="slogan" name="slogan"
                                        class="form-control @error('slogan') is-invalid @enderror"
                                        value="{{ old('slogan', $pr->slogan) }}" placeholder="Slogan singkat website">
                                    @error('slogan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="logo">Logo</label>
                                <div class="img-preview-box">
                                    <img id="logo-preview" src="{{ $pr->logo_url }}" alt="Logo website">
                                </div>
                                <input type="file" id="logo" name="logo" accept="image/*"
                                    class="form-control @error('logo') is-invalid @enderror"
                                    onchange="previewImage(this, 'logo-preview')">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">JPG, PNG, atau WebP. Maksimal 2 MB.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ---------- Kontak ---------- --}}
                <div class="card filter-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-address-book text-primary me-2 opacity-75"></i>Kontak</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" id="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $pr->email) }}" placeholder="admin@contoh.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="nomor_telepon">Nomor Telepon</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" id="nomor_telepon" name="nomor_telepon" maxlength="20"
                                        class="form-control @error('nomor_telepon') is-invalid @enderror"
                                        value="{{ old('nomor_telepon', $pr->nomor_telepon) }}"
                                        placeholder="08xxxxxxxxxx">
                                    @error('nomor_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="alamat">Alamat</label>
                                <textarea id="alamat" name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror"
                                    placeholder="Alamat lengkap">{{ old('alamat', $pr->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ---------- Media Sosial ---------- --}}
                <div class="card filter-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-share-alt text-primary me-2 opacity-75"></i>Media Sosial</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($sosial as $name => [$label, $icon, $placeholder])
                                <div class="col-md-6">
                                    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="{{ $icon }}"></i></span>
                                        <input type="url" id="{{ $name }}" name="{{ $name }}"
                                            class="form-control @error($name) is-invalid @enderror"
                                            value="{{ old($name, $pr->{$name}) }}" placeholder="{{ $placeholder }}">
                                        @error($name)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ---------- Pengantar ---------- --}}
                <div class="card filter-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bullhorn text-primary me-2 opacity-75"></i>Pengantar Beranda</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label" for="title_pengantar">Judul Pengantar</label>
                                    <input type="text" id="title_pengantar" name="title_pengantar"
                                        class="form-control @error('title_pengantar') is-invalid @enderror"
                                        value="{{ old('title_pengantar', $pr->title_pengantar) }}"
                                        placeholder="Judul di bagian atas beranda">
                                    @error('title_pengantar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label" for="paragraf_pengantar">Paragraf Pengantar</label>
                                    <textarea id="paragraf_pengantar" name="paragraf_pengantar" rows="7"
                                        class="form-control @error('paragraf_pengantar') is-invalid @enderror" placeholder="Teks pengantar singkat">{{ old('paragraf_pengantar', $pr->paragraf_pengantar) }}</textarea>
                                    @error('paragraf_pengantar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="gambar_pengantar">Gambar Pengantar</label>
                                <div class="img-preview-box">
                                    @if ($pr->gambar_pengantar_url)
                                        <img id="gambar-preview" src="{{ $pr->gambar_pengantar_url }}"
                                            alt="Gambar pengantar">
                                    @else
                                        <img id="gambar-preview" class="d-none" alt="Gambar pengantar">
                                        <span class="placeholder-text" id="gambar-placeholder">Belum ada gambar</span>
                                    @endif
                                </div>
                                <input type="file" id="gambar_pengantar" name="gambar_pengantar" accept="image/*"
                                    class="form-control @error('gambar_pengantar') is-invalid @enderror"
                                    onchange="previewImage(this, 'gambar-preview', 'gambar-placeholder')">
                                @error('gambar_pengantar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">JPG, PNG, atau WebP. Maksimal 4 MB.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ---------- About Us ---------- --}}
                <div class="card filter-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle text-primary me-2 opacity-75"></i>Tentang Kami</h5>
                    </div>
                    <div class="card-body">
                        <label class="form-label" for="about_us">Isi Tentang Kami</label>
                        <textarea id="about_us" name="about_us" rows="8" class="form-control @error('about_us') is-invalid @enderror"
                            placeholder="Ceritakan tentang website ini">{{ old('about_us', $pr->about_us) }}</textarea>
                        @error('about_us')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ---------- Tombol ---------- --}}
                <div class="d-flex justify-content-end gap-2 mb-4">
                    <a href="{{ route('admin.pengaturan') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-redo-alt me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Simpan perubahan
                    </button>
                </div>
            </form>

        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}
@endsection

@section('scripts')
    <script>
        // Preview gambar sebelum diunggah
        function previewImage(input, imgId, placeholderId = null) {
            const file = input.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(imgId);
                img.src = e.target.result;
                img.classList.remove('d-none');

                if (placeholderId) {
                    const ph = document.getElementById(placeholderId);
                    if (ph) ph.classList.add('d-none');
                }
            };
            reader.readAsDataURL(file);
        }
    </script>
@endsection
