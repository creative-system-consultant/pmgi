<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\SysMsgLog;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanLogMesejSistem implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public $start_date;
    public $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date   = $end_date;
    }    

    public function collection()
    {
        return  SysMsgLog::select([
                    'seq_no','report_date','user_id','pgm_grp', 
                    'pgm_sub_grp', 'err_proc', 'err_msg', 'err_no', 
                    'err_severity', 'err_state', 'err_line', 'event_timestamp',
                    'msg_type', 'debug_notes'
                ])
                ->whereBetween('report_date', [$this->start_date, $this->end_date])
                ->orderBy('seq_no')
                ->get();
    }
    
    public function headings(): array
    {
        return [
            'ID',
            'Tarikh Laporan	',
            'ID Pengguna',
            'Kumpulan Pgm',
            'Sub Kumpulan Pgm',
            'Prosidur Ralat',
            'Mesej Ralat',
            'Kod Ralat',
            'Tahap Kritikal',
            'Status Ralat',
            'Baris Ralat',
            'Tarikh Kejadian',
            'Jenis Mesej',
            'Nota Terperinci'
        ];
    }

    public function map($row): array
    {
        // Format the report_date
        $formattedDate = Carbon::parse($row->report_date)->format('d/m/Y');
        
        // Format the event_timestamp
        $formattedEventTimestamp = Carbon::parse($row->event_timestamp)->format('d/m/Y H:i:s');
        
        return [
            $row->seq_no,  
            $formattedDate, 
            $row->user_id,
            $row->pgm_grp,
            $row->pgm_sub_grp,
            $row->err_proc,
            $row->err_msg,
            $row->err_no,
            $row->err_severity,
            $row->err_state,
            $row->err_line,
            $formattedEventTimestamp,  
            $row->msg_type,
            $row->debug_notes
        ];
    }

    public function styles(Sheet $sheet)
    {
        // Apply date format to the column of report dates (column B)
        $sheet->getStyle('B2:B' . $sheet->getHighestRow())
            ->getNumberFormat()
            ->setFormatCode('DD/MM/YYYY');  // Set the Excel date format for report_date

        // Apply datetime format to the column of event timestamps (column H)
        $sheet->getStyle('L2:L' . $sheet->getHighestRow())
            ->getNumberFormat()
            ->setFormatCode('DD/MM/YYYY HH:MM:SS');  // Set the Excel datetime format for event_timestamp

        return [];
    } 
}
