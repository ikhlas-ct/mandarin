<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrupKosakata;
use App\Models\Kosakata;
use App\Services\GeneratorSoal;
use Illuminate\Http\Request;
use InvalidArgumentException;

class GrupKosakataController extends Controller
{
    public function index(Request $request)
    {
        $grups = GrupKosakata::withCount('kosakatas')->latest()->get();

        $kosakatas = Kosakata::with('levelHsk')
            ->cari($request->input('search'))
            ->urut()
            ->limit(200)
            ->get();

        return view('pages.grup-kosakata.index', compact('grups', 'kosakatas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'         => ['required', 'string', 'max:150'],
            'keterangan'   => ['nullable', 'string'],
            'kosakata_ids' => ['required', 'array', 'min:2'],
            'kosakata_ids.*' => ['integer', 'exists:kosakatas,id'],
        ], [
            'kosakata_ids.min' => 'Pilih minimal 2 kata untuk satu grup.',
        ]);

        $grup = GrupKosakata::create([
            'nama'       => $data['nama'],
            'keterangan' => $data['keterangan'] ?? null,
        ]);
        $grup->kosakatas()->sync($data['kosakata_ids']);

        return redirect()->route('admin.grup-kosakata.index')
            ->with('success', "Grup \"{$grup->nama}\" dibuat dengan " . count($data['kosakata_ids']) . ' kata.');
    }

    public function destroy(GrupKosakata $grup)
    {
        $grup->delete();

        return back()->with('success', 'Grup dihapus.');
    }

    public function generate(Request $request, GrupKosakata $grup, GeneratorSoal $generator)
    {
        $data = $request->validate([
            'tipe'   => ['required', 'array', 'min:1'],
            'tipe.*' => ['in:' . implode(',', GeneratorSoal::TIPE)],
            'jumlah' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $kembali = redirect()->route('admin.grup-kosakata.index');

        try {
            $grupSoal = $generator->buat($grup, $data['tipe'], (int) $data['jumlah']);
        } catch (InvalidArgumentException $e) {
            return $kembali->with('error', $e->getMessage());
        }

        return $kembali->with('success',
            "Dibuat latihan \"{$grupSoal->judul}\" berisi {$grupSoal->soals_count} soal (status: belum aktif, silakan dicek dulu)."
        );
    }
}
