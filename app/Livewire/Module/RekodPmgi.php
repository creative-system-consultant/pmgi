<?php

namespace App\Livewire\Module;

use App\Models\BankOfficer;
use App\Models\BnmStatecode;
use App\Models\Branch;
use App\Models\JttSessionParticipant;
use App\Models\MntrSession;
use App\Models\RefEvalPctg;
use App\Models\SessionInfo;
use App\Models\SessionPmcInfo;
use App\Models\SessionPydInfo;
use App\Models\SessionPymInfo;
use App\Models\SettPymPmc;
use App\Models\SummMthOfficer;
use App\Services\HtmlToImageService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RekodPmgi extends Component
{
    private $htmlToImageService;

    public $pmgiSession = false;
    public $pydIdOrigin;
    public $detailsModal = false;
    public $searchTerm;
    public $pydId;
    public $allSession;
    public $jt1Session;
    public $jt2Session;
    public $sessionId;
    public $isAdmin = true;

    public function __construct()
    {
        $this->htmlToImageService = new HtmlToImageService();
    }

    public function mount()
    {
        // Initialize $allSession as an empty collection
        $this->allSession = new Collection();
        $this->jt1Session = new Collection();

        $this->populateData();
    }

    protected function populateData()
    {
        $role = [];
        foreach(auth()->user()->roles as $roles) {
            $role[] = $roles->name;
        }

        if (in_array('PYD', $role)) {
            $this->pydId = auth()->user()->USERID;
            $this->isAdmin = false;
            $this->getData();
        }

        if($this->pmgiSession) {
            $this->pydId = $this->pydIdOrigin;
            $this->isAdmin = false;
            $this->getData();
        }
    }

    public function search()
    {
        $this->pydId = BankOfficer::join('NEWFMS_PROD.DBO.dbo.FMS_USERS', 'pmgi_fms_bank_officers.officer_id', '=', 'NEWFMS_PROD.DBO.dbo.FMS_USERS.USERID')
            ->where('NEWFMS_PROD.DBO.dbo.FMS_USERS.USERSTATUS', 1)
            ->where(function($q) {
                $q->where('pmgi_fms_bank_officers.officer_name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('pmgi_fms_bank_officers.staffno', 'LIKE', '%' . $this->searchTerm . '%');
            })
            ->value('officer_id');
        
        $this->getData();
    }

    protected function getData()
    {
        $this->allSession = SettPymPmc::wherePydId($this->pydId)
                                        ->whereStatus(1)
                                        ->get();

        $userId = $this->pydId;

        $this->jt1Session = JttSessionParticipant::with([
            'sessionInfo',
            'mntrSession' => function ($query) use ($userId) {
                $query->where('officer_id', $userId); // Apply condition to mntrSession
            }
        ])
        ->whereUserId($this->pydId) // Filter on the main JttSessionParticipant model
        ->where('pmgi_level', 'JT1')
        ->get();
    }

    public function toggleDetail($sessionId)
    {
        $this->sessionId = str_replace('/', '-', $sessionId);
        $this->detailsModal = true;
    }

    public function streamRekodPmgi($sessionId)
    {
        $sessionId = str_replace('-', '/', $sessionId);

        $settInfo = SettPymPmc::where('session_id', $sessionId)->first();
        $summMthOfficer = SummMthOfficer::whereDate('report_date', $settInfo->report_date)->whereOfficerId($settInfo->pyd_id)->first();
        $bankOfficerPyd = BankOfficer::with('hrData')->whereOfficerId($settInfo->pyd_id)->first();
        $state = BnmStatecode::whereCode(substr($settInfo->branch_code, 0, 2))->value('description');
        $branch = Branch::where('branch_code', $settInfo->branch_code)->value('branch_name');
        $tempohBerkhidmat = strtoupper(str_replace(['Y', 'M', 'D'], [' Tahun ', ' Bulan ', ' Hari'], $bankOfficerPyd->hrData->tempoh_penempatan_semasa));
        $address = $bankOfficerPyd->hrData->alamat;
        if (preg_match('/^(.*?)(\d{5}.*)$/', $address, $matches)) {
            $alamat1 = trim($matches[1]); // Part before the postcode
            $alamat2 = trim($matches[2]); // Part starting from the postcode
        }
        $sessionInfo = SessionInfo::whereSessionId($sessionId)->first();
        $bankOfficerPym = BankOfficer::whereOfficerId($settInfo->pym_id)->first();
        $accCount = ($summMthOfficer->bil_a1 ?? 0) + ($summMthOfficer->bil_a2 ?? 0) + ($summMthOfficer->bil_a3 ?? 0) + ($summMthOfficer->bil_b1 ?? 0) + ($summMthOfficer->bil_b2 ?? 0) + ($summMthOfficer->bil_c1 ?? 0) + ($summMthOfficer->bil_c2 ?? 0) + ($summMthOfficer->bil_d ?? 0);
        $osB1D = ($summMthOfficer->rm_b1 ?? 0) + ($summMthOfficer->rm_b2 ?? 0) + ($summMthOfficer->rm_c1 ?? 0) + ($summMthOfficer->rm_c2 ?? 0) + ($summMthOfficer->rm_d ?? 0);
        $osAll = ($summMthOfficer->rm_a1 ?? 0) + ($summMthOfficer->rm_a2 ?? 0) + ($summMthOfficer->rm_a3 ?? 0) + ($summMthOfficer->rm_b1 ?? 0) + ($summMthOfficer->rm_b2 ?? 0) + ($summMthOfficer->rm_c1 ?? 0) + ($summMthOfficer->rm_c2 ?? 0) + ($summMthOfficer->rm_d ?? 0);
        $npfOs = $osAll > 0 ? round(($osB1D / $osAll) * 100, 2) : 0;

        if ($settInfo->pmgi_level == 'PM3') {
            $bankOfficerPmc = BankOfficer::whereOfficerId($settInfo->pmc_id)->first();
        } else {
            $bankOfficerPmc = NULL;
        }

        $pydInfo = SessionPydInfo::with('problemTable')->whereSessionId($sessionId)->first();
        $pymInfo = SessionPymInfo::whereSessionId($sessionId)->first();
        if ($settInfo->pmgi_level == 'PM3') {
            $pmcInfo = SessionPmcInfo::whereSessionId($sessionId)->first();
        } else {
            $pmcInfo = NULL;
        }

        $from = strtoupper(Carbon::parse($sessionInfo->session_date)->copy()->addMonthNoOverflow()->translatedFormat('F Y'));
        $to = strtoupper(Carbon::parse($sessionInfo->session_date)->copy()->addMonthNoOverflow(2)->translatedFormat('F Y'));

        // prestasi kumulatf var
        $report_date = Carbon::parse($settInfo->report_date);
        $fromReportDate = $report_date->copy()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d');
        $toReportDate = $report_date->copy()->endOfMonth()->format('Y-m-d');

        $data = DB::table('PMGI_SUMM_MTH_OFFICER')
                    ->where('officer_id', $settInfo->pyd_id)
                    ->whereBetween('report_date', [$fromReportDate, $toReportDate])
                    ->whereNotIn('incl_pmgi_flag', ['G', 'H'])
                    ->orderBy('report_date', 'asc')
                    ->get();

        $percentage = RefEvalPctg::where('state_code', $data->first()->branch_state_code)
                ->whereDate('effective_date', '<=', $toReportDate)
                ->orderBy('evaluation_id', 'ASC')
                ->get();

        // Calculate month names for each entry in the retrieved data
        $data->each(function ($item) {
            $item->month_name = Carbon::parse($item->report_date)->translatedFormat('F Y');
        });

        $paths = $this->htmlToImageService->generate(
            'pdf.prestasi_kumulatif',
            [
                'datas' => $data,
                'percentage' => $percentage,
            ],
            'pdf/prestasi_kumulatif/',
            "{$settInfo->pyd_id}_{$fromReportDate}_to_{$toReportDate}"
        );

        // Determine which template to use based on PMGI level
        $template = $settInfo->pmgi_level == 'PM3' ? 'pdf.borang_jpoc_pm3' : 'pdf.borang_jpoc_pm12';
        
        // Copy attachment files to the same temp directory as the chart image (which works)
        $attachmentPaths = [];
        $tempAttachmentFiles = []; // Keep track of copied files for cleanup
        
        // Get the directory where the chart image is stored (this directory works)
        $tempDirectory = dirname($paths['image_path']);
        
        if ($pydInfo && $pydInfo->attachment) {
            $originalPath = storage_path('app/public/' . $pydInfo->attachment);
            
            if (file_exists($originalPath)) {
                // Copy to temp directory where chart image is stored
                $tempFileName = 'pyd_attachment_' . time() . '_' . basename($originalPath);
                $tempPath = $tempDirectory . DIRECTORY_SEPARATOR . $tempFileName;
                
                if (copy($originalPath, $tempPath)) {
                    $attachmentPaths['pyd_attachment'] = $tempPath;
                    $tempAttachmentFiles[] = $tempPath; // Track for cleanup
                }
            }
        }
        
        if ($pymInfo && $pymInfo->attachment) {
            $originalPath = storage_path('app/public/' . $pymInfo->attachment);
            
            if (file_exists($originalPath)) {
                // Copy to temp directory where chart image is stored
                $tempFileName = 'pym_attachment_' . time() . '_' . basename($originalPath);
                $tempPath = $tempDirectory . DIRECTORY_SEPARATOR . $tempFileName;
                
                if (copy($originalPath, $tempPath)) {
                    $attachmentPaths['pym_attachment'] = $tempPath;
                    $tempAttachmentFiles[] = $tempPath; // Track for cleanup
                }
            }
        }
        
        if ($pmcInfo && $pmcInfo->attachment) {
            $originalPath = storage_path('app/public/' . $pmcInfo->attachment);
            
            if (file_exists($originalPath)) {
                // Copy to temp directory where chart image is stored
                $tempFileName = 'pmc_attachment_' . time() . '_' . basename($originalPath);
                $tempPath = $tempDirectory . DIRECTORY_SEPARATOR . $tempFileName;
                
                if (copy($originalPath, $tempPath)) {
                    $attachmentPaths['pmc_attachment'] = $tempPath;
                    $tempAttachmentFiles[] = $tempPath; // Track for cleanup
                }
            }
        }
        
        $pdf = Pdf::loadView($template, compact(
                'settInfo','bankOfficerPyd', 'state', 'branch', 'tempohBerkhidmat', 'alamat1', 'alamat2','summMthOfficer', 'accCount', 'osB1D', 'osAll', 'npfOs', 'sessionInfo', 'bankOfficerPym', 'bankOfficerPmc',
                'pydInfo', 'pymInfo', 'pmcInfo', 'from', 'to', 'paths', 'attachmentPaths'
            ))->setPaper('A4', 'portrait');

        // Store the PDF content in a variable before cleanup
        $pdfContent = $pdf->output();

        // Clean up the temporary files after the PDF has been generated
        if (file_exists($paths['html_path'])) {
            unlink($paths['html_path']);
        }

        if (file_exists($paths['image_path'])) {
            unlink($paths['image_path']);
        }
        
        // Clean up temporary attachment files
        foreach ($tempAttachmentFiles as $tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
        
        // Also clean up compressed email image if it exists
        if (isset($paths['email_image_path']) && file_exists($paths['email_image_path'])) {
            unlink($paths['email_image_path']);
        }

        // Return the PDF response
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="borang_jpoc_12.pdf"'
        ]);
    }

    public function saveRekodPmgi($sessionId, $level)
    {
        $formattedSessionId = str_replace('/', '-', $sessionId);
        $sessionId = str_replace('-', '/', $sessionId);

        // Set up the directory path
        $directoryPath = storage_path("app/temp/reports");

        // Check if the directory exists; if not, create it
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        $settInfo = SettPymPmc::where('session_id', $sessionId)->first();
        $summMthOfficer = SummMthOfficer::whereDate('report_date', $settInfo->report_date)->whereOfficerId($settInfo->pyd_id)->first();
        $bankOfficerPyd = BankOfficer::with('hrData')->whereOfficerId($settInfo->pyd_id)->first();
        $state = BnmStatecode::whereCode(substr($settInfo->branch_code, 0, 2))->value('description');
        $branch = Branch::where('branch_code', $settInfo->branch_code)->value('branch_name');
        $tempohBerkhidmat = strtoupper(str_replace(['Y', 'M', 'D'], [' Tahun ', ' Bulan ', ' Hari'], $bankOfficerPyd->hrData->tempoh_penempatan_semasa));
        $address = $bankOfficerPyd->hrData->alamat;
        if (preg_match('/^(.*?)(\d{5}.*)$/', $address, $matches)) {
            $alamat1 = trim($matches[1]); // Part before the postcode
            $alamat2 = trim($matches[2]); // Part starting from the postcode
        }
        $sessionInfo = SessionInfo::whereSessionId($sessionId)->first();
        $bankOfficerPym = BankOfficer::whereOfficerId($settInfo->pym_id)->first();
        $accCount = ($summMthOfficer->bil_a1 ?? 0) + ($summMthOfficer->bil_a2 ?? 0) + ($summMthOfficer->bil_a3 ?? 0) + ($summMthOfficer->bil_b1 ?? 0) + ($summMthOfficer->bil_b2 ?? 0) + ($summMthOfficer->bil_c1 ?? 0) + ($summMthOfficer->bil_c2 ?? 0) + ($summMthOfficer->bil_d ?? 0);
        $osB1D = ($summMthOfficer->rm_b1 ?? 0) + ($summMthOfficer->rm_b2 ?? 0) + ($summMthOfficer->rm_c1 ?? 0) + ($summMthOfficer->rm_c2 ?? 0) + ($summMthOfficer->rm_d ?? 0);
        $osAll = ($summMthOfficer->rm_a1 ?? 0) + ($summMthOfficer->rm_a2 ?? 0) + ($summMthOfficer->rm_a3 ?? 0) + ($summMthOfficer->rm_b1 ?? 0) + ($summMthOfficer->rm_b2 ?? 0) + ($summMthOfficer->rm_c1 ?? 0) + ($summMthOfficer->rm_c2 ?? 0) + ($summMthOfficer->rm_d ?? 0);
        $npfOs = $osAll > 0 ? round(($osB1D / $osAll) * 100, 2) : 0;

        if ($settInfo->pmgi_level == 'PM3') {
            $bankOfficerPmc = BankOfficer::whereOfficerId($settInfo->pmc_id)->first();
        } else {
            $bankOfficerPmc = NULL;
        }

        $pydInfo = SessionPydInfo::with('problemTable')->whereSessionId($sessionId)->first();
        $pymInfo = SessionPymInfo::whereSessionId($sessionId)->first();
        if ($settInfo->pmgi_level == 'PM3') {
            $pmcInfo = SessionPmcInfo::whereSessionId($sessionId)->first();
        } else {
            $pmcInfo = NULL;
        }

        $from = strtoupper(Carbon::parse($sessionInfo->session_date)->copy()->addMonthNoOverflow()->translatedFormat('F Y'));
        $to = strtoupper(Carbon::parse($sessionInfo->session_date)->copy()->addMonthNoOverflow(2)->translatedFormat('F Y'));

        // prestasi kumulatf var
        $report_date = Carbon::parse($settInfo->report_date);
        $fromReportDate = $report_date->copy()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d');
        $toReportDate = $report_date->copy()->endOfMonth()->format('Y-m-d');

        $data = DB::table('PMGI_SUMM_MTH_OFFICER')
                    ->where('officer_id', $settInfo->pyd_id)
                    ->whereBetween('report_date', [$fromReportDate, $toReportDate])
                    ->whereNotIn('incl_pmgi_flag', ['G'])
                    ->orderBy('report_date', 'asc')
                    ->get();

        $percentage = RefEvalPctg::where('state_code', $data->first()->branch_state_code)
                ->whereDate('effective_date', '<=', $toReportDate)
                ->orderBy('evaluation_id', 'ASC')
                ->get();

        // Calculate month names for each entry in the retrieved data
        $data->each(function ($item) {
            $item->month_name = Carbon::parse($item->report_date)->translatedFormat('F Y');
        });

        $paths = $this->htmlToImageService->generate(
            'pdf.prestasi_kumulatif',
            [
                'datas' => $data,
                'percentage' => $percentage,
            ],
            'pdf/prestasi_kumulatif/',
            "{$settInfo->pyd_id}_{$fromReportDate}_to_{$toReportDate}"
        );

        // Determine which template to use based on PMGI level
        $template = $settInfo->pmgi_level == 'PM3' ? 'pdf.borang_jpoc_pm3' : 'pdf.borang_jpoc_pm12';
        
        $pdf = Pdf::loadView($template, compact(
                'settInfo','bankOfficerPyd', 'state', 'branch', 'tempohBerkhidmat', 'alamat1', 'alamat2','summMthOfficer', 'accCount', 'osB1D', 'osAll', 'npfOs', 'sessionInfo', 'bankOfficerPym', 'bankOfficerPmc',
                'pydInfo', 'pymInfo', 'pmcInfo', 'from', 'to', 'paths'
            ))->setPaper('A4', 'portrait');

        $filePath = storage_path("app/temp/reports/borang_jpoc_{$formattedSessionId}_{$level}.pdf");
        $pdf->save($filePath);

        return $filePath;
    }

    public function render()
    {
        return view('livewire.module.rekod-pmgi', [
            'jt1Session' => $this->jt1Session
        ])->extends('layouts.main');
    }
}
