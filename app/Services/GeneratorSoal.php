<?php

namespace App\Services;

use App\Models\GrupKosakata;
use App\Models\GrupSoal;
use App\Models\Kosakata;
use App\Models\Soal;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Membuat GrupSoal (latihan) + Soal pilihan ganda A/B/C/D dari sebuah grup kosakata.
 *
 * Tipe soal:
 * - hanzi_arti : "Apa arti dari 你好?"            -> pilihan berupa arti Indonesia   (reading)
 * - arti_hanzi : "Mana hanzi untuk 'halo'?"       -> pilihan berupa hanzi            (reading)
 * - isian      : kalimat contoh dengan titik kosong -> pilihan berupa hanzi          (reading)
 * - listening  : "Dengarkan audio, pilih arti"    -> audio diunggah admin belakangan (listening)
 */
class GeneratorSoal
{
    public const TIPE = ['hanzi_arti', 'arti_hanzi', 'isian', 'listening'];

    public function buat(GrupKosakata $grup, array $tipe, int $jumlah, ?int $levelHskId = null): GrupSoal
    {
        $tipe = array_values(array_intersect($tipe, self::TIPE));
        if (! $tipe) {
            throw new InvalidArgumentException('Pilih minimal satu tipe soal.');
        }

        $kata = $grup->kosakatas()->with('contohKalimats')->get();
        if ($kata->count() < 2) {
            throw new InvalidArgumentException('Grup harus punya minimal 2 kata.');
        }

        // Kandidat pengecoh: kata di grup dulu, lalu kata lain di level yang sama, lalu sembarang kata.
        $cadangan = Kosakata::query()
            ->whereNotIn('id', $kata->pluck('id'))
            ->when($levelHskId ?? $kata->first()->level_hsk_id, fn ($q, $l) => $q->orderByRaw('level_hsk_id = ? desc', [$l]))
            ->inRandomOrder()
            ->limit(60)
            ->get();

        $semuaKata = $kata->concat($cadangan);
        if ($semuaKata->unique('id')->count() < 4) {
            throw new InvalidArgumentException('Butuh minimal 4 kosakata di database untuk membuat pilihan A-D.');
        }

        return DB::transaction(function () use ($grup, $kata, $tipe, $jumlah, $semuaKata, $levelHskId) {
            $grupSoal = GrupSoal::create([
                'level_hsk_id'   => $levelHskId ?? $kata->first()->level_hsk_id,
                'jenis'          => GrupSoal::LATIHAN,
                'judul'          => 'Latihan: ' . $grup->nama,
                'deskripsi'      => 'Dibuat otomatis dari grup kosakata "' . $grup->nama . '".',
                'durasi_menit'   => null,
                'nilai_lulus'    => null,
                'maks_percobaan' => null,
                'aktif'          => false, // admin cek dulu sebelum diaktifkan
            ]);

            $urutan = 1;
            foreach ($this->rencana($kata, $tipe, $jumlah) as [$jenis, $target]) {
                $data = $this->bangunSoal($jenis, $target, $semuaKata);
                if (! $data) {
                    continue;
                }

                Soal::create($data + [
                    'grup_soal_id' => $grupSoal->id,
                    'urutan'       => $urutan++,
                    'poin'         => 1,
                ]);
            }

            return $grupSoal->loadCount('soals');
        });
    }

    /** Daftar [tipe, kosakata] sebanyak $jumlah, tipe digilir, kata diacak. */
    private function rencana(Collection $kata, array $tipe, int $jumlah): array
    {
        $antrian = collect();
        while ($antrian->count() < $jumlah) {
            $antrian = $antrian->concat($kata->shuffle());
        }

        $hasil = [];
        foreach ($antrian->take($jumlah)->values() as $i => $k) {
            $hasil[] = [$tipe[$i % count($tipe)], $k];
        }

        return $hasil;
    }

    private function bangunSoal(string $jenis, Kosakata $k, Collection $semua): ?array
    {
        switch ($jenis) {
            case 'hanzi_arti':
                return $this->susun(
                    bagian: Soal::READING,
                    pertanyaan: "Apa arti dari {$k->hanzi} ({$k->pinyin})?",
                    benar: $k->arti_indonesia,
                    pengecoh: $this->pengecoh($k, $semua, 'arti_indonesia'),
                    penjelasan: "{$k->hanzi} ({$k->pinyin}) = {$k->arti_indonesia}",
                );

            case 'arti_hanzi':
                return $this->susun(
                    bagian: Soal::READING,
                    pertanyaan: "Mana hanzi yang berarti \"{$k->arti_indonesia}\"?",
                    benar: $k->hanzi,
                    pengecoh: $this->pengecoh($k, $semua, 'hanzi'),
                    penjelasan: "{$k->hanzi} ({$k->pinyin}) = {$k->arti_indonesia}",
                );

            case 'isian':
                $kalimat = $k->contohKalimats->first(fn ($c) => str_contains($c->hanzi, $k->hanzi));
                if (! $kalimat) {
                    // Tidak ada contoh kalimat yang memuat kata ini: jatuh ke tipe hanzi_arti.
                    return $this->bangunSoal('hanzi_arti', $k, $semua);
                }

                return $this->susun(
                    bagian: Soal::READING,
                    pertanyaan: 'Pilih kata yang tepat untuk mengisi titik kosong.',
                    benar: $k->hanzi,
                    pengecoh: $this->pengecoh($k, $semua, 'hanzi'),
                    penjelasan: "{$kalimat->hanzi} ({$kalimat->pinyin}) = {$kalimat->arti_indonesia}",
                    paragraf: str_replace($k->hanzi, '＿＿', $kalimat->hanzi),
                );

            case 'listening':
                return $this->susun(
                    bagian: Soal::LISTENING,
                    pertanyaan: 'Dengarkan audio, lalu pilih arti yang tepat.',
                    benar: $k->arti_indonesia,
                    pengecoh: $this->pengecoh($k, $semua, 'arti_indonesia'),
                    // Catatan untuk admin: kata apa yang harus direkam. Audio diunggah lewat form soal.
                    penjelasan: "Audio: {$k->hanzi} ({$k->pinyin}) = {$k->arti_indonesia}",
                );
        }

        return null;
    }

    /** Ambil 3 nilai pengecoh yang unik dan berbeda dari jawaban benar. */
    private function pengecoh(Kosakata $k, Collection $semua, string $kolom): array
    {
        return $semua
            ->where('id', '!=', $k->id)
            ->pluck($kolom)
            ->filter()
            ->reject(fn ($v) => $v === $k->{$kolom})
            ->unique()
            ->take(3)
            ->values()
            ->all();
    }

    private function susun(string $bagian, string $pertanyaan, string $benar, array $pengecoh, string $penjelasan, ?string $paragraf = null): ?array
    {
        if (count($pengecoh) < 3) {
            return null;
        }

        $pilihan = collect(array_merge([$benar], $pengecoh))->shuffle()->values();
        $huruf = ['A', 'B', 'C', 'D'];

        return [
            'bagian'        => $bagian,
            'paragraf'      => $paragraf,
            'audio'         => null,
            'pertanyaan'    => $pertanyaan,
            'pilihan_a'     => $pilihan[0],
            'pilihan_b'     => $pilihan[1],
            'pilihan_c'     => $pilihan[2],
            'pilihan_d'     => $pilihan[3],
            'jawaban_benar' => $huruf[$pilihan->search($benar)],
            'penjelasan'    => $penjelasan,
        ];
    }
}
