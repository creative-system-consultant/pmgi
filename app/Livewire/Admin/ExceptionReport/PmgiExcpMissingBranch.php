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
        
        try 
        {
            $output = '';

            $procedureName = 'UP_PMGI_IMP_HR_OFFICER';

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
                $successMessage = "Stored procedure UP_PMGI_IMP_HR_OFFICER executed successfully with date: $dateString and user ID: $user.";
                
                // Dispatch success message to SweetAlert
                $this->dispatch('swal', title:'Success', text:$successMessage, icon:'success');

                $logMessage .= "Stored procedure executed successfully for date: $dateString. Output: $output\n";
            } 

            else {
                $errorMessage = "Stored procedure UP_PMGI_IMP_HR_OFFICER executed with errors for date: $dateString. Output: $output";
                
                // Dispatch error message to SweetAlert
                $this->dispatch('swal', title:'Error', text:$errorMessage, icon:'error');
                
                $logMessage .= "Stored procedure executed with errors for date: $dateString. Output: $output\n";

            }
        } 
        
        catch (\Exception $e) 
        {
            $errorMessage = "Failed to execute stored procedure UP_PMGI_IMP_HR_OFFICER for date: $dateString. Error: " . $e->getMessage();
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