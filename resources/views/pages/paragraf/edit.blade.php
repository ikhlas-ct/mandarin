@extends('layouts.user.user')

@section('title', 'Edit Paragraf')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
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
    .hanzi-input { font-size: 1.15rem; font-family: 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', 'Plus Jakarta Sans', sans-serif; }

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

    /* ===== SUMMERNOTE – disamakan dengan gaya form ===== */
    .note-editor.note-frame { border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: none; }
    .note-editor.note-frame.note-focused { border-color: #1a73e8; box-shadow: 0 0 0 3px rgba(26,115,232,.12); }
    .note-editor .note-toolbar { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 6px 8px; }
    .note-editor .note-editing-area .note-editable { background: #fff; font-family: 'Plus Jakarta Sans', 'Noto Sans TC', 'PingFang TC', 'Microsoft JhengHei', sans-serif; font-size: .9rem; line-height: 1.75; color: #334155; }
    .note-editor .note-editable img { max-width: 100%; height: auto; border-radius: 10px; }
    .note-editor .note-editable iframe { max-width: 100%; border-radius: 10px; }
    .note-editor .note-statusbar { background: #f8fafc; }
    .note-editor.note-frame.is-invalid { border-color: #dc3545; }

    /* indikator upload */
    .note-upload-status { display: none; align-items: center; gap: 8px; font-size: .78rem; color: #1a73e8; padding: 6px 12px; background: #e8f0fe; border-top: 1px solid #dbeafe; }
    .note-upload-status.aktif { display: flex; }
</style>
@endsection

@section('content')
    <div class="container">

        {{-- Header – di LUAR page-inner --}}
        <div class="ph-card edit-page">
            <div class="ph-left">
                <div class="ph-icon edit"><i class="fas fa-pencil-alt"></i></div>
                <div>
                    <h5 class="ph-title">Edit Paragraf</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.paragraf.index') }}">Paragraf</a></li>
                        <li><a href="{{ route('admin.paragraf.show', $paragraf) }}">{{ \Illuminate\Support\Str::limit($paragraf->judul, 30) }}</a></li>
                        <li><span class="bc-active">Edit</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.paragraf.show', $paragraf) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye me-1"></i> Lihat Detail
                </a>
                <a href="{{ route('admin.paragraf.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="page-inner">
            <form action="{{ route('admin.paragraf.update', $paragraf) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- Kolom kiri: isi paragraf + penjelasan --}}
                    <div class="col-lg-9">
                        {{-- ===== Isi paragraf ===== --}}
                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="section-divider"><i class="fas fa-paragraph me-2"></i>Isi Paragraf</div>
                        
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="judul" class="form-label">Judul <span class="required-mark">*</span></label>
                                        <input type="text" id="judul" name="judul" maxlength="100"
                                            class="form-control @error('judul') is-invalid @enderror"
                                            value="{{ old('judul', $paragraf->judul) }}" placeholder="例：我的一天 / Satu Hari Saya" required autofocus>
                                        @error('judul')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-12">
                                        <label for="hanzi" class="form-label">Teks Hanzi <span class="required-mark">*</span></label>
                                        <textarea id="hanzi" name="hanzi" rows="4"
                                            class="form-control hanzi-input @error('hanzi') is-invalid @enderror"
                                            placeholder="我每天早上七点起床。我喜欢喝水。" required>{{ old('hanzi', $paragraf->hanzi) }}</textarea>
                                        @error('hanzi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Pisahkan kalimat dengan tanda baca 。！？ supaya di halaman detail bisa didengar dan dilihat goresannya per kalimat.</div>
                                    </div>
                        
                                    <div class="col-12">
                                        <label for="pinyin" class="form-label">Pinyin <span class="required-mark">*</span></label>
                                        <textarea id="pinyin" name="pinyin" rows="3"
                                            class="form-control @error('pinyin') is-invalid @enderror"
                                            placeholder="Wǒ měitiān zǎoshang qī diǎn qǐchuáng. Wǒ xǐhuan hē shuǐ." required>{{ old('pinyin', $paragraf->pinyin) }}</textarea>
                                        @error('pinyin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-12">
                                        <label for="arti_indonesia" class="form-label">Arti Indonesia <span class="required-mark">*</span></label>
                                        <textarea id="arti_indonesia" name="arti_indonesia" rows="3"
                                            class="form-control @error('arti_indonesia') is-invalid @enderror"
                                            placeholder="Setiap pagi jam tujuh saya bangun. Saya suka minum air." required>{{ old('arti_indonesia', $paragraf->arti_indonesia) }}</textarea>
                                        @error('arti_indonesia')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- ===== Suara paragraf ===== --}}
                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="section-divider"><i class="fas fa-volume-up me-2"></i>Suara Paragraf <span class="text-muted fw-normal">(opsional)</span></div>
                        
                                @if ($paragraf->audio_url)
                                    <div class="mb-3">
                                        <label class="form-label">Audio saat ini</label>
                                        <audio controls preload="none" class="w-100" src="{{ $paragraf->audio_url }}"></audio>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="hapus_audio" id="hapus_audio" value="1"
                                                {{ old('hapus_audio') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="hapus_audio">Hapus audio ini</label>
                                        </div>
                                    </div>
                                @endif
                        
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="audio_file" class="form-label">Unggah rekaman</label>
                                        <input type="file" id="audio_file" name="audio_file" accept=".mp3,.wav,.m4a,.ogg,audio/*"
                                            class="form-control @error('audio_file') is-invalid @enderror">
                                        @error('audio_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Format mp3, wav, m4a, atau ogg, maksimal 10 MB. Cocok untuk rekaman penutur asli.</div>
                                    </div>
                        
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="buat_audio" id="buat_audio" value="1"
                                                data-siap="{{ $azureSiap ? '1' : '0' }}"
                                                {{ old('buat_audio') ? 'checked' : '' }} {{ $azureSiap ? '' : 'disabled' }}>
                                            <label class="form-check-label" for="buat_audio">Buat suara otomatis (Azure) saat disimpan</label>
                                        </div>
                                        @unless ($azureSiap)
                                            <div class="form-text text-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>AZURE_SPEECH_KEY dan AZURE_SPEECH_REGION belum diisi di file .env.
                                            </div>
                                        @endunless
                        
                                        <div id="opsi-azure" class="row g-2 mt-1 d-none">
                                            <div class="col-md-7">
                                                <label for="suara_azure" class="form-label">Suara</label>
                                                <select id="suara_azure" name="suara_azure" class="form-control">
                                                    @foreach ($suaraAzure as $kode => $label)
                                                        <option value="{{ $kode }}" {{ old('suara_azure', array_key_first($suaraAzure)) === $kode ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="laju_azure" class="form-label">Kecepatan</label>
                                                <select id="laju_azure" name="laju_azure" class="form-control">
                                                    @foreach ($lajuAzure as $kode => $label)
                                                        <option value="{{ $kode }}" {{ old('laju_azure', '0%') === $kode ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                        
                                        <div class="form-text">
                                            Audio dibuat dari teks hanzi satu kali saat menyimpan, lalu disimpan sebagai mp3 (pelajar yang memutar ulang tidak memakai kuota Azure).
                                            Kalau file rekaman juga dipilih, file itulah yang dipakai. Audio baru menggantikan audio lama.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
{{-- ===== Penjelasan tata bahasa (Summernote) ===== --}}
                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="section-divider"><i class="fas fa-book-open me-2"></i>Penjelasan Tata Bahasa</div>
                        
                                <textarea id="penjelasan_tata_bahasa" name="penjelasan_tata_bahasa"
                                    class="@error('penjelasan_tata_bahasa') is-invalid @enderror">{{ old('penjelasan_tata_bahasa', $paragraf->penjelasan_tata_bahasa) }}</textarea>
                                @error('penjelasan_tata_bahasa')
                                    <div class="text-danger mt-1" style="font-size:.8rem;">{{ $message }}</div>
                                @enderror
                        
                                <div class="form-text mt-2">
                                    Tombol <i class="fas fa-image"></i> untuk gambar (upload / seret / tempel, maks. 2 MB) dan
                                    <i class="fas fa-video"></i> untuk menempel link video (YouTube, Vimeo, Dailymotion).
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom kanan: info & tombol --}}
                    <div class="col-lg-3">
                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Informasi</div>
                                <div class="info-item">
                                    <div class="info-label">Dibuat</div>
                                    <div class="info-value">{{ $paragraf->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Terakhir Diubah</div>
                                    <div class="info-value">{{ $paragraf->updated_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                                </div>
                                <div class="form-text mt-3">
                                    Gambar yang dihapus dari editor lalu disimpan akan terhapus permanen dari storage.
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.paragraf.show', $paragraf) }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}
@endsection

@section('scripts')
    <script>
        // Summernote butuh jQuery. Kalau layout belum memuatnya, muat dari CDN.
        window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.7.1.min.js"><\/script>');
    </script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/lang/summernote-id-ID.min.js"></script>

    <script>
        (function ($) {
            const URL_UNGGAH = @json(route('admin.paragraf.upload-gambar'));
            const URL_HAPUS  = @json(route('admin.paragraf.hapus-gambar'));
            const CSRF       = @json(csrf_token());
            const MAKS_BYTE  = 2 * 1024 * 1024;

            // Gambar yang diunggah di halaman ini (belum tentu sudah disimpan).
            // Hanya gambar ini yang langsung dihapus dari server saat dibuang dari editor.
            // Gambar lama (sudah tersimpan) dibersihkan server ketika tombol Simpan ditekan.
            const baruDiunggah = new Set();

            const $editor = $('#penjelasan_tata_bahasa');

            function notif(judul, teks, icon) {
                if (typeof swal === 'function') {
                    swal({ title: judul, text: teks, icon: icon || 'error' });
                } else {
                    alert(judul + '\n' + teks);
                }
            }

            function status(aktif, teks) {
                const el = $editor.next('.note-editor').find('.note-upload-status');
                el.toggleClass('aktif', aktif).find('span').text(teks || '');
            }

            function unggah(file) {
                if (!/^image\/(jpe?g|png|gif|webp)$/i.test(file.type)) {
                    return notif('Format tidak didukung', 'Gunakan gambar jpg, png, gif, atau webp.');
                }
                if (file.size > MAKS_BYTE) {
                    return notif('Gambar terlalu besar', 'Ukuran maksimal 2 MB.');
                }

                const fd = new FormData();
                fd.append('gambar', file);

                status(true, 'Mengunggah ' + file.name + '…');

                fetch(URL_UNGGAH, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: fd,
                })
                    .then(async (res) => {
                        const json = await res.json().catch(() => ({}));
                        if (!res.ok) {
                            const galat = json.errors ? Object.values(json.errors)[0][0] : null;
                            throw new Error(galat || json.message || 'Gagal mengunggah gambar.');
                        }
                        return json;
                    })
                    .then((json) => {
                        baruDiunggah.add(json.url);
                        $editor.summernote('insertImage', json.url, function ($img) {
                            $img.addClass('img-fluid').css('max-width', '100%');
                        });
                    })
                    .catch((e) => notif('Gagal mengunggah', e.message))
                    .finally(() => status(false));
            }

            function hapusDiServer(src) {
                fetch(URL_HAPUS, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ src: src }),
                }).catch(() => { /* gagal hapus file tidak mengganggu penulisan */ });
            }

            $editor.summernote({
                lang: 'id-ID',
                height: 340,
                placeholder: 'Tulis penjelasan tata bahasa… (bisa sisipkan gambar atau link video)',
                dialogsInBody: true,
                disableDragAndDrop: false,
                toolbar: [
                    ['style',   ['style']],
                    ['font',    ['bold', 'italic', 'underline', 'clear']],
                    ['color',   ['color']],
                    ['para',    ['ul', 'ol', 'paragraph']],
                    ['table',   ['table']],
                    ['insert',  ['link', 'picture', 'video', 'hr']],
                    ['history', ['undo', 'redo']],
                    ['view',    ['fullscreen', 'codeview']],
                ],
                popover: {
                    image: [
                        ['resize', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                        ['float',  ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']],
                    ],
                },
                callbacks: {
                    // Tombol gambar, seret-lepas, dan tempel (paste) semuanya lewat sini.
                    onImageUpload: function (files) {
                        Array.from(files).forEach(unggah);
                    },
                    // Gambar dihapus dari editor (tombol hapus di popover atau tombol Delete).
                    onMediaDelete: function ($target) {
                        if (!$target.is('img')) return;
                        const src = $target.attr('src');
                        if (baruDiunggah.has(src)) {
                            baruDiunggah.delete(src);
                            hapusDiServer(src);
                        }
                    },
                },
            });

            // Bar status upload di bawah editor.
            $editor.next('.note-editor').append('<div class="note-upload-status"><i class="fas fa-spinner fa-spin"></i><span></span></div>');

            // Editor kosong (<p><br></p>) dikirim sebagai string kosong.
            $editor.closest('form').on('submit', function () {
                if ($editor.summernote('isEmpty')) {
                    $editor.val('');
                }
            });
        })(window.jQuery);
    </script>

    <script>
        // Opsi suara otomatis: tampil hanya kalau dicentang, dan dimatikan kalau file rekaman dipilih.
        (function () {
            const cek = document.getElementById('buat_audio');
            const opsi = document.getElementById('opsi-azure');
            const file = document.getElementById('audio_file');
            if (!cek || !opsi || !file) return;

            function sinkron() {
                const adaFile = file.files.length > 0;
                if (adaFile) cek.checked = false;
                cek.disabled = adaFile || cek.dataset.siap !== '1';
                opsi.classList.toggle('d-none', !cek.checked);
            }

            cek.addEventListener('change', sinkron);
            file.addEventListener('change', sinkron);
            sinkron();
        })();
    </script>
@endsection
