<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Template Excel untuk import kosakata.
 * Dua sheet: "Kosakata" dan "Contoh Kalimat" (nama sheet & judul kolom harus persis).
 */
class KosakataTemplateExport implements Export, WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            $this->lembar(
                'Kosakata',
                ['hanzi', 'pinyin', 'arti_indonesia', 'baca_indonesia', 'english', 'kegunaan', 'kategori', 'level_hsk', 'urutan'],
                [
                    ['你好', 'nǐ hǎo', 'Halo', 'ni hao', 'Hello', 'Sapaan umum saat bertemu siapa saja, kapan pun sepanjang hari.', 'Sapaan', 1, ''],
                    ['谢谢', 'xièxie', 'Terima kasih', 'sie sie', 'Thank you', 'Diucapkan untuk berterima kasih setelah dibantu atau diberi sesuatu.', 'Sapaan', 1, ''],
                ]
            ),
            $this->lembar(
                'Contoh Kalimat',
                ['kosakata_hanzi', 'kosakata_pinyin', 'kalimat_hanzi', 'kalimat_pinyin', 'kalimat_arti', 'catatan_tata_bahasa'],
                [
                    ['你好', 'nǐ hǎo', '你好，我叫小明。', 'Nǐ hǎo, wǒ jiào Xiǎo Míng.', 'Halo, nama saya Xiao Ming.', '叫 (jiào) = dipanggil / bernama'],
                    ['你好', 'nǐ hǎo', '老师，你好！', 'Lǎoshī, nǐ hǎo!', 'Halo, Guru!', ''],
                    ['谢谢', 'xièxie', '谢谢你的帮助。', 'Xièxie nǐ de bāngzhù.', 'Terima kasih atas bantuanmu.', ''],
                ]
            ),
        ];
    }

    private function lembar(string $judul, array $kepala, array $baris): object
    {
        return new class($judul, $kepala, $baris) implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize {
            public function __construct(
                private string $judul,
                private array $kepala,
                private array $baris,
            ) {}

            public function array(): array
            {
                return $this->baris;
            }

            public function headings(): array
            {
                return $this->kepala;
            }

            public function title(): string
            {
                return $this->judul;
            }

            public function styles(Worksheet $sheet): array
            {
                return [
                    1 => [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1A73E8']],
                    ],
                ];
            }
        };
    }
}
