@extends('layouts.user.user')

@section('title', 'Edit Anak Asuh - ' . $anakAsuh->nama)

@section('styles')
<style>
    /* ===== SECTION DIVIDER ===== */
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

    /* ===== FOTO ===== */
    .foto-preview-wrap {
        width: 120px;
        height: 120px;
        border: 2px dashed #ced4da;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        overflow: hidden;
        transition: border-color .2s;
    }
    .foto-preview-wrap:hover { border-color: #e96c1a; }
    .foto-preview-wrap img { width: 100%; height: 100%; object-fit: cover; }
    .required-mark { color: #dc3545; }
    label { font-size: .875rem; font-weight: 500; }

    /* ===== PAGE HEADER CARD ===== */
    .ph-card {
        background: #fff;
        border: 1px solid #e9ecef;
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
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        border-radius: 14px 0 0 14px;
    }
    .ph-card.edit-page::before { background: #e96c1a; }

    .ph-left { display: flex; align-items: center; gap: 12px; }

    .ph-icon {
        width: 42px; height: 42px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .ph-icon.edit { background: #fff4ed; color: #e96c1a; }

    .ph-title {
        font-size: 1.05rem; font-weight: 700;
        color: #1e293b; letter-spacing: -.2px; line-height: 1.2;
        margin: 0;
    }
    .ph-breadcrumb {
        display: flex; align-items: center; gap: 4px;
        flex-wrap: wrap; margin-top: 4px;
        list-style: none; padding: 0; margin-bottom: 0;
    }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li + li::before {
        content: '›';
        color: #cbd5e1;
        font-size: .7rem;
        margin: 0 4px;
    }
    .ph-breadcrumb a    { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Header Card --}}
    <div class="ph-card edit-page">
        <div class="ph-left">
            <div class="ph-icon edit">
                <i class="fas fa-pen-to-square"></i>
            </div>
            <div>
                <h5 class="ph-title">Edit Anak Asuh</h5>
                <ol class="ph-breadcrumb" aria-label="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('anak-asuh.index') }}">Anak Asuh</a></li>
                    <li><a href="{{ route('anak-asuh.show', ['anakAsuh' => $anakAsuh->id]) }}">{{ $anakAsuh->nama }}</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('anak-asuh.show', ['anakAsuh' => $anakAsuh->id]) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-eye me-1"></i> Detail
            </a>
            <a href="{{ route('anak-asuh.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="page-inner">
        <form action="{{ route('anak-asuh.update', ['anakAsuh' => $anakAsuh->id]) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- Kolom Kiri --}}
                <div class="col-lg-8">

                    {{-- Data Diri --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-id-card me-2"></i>Data Diri</div>
                            <div class="row g-3">

                                @if(auth()->user()->isAdminDinsos())
                                <div class="col-12">
                                    <label class="form-label">Panti Asuhan <span class="required-mark">*</span></label>
                                    <select name="panti_asuhan_id" class="form-select select2 @error('panti_asuhan_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Panti --</option>
                                        @foreach($pantis as $panti)
                                            <option value="{{ $panti->id }}"
                                                {{ old('panti_asuhan_id', $anakAsuh->panti_asuhan_id) == $panti->id ? 'selected' : '' }}>
                                                {{ $panti->nama_panti }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('panti_asuhan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @else
                                <input type="hidden" name="panti_asuhan_id" value="{{ $anakAsuh->panti_asuhan_id }}">
                                @endif

                                <div class="col-md-8">
                                    <label class="form-label">Nama Lengkap <span class="required-mark">*</span></label>
                                    <input type="text" name="nama"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama', $anakAsuh->nama) }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Jenis Kelamin <span class="required-mark">*</span></label>
                                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                        <option value="L" {{ old('jenis_kelamin', $anakAsuh->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin', $anakAsuh->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">NIK</label>
                                    <input type="text" name="nik"
                                        class="form-control @error('nik') is-invalid @enderror"
                                        value="{{ old('nik', $anakAsuh->nik) }}" maxlength="16">
                                    @error('nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">No. KK</label>
                                    <input type="text" name="no_kk"
                                        class="form-control @error('no_kk') is-invalid @enderror"
                                        value="{{ old('no_kk', $anakAsuh->no_kk) }}" maxlength="16">
                                    @error('no_kk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir"
                                        class="form-control @error('tempat_lahir') is-invalid @enderror"
                                        value="{{ old('tempat_lahir', $anakAsuh->tempat_lahir) }}">
                                    @error('tempat_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir"
                                        class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                        value="{{ old('tanggal_lahir', $anakAsuh->tanggal_lahir?->format('Y-m-d')) }}">
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Agama</label>
                                    <select name="agama" class="form-select @error('agama') is-invalid @enderror">
                                        <option value="">-- Pilih --</option>
                                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                                            <option value="{{ $agama }}" {{ old('agama', $anakAsuh->agama) === $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                        @endforeach
                                    </select>
                                    @error('agama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">Alamat Asal</label>
                                    <textarea name="alamat_asal" rows="2"
                                        class="form-control @error('alamat_asal') is-invalid @enderror">{{ old('alamat_asal', $anakAsuh->alamat_asal) }}</textarea>
                                    @error('alamat_asal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Asal Daerah</label>
                                    <input type="text" name="asal_daerah"
                                        class="form-control @error('asal_daerah') is-invalid @enderror"
                                        value="{{ old('asal_daerah', $anakAsuh->asal_daerah) }}">
                                    @error('asal_daerah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Data Orang Tua --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-users me-2"></i>Data Orang Tua / Wali</div>
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Nama Ayah</label>
                                    <input type="text" name="nama_ayah"
                                        class="form-control @error('nama_ayah') is-invalid @enderror"
                                        value="{{ old('nama_ayah', $anakAsuh->nama_ayah) }}">
                                    @error('nama_ayah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nama Ibu</label>
                                    <input type="text" name="nama_ibu"
                                        class="form-control @error('nama_ibu') is-invalid @enderror"
                                        value="{{ old('nama_ibu', $anakAsuh->nama_ibu) }}">
                                    @error('nama_ibu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Pekerjaan Orang Tua / Wali</label>
                                    <input type="text" name="pekerjaan_ortu"
                                        class="form-control @error('pekerjaan_ortu') is-invalid @enderror"
                                        value="{{ old('pekerjaan_ortu', $anakAsuh->pekerjaan_ortu) }}">
                                    @error('pekerjaan_ortu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">No. Telepon Wali</label>
                                    <input type="text" name="no_telp_wali"
                                        class="form-control @error('no_telp_wali') is-invalid @enderror"
                                        value="{{ old('no_telp_wali', $anakAsuh->no_telp_wali) }}">
                                    @error('no_telp_wali')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Status Yatim <span class="required-mark">*</span></label>
                                    <div class="d-flex flex-wrap gap-3">
                                        @foreach(['yatim' => 'Yatim', 'piatu' => 'Piatu', 'yatim_piatu' => 'Yatim Piatu', 'dhuafa' => 'Dhuafa'] as $val => $label)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_yatim"
                                                id="sy_{{ $val }}" value="{{ $val }}"
                                                {{ old('status_yatim', $anakAsuh->status_yatim) === $val ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="sy_{{ $val }}">{{ $label }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('status_yatim')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Pendidikan --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-graduation-cap me-2"></i>Pendidikan</div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Jenjang Pendidikan</label>
                                    <select name="jenjang_pendidikan" class="form-select @error('jenjang_pendidikan') is-invalid @enderror">
                                        <option value="">-- Pilih Jenjang --</option>
                                        @foreach($jenjangList as $j)
                                            <option value="{{ $j }}" {{ old('jenjang_pendidikan', $anakAsuh->jenjang_pendidikan) === $j ? 'selected' : '' }}>{{ $j }}</option>
                                        @endforeach
                                    </select>
                                    @error('jenjang_pendidikan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nama Sekolah</label>
                                    <input type="text" name="nama_sekolah"
                                        class="form-control @error('nama_sekolah') is-invalid @enderror"
                                        value="{{ old('nama_sekolah', $anakAsuh->nama_sekolah) }}">
                                    @error('nama_sekolah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Kelas</label>
                                    <input type="text" name="kelas"
                                        class="form-control @error('kelas') is-invalid @enderror"
                                        value="{{ old('kelas', $anakAsuh->kelas) }}">
                                    @error('kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan --}}
                <div class="col-lg-4">

                    {{-- Foto --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body text-center">
                            <div class="section-divider text-start"><i class="fas fa-camera me-2"></i>Foto Anak</div>
                            <div class="foto-preview-wrap mx-auto mb-3" id="foto-preview-wrap">
                                <img id="foto-preview"
                                    src="{{ $anakAsuh->foto ? asset('storage/' . $anakAsuh->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($anakAsuh->nama) . '&background=e9ecef&color=6c757d&size=120' }}"
                                    alt="{{ $anakAsuh->nama }}">
                            </div>
                            <input type="file" name="foto" id="foto-input" accept="image/*" class="d-none">
                            <button type="button" class="btn btn-outline-warning btn-sm w-100" onclick="document.getElementById('foto-input').click()">
                                <i class="fas fa-sync me-1"></i> Ganti Foto
                            </button>
                            <div class="text-muted small mt-2">Kosongkan jika tidak ingin mengubah foto</div>
                            @error('foto')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Status Penerimaan</div>

                            <div class="mb-3">
                                <label class="form-label">Status <span class="required-mark">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required id="status-select">
                                    <option value="aktif"    {{ old('status', $anakAsuh->status) === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $anakAsuh->status) === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                                    <option value="keluar"   {{ old('status', $anakAsuh->status) === 'keluar'   ? 'selected' : '' }}>Keluar</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Tinggal <span class="required-mark">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_tinggal"
                                            id="jt_dalam" value="dalam"
                                            {{ old('jenis_tinggal', $anakAsuh->jenis_tinggal) === 'dalam' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="jt_dalam">
                                            <i class="fas fa-home me-1 text-success"></i> Dalam
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_tinggal"
                                            id="jt_luar" value="luar"
                                            {{ old('jenis_tinggal', $anakAsuh->jenis_tinggal) === 'luar' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jt_luar">
                                            <i class="fas fa-map-marker-alt me-1 text-secondary"></i> Luar
                                        </label>
                                    </div>
                                </div>
                                @error('jenis_tinggal')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Masuk</label>
                                <input type="date" name="tanggal_masuk"
                                    class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                    value="{{ old('tanggal_masuk', $anakAsuh->tanggal_masuk?->format('Y-m-d')) }}">
                                @error('tanggal_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="wrap-keluar">
                                <label class="form-label">Tanggal Keluar</label>
                                <input type="date" name="tanggal_keluar"
                                    class="form-control @error('tanggal_keluar') is-invalid @enderror"
                                    value="{{ old('tanggal_keluar', $anakAsuh->tanggal_keluar?->format('Y-m-d')) }}">
                                @error('tanggal_keluar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="wrap-alasan-keluar">
                                <label class="form-label">Alasan Keluar</label>
                                <textarea name="alasan_keluar" rows="2"
                                    class="form-control @error('alasan_keluar') is-invalid @enderror">{{ old('alasan_keluar', $anakAsuh->alasan_keluar) }}</textarea>
                                @error('alasan_keluar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <label class="form-label">Keterangan Tambahan</label>
                            <textarea name="keterangan" rows="3"
                                class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $anakAsuh->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('anak-asuh.show', ['anakAsuh' => $anakAsuh->id]) }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>

                </div>
            </div>
        </form>

    </div>{{-- end .page-inner --}}
</div>{{-- end .container --}}
@endsection

@section('scripts')
<script>
    // Preview foto
    document.getElementById('foto-input').addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('foto-preview').src = e.target.result;
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Toggle field keluar
    const statusSelect = document.getElementById('status-select');
    const wrapKeluar   = document.getElementById('wrap-keluar');
    const wrapAlasan   = document.getElementById('wrap-alasan-keluar');

    function toggleKeluar() {
        const show = statusSelect.value === 'keluar';
        wrapKeluar.style.display = show ? '' : 'none';
        wrapAlasan.style.display = show ? '' : 'none';
    }

    statusSelect.addEventListener('change', toggleKeluar);
    toggleKeluar();

    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select2').select2({ theme: 'bootstrap-5' });
    }
</script>
@endsection
