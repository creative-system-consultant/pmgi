<?php

namespace App\Exports;

use App\Models\SummMthOfficer;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
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
    protected $groupedData;

    public function __construct($date, $state, $branch)
    {
        $this->reportDate = Carbon::parse($date);
        $this->state = $state;
        $this->branch = $branch;
    }

    public function view(): View
    {
        $query = SummMthOfficer::with(['branch', 'officerBranch'])
                                ->whereDate('report_date', $this->reportDate->copy()->endOfMonth()->format('Y-m-d'));

        if(!$this->state) {
            $branch_code = SummMthOfficer::whereOfficerId(auth()->user()->userid)
                                            ->orderBy('report_date', 'desc')
                                            ->first()->officer_branch_code;

            $query->whereAcctBranchCode($branch_code);
        } else {
            if ($this->state && $this->state != '%') {
                $query->where('branch_state_code', $this->state);
            }

            if ($this->branch && $this->branch != '%%') {
                $query->where('acct_branch_code', $this->branch);
            }
        }

        $data = $query->orderBy('branch_state_code', 'asc')
                        ->orderBy('cawangan', 'asc')
                        ->orderBy('incl_pmgi_flag', 'asc')
                        ->get();

        $this->groupedData = $this->groupData($data);

        // get negeri,branch title data
        if (!$this->state) {
            $selectedState = $data->first()->negeri;
            $selectedBranch = $data->first()->cawangan;
        } else {
            if ($this->state != '%') {
                $selectedState = $data->first()->negeri;
            } else {
                $selectedState = 'SEMUA NEGERI';
            }

            if ($this->branch != '%%') {
                $selectedBranch = $data->first()->cawangan;
            }  else {
                $selectedBranch = 'SEMUA CAWANGAN';
            }
        }

        return view('exports.prestasi-bulanan-keseluruhan', [
            'groupedData' => $this->groupedData,
            'selectedState' => $selectedState,
            'selectedBranch' => $selectedBranch,
            'reportDate' => strtoupper($this->reportDate->format('F Y'))
        ]);
    }

    private function groupData(Collection $officerData): Collection
    {
        return $officerData->groupBy('branch_state_code')->map(function ($stateData) {
            return $stateData->groupBy('acct_branch_code')->map(function ($branchData) {
                return $branchData->groupBy('officer_id')->map(function ($officerRecords) {
                    return $officerRecords->take(1);
                });
            });
        });
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

        // Style rows based on flags
        $startRow = 11;
        foreach ($this->groupedData as $stateData) {
            foreach ($stateData as $branchData) {
                foreach ($branchData as $officerData) {
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
            }
        }

        // Freeze columns A and B
        $sheet->freezePane('C11');  // This will freeze the columns A and B up to column C and row 10.

        return $sheet;
    }

    // Make sure calculateLastRow returns the correct last row number
    private function calculateLastRow(): int
    {
        // Start at row 10 because we have headers up to row 9
        $totalRows = 10;

        // Iterate through grouped data and count each officer's record
        foreach ($this->groupedData as $stateData) {
            foreach ($stateData as $branchData) {
                foreach ($branchData as $officerData) {
                    $totalRows++; // Increment row count for each officer data row
                }
            }
        }

        return $totalRows; // Return the actual total number of rows in the sheet
    }
}
