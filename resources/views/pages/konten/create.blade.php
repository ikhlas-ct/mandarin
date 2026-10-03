@extends('layouts.user.user')

@section('title', 'Tambah ' . ucfirst($jenis))

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root {
        --accent:        {{ $jenis === 'berita' ? '#1a73e8' : '#16a34a' }};
        --accent-light:  {{ $jenis === 'berita' ? '#e8f0fe' : '#dcfce7' }};
        --accent-shadow: {{ $jenis === 'berita' ? 'rgba(26,115,232,.12)' : 'rgba(22,163,74,.12)' }};
    }

    /* ── Layout spacing ── */
    .container { padding-left: 28px; padding-right: 24px; }

    /* ── Page Header ── */
    .ph-card {
        background:#fff; border:1px solid #e9ecef; border-radius:14px;
        padding:16px 22px; display:flex; align-items:center;
        justify-content:space-between; gap:16px; flex-wrap:wrap;
        margin-bottom:1.5rem; position:relative; overflow:hidden;
        box-shadow:0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:44px; height:44px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:1.05rem; flex-shrink:0; background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; line-height:1.2; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; flex-wrap:wrap; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb a:hover { text-decoration:underline; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ── Section cards ── */
    .section-card { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); margin-bottom:1.25rem; }
    .section-card .card-body { padding:22px 24px; }

    .section-divider {
        border-left:4px solid var(--accent); background:#f8f9fa;
        padding:7px 13px; border-radius:0 6px 6px 0;
        font-weight:700; font-size:.82rem; color:var(--accent);
        display:flex; align-items:center; gap:8px; margin-bottom:1.1rem;
    }

    /* ── Form controls ── */
    label { font-size:.83rem; font-weight:600; color:#475569; }
    .required-mark { color:#dc3545; }
    .form-control, .form-select {
        border-radius:10px; border:1.5px solid #e2e8f0; font-size:.85rem;
        padding:8px 12px; color:#334155; background:#f8fafc;
        transition:border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus { border-color:var(--accent); background:#fff; box-shadow:0 0 0 3px var(--accent-shadow); }
    .form-control::placeholder { color:#b0bec5; }
    .input-group-text { background:#f8fafc; border:1.5px solid #e2e8f0; border-right:none; border-radius:10px 0 0 10px; font-size:.85rem; color:#94a3b8; }
    .input-group .form-control { border-left:none; border-radius:0 10px 10px 0; }
    .form-text { font-size:.75rem; color:#94a3b8; margin-top:4px; }

    /* ── Summernote frame ── */
    .note-editor.note-frame { border-radius:10px; border:1.5px solid #e2e8f0; overflow:hidden; }
    .note-editor.note-frame .note-toolbar { background:#f8fafc; border-bottom:1px solid #e2e8f0; }
    .note-editor.note-frame.focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-shadow); }
    .note-editor .note-editable { font-family:'Plus Jakarta Sans',sans-serif; font-size:.9rem; min-height:320px; }

    /* ── Gambar drop zone ── */
    .gambar-drop-zone {
        width:100%; height:190px; border:2px dashed #ced4da; border-radius:12px;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        cursor:pointer; overflow:hidden; transition:border-color .2s, background .2s;
        background:#fafbfc; position:relative;
    }
    .gambar-drop-zone:hover, .gambar-drop-zone.drag-over { border-color:var(--accent); background:var(--accent-light); }
    #gambar-preview { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; display:none; }
    .gambar-placeholder { text-align:center; color:#94a3b8; pointer-events:none; }
    .gambar-placeholder i { font-size:2rem; margin-bottom:6px; display:block; }
    .gambar-placeholder span { font-size:.78rem; }

    /* ── Status pills ── */
    .status-radio { display:none; }
    .status-label {
        display:inline-flex; align-items:center; gap:6px;
        padding:7px 14px; border-radius:20px; font-size:.8rem; font-weight:600;
        cursor:pointer; border:1.5px solid #e2e8f0; background:#f8fafc; color:#64748b;
        transition:all .15s; white-space:nowrap;
    }
    .status-radio:checked + .status-label { border-color:var(--accent); background:var(--accent-light); color:var(--accent); }

    /* ── Panti notice ── */
    .panti-notice {
        background:var(--accent-light); border-radius:10px;
        padding:10px 14px; font-size:.83rem; color:var(--accent);
        display:flex; align-items:center; gap:8px;
    }

    /* ── Char counter ── */
    .char-counter { font-size:.72rem; color:#94a3b8; text-align:right; margin-top:3px; }
    .char-counter.warning { color:#d97706; }
    .char-counter.danger  { color:#dc2626; }

    /* ── Buttons ── */
    .btn-accent {
        background:linear-gradient(135deg, var(--accent), color-mix(in srgb,var(--accent),#000 18%));
        border:none; border-radius:10px; font-weight:600; font-size:.85rem;
        padding:9px 22px; color:#fff; box-shadow:0 2px 8px var(--accent-shadow); transition:all .2s;
    }
    .btn-accent:hover { transform:translateY(-1px); filter:brightness(1.07); color:#fff; }
    .btn-outline-secondary { border-radius:10px; font-size:.83rem; border-color:#e2e8f0; color:#64748b; }
    .btn-outline-secondary:hover { background:#f1f5f9; }

    /* ── Tip box ── */
    .tip-box { background:#fafbfc; border:1px solid #f1f5f9; border-radius:10px; padding:12px 14px; font-size:.78rem; color:#64748b; margin-top:.75rem; }
    .tip-box i { color:var(--accent); margin-right:4px; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon">
                <i class="fas fa-{{ $jenis === 'berita' ? 'newspaper' : 'calendar-plus' }}"></i>
            </div>
            <div>
                <h5 class="ph-title">Tambah {{ ucfirst($jenis) }}</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('konten.index', $jenis) }}">{{ ucfirst($jenis) }}</a></li>
                    <li><span class="bc-active">Tambah</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('konten.index', $jenis) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('konten.store', $jenis) }}" method="POST"
          enctype="multipart/form-data" novalidate>
        @csrf

        <div class="row g-4">

            {{-- ══════════ KOLOM KIRI ══════════ --}}
            <div class="col-lg-8">

                {{-- Informasi Dasar --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-file-alt"></i> Informasi Dasar</div>

                        <div class="mb-3">
                            <label class="form-label">Judul <span class="required-mark">*</span></label>
                            <input type="text" name="judul" id="judul"
                                   class="form-control @error('judul') is-invalid @enderror"
                                   value="{{ old('judul') }}"
                                   placeholder="Tulis judul yang menarik…" required maxlength="255">
                            <div class="char-counter" id="judul-counter">0 / 255</div>
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        @if($kategori->isNotEmpty())
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="id_kategori" class="form-select @error('id_kategori') is-invalid @enderror">
                                <option value="">-- Tanpa Kategori --</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id_kategori }}"
                                        {{ old('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @endif

                        <div class="mb-0">
                            <label class="form-label">Ringkasan</label>
                            <textarea name="ringkasan" id="ringkasan" rows="2"
                                      class="form-control @error('ringkasan') is-invalid @enderror"
                                      placeholder="Ringkasan singkat yang tampil di halaman daftar…"
                                      maxlength="255">{{ old('ringkasan') }}</textarea>
                            <div class="char-counter" id="ringkasan-counter">0 / 255</div>
                            @error('ringkasan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Isi Konten --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-pen-nib"></i> Isi {{ ucfirst($jenis) }}
                            <span class="required-mark ms-1">*</span>
                        </div>
                        <textarea name="isi" id="isi"
                                  class="form-control summernote @error('isi') is-invalid @enderror"
                                  rows="10">{{ old('isi') }}</textarea>
                        @error('isi')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Detail Kegiatan --}}
                @if($jenis === 'kegiatan')
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-calendar-alt"></i> Detail Kegiatan</div>

                        <div class="row g-3">

                            {{-- Panti --}}
                            @if(auth()->user()->isAdminPanti() && $pantiPengurus)
                                <div class="col-12">
                                    <div class="panti-notice">
                                        <i class="fas fa-building"></i>
                                        <div>
                                            <strong>Panti:</strong> {{ $pantiPengurus->nama_panti }}
                                            <span class="ms-2 opacity-75" style="font-size:.77rem;">(otomatis dari akun Anda)</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="panti_asuhan_id" value="{{ $pantiPengurus->id }}">
                                </div>
                            @elseif(auth()->user()->isAdminDinsos())
                                <div class="col-12">
                                    <label class="form-label">Panti Asuhan Terkait</label>
                                    <select name="panti_asuhan_id" class="form-select @error('panti_asuhan_id') is-invalid @enderror">
                                        <option value="">-- Kegiatan Dinsos Sendiri --</option>
                                        @foreach($pantis as $panti)
                                            <option value="{{ $panti->id }}" {{ old('panti_asuhan_id') == $panti->id ? 'selected' : '' }}>
                                                {{ $panti->nama_panti }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Kosongkan jika kegiatan ini milik Dinsos langsung.</div>
                                    @error('panti_asuhan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            @endif

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Mulai <span class="required-mark">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    <input type="date" name="tanggal_mulai"
                                           class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                           value="{{ old('tanggal_mulai') }}" required>
                                    @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Selesai</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                    <input type="date" name="tanggal_selesai"
                                           class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                           value="{{ old('tanggal_selesai') }}">
                                    @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">Lokasi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" name="lokasi"
                                           class="form-control @error('lokasi') is-invalid @enderror"
                                           value="{{ old('lokasi') }}"
                                           placeholder="Nama tempat / alamat" maxlength="100">
                                    @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Jumlah Peserta</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                    <input type="number" name="jumlah_peserta"
                                           class="form-control @error('jumlah_peserta') is-invalid @enderror"
                                           value="{{ old('jumlah_peserta', 0) }}" min="0">
                                    @error('jumlah_peserta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Penanggung Jawab</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                    <input type="text" name="penanggung_jawab"
                                           class="form-control @error('penanggung_jawab') is-invalid @enderror"
                                           value="{{ old('penanggung_jawab') }}" maxlength="100">
                                    @error('penanggung_jawab')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label d-block">Status <span class="required-mark">*</span></label>
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    @foreach(['direncanakan' => 'clock', 'berlangsung' => 'spinner', 'selesai' => 'check-circle', 'dibatalkan' => 'times-circle'] as $val => $icon)
                                        <div>
                                            <input type="radio" name="status" id="status-{{ $val }}"
                                                   value="{{ $val }}" class="status-radio"
                                                   {{ old('status', 'direncanakan') === $val ? 'checked' : '' }}>
                                            <label for="status-{{ $val }}" class="status-label">
                                                <i class="fas fa-{{ $icon }}"></i> {{ ucfirst($val) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>
                </div>
                @endif

            </div>{{-- end col-lg-8 --}}

            {{-- ══════════ KOLOM KANAN ══════════ --}}
            <div class="col-lg-4">

                {{-- Gambar Sampul --}}
                <div class="card section-card mb-3">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-image"></i> Gambar Sampul
                            <span class="required-mark ms-1">*</span>
                        </div>

                        <div class="gambar-drop-zone" id="gambar-drop-zone"
                             onclick="document.getElementById('gambar-input').click()">
                            <img id="gambar-preview" src="" alt="">
                            <div class="gambar-placeholder" id="gambar-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Klik atau seret gambar ke sini</span>
                                <span class="d-block mt-1" style="font-size:.7rem;">JPG / PNG / WEBP — maks. 3 MB</span>
                            </div>
                        </div>

                        <input type="file" name="gambar" id="gambar-input"
                               accept="image/*" class="d-none" required>

                        <button type="button" class="btn btn-sm w-100 mt-2"
                                style="border:1.5px dashed var(--accent);color:var(--accent);border-radius:10px;background:var(--accent-light);"
                                onclick="document.getElementById('gambar-input').click()">
                            <i class="fas fa-upload me-1"></i> Pilih Gambar
                        </button>
                        @error('gambar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                        <div class="tip-box">
                            <i class="fas fa-lightbulb"></i>
                            Rasio 16:9 memberikan tampilan terbaik di halaman daftar.
                        </div>
                    </div>
                </div>

                {{-- Publikasi --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-paper-plane"></i> Publikasi</div>

                        <div class="tip-box mb-3">
                            <i class="fas fa-info-circle"></i>
                            {{ $jenis === 'berita'
                                ? 'Berita akan langsung dipublikasikan setelah disimpan.'
                                : 'Kegiatan akan tampil sesuai status yang dipilih.' }}
                        </div>

                        <div class="d-flex flex-column gap-2">
                            <button type="submit" class="btn btn-accent w-100">
                                <i class="fas fa-save me-2"></i> Simpan {{ ucfirst($jenis) }}
                            </button>
                            <a href="{{ route('konten.index', $jenis) }}" class="btn btn-outline-secondary w-100">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>

            </div>{{-- end col-lg-4 --}}
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @include('pages.konten.konten_summernote')
@endsection
