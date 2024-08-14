<?php

namespace App\Exports;

use App\Models\BankOfficer;
use App\Models\MntrSession;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EvaluatorSessionList implements FromView, WithColumnFormatting, ShouldAutoSize, WithStyles
{
    protected $pmgi;
    protected $selectedPym;
    protected $selectedPmc;
    protected $officerIds;
    protected $reportDate;

    public function __construct($pmgi, $selectedPym, $selectedPmc, $officerIds, $reportDate)
    {
        $this->pmgi = $pmgi;
        $this->selectedPym = $selectedPym;
        $this->selectedPmc = $selectedPmc;
        $this->officerIds = $officerIds;
        $this->reportDate = $reportDate;
    }

    public function view(): View
    {
        $data = MntrSession::join('PMGI_BANK_OFFICERS_NAZ', 'PMGI_NAZ_MNTR_SESSION.OFFICER_ID', '=', 'PMGI_BANK_OFFICERS_NAZ.officer_id')
                ->join('BRANCHES', 'PMGI_BANK_OFFICERS_NAZ.branch_code', '=', 'BRANCHES.branch_code')
                ->whereIn('PMGI_NAZ_MNTR_SESSION.OFFICER_ID', $this->officerIds)
                ->whereDate('PMGI_NAZ_MNTR_SESSION.SESSION_DATE_START', $this->reportDate)
                ->orderBy('BRANCHES.branch_name')
                ->select(
                    'PMGI_NAZ_MNTR_SESSION.pmgi_level',
                    'PMGI_NAZ_MNTR_SESSION.report_date',
                    'PMGI_BANK_OFFICERS_NAZ.officer_name',
                    'PMGI_BANK_OFFICERS_NAZ.staffno',
                    'PMGI_BANK_OFFICERS_NAZ.nokp',
                    'BRANCHES.branch_name'
                )
                ->get()
                ->map(function($item) {
                    $item->report_date = Carbon::parse($item->report_date)->format('Y-m-d'); // Ensuring date is in the correct format
                    return $item;
                });

        $pym = BankOfficer::whereOfficerId($this->selectedPym)->value('officer_name');

        if ($this->selectedPmc) {
            $pmc = BankOfficer::whereOfficerId($this->selectedPmc)->value('officer_name');
        } else {
            $pmc = null;
        }

        return view('exports.evaluator-session-list', [
            'pmgi' => $this->pmgi,
            'date' => $this->reportDate->translatedFormat('F Y'),
            'pym' => $pym,
            'pmc' => $pmc,
            'datas' => $data
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $startingRow = $this->selectedPmc ? 7 : 6;

        // Determine the highest row number
        $highestRow = $sheet->getHighestRow(); // e.g. 10
        $highestColumn = $sheet->getHighestColumn(); // e.g 'G'

        // Apply border to the data table
        $sheet->getStyle("B{$startingRow}:{$highestColumn}{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Apply gray background to the header row
        $sheet->getStyle("B{$startingRow}:{$highestColumn}{$startingRow}")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFd3d3d3',
                ],
            ],
            'font' => [
                'bold' => true,
            ],
        ]);

        // Center align columns E and G, left align column F
        $sheet->getStyle("E{$startingRow}:E{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("G{$startingRow}:G{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("F{$startingRow}:F{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);


        return [];
    }
}
