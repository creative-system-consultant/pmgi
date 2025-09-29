<?php

namespace App\Jobs;

use App\Mail\KeputusanPmgi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendKeputusanPmgiPyd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $imagePath;
    protected $reportPaths;

    public function __construct($email, $imagePath, $reportPaths)
    {
        $this->email = $email;
        $this->imagePath = $imagePath;
        $this->reportPaths = $reportPaths;
    }

    public function handle(): void
    {
        Mail::to($this->email)
            ->send(new KeputusanPmgi($this->imagePath, $this->reportPaths));
    }
}
