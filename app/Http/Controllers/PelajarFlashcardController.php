<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Kosakata;
use App\Models\LevelHsk;
use App\Models\Pelajar;
use App\Models\ProgresHafalan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Flashcard untuk pelajar: review harian (kata jatuh tempo) dan latihan bebas
 * (pilih kategori / level HSK / status).
 */
class PelajarFlashcardController extends Controller
{
    /** Maksimum kartu dalam satu sesi review harian. */
    private const BATAS_REVIEW = 50;

    private const PILIHAN_JUMLAH = [10, 20, 50];

    public function index(): View
    {
        $pid = $this->pelajarLogin()->id;

        return view('pages.hafalankosakata.flashcard', [
            'jatuhTempo'    => $this->queryReview($pid)->count(),
            'batasReview'   => self::BATAS_REVIEW,
            'kategoris'     => Kategori::orderBy('nama')->get(),
            'levels'        => LevelHsk::orderBy('tingkat')->get(),
            'statusList'    => PelajarKosakataController::STATUS,
            'pilihanJumlah' => self::PILIHAN_JUMLAH,
        ]);
    }

    public function mulai(Request $request): View|RedirectResponse
    {
        $pid = $this->pelajarLogin()->id;

        $data = $request->validate([
            'mode'         => ['required', Rule::in(['review', 'bebas'])],
            'status'       => ['nullable', Rule::in(array_keys(PelajarKosakataController::STATUS))],
            'kategori_id'  => ['nullable', 'integer', 'exists:kategoris,id'],
            'level_hsk_id' => ['nullable', 'integer', 'exists:level_hsks,id'],
            'jumlah'       => ['nullable', Rule::in(self::PILIHAN_JUMLAH)],
            'acak'         => ['nullable', 'boolean'],
        ]);

        $kosakatas = $data['mode'] === 'review'
            ? $this->deckReview($pid)
            : $this->deckBebas($pid, $data);

        if ($kosakatas->isEmpty()) {
            return redirect()
                ->route('pelajar.flashcard.index')
                ->with('error', $data['mode'] === 'review'
                    ? 'Belum ada kata yang perlu direview hari ini.'
                    : 'Tidak ada kata yang cocok dengan pilihan tersebut.');
        }

        return view('pages.hafalankosakata.flashcard-sesi', [
            'mode'  => $data['mode'],
            'kartu' => $kosakatas->map(fn (Kosakata $k) => $this->kartu($k))->values(),
        ]);
    }

    /**
     * Simpan satu jawaban kartu. Dipanggil lewat fetch() dari halaman sesi.
     * Lupa -> lupa_dan_ingat, Ingat -> ingat_sepenuhnya (lihat ProgresHafalan::catatKartu).
     */
    public function jawab(Request $request): JsonResponse
    {
        $pelajar = $this->pelajarLogin();

        $data = $request->validate([
            'kosakata_id' => ['required', 'integer', 'exists:kosakatas,id'],
            'ingat'       => ['required', 'boolean'],
        ]);

        $progres = ProgresHafalan::firstOrCreate(
            ['pelajar_id' => $pelajar->id, 'kosakata_id' => $data['kosakata_id']],
            ['status' => ProgresHafalan::BERIKUTNYA],
        );

        $progres->catatKartu((bool) $data['ingat']);

        return response()->json([
            'ok'     => true,
            'status' => $progres->status,
            'label'  => PelajarKosakataController::STATUS[$progres->status]['label'],
        ]);
    }

    /* ------------------------------------------------------------------ */

    /** Progres yang ikut review dan sudah jatuh tempo, milik satu pelajar. */
    private function queryReview(int $pelajarId)
    {
        return ProgresHafalan::where('pelajar_id', $pelajarId)
            ->untukPopup()
            ->jatuhTempo();
    }

    private function deckReview(int $pelajarId)
    {
        return $this->queryReview($pelajarId)
            ->with('kosakata.kategori', 'kosakata.levelHsk', 'kosakata.contohKalimats')
            ->orderByRaw('review_berikutnya IS NULL DESC') // yang baru dipindah dulu
            ->orderBy('review_berikutnya')
            ->limit(self::BATAS_REVIEW)
            ->get()
            ->pluck('kosakata')
            ->filter();
    }

    private function deckBebas(int $pelajarId, array $data)
    {
        $jumlah = (int) ($data['jumlah'] ?? 20);
        $status = $data['status'] ?? null;
        $denganProgres = [ProgresHafalan::LUPA_DAN_INGAT, ProgresHafalan::INGAT_SEPENUHNYA];

        $query = Kosakata::query()->with('kategori', 'levelHsk', 'contohKalimats');

        if (filled($data['kategori_id'] ?? null)) {
            $query->where('kategori_id', $data['kategori_id']);
        }
        if (filled($data['level_hsk_id'] ?? null)) {
            $query->where('level_hsk_id', $data['level_hsk_id']);
        }

        // Kata tanpa baris progres dianggap "berikutnya" (sama seperti halaman daftar).
        if ($status === ProgresHafalan::BERIKUTNYA) {
            $query->whereDoesntHave('progresHafalans', fn ($q) => $q
                ->where('pelajar_id', $pelajarId)
                ->whereIn('status', $denganProgres));
        } elseif (in_array($status, $denganProgres, true)) {
            $query->whereHas('progresHafalans', fn ($q) => $q
                ->where('pelajar_id', $pelajarId)
                ->where('status', $status));
        }

        ! empty($data['acak']) ? $query->inRandomOrder() : $query->urut();

        return $query->limit($jumlah)->get();
    }

    /** Bentuk data kartu yang dikirim ke JavaScript. */
    private function kartu(Kosakata $k): array
    {
        return [
            'id'       => $k->id,
            'hanzi'    => $k->hanzi,
            'pinyin'   => $k->pinyin,
            'baca'     => $k->baca_indonesia,
            'arti'     => $k->arti_indonesia,
            'english'  => $k->english,
            'kategori' => $k->kategori?->nama,
            'level'    => $k->levelHsk?->nama,
            'contoh'   => $k->contohKalimats->take(2)->map(fn ($c) => [
                'hanzi'  => $c->hanzi,
                'pinyin' => $c->pinyin,
                'arti'   => $c->arti_indonesia,
            ])->values(),
        ];
    }

    private function pelajarLogin(): Pelajar
    {
        $pelajar = auth()->user()?->pelajar;

        abort_if(! $pelajar, 403, 'Akun ini belum terhubung dengan data pelajar.');

        return $pelajar;
    }
}
