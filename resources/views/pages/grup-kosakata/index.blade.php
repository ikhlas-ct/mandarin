@extends('layouts.user.user')

@section('title', 'Grup Kosakata & Generator Soal')

@section('content')
<div class="container">
    <h5 class="mb-3">Grup Kosakata &amp; Generator Soal</h5>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error') || $errors->any())
        <div class="alert alert-danger">{{ session('error') ?? $errors->first() }}</div>
    @endif

    <div class="row g-3">
        {{-- Buat grup baru --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header fw-semibold">1. Buat grup kata</div>
                <div class="card-body">
                    <form method="GET" class="input-group mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Cari hanzi / pinyin / arti..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary">Cari</button>
                    </form>

                    <form method="POST" action="{{ route('admin.grup-kosakata.store') }}">
                        @csrf
                        <div class="row g-2 mb-3">
                            <div class="col-md-5">
                                <input type="text" name="nama" class="form-control" placeholder="Nama grup, mis. Salam & Perkenalan" required>
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="keterangan" class="form-control" placeholder="Keterangan (opsional)">
                            </div>
                        </div>

                        <div style="max-height:380px; overflow:auto;" class="border rounded">
                            <table class="table table-sm table-hover mb-0">
                                <tbody>
                                @foreach ($kosakatas as $k)
                                    <tr>
                                        <td width="30"><input type="checkbox" class="form-check-input" name="kosakata_ids[]" value="{{ $k->id }}"></td>
                                        <td style="font-size:1.1rem">{{ $k->hanzi }}</td>
                                        <td>{{ $k->pinyin }}</td>
                                        <td>{{ $k->arti_indonesia }}</td>
                                        <td class="text-muted">{{ $k->levelHsk->nama ?? '-' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted">Menampilkan maksimal 200 kata; gunakan pencarian untuk mempersempit.</small>

                        <div class="mt-3">
                            <button class="btn btn-primary">Simpan grup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Daftar grup + generate --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header fw-semibold">2. Buat soal dari grup</div>
                <div class="card-body">
                    @forelse ($grups as $grup)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="fw-semibold">{{ $grup->nama }}</div>
                                    <small class="text-muted">{{ $grup->kosakatas_count }} kata</small>
                                </div>
                                <form method="POST" action="{{ route('admin.grup-kosakata.destroy', $grup) }}" onsubmit="return confirm('Hapus grup ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>

                            <form method="POST" action="{{ route('admin.grup-kosakata.generate', $grup) }}" class="mt-2">
                                @csrf
                                <div class="mb-2" style="font-size:.85rem">
                                    <label class="me-2"><input type="checkbox" name="tipe[]" value="hanzi_arti" checked> Hanzi → arti</label>
                                    <label class="me-2"><input type="checkbox" name="tipe[]" value="arti_hanzi" checked> Arti → hanzi</label>
                                    <label class="me-2"><input type="checkbox" name="tipe[]" value="isian"> Isian kalimat</label>
                                    <label><input type="checkbox" name="tipe[]" value="listening"> Listening</label>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Jumlah soal</span>
                                    <input type="number" name="jumlah" class="form-control" value="{{ max(5, $grup->kosakatas_count) }}" min="1" max="100">
                                    <button class="btn btn-primary">Buat soal</button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <div class="text-muted">Belum ada grup. Buat dulu di sebelah kiri.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
