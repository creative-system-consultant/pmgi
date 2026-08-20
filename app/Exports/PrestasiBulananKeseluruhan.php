<?php

namespace App\Exports;

use App\Services\Prestasi\PrestasiBulananKeseluruhanService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PrestasiBulananKeseluruhan implements FromView, WithStyles
{
    protected $reportDate;
    protected $state;
    protected $branch;
    protected $pydId;
    protected $rows;

    public function __construct($date, $state, $branch, $pydId = null)
    {
        $this->reportDate = Carbon::parse($date);
        $this->state = $state;
        $this->branch = $branch;
        $this->pydId = $pydId;
    }

    public function view(): View
    {
        $service = new PrestasiBulananKeseluruhanService();
        $date = $this->reportDate->format('Y-m-d');

        // No state means the download was triggered by a PYD, who only sees their own data.
        if (!$this->state) {
            $this->rows = $service->forOfficer($date, auth()->user()->USERID);

            $selectedState = $this->rows->first()?->negeri ?? 'N/A';
            $selectedBranch = $this->rows->first()?->cawangan ?? 'N/A';
        } else {
            $this->rows = $service->forAdmin($date, $this->state, $this->branch, $this->pydId);

            $selectedState = $this->state == PrestasiBulananKeseluruhanService::ALL_STATES
                ? 'SEMUA NEGERI'
                : ($this->rows->first()?->negeri ?? 'N/A');

            $selectedBranch = $this->branch == PrestasiBulananKeseluruhanService::ALL_BRANCHES
                ? 'SEMUA CAWANGAN'
                : ($this->rows->first()?->cawangan ?? 'N/A');
        }

        return view('exports.prestasi-bulanan-keseluruhan', [
            'rows' => $this->rows,
            'selectedState' => $selectedState,
            'selectedBranch' => $selectedBranch,
            'reportDate' => strtoupper($this->reportDate->format('F Y'))
        ]);
    }

    private function ynStyle(?string $flag): array
    {
        return match ($flag) {
            'Y' => ['font' => ['bold' => true, 'color' => ['argb' => 'FF047857']]], // hijau
            'N' => ['font' => ['bold' => true, 'color' => ['argb' => 'FFB91C1C']]], // merah
            default => [], // kosong / null -> no style
        };
    }


    public function styles(Worksheet $sheet)
    {
        // Adjust column width dynamically
        foreach (range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Apply style for kriteria header
        $sheet->getStyle('C8:X8')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE], // Text color white
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID, // Background color
                'startColor' => ['argb' => 'FF9CA3AF'], // Light gray color (bg-gray-400 equivalent)
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Apply style for subheading kriteria
        $sheet->getStyle('C9:W9')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID, // Solid background color
                'startColor' => ['argb' => 'D1D5DB'], // Light gray color (bg-gray-200 equivalent)
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Apply style for subheading
        $sheet->getStyle('B10:W10')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID, // Solid background color
                'startColor' => ['argb' => 'FFE5E7EB'], // Light gray color (bg-gray-200 equivalent)
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Calculate the last row based on the dataset
        $lastRow = $this->calculateLastRow(); // Make sure this returns the actual last row number

        // Define the range from C8 to N and dynamically detect the last row
        $range = "C8:N$lastRow";

        // Apply borders to row 8 (C8:N8)
        $sheet->getStyle('C8:X9')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Apply borders from row 10 till the last row (B10:N$lastRow)
        $sheet->getStyle("B10:X$lastRow")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $flagColumnMap = [
            'F' => 'rm_dapat_kutip_capai_flag',
            'J' => 'bil_dapat_kutip_capai_flag',
            'N' => 'bil_lawat_capai_flag',
            'S' => 'bil_kawal_npf_capai_flag',
            'W' => 'bil_pulih_npf_capai_flag',
        ];

        $startRow = 11;

        foreach ($this->rows as $rowRecord) {
            foreach ($flagColumnMap as $col => $field) {
                $flag = $rowRecord->{$field} ?? null;
                $sheet->getStyle("{$col}{$startRow}")
                    ->applyFromArray($this->ynStyle($flag));
            }

            $startRow++;
        }

        $startRow = 11;

        foreach ($this->rows as $rowRecord) {
            $inclPmgiFlag = $rowRecord->incl_pmgi_flag;

            $cell = "B$startRow"; // Adjust for the actual column

            if ($inclPmgiFlag == 'J') {
                $sheet->getStyle($cell)->applyFromArray([
                    'font' => [
                        'bold' => true,  // Apply bold for resigned officers
                        'color' => ['argb' => Color::COLOR_RED],
                        'size' => 11,
                    ]
                ]);
            } elseif ($inclPmgiFlag == 'G') {
                $sheet->getStyle($cell)->applyFromArray([
                    'font' => [
                        'bold' => true,  // Apply bold for transferred officers
                        'color' => ['argb' => Color::COLOR_RED],
                        'size' => 11,
                    ]
                ]);
            } elseif ($inclPmgiFlag == 'N') {
                $sheet->getStyle($cell)->applyFromArray([
                    'font' => [
                        'color' => ['argb' => Color::COLOR_BLACK],  // Black text color
                        'size' => 11,
                    ]
                ]);
            } elseif ($inclPmgiFlag == 'S' || $inclPmgiFlag == 'W') {
                $sheet->getStyle($cell)->applyFromArray([
                    'font' => [
                        'color' => ['argb' => Color::COLOR_WHITE],  // White text color
                        'size' => 11,
                    ]
                ]);
            } else {
                $sheet->getStyle($cell)->applyFromArray([
                    'font' => [
                        'bold' => false,  // Normal text for other cases
                        'color' => ['argb' => Color::COLOR_BLACK],
                        'size' => 11,
                    ]
                ]);
            }

            // Increment row for the next officer
            $startRow++;
        }

        // Freeze columns A and B
        $sheet->freezePane('C11');  // This will freeze the columns A and B up to column C and row 10.

        return $sheet;
    }

    // Make sure calculateLastRow returns the correct last row number
    private function calculateLastRow(): int
    {
        // Start at row 10 because we have headers up to row 9, then one row per record
        return 10 + ($this->rows?->count() ?? 0);
    }
}
