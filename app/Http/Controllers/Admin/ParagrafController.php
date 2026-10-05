<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paragraf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ParagrafController extends Controller
{
    /** Folder gambar Summernote di disk "public" (storage/app/public/paragraf/summernote). */
    private const FOLDER = 'paragraf/summernote';

    /** Folder audio paragraf di disk "public" (storage/app/public/paragraf/audio). */
    private const FOLDER_AUDIO = 'paragraf/audio';

    /** Suara Azure yang boleh dipilih (nama suara => label). */
    private const SUARA_AZURE = [
        'zh-CN-XiaoxiaoNeural'  => 'Xiaoxiao - perempuan, Mandarin standar',
        'zh-CN-YunxiNeural'     => 'Yunxi - laki-laki, Mandarin standar',
        'zh-TW-HsiaoChenNeural' => 'HsiaoChen - perempuan, logat Taiwan',
        'zh-TW-YunJheNeural'    => 'YunJhe - laki-laki, logat Taiwan',
    ];

    /** Kecepatan bicara (nilai prosody rate => label). */
    private const LAJU_AZURE = [
        '0%'   => 'Normal',
        '-15%' => 'Agak pelan',
        '-30%' => 'Pelan',
    ];

    /** Host iframe (video) yang boleh tampil di penjelasan. */
    private const HOST_VIDEO = [
        'youtube.com', 'www.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com',
        'player.vimeo.com', 'www.dailymotion.com', 'www.facebook.com', 'www.instagram.com',
    ];

    /* =====================================================================
     |  CRUD
     * ===================================================================*/

    public function index(Request $request)
    {
        $query = Paragraf::query();

        if ($cari = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('hanzi', 'like', "%{$cari}%")
                    ->orWhere('pinyin', 'like', "%{$cari}%")
                    ->orWhere('arti_indonesia', 'like', "%{$cari}%");
            });
        }

        if ($request->input('penjelasan') === 'ada') {
            $query->whereNotNull('penjelasan_tata_bahasa')->where('penjelasan_tata_bahasa', '!=', '');
        } elseif ($request->input('penjelasan') === 'kosong') {
            $query->where(function ($q) {
                $q->whereNull('penjelasan_tata_bahasa')->orWhere('penjelasan_tata_bahasa', '');
            });
        }

        if ($request->input('media') === 'ada') {
            $query->where(function ($q) {
                $q->where('penjelasan_tata_bahasa', 'like', '%<img%')
                    ->orWhere('penjelasan_tata_bahasa', 'like', '%<iframe%');
            });
        }

        $paragrafs = $query->orderByDesc('id')->paginate(10)->withQueryString();

        $adaPenjelasan = fn () => Paragraf::whereNotNull('penjelasan_tata_bahasa')->where('penjelasan_tata_bahasa', '!=', '');

        $stats = [
            'total'             => Paragraf::count(),
            'dengan_penjelasan' => $adaPenjelasan()->count(),
            'tanpa_penjelasan'  => Paragraf::where(function ($q) {
                $q->whereNull('penjelasan_tata_bahasa')->orWhere('penjelasan_tata_bahasa', '');
            })->count(),
            'dengan_media'      => Paragraf::where(function ($q) {
                $q->where('penjelasan_tata_bahasa', 'like', '%<img%')
                    ->orWhere('penjelasan_tata_bahasa', 'like', '%<iframe%');
            })->count(),
        ];

        return view('pages.paragraf.index', compact('paragrafs', 'stats'));
    }

    public function create()
    {
        return view('pages.paragraf.create', $this->dataAudio());
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        $paragraf = Paragraf::create($this->kolom($data));
        $galatAudio = $this->simpanAudio($request, $paragraf);

        $balik = redirect()->route('admin.paragraf.index')
            ->with('success', 'Paragraf berhasil ditambahkan.');

        return $galatAudio ? $balik->with('error', $galatAudio) : $balik;
    }

    public function show(Paragraf $paragraf)
    {
        return view('pages.paragraf.show', compact('paragraf'));
    }

    public function edit(Paragraf $paragraf)
    {
        return view('pages.paragraf.edit', ['paragraf' => $paragraf] + $this->dataAudio());
    }

    public function update(Request $request, Paragraf $paragraf)
    {
        $data = $this->validasi($request, $paragraf);
        $kolom = $this->kolom($data);

        $gambarLama   = $this->gambarDari($paragraf->penjelasan_tata_bahasa);
        $hanziBerubah = $kolom['hanzi'] !== $paragraf->hanzi;

        $paragraf->update($kolom);

        // Gambar yang sudah dibuang dari editor ikut dihapus dari storage.
        $this->hapusFile(array_diff($gambarLama, $this->gambarDari($paragraf->penjelasan_tata_bahasa)));

        $galatAudio = $this->simpanAudio($request, $paragraf);

        $pesan = 'Paragraf berhasil diperbarui.';
        if ($hanziBerubah && $paragraf->audio
            && ! $request->hasFile('audio_file') && ! $request->boolean('buat_audio') && ! $request->boolean('hapus_audio')) {
            $pesan .= ' Teks hanzi berubah, jadi audio lama mungkin tidak cocok lagi. Buat ulang atau ganti audionya.';
        }

        $balik = redirect()->route('admin.paragraf.index')->with('success', $pesan);

        return $galatAudio ? $balik->with('error', $galatAudio) : $balik;
    }

    public function destroy(Paragraf $paragraf)
    {
        $this->hapusFile($this->gambarDari($paragraf->penjelasan_tata_bahasa));
        $this->hapusAudio($paragraf->audio);
        $paragraf->delete();

        return redirect()->route('admin.paragraf.index')
            ->with('success', 'Paragraf berhasil dihapus.');
    }

    /* =====================================================================
     |  SUMMERNOTE: UPLOAD & HAPUS GAMBAR
     * ===================================================================*/

    public function uploadGambar(Request $request): JsonResponse
    {
        $request->validate([
            'gambar' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ], [
            'gambar.required' => 'Tidak ada gambar yang dikirim.',
            'gambar.image'    => 'File harus berupa gambar.',
            'gambar.mimes'    => 'Format gambar harus jpg, jpeg, png, gif, atau webp.',
            'gambar.max'      => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $file = $request->file('gambar');
        $nama = Str::uuid() . '.' . $file->extension();
        $path = $file->storeAs(self::FOLDER, $nama, 'public');

        // Catat di session: hanya gambar yang baru diunggah di sesi ini yang boleh dihapus lewat endpoint hapus.
        $request->session()->push('paragraf_unggahan', $nama);

        return response()->json([
            // URL relatif supaya tidak rusak kalau APP_URL / domain berubah.
            'url' => '/storage/' . $path,
        ]);
    }

    public function hapusGambar(Request $request): JsonResponse
    {
        $path = parse_url((string) $request->input('src'), PHP_URL_PATH) ?: '';

        if (! Str::contains($path, '/storage/' . self::FOLDER . '/')) {
            return response()->json(['ok' => false, 'message' => 'Bukan gambar editor.'], 422);
        }

        $nama = basename($path);

        if (! in_array($nama, $request->session()->get('paragraf_unggahan', []), true)) {
            // Gambar yang sudah tersimpan di paragraf dibersihkan otomatis saat tombol Simpan ditekan.
            return response()->json(['ok' => false, 'message' => 'Gambar sudah tersimpan, akan dibersihkan saat disimpan.'], 200);
        }

        Storage::disk('public')->delete(self::FOLDER . '/' . $nama);

        return response()->json(['ok' => true]);
    }

    /* =====================================================================
     |  IMPORT EXCEL
     * ===================================================================*/

    public function import()
    {
        return view('pages.paragraf.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:5120'],
            'mode' => ['required', Rule::in(['perbarui', 'lewati'])],
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes'    => 'File harus berformat .xlsx atau .xls.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
            'mode.required' => 'Pilih salah satu mode import.',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        } catch (\Throwable $e) {
            return back()->with('error', 'File Excel tidak bisa dibaca. Pastikan file tidak rusak dan tidak diberi password.');
        }

        $sheet = $spreadsheet->getSheetByName('Paragraf');
        if (! $sheet) {
            return back()->with('error', 'Sheet bernama "Paragraf" tidak ditemukan. Gunakan template yang disediakan.');
        }

        $baris  = $sheet->toArray(null, true, true, false);
        $header = array_map(fn ($h) => Str::lower(trim((string) $h)), array_shift($baris) ?? []);
        $kolom  = array_flip($header);

        $wajib  = ['judul', 'hanzi', 'pinyin', 'arti_indonesia'];
        $hilang = array_values(array_diff($wajib, array_keys($kolom)));
        if ($hilang) {
            return back()->with('error', 'Kolom wajib tidak ditemukan di baris 1: ' . implode(', ', $hilang) . '.');
        }

        $ambil = function (array $row, string $nama) use ($kolom): string {
            return isset($kolom[$nama]) ? trim((string) ($row[$kolom[$nama]] ?? '')) : '';
        };

        $mode  = $request->input('mode');
        $hasil = ['baru' => 0, 'diperbarui' => 0, 'dilewati' => 0, 'gagal' => 0, 'galat' => []];

        foreach ($baris as $n => $row) {
            $nomor = $n + 2; // baris 1 = header

            $judul = $ambil($row, 'judul');
            $hanzi = $ambil($row, 'hanzi');
            $pinyin = $ambil($row, 'pinyin');
            $arti  = $ambil($row, 'arti_indonesia');
            $teksPenjelasan = $ambil($row, 'penjelasan_tata_bahasa');

            // Lewati baris yang benar-benar kosong.
            if ($judul === '' && $hanzi === '' && $pinyin === '' && $arti === '' && $teksPenjelasan === '') {
                continue;
            }

            $galat = [];
            if ($judul === '')  $galat[] = 'judul kosong';
            if (mb_strlen($judul) > 100) $galat[] = 'judul lebih dari 100 karakter';
            if ($hanzi === '')  $galat[] = 'hanzi kosong';
            if ($pinyin === '') $galat[] = 'pinyin kosong';
            if ($arti === '')   $galat[] = 'arti_indonesia kosong';

            if ($galat) {
                $hasil['gagal']++;
                $hasil['galat'][] = "Baris {$nomor}: " . implode(', ', $galat) . '.';
                continue;
            }

            try {
                DB::transaction(function () use ($judul, $hanzi, $pinyin, $arti, $teksPenjelasan, $mode, &$hasil) {
                    $ada = Paragraf::where('judul', $judul)->first();

                    if (! $ada) {
                        Paragraf::create([
                            'judul'                  => $judul,
                            'hanzi'                  => $hanzi,
                            'pinyin'                 => $pinyin,
                            'arti_indonesia'         => $arti,
                            'penjelasan_tata_bahasa' => $this->teksKeHtml($teksPenjelasan),
                        ]);
                        $hasil['baru']++;
                        return;
                    }

                    if ($mode === 'lewati') {
                        $hasil['dilewati']++;
                        return;
                    }

                    $data = ['hanzi' => $hanzi, 'pinyin' => $pinyin, 'arti_indonesia' => $arti];

                    // Sel penjelasan yang dikosongkan tidak menghapus penjelasan lama.
                    if ($teksPenjelasan !== '') {
                        $gambarLama = $this->gambarDari($ada->penjelasan_tata_bahasa);
                        $data['penjelasan_tata_bahasa'] = $this->teksKeHtml($teksPenjelasan);
                        $this->hapusFile($gambarLama);
                    }

                    $ada->update($data);
                    $hasil['diperbarui']++;
                });
            } catch (\Throwable $e) {
                $hasil['gagal']++;
                $hasil['galat'][] = "Baris {$nomor}: gagal disimpan ({$e->getMessage()}).";
            }
        }

        $hasil['galat'] = array_slice($hasil['galat'], 0, 50);

        return redirect()->route('admin.paragraf.import')->with('hasil', $hasil);
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Paragraf');

        $sheet->fromArray(['judul', 'hanzi', 'pinyin', 'arti_indonesia', 'penjelasan_tata_bahasa'], null, 'A1');
        $sheet->fromArray([[
            '我的一天',
            '我每天早上七点起床。我喜欢喝水。',
            'Wǒ měitiān zǎoshang qī diǎn qǐchuáng. Wǒ xǐhuan hē shuǐ.',
            'Setiap pagi jam tujuh saya bangun. Saya suka minum air.',
            '每天 = setiap hari. 喜欢 + kata kerja = suka melakukan sesuatu.',
        ]], null, 'A2');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:E1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1A73E8');

        foreach (['A' => 24, 'B' => 45, 'C' => 55, 'D' => 45, 'E' => 55] as $huruf => $lebar) {
            $sheet->getColumnDimension($huruf)->setWidth($lebar);
        }
        $sheet->getStyle('A2:E200')->getAlignment()->setWrapText(true)->setVertical('top');
        $sheet->freezePane('A2');

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, 'template-import-paragraf.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /* =====================================================================
     |  HELPER
     * ===================================================================*/

    private function validasi(Request $request, ?Paragraf $paragraf = null): array
    {
        return $request->validate([
            'judul'                  => ['required', 'string', 'max:100', Rule::unique('paragrafs', 'judul')->ignore($paragraf?->id)],
            'hanzi'                  => ['required', 'string'],
            'pinyin'                 => ['required', 'string'],
            'arti_indonesia'         => ['required', 'string'],
            'penjelasan_tata_bahasa' => ['nullable', 'string'],
            'audio_file'             => ['nullable', 'file', 'mimes:mp3,mpga,wav,m4a,ogg', 'max:10240'],
            'buat_audio'             => ['nullable', 'boolean'],
            'hapus_audio'            => ['nullable', 'boolean'],
            'suara_azure'            => ['nullable', Rule::in(array_keys(self::SUARA_AZURE))],
            'laju_azure'             => ['nullable', Rule::in(array_keys(self::LAJU_AZURE))],
        ], [
            'judul.required'          => 'Judul wajib diisi.',
            'judul.max'               => 'Judul maksimal 100 karakter.',
            'judul.unique'            => 'Judul ini sudah dipakai paragraf lain.',
            'hanzi.required'          => 'Teks hanzi wajib diisi.',
            'pinyin.required'         => 'Pinyin wajib diisi.',
            'arti_indonesia.required' => 'Arti Indonesia wajib diisi.',
            'audio_file.mimes'        => 'Audio harus berformat mp3, wav, m4a, atau ogg.',
            'audio_file.max'          => 'Ukuran audio maksimal 10 MB.',
            'audio_file.uploaded'     => 'Audio gagal diunggah. Ukuran file mungkin melebihi batas upload_max_filesize di php.ini.',
        ]);
    }

    /* =====================================================================
     |  AUDIO (UPLOAD / AZURE)
     * ===================================================================*/

    /** Data tambahan untuk form create & edit. */
    private function dataAudio(): array
    {
        return [
            'suaraAzure' => self::SUARA_AZURE,
            'lajuAzure'  => self::LAJU_AZURE,
            'azureSiap'  => filled(config('services.azure_speech.key')) && filled(config('services.azure_speech.region')),
        ];
    }

    /** Hanya kolom tabel (tanpa field form audio), penjelasan sudah dibersihkan. */
    private function kolom(array $data): array
    {
        $kolom = Arr::only($data, ['judul', 'hanzi', 'pinyin', 'arti_indonesia', 'penjelasan_tata_bahasa']);
        $kolom['penjelasan_tata_bahasa'] = $this->bersihkan($kolom['penjelasan_tata_bahasa'] ?? null);

        return $kolom;
    }

    /**
     * Simpan audio sesuai pilihan di form. Urutan prioritas:
     * file yang diunggah > buat otomatis (Azure) > hapus audio.
     * Mengembalikan pesan galat (string) kalau gagal, atau null kalau aman.
     * Paragrafnya sendiri sudah tersimpan sebelum fungsi ini dipanggil.
     */
    private function simpanAudio(Request $request, Paragraf $paragraf): ?string
    {
        $lama = $paragraf->audio;

        if ($request->hasFile('audio_file')) {
            $file = $request->file('audio_file');
            $path = $file->storeAs(self::FOLDER_AUDIO, Str::uuid() . '.' . $file->extension(), 'public');
        } elseif ($request->boolean('buat_audio')) {
            try {
                $biner = $this->suaraAzure(
                    $paragraf->hanzi,
                    $request->input('suara_azure', array_key_first(self::SUARA_AZURE)),
                    $request->input('laju_azure', '0%')
                );
            } catch (\RuntimeException $e) {
                return 'Paragraf tersimpan, tetapi audio gagal dibuat: ' . $e->getMessage();
            }

            $path = self::FOLDER_AUDIO . '/' . Str::uuid() . '.mp3';
            Storage::disk('public')->put($path, $biner);
        } elseif ($request->boolean('hapus_audio') && $lama) {
            $this->hapusAudio($lama);
            $paragraf->update(['audio' => null]);

            return null;
        } else {
            return null;
        }

        $paragraf->update(['audio' => $path]);
        $this->hapusAudio($lama); // audio lama diganti

        return null;
    }

    /** Minta Azure AI Speech membacakan teks, hasilnya biner mp3. */
    private function suaraAzure(string $teks, string $suara, string $laju): string
    {
        $key    = config('services.azure_speech.key');
        $region = config('services.azure_speech.region');

        if (! $key || ! $region) {
            throw new \RuntimeException('AZURE_SPEECH_KEY / AZURE_SPEECH_REGION belum diisi di .env.');
        }
        if (! array_key_exists($suara, self::SUARA_AZURE) || ! array_key_exists($laju, self::LAJU_AZURE)) {
            throw new \RuntimeException('Pilihan suara atau kecepatan tidak dikenal.');
        }

        $teks = trim(preg_replace('/\s+/u', ' ', $teks));
        if ($teks === '') {
            throw new \RuntimeException('Teks hanzi kosong.');
        }
        if (mb_strlen($teks) > 3000) {
            throw new \RuntimeException('Teks hanzi terlalu panjang (maksimal 3000 karakter).');
        }

        $bahasa = substr($suara, 0, 5); // zh-CN / zh-TW
        $ssml = "<speak version='1.0' xml:lang='{$bahasa}'><voice name='{$suara}'><prosody rate='{$laju}'>"
            . htmlspecialchars($teks, ENT_XML1 | ENT_QUOTES, 'UTF-8')
            . '</prosody></voice></speak>';

        try {
            $res = Http::timeout(30)
                ->withHeaders([
                    'Ocp-Apim-Subscription-Key' => $key,
                    'X-Microsoft-OutputFormat'  => 'audio-24khz-48kbitrate-mono-mp3',
                    'User-Agent'                => 'laravel-belajar-mandarin',
                ])
                ->withBody($ssml, 'application/ssml+xml')
                ->post("https://{$region}.tts.speech.microsoft.com/cognitiveservices/v1");
        } catch (ConnectionException $e) {
            throw new \RuntimeException('Server tidak bisa terhubung ke Azure. Periksa koneksi internet server.');
        }

        if ($res->failed()) {
            Log::warning('Azure TTS gagal', ['status' => $res->status(), 'body' => mb_substr($res->body(), 0, 300)]);

            throw new \RuntimeException(match ($res->status()) {
                401, 403 => 'Key Azure ditolak. Periksa AZURE_SPEECH_KEY dan region di .env.',
                429      => 'Batas pemakaian Azure tercapai. Coba lagi nanti.',
                default  => 'Azure menolak permintaan (kode ' . $res->status() . ').',
            });
        }

        return $res->body();
    }

    private function hapusAudio(?string $path): void
    {
        if ($path && Str::startsWith($path, self::FOLDER_AUDIO . '/')) {
            Storage::disk('public')->delete(self::FOLDER_AUDIO . '/' . basename($path));
        }
    }

    /**
     * Bersihkan HTML dari Summernote.
     * - <p><br></p> (editor kosong) -> null
     * - buang <script>, atribut on*="...", dan javascript: pada href/src
     * - iframe hanya boleh dari host video yang dikenal
     *
     * Catatan: ini pembersih ringan karena yang menulis hanya admin. Kalau suatu saat
     * pengguna umum bisa menulis HTML, pakai library seperti mews/purifier.
     */
    private function bersihkan(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html);
        $html = preg_replace('#\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $html);
        $html = preg_replace('#(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2#i', '$1=$2#$2', $html);

        $html = preg_replace_callback('#<iframe\b[^>]*>.*?</iframe>#is', function ($m) {
            if (preg_match('#\ssrc\s*=\s*["\']([^"\']+)["\']#i', $m[0], $src)) {
                $host = parse_url(str_starts_with($src[1], '//') ? 'https:' . $src[1] : $src[1], PHP_URL_HOST);
                if ($host && in_array(Str::lower($host), self::HOST_VIDEO, true)) {
                    return $m[0];
                }
            }
            return '';
        }, $html);

        $isi = trim(html_entity_decode(strip_tags(str_replace('&nbsp;', ' ', $html), '<img><iframe><video>')));

        return $isi === '' ? null : trim($html);
    }

    /** Teks biasa dari Excel -> HTML paragraf (baris kosong = paragraf baru, enter = <br>). */
    private function teksKeHtml(string $teks): ?string
    {
        $teks = trim($teks);
        if ($teks === '') {
            return null;
        }

        $paragraf = preg_split('/\R{2,}/u', $teks) ?: [$teks];

        return collect($paragraf)
            ->map(fn ($p) => '<p>' . nl2br(e(trim($p)), false) . '</p>')
            ->implode('');
    }

    /** Nama file gambar editor (di folder paragraf/summernote) yang dipakai di sebuah HTML. */
    private function gambarDari(?string $html): array
    {
        if (! $html) {
            return [];
        }

        preg_match_all('#<img[^>]+src=["\']([^"\']+)["\']#i', $html, $cocok);

        return collect($cocok[1] ?? [])
            ->map(fn ($src) => parse_url($src, PHP_URL_PATH) ?: '')
            ->filter(fn ($path) => Str::contains($path, '/storage/' . self::FOLDER . '/'))
            ->map(fn ($path) => basename($path))
            ->unique()
            ->values()
            ->all();
    }

    private function hapusFile(iterable $namaFile): void
    {
        foreach ($namaFile as $nama) {
            Storage::disk('public')->delete(self::FOLDER . '/' . basename($nama));
        }
    }
}
