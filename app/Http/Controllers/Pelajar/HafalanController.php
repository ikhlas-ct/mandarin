<?php

namespace App\Http\Controllers\Pelajar;

use App\Http\Controllers\Controller;
use App\Models\Kosakata;
use App\Models\Pelajar;
use App\Models\ProgresHafalan;
use App\Models\RiwayatReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HafalanController extends Controller
{
    /** Nilai filter "belum pernah disentuh" (kata yang belum punya baris progres). */
    private const BELUM_DISENTUH = 'belum';

    private function pelajar(): Pelajar
    {
        $pelajar = Auth::user()?->pelajar;
        abort_if(! $pelajar, 403, 'Akun ini belum punya profil pelajar.');

        return $pelajar;
    }

    public function index(Request $request)
    {
        $pelajar = $this->pelajar();
        $cari    = trim((string) $request->query('search'));
        $status  = (string) $request->query('status');

        // ---- Ringkasan jumlah per status ----
        $hitung = ProgresHafalan::where('pelajar_id', $pelajar->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalKosakata = Kosakata::count();
        $ingat         = (int) ($hitung[ProgresHafalan::INGAT_SEPENUHNYA] ?? 0);
        $lupa          = (int) ($hitung[ProgresHafalan::LUPA_DAN_INGAT] ?? 0);
        $berikutnya    = (int) ($hitung[ProgresHafalan::BERIKUTNYA] ?? 0);
        $belum         = max(0, $totalKosakata - $ingat - $lupa - $berikutnya);

        $jatuhTempo = ProgresHafalan::where('pelajar_id', $pelajar->id)
            ->untukPopup()
            ->jatuhTempo()
            ->count();

        // ---- Akurasi dari seluruh riwayat review (popup + soal) ----
        $totalReview = RiwayatReview::where('pelajar_id', $pelajar->id)->count();
        $totalBenar  = RiwayatReview::where('pelajar_id', $pelajar->id)->where('benar', true)->count();
        $akurasi     = $totalReview > 0 ? (int) round($totalBenar / $totalReview * 100) : null;

        $riwayatTerbaru = RiwayatReview::with('kosakata')
            ->where('pelajar_id', $pelajar->id)
            ->orderByDesc('direview_pada')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        // ---- Daftar kata (pencarian + filter status) ----
        $kosakatas = Kosakata::with([
                'levelHsk',
                'progresHafalans' => fn ($q) => $q->where('pelajar_id', $pelajar->id),
            ])
            ->cari($cari)
            ->when($status === self::BELUM_DISENTUH, function ($q) use ($pelajar) {
                $q->whereDoesntHave('progresHafalans', fn ($p) => $p->where('pelajar_id', $pelajar->id));
            })
            ->when(array_key_exists($status, ProgresHafalan::LABEL), function ($q) use ($pelajar, $status) {
                $q->whereHas('progresHafalans', fn ($p) => $p->where('pelajar_id', $pelajar->id)->where('status', $status));
            })
            ->urut()
            ->paginate(20)
            ->withQueryString();

        return view('pages.pelajar.hafalan.index', [
            'pelajar'        => $pelajar,
            'cari'           => $cari,
            'status'         => $status,
            'totalKosakata'  => $totalKosakata,
            'ingat'          => $ingat,
            'lupa'           => $lupa,
            'berikutnya'     => $berikutnya,
            'belum'          => $belum,
            'jatuhTempo'     => $jatuhTempo,
            'akurasi'        => $akurasi,
            'totalReview'    => $totalReview,
            'riwayatTerbaru' => $riwayatTerbaru,
            'kosakatas'      => $kosakatas,
        ]);
    }
}
