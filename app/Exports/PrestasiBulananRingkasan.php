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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PrestasiBulananRingkasan implements FromView, WithStyles
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
            ->whereBetween('report_date', [$this->reportDate->copy()->subMonth()->startOfMonth(), $this->reportDate->copy()->endOfMonth()]);

        if(!$this->state) {
            $userData = SummMthOfficer::whereOfficerId(auth()->user()->userid)
                                        ->orderBy('report_date', 'desc')
                                        ->first();

            if ($userData) {
                $branch_code = $userData->officer_branch_code;
            } else {
                return view('exports.prestasi-bulanan-ringkasan', [
                    'groupedData' => collect(),
                    'months' => collect(),
                    'selectedState' => 'N/A',
                    'selectedBranch' => 'N/A',
                    'reportDate' => strtoupper($this->reportDate->format('F Y')),
                ]);
            }

            $query->whereAcctBranchCode($branch_code);
        } else {
            if ($this->state && $this->state != '%') {
                $query->where('branch_state_code', $this->state);
            }

            if ($this->branch && $this->branch != '%%') {
                $query->where('acct_branch_code', $this->branch);
            }
        }

        $data = $query->orderBy('report_date', 'asc')
                        ->orderBy('branch_state_code', 'asc')
                        ->orderBy('cawangan', 'asc')
                        ->orderBy('incl_pmgi_flag', 'asc')
                        ->get()
                        ->map(function($item) {
                            $item->report_date = Carbon::parse($item->report_date)->format('Y-m-d');
                            return $item;
                        });

        $data->transform(function ($item) {
            $item->report_date = strtoupper(Carbon::parse($item->report_date)->translatedFormat('F Y'));
            return $item;
        });

        $months = $data->pluck('report_date')->unique()->take(2)->values();

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

        return view('exports.prestasi-bulanan-ringkasan', [
            'groupedData' => $this->groupedData,
            'months' => $months,
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

        if (!empty($this->groupedData) && $this->groupedData->isNotEmpty()) {
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
        }

        return $sheet;
    }

    // Make sure calculateLastRow returns the correct last row number
    private function calculateLastRow(): int
    {
        $totalRows = 9; // Start at row 9

        // Ensure groupedData is not null or empty
        if (empty($this->groupedData) || $this->groupedData->isEmpty()) {
            return $totalRows; // Return just the header rows if there's no data
        }

        foreach ($this->groupedData as $stateData) {
            foreach ($stateData as $branchData) {
                foreach ($branchData as $officerData) {
                    $totalRows++; // Increment row count for each officer data row.
                }
            }
        }

        return $totalRows; // Return the total number of rows in the sheet
    }

    private function groupData(Collection $officerData): Collection
    {
        return $officerData->groupBy('branch_state_code')->map(function ($stateData) {
            return $stateData->groupBy('acct_branch_code')->map(function ($branchData) {
                return $branchData->groupBy('officer_id')->map(function ($officerRecords) {
                    return $officerRecords->take(2);
                });
            });
        });
    }
}
