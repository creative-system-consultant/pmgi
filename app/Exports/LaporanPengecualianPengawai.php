<?php
namespace App\Exports;

use Maatwebsite\Excel\Sheet;
use App\Models\ExcpMissingMgr;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanPengecualianPengawai implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    // Store for the the number column
    private $index = 1;
    public $report_date;

    public function __construct($report_date)
    {
        $this->report_date = $report_date;
    }    
    

    public function collection()
    {
        // Fetch data to be exported
        return ExcpMissingMgr::select(['negeri','branch_code','cawangan','report_date'])
        ->whereDate('report_date', $this->report_date)
        ->orderBy('negeri')
        ->orderBy('branch_code')
        ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Negeri',
            'Kod Cawangan',
            'Cawangan',
            'Tarikh Laporan',
        ];
    }

    public function map($row): array
    {
        // Format the date
        $formattedDate = \Carbon\Carbon::parse($row->report_date)->format('d/m/Y');
        
        return [
            $this->index++,  
            $row->negeri,
            $row->cawangan,
            $row->branch_code,
            $formattedDate, 
        ];
    }
    
    // Apply styling to the cells
    public function styles(Sheet $sheet)
    {
        // Apply date format to the column of report dates
        $sheet->getStyle('E2:E' . $sheet->getHighestRow())
            ->getNumberFormat()
            ->setFormatCode('DD/MM/YYYY');  // Set the Excel date format

        return [];
    }    
}

