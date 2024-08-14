<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanupTemporaryFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $imagePaths;
    protected $htmlPaths;
    protected $fileUrl;

    public function __construct($imagePaths, $htmlPaths, $fileUrl = null)
    {
        $this->imagePaths = $imagePaths;
        $this->htmlPaths = $htmlPaths;
        $this->fileUrl = $fileUrl;
    }

    public function handle(): void
    {
        // Cleanup image files
        foreach ($this->imagePaths as $imagePath) {
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Cleanup HTML files
        foreach ($this->htmlPaths as $htmlPath) {
            if (file_exists($htmlPath)) {
                unlink($htmlPath);
            }
        }

        // Cleanup Excel file
        if($this->fileUrl) {
            $filePath = public_path($this->fileUrl);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }
}
