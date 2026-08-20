<?php

namespace App\Exports;

use App\Services\Prestasi\PrestasiBulananRingkasanService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PrestasiBulananRingkasan implements FromView, WithStyles
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
        $service = new PrestasiBulananRingkasanService();
        $date = $this->reportDate->format('Y-m-d');

        // No state means the download was triggered by a PYD, who only sees their own data.
        if (!$this->state) {
            $data = $service->forOfficer($date, auth()->user()->USERID);
        } else {
            $data = $service->forAdmin($date, $this->state, $this->branch, $this->pydId);
        }

        $this->rows = $data['rows'];
        $first = $this->rows->first()?->first();

        if (!$this->state) {
            $selectedState = $first?->negeri ?? 'N/A';
            $selectedBranch = $first?->cawangan ?? 'N/A';
        } else {
            $selectedState = $this->state == PrestasiBulananRingkasanService::ALL_STATES
                ? 'SEMUA NEGERI'
                : ($first?->negeri ?? 'N/A');

            $selectedBranch = $this->branch == PrestasiBulananRingkasanService::ALL_BRANCHES
                ? 'SEMUA CAWANGAN'
                : ($first?->cawangan ?? 'N/A');
        }

        return view('exports.prestasi-bulanan-ringkasan', [
            'rows' => $this->rows,
            'months' => $data['months'],
            'selectedState' => $selectedState,
            'selectedBranch' => $selectedBranch,
            'reportDate' => strtoupper($this->reportDate->format('F Y'))
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Apply header styles
        $sheet->getStyle('A1:Z1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Adjust column width dynamically
        foreach (range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Apply style for months header
        $sheet->getStyle('C8:N8')->applyFromArray([
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

        // Apply style for subheading
        $sheet->getStyle('B9:N9')->applyFromArray([
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
        $sheet->getStyle('C8:N8')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Apply borders from row 9 till the last row (B9:N$lastRow)
        $sheet->getStyle("B9:N$lastRow")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Style rows based on flags
        $startRow = 10;

        foreach ($this->rows as $officerData) {
            $inclPmgiFlag = $officerData->first()->incl_pmgi_flag;

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

        return $sheet;
    }

    // Make sure calculateLastRow returns the correct last row number
    private function calculateLastRow(): int
    {
        // Start at row 9 because we have headers up to row 8, then one row per pegawai
        return 9 + ($this->rows?->count() ?? 0);
    }
}
