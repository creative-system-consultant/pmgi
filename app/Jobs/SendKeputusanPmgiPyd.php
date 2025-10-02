<?php

namespace App\Jobs;

use App\Mail\KeputusanPmgi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
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
        // ✅ Log paths before sending
        Log::info("📧 Sending Keputusan PMGI email", [
            'to' => $this->email,
            'imagePath' => $this->imagePath,
            'reportPaths' => $this->reportPaths,
            'fileExists' => file_exists($this->imagePath) ? 'yes' : 'no'
        ]);

        try {
            Mail::to($this->email)
                ->send(new KeputusanPmgi($this->imagePath, $this->reportPaths));

            Log::info("✅ Email sent successfully to {$this->email}");
        } catch (\Throwable $e) {
            Log::error("❌ Failed to send Keputusan PMGI email", [
                'to' => $this->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
