<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Kosakata;
use App\Models\LevelHsk;
use App\Models\Pelajar;
use App\Models\ProgresHafalan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Kosakata untuk pelajar: hanya melihat dan memindahkan status hafalan.
 * Tambah / edit / hapus / import tetap hanya di sisi admin.
 */
class PelajarKosakataController extends Controller
{
    /** Label, ikon, dan keterangan tiap status (dipakai di kedua view). */
    public const STATUS = [
        ProgresHafalan::BERIKUTNYA => [
            'label'     => 'Berikutnya',
            'ikon'      => 'fa-forward',
            'deskripsi' => 'Belum dipelajari atau nanti saja. Tidak masuk popup review.',
        ],
        ProgresHafalan::LUPA_DAN_INGAT => [
            'label'     => 'Lupa & Ingat',
            'ikon'      => 'fa-lightbulb',
            'deskripsi' => 'Kadang masih lupa. Muncul di popup review, diulang setiap hari.',
        ],
        ProgresHafalan::INGAT_SEPENUHNYA => [
            'label'     => 'Ingat Sepenuhnya',
            'ikon'      => 'fa-check-double',
            'deskripsi' => 'Sudah hafal. Muncul di popup review, diulang tiap 7 hari kalau jawabannya benar.',
        ],
    ];

    public function index(Request $request): View
    {
        $pelajar = $this->pelajarLogin();
        $pid     = $pelajar->id;

        $status  = $request->query('status');
        $perPage = in_array((int) $request->query('per_page'), [25, 50, 100], true)
            ? (int) $request->query('per_page')
            : 25;

        $query = Kosakata::query()
            ->with([
                'kategori',
                'levelHsk',
                // Hanya progres milik pelajar yang login.
                'progresHafalans' => fn ($q) => $q->where('pelajar_id', $pid),
            ])
            ->withCount('contohKalimats')
            ->cari($request->query('search'));

        // Filter kategori & level (sama seperti halaman admin)
        foreach (['kategori_id', 'level_hsk_id'] as $kolom) {
            $nilai = $request->query($kolom);
            if ($nilai === 'kosong') {
                $query->whereNull($kolom);
            } elseif (filled($nilai)) {
                $query->where($kolom, $nilai);
            }
        }

        // Filter status. Kata yang belum punya baris progres dianggap "berikutnya".
        $statusDenganProgres = [ProgresHafalan::LUPA_DAN_INGAT, ProgresHafalan::INGAT_SEPENUHNYA];

        if ($status === ProgresHafalan::BERIKUTNYA) {
            $query->whereDoesntHave('progresHafalans', fn ($q) => $q
                ->where('pelajar_id', $pid)
                ->whereIn('status', $statusDenganProgres));
        } elseif (in_array($status, $statusDenganProgres, true)) {
            $query->whereHas('progresHafalans', fn ($q) => $q
                ->where('pelajar_id', $pid)
                ->where('status', $status));
        }

        $kosakatas = $query->urut()->paginate($perPage)->withQueryString();

        // Statistik (selalu dari seluruh kosakata, tidak ikut filter)
        $total  = Kosakata::count();
        $hitung = ProgresHafalan::where('pelajar_id', $pid)
            ->whereIn('status', $statusDenganProgres)
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $lupa  = (int) ($hitung[ProgresHafalan::LUPA_DAN_INGAT] ?? 0);
        $ingat = (int) ($hitung[ProgresHafalan::INGAT_SEPENUHNYA] ?? 0);

        $stats = [
            'total'                          => $total,
            ProgresHafalan::BERIKUTNYA       => max($total - $lupa - $ingat, 0),
            ProgresHafalan::LUPA_DAN_INGAT   => $lupa,
            ProgresHafalan::INGAT_SEPENUHNYA => $ingat,
        ];

        return view('pages.hafalankosakata.index', [
            'kosakatas'  => $kosakatas,
            'kategoris'  => Kategori::orderBy('nama')->get(),
            'levels'     => LevelHsk::orderBy('tingkat')->get(),
            'stats'      => $stats,
            'statusList' => self::STATUS,
            'statusAktif' => $status,
            'perPage'    => $perPage,
        ]);
    }

    public function show(Kosakata $kosakata): View
    {
        $pelajar = $this->pelajarLogin();

        $kosakata->load(['kategori', 'levelHsk', 'contohKalimats']);

        $progres = ProgresHafalan::where('pelajar_id', $pelajar->id)
            ->where('kosakata_id', $kosakata->id)
            ->first();

        return view('pages.hafalankosakata.show', [
            'kosakata'      => $kosakata,
            'progres'       => $progres,
            'statusSekarang' => $progres?->status ?? ProgresHafalan::BERIKUTNYA,
            'statusList'    => self::STATUS,
        ]);
    }

    /**
     * Pindahkan status satu atau banyak kosakata sekaligus.
     * Dipakai oleh halaman daftar (pilih banyak) dan halaman detail (satu kata).
     */
    public function updateStatus(Request $request): RedirectResponse
    {
        $pelajar = $this->pelajarLogin();

        $data = $request->validate([
            'ids'    => ['required', 'array', 'min:1'],
            'ids.*'  => ['integer', 'exists:kosakatas,id'],
            'status' => ['required', Rule::in(array_keys(self::STATUS))],
        ], [
            'ids.required'  => 'Pilih minimal satu kosakata dulu.',
            'ids.min'       => 'Pilih minimal satu kosakata dulu.',
            'status.in'     => 'Status tidak valid.',
        ]);

        $ids = array_values(array_unique($data['ids']));

        $dipindah = DB::transaction(function () use ($pelajar, $ids, $data) {
            $ada = ProgresHafalan::where('pelajar_id', $pelajar->id)
                ->whereIn('kosakata_id', $ids)
                ->get()
                ->keyBy('kosakata_id');

            $jumlah = 0;

            foreach ($ids as $id) {
                $progres = $ada->get($id);

                // Status sama: biarkan, supaya jadwal review yang sudah berjalan tidak ter-reset.
                if ($progres && $progres->status === $data['status']) {
                    continue;
                }

                // review_berikutnya = null -> langsung jatuh tempo, jadi kata
                // lupa_dan_ingat / ingat_sepenuhnya yang baru dipindah ikut popup review.
                // Untuk "berikutnya" memang tidak dijadwalkan.
                ProgresHafalan::updateOrCreate(
                    ['pelajar_id' => $pelajar->id, 'kosakata_id' => $id],
                    ['status' => $data['status'], 'review_berikutnya' => null],
                );

                $jumlah++;
            }

            return $jumlah;
        });

        $label = self::STATUS[$data['status']]['label'];

        return back()->with(
            'success',
            $dipindah > 0
                ? "{$dipindah} kata dipindahkan ke \"{$label}\"."
                : "Kata yang dipilih sudah berstatus \"{$label}\"."
        );
    }

    /** Data pelajar dari akun yang sedang login. */
    private function pelajarLogin(): Pelajar
    {
        $pelajar = auth()->user()?->pelajar;

        abort_if(! $pelajar, 403, 'Akun ini belum terhubung dengan data pelajar.');

        return $pelajar;
    }
}
