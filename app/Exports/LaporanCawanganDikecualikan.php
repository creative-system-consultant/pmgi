<?php

namespace App\Exports;

use App\Models\ExclBranch;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanCawanganDikecualikan implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    // Store for the the number cplumn
    private $index = 1;

    public function collection()
    {
        return ExclBranch::orderBy('state_name')->orderBy('branch_name')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Negeri',
            'Cawangan',
        ];
    }

    public function map($row): array
    {        
        return [
            $this->index++,  
            $row->state_name,
            $row->branch_name,
        ];
    }        
}
