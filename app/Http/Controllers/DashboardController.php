<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Kosakata;
use App\Models\LevelHsk;
use App\Models\Paragraf;
use App\Models\Pelajar;
use App\Models\ProgresHafalan;
use App\Models\RiwayatReview;
use App\Models\Visitor;
use App\Models\WebsiteSetting;
use App\Models\ContohKalimat;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $setting = WebsiteSetting::getSetting();

        // ── Statistik utama ──
        $reviewHariIni = RiwayatReview::whereDate('direview_pada', today())->count();
        $benarHariIni  = RiwayatReview::whereDate('direview_pada', today())->where('benar', true)->count();

        $stats = [
            'total_pelajar'   => Pelajar::count(),
            'pelajar_aktif'   => Pelajar::where('status', 'aktif')->count(),
            'total_kosakata'  => Kosakata::count(),
            'total_kategori'  => Kategori::count(),
            'total_level'     => LevelHsk::count(),
            'total_paragraf'  => Paragraf::count(),
            'total_kalimat'   => ContohKalimat::count(),
            'total_visitor'   => Visitor::count(),
            'visitor_hari_ini'=> Visitor::whereDate('created_at', today())->count(),
            'review_hari_ini' => $reviewHariIni,
            'akurasi_hari_ini'=> $reviewHariIni > 0 ? round($benarHariIni / $reviewHariIni * 100) : 0,
        ];

        // ── Kelengkapan profil website (untuk alert di dashboard) ──
        $kelengkapan = ProfilController::hitungKelengkapan($setting);

        // ── Grafik pengunjung 12 bulan terakhir ──
        $visitor_per_bulan = Visitor::select(
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total'),
                DB::raw('COUNT(DISTINCT ip_address) as unik')
            )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('tahun', 'bulan')
            ->get();

        // ── Distribusi kosakata per level HSK ──
        $distribusi_level = LevelHsk::withCount('kosakatas')
            ->orderBy('tingkat')
            ->get()
            ->map(fn ($l) => ['label' => $l->nama, 'total' => $l->kosakatas_count]);

        $tanpaLevel = Kosakata::whereNull('level_hsk_id')->count();
        if ($tanpaLevel > 0) {
            $distribusi_level->push(['label' => 'Tanpa Level', 'total' => $tanpaLevel]);
        }

        // ── Distribusi status hafalan seluruh pelajar ──
        $distribusi_status = ProgresHafalan::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // ── Tabel & list ──
        $pelajar_terbaru = Pelajar::with('user')->latest()->take(6)->get();

        $review_terbaru = RiwayatReview::with(['pelajar', 'kosakata'])
            ->orderByDesc('direview_pada')
            ->take(6)
            ->get();

        $kategori_top = Kategori::withCount('kosakatas')
            ->orderByDesc('kosakatas_count')
            ->take(5)
            ->get();

        $pelajar_teraktif = Pelajar::withCount('riwayatReviews')
            ->orderByDesc('riwayat_reviews_count')
            ->take(5)
            ->get();

        $visitor_terbaru = Visitor::latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'setting',
            'stats',
            'kelengkapan',
            'visitor_per_bulan',
            'distribusi_level',
            'distribusi_status',
            'pelajar_terbaru',
            'review_terbaru',
            'kategori_top',
            'pelajar_teraktif',
            'visitor_terbaru'
        ));
    }
}
