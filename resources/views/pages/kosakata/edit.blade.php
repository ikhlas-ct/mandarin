@extends('layouts.user.user')

@section('title', 'Edit Kosakata')

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

    /* ===== FORM KOSAKATA & CONTOH KALIMAT ===== */
    .hanzi-input { font-size: 1.15rem; }
    .contoh-item { background: #fafbfc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; margin-bottom: 12px; }
    .contoh-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
    .contoh-no { font-size: .8rem; font-weight: 700; color: #1269db; }
    .contoh-item .form-label { font-size: .78rem; color: #64748b; margin-bottom: 3px; }
    .contoh-kosong { border: 1.5px dashed #cbd5e1; border-radius: 12px; padding: 22px; text-align: center; color: #94a3b8; font-size: .83rem; }
    .btn-action { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; font-size: .75rem; padding: 0; border: none; transition: all .15s ease; }
    .btn-hapus { background: #fee2e2; color: #dc2626; } .btn-hapus:hover { background: #dc2626; color: #fff; }
    .form-select { border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: .83rem; padding: 7px 12px; color: #334155; background-color: #f8fafc; }
    .form-select:focus { border-color: #1a73e8; background-color: #fff; box-shadow: 0 0 0 3px rgba(26,115,232,.12); }
</style>
@endsection

@section('content')
    <div class="container">

        {{-- Header – di LUAR page-inner --}}
        <div class="ph-card edit-page">
            <div class="ph-left">
                <div class="ph-icon edit"><i class="fas fa-pencil-alt"></i></div>
                <div>
                    <h5 class="ph-title">Edit Kosakata</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.kosakata.index') }}">Kosakata</a></li>
                        <li><a href="{{ route('admin.kosakata.show', $kosakata) }}">{{ $kosakata->hanzi }}</a></li>
                        <li><span class="bc-active">Edit</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.kosakata.show', $kosakata) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye me-1"></i> Lihat Detail
                </a>
                <a href="{{ route('admin.kosakata.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="page-inner">
            <form action="{{ route('admin.kosakata.update', $kosakata) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- Kolom kiri: data kosakata + contoh kalimat --}}
                    <div class="col-lg-8">
                        {{-- ===== Data kosakata ===== --}}
                        @php
                            // Prioritas: input lama (setelah gagal validasi) -> data database -> satu baris kosong.
                            $contohRows = old('contoh_kalimat', $kosakata->contohKalimats
                                ->map(fn ($c) => $c->only(['id', 'hanzi', 'pinyin', 'arti_indonesia', 'catatan_tata_bahasa']))
                                ->all());
                            $contohRows = array_values($contohRows ?: [[]]);
                        @endphp

                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="section-divider"><i class="fas fa-language me-2"></i>Data Kosakata</div>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="hanzi" class="form-label">Hanzi <span class="required-mark">*</span></label>
                                        <input type="text" id="hanzi" name="hanzi" maxlength="20"
                                            class="form-control hanzi-input @error('hanzi') is-invalid @enderror"
                                            value="{{ old('hanzi', $kosakata->hanzi) }}" placeholder="例：水" required autofocus>
                                        @error('hanzi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="pinyin" class="form-label">Pinyin <span class="required-mark">*</span></label>
                                        <input type="text" id="pinyin" name="pinyin" maxlength="60"
                                            class="form-control @error('pinyin') is-invalid @enderror"
                                            value="{{ old('pinyin', $kosakata->pinyin) }}" placeholder="shuǐ" required>
                                        @error('pinyin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="baca_indonesia" class="form-label">Cara Baca Indonesia</label>
                                        <input type="text" id="baca_indonesia" name="baca_indonesia" maxlength="60"
                                            class="form-control @error('baca_indonesia') is-invalid @enderror"
                                            value="{{ old('baca_indonesia', $kosakata->baca_indonesia) }}" placeholder="shuei">
                                        @error('baca_indonesia')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="arti_indonesia" class="form-label">Arti Indonesia <span class="required-mark">*</span></label>
                                        <input type="text" id="arti_indonesia" name="arti_indonesia" maxlength="150"
                                            class="form-control @error('arti_indonesia') is-invalid @enderror"
                                            value="{{ old('arti_indonesia', $kosakata->arti_indonesia) }}" placeholder="air" required>
                                        @error('arti_indonesia')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="english" class="form-label">Arti Inggris</label>
                                        <input type="text" id="english" name="english" maxlength="150"
                                            class="form-control @error('english') is-invalid @enderror"
                                            value="{{ old('english', $kosakata->english) }}" placeholder="water">
                                        @error('english')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="kategori_id" class="form-label">Kategori</label>
                                        <select id="kategori_id" name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                                            <option value="">- Tanpa kategori -</option>
                                            @foreach ($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}"
                                                    {{ (string) old('kategori_id', $kosakata->kategori_id) === (string) $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kategori_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="level_hsk_id" class="form-label">Level HSK</label>
                                        <select id="level_hsk_id" name="level_hsk_id" class="form-select @error('level_hsk_id') is-invalid @enderror">
                                            <option value="">- Tanpa level -</option>
                                            @foreach ($levels as $level)
                                                <option value="{{ $level->id }}"
                                                    {{ (string) old('level_hsk_id', $kosakata->level_hsk_id) === (string) $level->id ? 'selected' : '' }}>
                                                    {{ $level->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('level_hsk_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="urutan" class="form-label">Urutan</label>
                                        <input type="number" id="urutan" name="urutan" min="0"
                                            class="form-control @error('urutan') is-invalid @enderror"
                                            value="{{ old('urutan', $kosakata->urutan) }}" placeholder="Otomatis">
                                        @error('urutan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===== Contoh kalimat ===== --}}
                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                    <div class="section-divider mb-0"><i class="fas fa-comment-dots me-2"></i>Contoh Kalimat</div>
                                    <button type="button" id="btn-tambah-contoh" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-plus me-1"></i> Tambah Contoh
                                    </button>
                                </div>

                                @error('contoh_kalimat')
                                    <div class="alert alert-danger py-2" style="font-size:.83rem;">{{ $message }}</div>
                                @enderror

                                {{-- Baris diisi oleh JavaScript dari cetakan di bawah --}}
                                <div id="contoh-wrapper"></div>

                                <div id="contoh-kosong" class="contoh-kosong d-none">
                                    Belum ada contoh kalimat. Klik <strong>Tambah Contoh</strong> untuk menambahkan.
                                </div>

                                <div class="form-text mt-2">Baris yang dibiarkan kosong tidak akan disimpan.</div>
                            </div>
                        </div>

                        {{-- Cetakan satu baris contoh kalimat --}}
                        <template id="tpl-contoh">
                            <div class="contoh-item" data-contoh-item>
                                <div class="contoh-head">
                                    <span class="contoh-no"></span>
                                    <button type="button" class="btn btn-action btn-hapus" data-hapus-contoh title="Hapus contoh ini">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Kalimat (Hanzi) <span class="required-mark">*</span></label>
                                        <input type="text" name="contoh_kalimat[__INDEX__][hanzi]" maxlength="255"
                                            class="form-control" placeholder="例：我想喝水。">
                                        <div class="invalid-feedback" data-error="hanzi"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Pinyin <span class="required-mark">*</span></label>
                                        <input type="text" name="contoh_kalimat[__INDEX__][pinyin]" maxlength="255"
                                            class="form-control" placeholder="wǒ xiǎng hē shuǐ.">
                                        <div class="invalid-feedback" data-error="pinyin"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Arti Indonesia <span class="required-mark">*</span></label>
                                        <input type="text" name="contoh_kalimat[__INDEX__][arti_indonesia]" maxlength="255"
                                            class="form-control" placeholder="Saya ingin minum air.">
                                        <div class="invalid-feedback" data-error="arti_indonesia"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Catatan Tata Bahasa</label>
                                        <input type="text" name="contoh_kalimat[__INDEX__][catatan_tata_bahasa]" maxlength="255"
                                            class="form-control" placeholder="想 + kata kerja = ingin (opsional)">
                                        <div class="invalid-feedback" data-error="catatan_tata_bahasa"></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Kolom kanan: info & tombol --}}
                    <div class="col-lg-4">
                        <div class="card form-card mb-4">
                            <div class="card-body">
                                <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Informasi</div>
                                <div class="info-item">
                                    <div class="info-label">Contoh Kalimat Tersimpan</div>
                                    <div class="info-value">{{ $kosakata->contohKalimats->count() }} kalimat</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Dibuat</div>
                                    <div class="info-value">{{ $kosakata->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Terakhir Diubah</div>
                                    <div class="info-value">{{ $kosakata->updated_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                                </div>
                                <div class="form-text mt-3">
                                    Menghapus sebuah baris contoh kalimat lalu menyimpan akan menghapusnya permanen.
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.kosakata.show', $kosakata) }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}
@endsection

@section('scripts')
    <script>
        (function () {
            const FIELD   = ['hanzi', 'pinyin', 'arti_indonesia', 'catatan_tata_bahasa'];
            const data    = @json($contohRows);
            const errors  = @json($errors->getMessages());
            const wrapper = document.getElementById('contoh-wrapper');
            const tpl     = document.getElementById('tpl-contoh');
            const kosong  = document.getElementById('contoh-kosong');
            let index     = 0;

            function nomori() {
                const items = wrapper.querySelectorAll('[data-contoh-item]');
                items.forEach((el, n) => {
                    el.querySelector('.contoh-no').textContent = 'Contoh ' + (n + 1);
                });
                kosong.classList.toggle('d-none', items.length > 0);
            }

            // Tambah satu baris; row diisi kalau berasal dari database / input lama.
            function tambah(row) {
                row = row || {};
                const i = index++;
                const holder = document.createElement('div');
                holder.innerHTML = tpl.innerHTML.replaceAll('__INDEX__', i).trim();
                const item = holder.firstElementChild;

                if (row.id) {
                    const hidden = document.createElement('input');
                    hidden.type  = 'hidden';
                    hidden.name  = 'contoh_kalimat[' + i + '][id]';
                    hidden.value = row.id;
                    item.prepend(hidden);
                }

                FIELD.forEach(function (f) {
                    const input = item.querySelector('[name="contoh_kalimat[' + i + '][' + f + ']"]');
                    input.value = row[f] ?? '';

                    const pesan = errors['contoh_kalimat.' + i + '.' + f];
                    if (pesan) {
                        input.classList.add('is-invalid');
                        item.querySelector('[data-error="' + f + '"]').textContent = pesan[0];
                    }
                });

                wrapper.appendChild(item);
                nomori();
                return item;
            }

            data.forEach(tambah);

            document.getElementById('btn-tambah-contoh').addEventListener('click', function () {
                tambah().querySelector('input[type="text"]').focus();
            });

            wrapper.addEventListener('click', function (e) {
                const tombol = e.target.closest('[data-hapus-contoh]');
                if (!tombol) return;
                tombol.closest('[data-contoh-item]').remove();
                nomori();
            });

            nomori();
        })();
    </script>
@endsection
