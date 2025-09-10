<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GlobalRekapDetailedExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    protected $rekapData;

    public function __construct($rekapData)
    {
        $this->rekapData = $rekapData;
    }

    public function array(): array
    {
        $rows = [];
        
        // Debug: Log the data structure
        Log::info('Export Data Structure:', ['count' => count($this->rekapData), 'sample' => $this->rekapData[0] ?? 'No data']);
        
        foreach ($this->rekapData as $item) {
            $processedRows = $this->processItem($item);
            $rows = array_merge($rows, $processedRows);
        }
        
        Log::info('Export Rows Generated:', ['total_rows' => count($rows)]);
        
        return $rows;
    }

    public function headings(): array
    {
        return [
            'No. Nota Dinas',
            'Asal & Tujuan',
            'No. & Tanggal SPT',
            'Penandatangan SPT',
            'No. & Tanggal SPPD',
            'Penandatangan SPPD',
            'Alat Angkutan',
            'Nama PPTK',
            'No. & Tanggal Laporan',
            'Nama Peserta',
            'No. & Tanggal Kwitansi',
            'Transportasi - Uraian',
            'Transportasi - Nilai',
            'Transportasi - Deskripsi',
            'Penginapan - Uraian',
            'Penginapan - Nilai',
            'Penginapan - Deskripsi',
            'Uang Harian - Uraian',
            'Uang Harian - Nilai',
            'Uang Harian - Deskripsi',
            'Representatif - Uraian',
            'Representatif - Nilai',
            'Representatif - Deskripsi',
            'Biaya Lainnya - Uraian',
            'Biaya Lainnya - Nilai',
            'Biaya Lainnya - Deskripsi',
            'Total Kwitansi',
            'Dokumen Pendukung'
        ];
    }

    private function processItem($item): array
    {
        $rows = [];
        
        // Format supporting documents
        $supportingDocs = '';
        if (isset($item['supporting_documents']) && $item['supporting_documents']) {
            $docs = [];
            foreach ($item['supporting_documents'] as $doc) {
                $docs[] = $doc['name'] ?? $doc['file_name'] ?? 'Unknown';
            }
            $supportingDocs = implode('; ', $docs);
        }

        // Check if this is a single receipt line item (additional rows)
        if (isset($item['receipt_line']) && $item['receipt_line']) {
            $line = $item['receipt_line'];
            $category = $line['category'] ?? '';
            
            // For additional rows, only show receipt info and the specific line
            $baseData = [
                '', // No. Nota Dinas (empty for additional rows)
                '', // Asal & Tujuan (empty for additional rows)
                '', // No. & Tanggal SPT (empty for additional rows)
                '', // Penandatangan SPT (empty for additional rows)
                '', // No. & Tanggal SPPD (empty for additional rows)
                '', // Penandatangan SPPD (empty for additional rows)
                '', // Alat Angkutan (empty for additional rows)
                '', // Nama PPTK (empty for additional rows)
                '', // No. & Tanggal Laporan (empty for additional rows)
                '', // Nama Peserta (empty for additional rows)
                '', // No. & Tanggal Kwitansi (empty for additional rows)
            ];
            
            // Add the specific line data based on category
            $lineData = $this->getEmptyLineData();
            $lineData = $this->setLineData($lineData, $category, $line['line']);
            
            $rows[] = array_merge($baseData, $lineData, [
                '', // Total Kwitansi (empty for additional rows)
                $supportingDocs
            ]);
        } else {
            // Handle main receipt lines - create synchronized rows
            $categories = ['transport', 'lodging', 'perdiem', 'representation', 'other'];
            $hasData = false;
            
            // Find the maximum number of items in any category
            $maxItems = 0;
            foreach ($categories as $category) {
                $lines = $item['receipt_lines'][$category] ?? [];
                $maxItems = max($maxItems, count($lines));
            }
            
            // Create rows synchronized by item index
            for ($itemIndex = 0; $itemIndex < $maxItems; $itemIndex++) {
                $hasData = true;
                
                // Base data for main rows
                $baseData = [
                    $this->formatNotaDinas($item),
                    $this->formatOriginDestination($item),
                    $this->formatSpt($item),
                    $item['spt_signer'] ?? '',
                    $this->formatSppd($item),
                    $item['sppd_signer'] ?? '',
                    $item['transport_mode'] ?? '',
                    $item['pptk_name'] ?? '',
                    $this->formatTripReport($item),
                    $this->formatParticipant($item),
                    $this->formatReceipt($item),
                ];
                
                // Create line data with items at the same index from all categories
                $lineData = $this->getEmptyLineData();
                
                foreach ($categories as $category) {
                    $lines = $item['receipt_lines'][$category] ?? [];
                    if (isset($lines[$itemIndex])) {
                        $lineData = $this->setLineData($lineData, $category, $lines[$itemIndex]);
                    }
                }
                
                $rows[] = array_merge($baseData, $lineData, [
                    $item['receipt_total'] ? number_format($item['receipt_total'], 0, ',', '.') : '',
                    $supportingDocs
                ]);
            }
            
            // If no receipt lines, add a row with empty receipt data
            if (!$hasData) {
                $baseData = [
                    $this->formatNotaDinas($item),
                    $this->formatOriginDestination($item),
                    $this->formatSpt($item),
                    $item['spt_signer'] ?? '',
                    $this->formatSppd($item),
                    $item['sppd_signer'] ?? '',
                    $item['transport_mode'] ?? '',
                    $item['pptk_name'] ?? '',
                    $this->formatTripReport($item),
                    $this->formatParticipant($item),
                    $this->formatReceipt($item),
                ];
                
                $lineData = $this->getEmptyLineData();
                
                $rows[] = array_merge($baseData, $lineData, [
                    $item['receipt_total'] ? number_format($item['receipt_total'], 0, ',', '.') : '',
                    $supportingDocs
                ]);
            }
        }
        
        return $rows;
    }

    private function formatNotaDinas($item)
    {
        if (!$item['number']) return '';
        
        $result = $item['number'];
        if ($item['date']) {
            $result .= "\n" . \Carbon\Carbon::parse($item['date'])->format('d/m/Y');
        }
        if ($item['requesting_unit']) {
            $result .= "\nBidang: " . $item['requesting_unit'];
        }
        return $result;
    }

    private function formatOriginDestination($item)
    {
        if (!$item['origin']) return '';
        
        $result = $item['origin'];
        if ($item['destination']) {
            $result .= " → " . $item['destination'];
        }
        if ($item['start_date'] && $item['end_date']) {
            $result .= "\n" . \Carbon\Carbon::parse($item['start_date'])->format('d/m/Y') . " - " . \Carbon\Carbon::parse($item['end_date'])->format('d/m/Y');
            $result .= "\n(" . ($item['duration'] ?: \Carbon\Carbon::parse($item['start_date'])->diffInDays(\Carbon\Carbon::parse($item['end_date'])) + 1) . " Hari)";
        }
        return $result;
    }

    private function formatSpt($item)
    {
        if (!$item['spt_number']) return '';
        
        $result = $item['spt_number'];
        if ($item['spt_date']) {
            $result .= "\n" . \Carbon\Carbon::parse($item['spt_date'])->format('d/m/Y');
        }
        return $result;
    }

    private function formatSppd($item)
    {
        if (!$item['sppd_number']) return '';
        
        $result = $item['sppd_number'];
        if ($item['sppd_date']) {
            $result .= "\n" . \Carbon\Carbon::parse($item['sppd_date'])->format('d/m/Y');
        }
        return $result;
    }

    private function formatTripReport($item)
    {
        if (!$item['trip_report_number']) return '';
        
        $result = $item['trip_report_number'];
        if ($item['trip_report_date']) {
            $result .= "\n" . \Carbon\Carbon::parse($item['trip_report_date'])->format('d/m/Y');
        }
        return $result;
    }

    private function formatParticipant($item)
    {
        if (!$item['participant_name']) return '';
        
        $result = $item['participant_name'];
        if ($item['participant_nip']) {
            $result .= "\nNIP: " . $item['participant_nip'];
        }
        if ($item['participant_rank']) {
            $result .= "\n" . $item['participant_rank'];
        }
        return $result;
    }

    private function formatReceipt($item)
    {
        if (!$item['receipt_number']) return '';
        
        $result = $item['receipt_number'];
        if ($item['receipt_date']) {
            $result .= "\n" . \Carbon\Carbon::parse($item['receipt_date'])->format('d/m/Y');
        }
        return $result;
    }

    private function getEmptyLineData()
    {
        return [
            '', // Transportasi - Uraian
            '', // Transportasi - Nilai
            '', // Transportasi - Deskripsi
            '', // Penginapan - Uraian
            '', // Penginapan - Nilai
            '', // Penginapan - Deskripsi
            '', // Uang Harian - Uraian
            '', // Uang Harian - Nilai
            '', // Uang Harian - Deskripsi
            '', // Representatif - Uraian
            '', // Representatif - Nilai
            '', // Representatif - Deskripsi
            '', // Biaya Lainnya - Uraian
            '', // Biaya Lainnya - Nilai
            '', // Biaya Lainnya - Deskripsi
        ];
    }

    private function setLineData($lineData, $category, $line)
    {
        $formattedLine = $this->formatReceiptLine($line);
        $value = number_format($line['line_total'] ?? 0, 0, ',', '.');
        $desc = $line['desc'] ?? '';

        switch ($category) {
            case 'transport':
                $lineData[0] = $formattedLine;  // Uraian
                $lineData[1] = $value;          // Nilai
                $lineData[2] = $desc;           // Deskripsi
                break;
            case 'lodging':
                $lineData[3] = $formattedLine;  // Uraian
                $lineData[4] = $value;          // Nilai
                $lineData[5] = $desc;           // Deskripsi
                break;
            case 'perdiem':
                $lineData[6] = $formattedLine;  // Uraian
                $lineData[7] = $value;          // Nilai
                $lineData[8] = $desc;           // Deskripsi
                break;
            case 'representation':
                $lineData[9] = $formattedLine;  // Uraian
                $lineData[10] = $value;         // Nilai
                $lineData[11] = $desc;          // Deskripsi
                break;
            case 'other':
                $lineData[12] = $formattedLine; // Uraian
                $lineData[13] = $value;         // Nilai
                $lineData[14] = $desc;          // Deskripsi
                break;
        }

        return $lineData;
    }

    private function getCategoryName($category)
    {
        $names = [
            'transport' => 'Transportasi',
            'lodging' => 'Penginapan',
            'perdiem' => 'Uang Harian',
            'representation' => 'Representatif',
            'other' => 'Biaya Lainnya'
        ];
        
        return $names[$category] ?? $category;
    }

    private function formatReceiptLine($line)
    {
        if (is_array($line)) {
            $qty = $line['qty'] ?? 0;
            $unitAmount = $line['unit_amount'] ?? 0;
            
            // Special handling for lodging 30%
            if (isset($line['no_lodging']) && $line['no_lodging'] && isset($line['reference_rate'])) {
                return "({$qty} x (30% x Rp " . number_format($line['reference_rate'], 0, ',', '.') . "))";
            }
            
            return "({$qty} x Rp " . number_format($unitAmount, 0, ',', '.') . ")";
        }
        
        return '';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25, // No. Nota Dinas
            'B' => 30, // Asal & Tujuan
            'C' => 20, // No. & Tanggal SPT
            'D' => 25, // Penandatangan SPT
            'E' => 20, // No. & Tanggal SPPD
            'F' => 25, // Penandatangan SPPD
            'G' => 15, // Alat Angkutan
            'H' => 25, // Nama PPTK
            'I' => 20, // No. & Tanggal Laporan
            'J' => 30, // Nama Peserta
            'K' => 20, // No. & Tanggal Kwitansi
            'L' => 25, // Transportasi - Uraian
            'M' => 15, // Transportasi - Nilai
            'N' => 30, // Transportasi - Deskripsi
            'O' => 25, // Penginapan - Uraian
            'P' => 15, // Penginapan - Nilai
            'Q' => 30, // Penginapan - Deskripsi
            'R' => 20, // Uang Harian - Uraian
            'S' => 15, // Uang Harian - Nilai
            'T' => 25, // Uang Harian - Deskripsi
            'U' => 20, // Representatif - Uraian
            'V' => 15, // Representatif - Nilai
            'W' => 25, // Representatif - Deskripsi
            'X' => 25, // Biaya Lainnya - Uraian
            'Y' => 15, // Biaya Lainnya - Nilai
            'Z' => 30, // Biaya Lainnya - Deskripsi
            'AA' => 20, // Total Kwitansi
            'AB' => 30, // Dokumen Pendukung
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'E3F2FD',
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Apply borders to all cells with data
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
                
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                
                // Auto-fit row heights
                for ($row = 1; $row <= $lastRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(-1);
                }
                
                // Freeze header row
                $sheet->freezePane('A2');
            },
        ];
    }
}
