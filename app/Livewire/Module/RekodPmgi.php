<?php

namespace App\Livewire\Module;

use App\Models\BankOfficer;
use App\Models\BnmStatecode;
use App\Models\Branch;
use App\Models\JttSessionInfo;
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
use App\Services\PdfToImageService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class RekodPmgi extends Component
{
    private $htmlToImageService;
    private $pdfToImageService;

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
        $this->pdfToImageService = new PdfToImageService();
    }

    public function mount()
    {
        // Initialize $allSession as an empty collection
        $this->allSession = new Collection();
        $this->jt1Session = new Collection();
        $this->jt2Session = new Collection();

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
        $this->pydId = BankOfficer::join('pmgi_fms_users', 'pmgi_fms_bank_officers.officer_id', '=', 'pmgi_fms_users.USERID')
            ->where('pmgi_fms_users.USERSTATUS', 1)
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

        $this->jt2Session = JttSessionParticipant::with([
            'sessionInfo',
            'mntrSession' => function ($query) use ($userId) {
                $query->where('officer_id', $userId); // Apply condition to mntrSession
            }
        ])
        ->whereUserId($this->pydId) // Filter on the main JttSessionParticipant model
        ->where('pmgi_level', 'JT2')
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
        $tarikhLantikan = $bankOfficerPyd->hrData ? $bankOfficerPyd->hrData->tarikh_lantikan->format('d/m/Y') : 'Tiada maklumat';
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

        $percentage = RefEvalPctg::where('state_code', $data->first()?->branch_state_code)
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
        $attachmentExtension = [];
        $imageExtensions = ['png', 'jpg', 'jpeg'];
        $pdfExtension = 'pdf';

        $pathSessionId = str_replace('/', '-', $sessionId);
        
        // Get the directory where the chart image is stored (this directory works)
        $tempDirectory = dirname($paths['image']);
        
        if ($pydInfo) 
        {
            $pydAttachments = [
                'attachment' => $pydInfo->attachment,
                'attachment2' => $pydInfo->attachment2,
                'attachment3' => $pydInfo->attachment3,
            ];

            foreach ($pydAttachments as $key => $attachmentPath) {
                if (!empty($attachmentPath)) {
                    $attachmentKey = 'pyd_' . $key;
                    
                    $this->processAttachment(
                        $attachmentPath,
                        $attachmentKey,
                        $tempDirectory,
                        $settInfo->pyd_id,
                        $pathSessionId,
                        $attachmentPaths,
                        $attachmentExtension,
                        $tempAttachmentFiles
                    );
                }
            }
        }
        
        if ($pymInfo) {
            $pymAttachments = [
                'attachment' => $pymInfo->attachment,
                'attachment2' => $pymInfo->attachment2,
                'attachment3' => $pymInfo->attachment3,
            ];

            foreach ($pymAttachments as $key => $attachmentPath) {
                if (!empty($attachmentPath)) {
                    $attachmentKey = 'pym_' . $key;
                    
                    $this->processAttachment(
                        $attachmentPath,
                        $attachmentKey,
                        $tempDirectory,
                        $settInfo->pyd_id,
                        $pathSessionId,
                        $attachmentPaths,
                        $attachmentExtension,
                        $tempAttachmentFiles
                    );
                }
            }
        }
        
        if ($pmcInfo) {
            $pmcAttachments = [
                'attachment' => $pmcInfo->attachment,
                'attachment2' => $pmcInfo->attachment2,
                'attachment3' => $pmcInfo->attachment3,
            ];

            foreach ($pmcAttachments as $key => $attachmentPath) {
                if (!empty($attachmentPath)) {
                    $attachmentKey = 'pmc_' . $key;
                    
                    $this->processAttachment(
                        $attachmentPath,
                        $attachmentKey,
                        $tempDirectory,
                        $settInfo->pyd_id,
                        $pathSessionId,
                        $attachmentPaths,
                        $attachmentExtension,
                        $tempAttachmentFiles
                    );
                }
            }
        }
        
        $pdf = Pdf::loadView($template, compact(
                'settInfo','bankOfficerPyd', 'state', 'branch', 'tarikhLantikan', 'tempohBerkhidmat', 'alamat1', 'alamat2','summMthOfficer', 'accCount', 'osB1D', 'osAll', 'npfOs', 'sessionInfo', 'bankOfficerPym', 'bankOfficerPmc',
                'pydInfo', 'pymInfo', 'pmcInfo', 'from', 'to', 'paths', 'attachmentPaths', 'attachmentExtension', 'imageExtensions', 'pdfExtension'
            ))->setPaper('A4', 'portrait');

        // Store the PDF content in a variable before cleanup
        $pdfContent = $pdf->output();

        // Clean up the temporary files after the PDF has been generated
        if (file_exists($paths['html'])) {
            unlink($paths['html']);
        }

        if (file_exists($paths['image'])) {
            unlink($paths['image']);
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

    public function streamAttachment(Request $request)
    {
        try {
            $path = decrypt($request->path);
            $filePath = storage_path('app/public/' . $path);
            
            if (!file_exists($filePath)) {
                abort(404);
            }
            
            return response()->file($filePath);
            
        } catch (\Exception $e) {
            Log::error('Failed to stream attachment: ' . $e->getMessage());
            abort(404);
        }
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

    private function processAttachment(string $attachmentPath, string $attachmentKey, string $tempDirectory, string $pydId,
        string $pathSessionId, array &$attachmentPaths, array &$attachmentExtension, array &$tempAttachmentFiles): void {
        
        $originalPath = storage_path('app/public/' . $attachmentPath);
        
        if (!file_exists($originalPath)) {
            Log::warning("Attachment file not found: {$originalPath}");
            return;
        }
        
        $originalName = strtoupper(pathinfo($originalPath, PATHINFO_FILENAME));
        $extension = strtolower(pathinfo($originalPath, PATHINFO_EXTENSION));
        
        // Copy to temp directory
        $tempFileName = $attachmentKey . '_' . time() . '_' . basename($originalPath);
        $tempPath = $tempDirectory . DIRECTORY_SEPARATOR . $tempFileName;
        
        if (!copy($originalPath, $tempPath)) {
            Log::error("Failed to copy attachment to temp directory: {$originalPath}");
            return;
        }
        
        $tempAttachmentFiles[] = $tempPath; // Track for cleanup
        
        // Process based on file type
        if ($extension === 'pdf') {
            try {
                // Convert PDF to images
                $convertImage = $this->pdfToImageService->generate($pydId, $pathSessionId, $tempPath, $originalName);
                
                $attachmentPaths[$attachmentKey] = $convertImage['image'];
                $attachmentExtension[$attachmentKey] = $convertImage['extension'];
                
                Log::info("PDF converted to images for {$attachmentKey}");
                
            } catch (\Exception $e) {
                Log::error("PDF conversion failed for {$attachmentKey}: " . $e->getMessage());
                
                // Fallback: use original PDF path
                $attachmentPaths[$attachmentKey] = $tempPath;
                $attachmentExtension[$attachmentKey] = 'pdf';
            }
        } else {
            // For images, use the temp path directly
            $attachmentPaths[$attachmentKey] = $tempPath;
            $attachmentExtension[$attachmentKey] = $extension;
            
            Log::info("Image attachment processed for {$attachmentKey}");
        }
    }

    public function render()
    {
        return view('livewire.module.rekod-pmgi', [
            'jt1Session' => $this->jt1Session,
            'jt2Session' => $this->jt2Session
        ])->extends('layouts.main');
    }
}
