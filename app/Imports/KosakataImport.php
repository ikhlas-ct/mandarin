<?php

namespace App\Imports;

use App\Models\ContohKalimat;
use App\Models\Kategori;
use App\Models\Kosakata;
use App\Models\LevelHsk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Import;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Import kosakata + contoh kalimat dari satu file Excel (2 sheet).
 *
 * Alur: Excel::import() hanya MENGUMPULKAN baris dari kedua sheet,
 * lalu selesai() yang memproses (kosakata dulu, baru contoh kalimat),
 * jadi urutan sheet di file tidak berpengaruh.
 */
class KosakataImport implements Import, WithMultipleSheets, SkipsUnknownSheets
{
    private const MAKS_GALAT = 100;

    private Collection $barisKosakata;
    private Collection $barisContoh;

    private array $ringkasan = [
        'kosakata_baru'        => 0,
        'kosakata_diperbarui'  => 0,
        'kosakata_dilewati'    => 0,
        'kategori_baru'        => 0,
        'contoh_baru'          => 0,
        'contoh_diperbarui'    => 0,
        'contoh_dilewati'      => 0,
    ];

    private int $gagal = 0;
    private int $jumlahBaris = 0;
    private array $galat = [];

    /** "hanzi|pinyin" => id kosakata */
    private array $peta = [];
    /** nama kategori (huruf kecil) => id */
    private array $kategori = [];
    /** angka tingkat => id */
    private array $level = [];
    private int $urutanBerikut = 1;

    public function __construct(private bool $perbarui = true)
    {
        $this->barisKosakata = collect();
        $this->barisContoh   = collect();
    }

    /* ---------- Bagian Laravel Excel ---------- */

    public function sheets(): array
    {
        return [
            'Kosakata'       => $this->pengumpul('kosakata'),
            'Contoh Kalimat' => $this->pengumpul('contoh'),
        ];
    }

    public function onUnknownSheet(string|int $sheetName): void
    {
        // Sheet lain (mis. catatan) diabaikan.
    }

    public function terima(string $jenis, Collection $rows): void
    {
        if ($jenis === 'kosakata') {
            $this->barisKosakata = $this->barisKosakata->concat($rows->values());
        } else {
            $this->barisContoh = $this->barisContoh->concat($rows->values());
        }
    }

    private function pengumpul(string $jenis): object
    {
        return new class($this, $jenis) implements ToCollection, WithHeadingRow {
            public function __construct(private KosakataImport $induk, private string $jenis) {}

            public function collection(Collection $rows): void
            {
                $this->induk->terima($this->jenis, $rows);
            }
        };
    }

    /* ---------- Dipanggil controller ---------- */

    public function selesai(): void
    {
        $this->kategori = Kategori::pluck('id', 'nama')
            ->mapWithKeys(fn ($id, $nama) => [mb_strtolower($nama) => $id])
            ->all();
        $this->level         = LevelHsk::pluck('id', 'tingkat')->all();
        $this->urutanBerikut = (int) Kosakata::max('urutan') + 1;

        $this->prosesKosakata();
        $this->prosesContoh();
    }

    public function adaIsi(): bool
    {
        return $this->jumlahBaris > 0;
    }

    public function ringkasan(): array
    {
        return $this->ringkasan + ['gagal' => $this->gagal, 'galat' => $this->galat];
    }

    /* ---------- Sheet Kosakata ---------- */

    private function prosesKosakata(): void
    {
        foreach ($this->barisKosakata as $i => $row) {
            $nomor = $i + 2; // baris 1 = judul kolom
            $d = $this->ambil($row, [
                'hanzi', 'pinyin', 'arti_indonesia', 'baca_indonesia',
                'english', 'kegunaan', 'kategori', 'level_hsk', 'urutan',
            ]);

            if ($this->kosong($d)) {
                continue;
            }
            $this->jumlahBaris++;

            $v = Validator::make($d, [
                'hanzi'          => ['required', 'string', 'max:20'],
                'pinyin'         => ['required', 'string', 'max:60'],
                'arti_indonesia' => ['required', 'string', 'max:150'],
                'baca_indonesia' => ['nullable', 'string', 'max:60'],
                'english'        => ['nullable', 'string', 'max:150'],
                'kegunaan'       => ['nullable', 'string', 'max:2000'],
                'kategori'       => ['nullable', 'string', 'max:50'],
                'level_hsk'      => ['nullable', 'integer'],
                'urutan'         => ['nullable', 'integer', 'min:0'],
            ], [
                'required' => 'kolom :attribute wajib diisi',
                'max'      => 'kolom :attribute terlalu panjang (maks. :max karakter)',
                'integer'  => 'kolom :attribute harus berupa angka bulat',
                'min'      => 'kolom :attribute tidak boleh negatif',
            ]);

            if ($v->fails()) {
                $this->catatGagal("Sheet Kosakata baris {$nomor}", $v->errors()->all());
                continue;
            }

            if ($d['level_hsk'] !== null && ! isset($this->level[(int) $d['level_hsk']])) {
                $this->catatGagal("Sheet Kosakata baris {$nomor}", ["level_hsk {$d['level_hsk']} tidak ada di sistem"]);
                continue;
            }

            try {
                $this->simpanKosakata($d);
            } catch (\Throwable $e) {
                report($e);
                $this->catatGagal("Sheet Kosakata baris {$nomor}", [$e->getMessage()]);
            }
        }
    }

    private function simpanKosakata(array $d): void
    {
        $kunci = $d['hanzi'] . '|' . $d['pinyin'];

        $ada = Kosakata::where('hanzi', $d['hanzi'])->where('pinyin', $d['pinyin'])->first();

        if ($ada) {
            $this->peta[$kunci] = $ada->id;

            if (! $this->perbarui) {
                $this->ringkasan['kosakata_dilewati']++;
                return;
            }

            // Sel kosong di Excel tidak menghapus data lama.
            $ada->update($this->isiKosakata($d));
            $this->ringkasan['kosakata_diperbarui']++;
            return;
        }

        $isi = $this->isiKosakata($d);
        $isi['urutan'] ??= $this->urutanBerikut++;

        $baru = Kosakata::create($isi + ['hanzi' => $d['hanzi'], 'pinyin' => $d['pinyin']]);
        $this->peta[$kunci] = $baru->id;
        $this->ringkasan['kosakata_baru']++;
    }

    /** Kolom yang boleh diubah, hanya yang terisi. */
    private function isiKosakata(array $d): array
    {
        $isi = [
            'arti_indonesia' => $d['arti_indonesia'],
            'baca_indonesia' => $d['baca_indonesia'],
            'english'        => $d['english'],
            'kegunaan'       => $d['kegunaan'],
            'kategori_id'   => $d['kategori'] !== null ? $this->idKategori($d['kategori']) : null,
            'level_hsk_id'   => $d['level_hsk'] !== null ? $this->level[(int) $d['level_hsk']] : null,
            'urutan'         => $d['urutan'] !== null ? (int) $d['urutan'] : null,
        ];

        return array_filter($isi, fn ($nilai) => $nilai !== null);
    }

    private function idKategori(string $nama): int
    {
        $kunci = mb_strtolower($nama);

        if (! isset($this->kategori[$kunci])) {
            $this->kategori[$kunci] = Kategori::create(['nama' => $nama])->id;
            $this->ringkasan['kategori_baru']++;
        }

        return $this->kategori[$kunci];
    }

    /* ---------- Sheet Contoh Kalimat ---------- */

    private function prosesContoh(): void
    {
        foreach ($this->barisContoh as $i => $row) {
            $nomor = $i + 2;
            $d = $this->ambil($row, [
                'kosakata_hanzi', 'kosakata_pinyin', 'kalimat_hanzi',
                'kalimat_pinyin', 'kalimat_arti', 'catatan_tata_bahasa',
            ]);

            if ($this->kosong($d)) {
                continue;
            }
            $this->jumlahBaris++;

            $v = Validator::make($d, [
                'kosakata_hanzi'      => ['required', 'string'],
                'kosakata_pinyin'     => ['required', 'string'],
                'kalimat_hanzi'       => ['required', 'string', 'max:255'],
                'kalimat_pinyin'      => ['required', 'string', 'max:255'],
                'kalimat_arti'        => ['required', 'string', 'max:255'],
                'catatan_tata_bahasa' => ['nullable', 'string', 'max:255'],
            ], [
                'required' => 'kolom :attribute wajib diisi',
                'max'      => 'kolom :attribute terlalu panjang (maks. :max karakter)',
            ]);

            if ($v->fails()) {
                $this->catatGagal("Sheet Contoh Kalimat baris {$nomor}", $v->errors()->all());
                continue;
            }

            $kosakataId = $this->cariKosakata($d['kosakata_hanzi'], $d['kosakata_pinyin']);

            if (! $kosakataId) {
                $this->catatGagal("Sheet Contoh Kalimat baris {$nomor}", [
                    "kosakata {$d['kosakata_hanzi']} ({$d['kosakata_pinyin']}) tidak ditemukan; hanzi dan pinyin harus sama persis",
                ]);
                continue;
            }

            try {
                $this->simpanContoh($kosakataId, $d);
            } catch (\Throwable $e) {
                report($e);
                $this->catatGagal("Sheet Contoh Kalimat baris {$nomor}", [$e->getMessage()]);
            }
        }
    }

    private function cariKosakata(string $hanzi, string $pinyin): ?int
    {
        $kunci = $hanzi . '|' . $pinyin;

        if (! array_key_exists($kunci, $this->peta)) {
            $this->peta[$kunci] = Kosakata::where('hanzi', $hanzi)->where('pinyin', $pinyin)->value('id');
        }

        return $this->peta[$kunci];
    }

    private function simpanContoh(int $kosakataId, array $d): void
    {
        $ada = ContohKalimat::where('kosakata_id', $kosakataId)
            ->where('hanzi', $d['kalimat_hanzi'])
            ->first();

        if ($ada) {
            if (! $this->perbarui) {
                $this->ringkasan['contoh_dilewati']++;
                return;
            }

            $ada->update(array_filter([
                'pinyin'              => $d['kalimat_pinyin'],
                'arti_indonesia'      => $d['kalimat_arti'],
                'catatan_tata_bahasa' => $d['catatan_tata_bahasa'],
            ], fn ($nilai) => $nilai !== null));
            $this->ringkasan['contoh_diperbarui']++;
            return;
        }

        ContohKalimat::create([
            'kosakata_id'         => $kosakataId,
            'hanzi'               => $d['kalimat_hanzi'],
            'pinyin'              => $d['kalimat_pinyin'],
            'arti_indonesia'      => $d['kalimat_arti'],
            'catatan_tata_bahasa' => $d['catatan_tata_bahasa'],
        ]);
        $this->ringkasan['contoh_baru']++;
    }

    /* ---------- Helper ---------- */

    private function ambil($row, array $kolom): array
    {
        $hasil = [];
        foreach ($kolom as $k) {
            $hasil[$k] = $this->bersih($row[$k] ?? null);
        }

        return $hasil;
    }

    /** Rapikan nilai sel: trim, kosong jadi null, angka desimal bulat (1.0) jadi "1". */
    private function bersih(mixed $nilai): ?string
    {
        if ($nilai === null) {
            return null;
        }
        if (is_float($nilai) && floor($nilai) == $nilai) {
            $nilai = (int) $nilai;
        }

        $nilai = trim((string) $nilai);

        return $nilai === '' ? null : $nilai;
    }

    private function kosong(array $d): bool
    {
        return collect($d)->filter(fn ($v) => $v !== null)->isEmpty();
    }

    private function catatGagal(string $lokasi, array $pesan): void
    {
        $this->gagal++;

        if (count($this->galat) < self::MAKS_GALAT) {
            $this->galat[] = $lokasi . ': ' . implode('; ', $pesan) . '.';
        }
    }
}
