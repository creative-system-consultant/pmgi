<?php

namespace App\Exports;

use App\Models\ExcpMissingBranch;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanPengecualianCawangan implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    // Store for the the number cplumn
    private $index = 1;

    public function collection()
    {
        return ExcpMissingBranch::orderBy('hr_state_name')->orderBy('hr_branch_name')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Negeri Dalam HR',
            'Cawangan Dalam HR',
        ];
    }

    public function map($row): array
    {        
        return [
            $this->index++,  
            $row->hr_state_name,
            $row->hr_branch_name,
        ];
    }    
}
