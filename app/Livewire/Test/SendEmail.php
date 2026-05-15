<?php

namespace App\Livewire\Test;

use App\Jobs\CleanupTemporaryFiles;
use App\Jobs\SendKeputusanPmgiPyd;
use App\Models\MntrSession;
use App\Models\SettPymPmc;
use Illuminate\Support\Facades\Bus;
use Livewire\Component;

class SendEmail extends Component
{
    public $sessionId;
    public $pydId;
    public $pmgiType;
    public $pmgiLevel;

    public function mount()
    {
        $this->sessionId = str_replace('-', '/', 'PMG32508/1/A_ZIKRI');
        $this->pmgiLevel = substr($this->sessionId, 3, 1);
        $this->pmgiType = substr($this->sessionId, 0, 2);
        $this->pydId = substr($this->sessionId, 13);
    }
    public function testSendEmail()
    {
        $setting = SettPymPmc::whereSessionId($this->sessionId)->first();
        $pyd_data = MntrSession::with('user', 'state', 'branch', 'bankOfficer')
                            ->whereOfficerId($this->pydId)
                            ->whereDate('report_date', $setting->report_date)
                            ->first();

        $pmgi_description = substr($pyd_data->pmgi_level, 0, 2) == 'PM' ? 'PMGI' : (substr($pyd_data->pmgi_level, 0, 2) == 'JT' ? 'JKPI' : (substr($pyd_data->pmgi_level, 0, 2) == 'HR' ? 'HR' : 'undefined'));

        $path = $this->generateImageFromHtml($pyd_data, $pmgi_description);
        // $email = $pyd_data->bankOfficer?->email;
        $email = 'farhan@csc.net.my';

        $this->sendEmail($email, $path['image'], $path['html']);
    }

    private function generateImageFromHtml($data, $pmgi_type)
    {
        return $this->htmlToImageService->generate(
            'emails.keputusan_pmgi',
            [
                'pmgi_session_date' => now()->format('d/m/Y'),
                'pyd_name' => $data->bankOfficer?->officer_name,
                'pyd_ic' => $data->bankOfficer?->nokp,
                'pyd_state' => $data->state->description,
                'pyd_branch' => $data->branch->branch_name,
                'pmgi_type' => $pmgi_type,
                'pmgi_level' => $this->pmgiLevel,
            ],
            'emails/pyd/',
            "email_pyd_{$this->pydId}"
        );
    }

    private function sendEmail($email, $imagePath, $htmlPath)
    {
        $jobs = [];

        if ($email) {
            $jobs[] = new SendKeputusanPmgiPyd($email, $imagePath, $htmlPath);
        }

        // Chain the cleanup job after the email jobs
        $jobs[] = new CleanupTemporaryFiles([$imagePath], [$htmlPath]);

        // Dispatch the jobs as a chain
        Bus::chain($jobs)->dispatch();
    }

    public function render()
    {
        return view('livewire.test.send-email');
    }
}
