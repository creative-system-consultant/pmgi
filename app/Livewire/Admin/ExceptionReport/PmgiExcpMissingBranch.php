<?php

namespace App\Livewire\Admin\ExceptionReport;

use PDO;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ExcpMissingBranch;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPengecualianCawangan;

class PmgiExcpMissingBranch extends Component
{
    use WithPagination;

    public function runStoredProcedureToday()
    {
        // Get today's date
        $todayDate = now()->toDateString();
        
        // Call the existing method to run the procedure
        $this->runStoredProcedure($todayDate);
    }    

    public function runStoredProcedure($dateString)
    {
        $user = auth()->user()->USERID;
        $logMessage = '';

        $messageDate = now()->format('d-m-Y');
        
        try 
        {
            $output = '';

            $procedureName = 'UP_PMGI_CHK_MISSING_BRN';

            $bindings = [
                $dateString,
                auth()->user()->USERID,
                'pi_ret_msg' => [
                    'value' => &$output,
                    'type' => PDO::PARAM_STR,
                    'length' => 4000,
                ],
            ];

            // Execute the procedure
            DB::executeProcedure($procedureName, $bindings);

            if (substr($output, 0, 1) == '0') {
                $successMessage = "Stored procedure $procedureName executed successfully with date: $messageDate and user ID: $user.";
                
                // Dispatch success message to SweetAlert
                $this->dispatch('swal', title:'Success', text:$successMessage, icon:'success');

                $logMessage .= "Stored procedure executed successfully for date: $messageDate. Output: $output\n";
            } 

            else {
                $errorMessage = "Stored procedure $procedureName executed with errors for date: $messageDate. Output: $output";
                
                // Dispatch error message to SweetAlert
                $this->dispatch('swal', title:'Error', text:$errorMessage, icon:'error');
                
                $logMessage .= "Stored procedure executed with errors for date: $messageDate. Output: $output\n";

            }
        } 
        
        catch (\Exception $e) 
        {
            $errorMessage = "Failed to execute stored procedure $procedureName for date: $messageDate. Error: " . $e->getMessage();
            $logMessage .= $errorMessage . "\n";
            
            $this->dispatch('swal', title:'Error', text:$errorMessage, icon:'error');
        }
        
        return $logMessage;        
    }

    public function exportExcel()
    {
        return Excel::download(new LaporanPengecualianCawangan, 'Laporan_Pengecualian_Cawangan.xlsx');
    }    

    public function render()
    {
        $data = ExcpMissingBranch::orderBy('hr_state_name')->orderBy('hr_branch_name')->paginate(15);         

        return view('livewire.admin.exception-report.excp-missing-branch', compact('data'))->extends('layouts.main');
    }
}