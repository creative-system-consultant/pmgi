<?php

namespace App\Jobs;

use App\Mail\LantikanPymPmc;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLantikanPymPmcEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pym;
    protected $pmc;
    protected $fileUrl;
    protected $type;
    protected $imagePath;
    protected $htmlPath;

    public function __construct($pym, $pmc, $fileUrl, $type, $imagePath, $htmlPath)
    {
        $this->pym = $pym;
        $this->pmc = $pmc;
        $this->fileUrl = $fileUrl;
        $this->type = $type;
        $this->imagePath = $imagePath;
        $this->htmlPath = $htmlPath;
    }

    public function handle(): void
    {
        Mail::to($this->type == 'pym' ? $this->pym : $this->pmc)
            ->send(new LantikanPymPmc($this->fileUrl, $this->type, $this->imagePath));
    }
}
