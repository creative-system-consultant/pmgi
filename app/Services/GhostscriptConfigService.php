<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class GhostscriptConfigService
{
    public static function configure(): void
    {
        // Only configure once per request
        static $configured = false;
        
        if ($configured) {
            return;
        }

        // Check if running on Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $gsPath = config('services.ghostscript.path');
            
            // Verify Ghostscript exists
            if (file_exists($gsPath . '/gs.exe')) {
                $currentPath = getenv('PATH') ?: '';
                putenv('PATH=' . $gsPath . ';' . $currentPath);
                
                // Also set MAGICK_GHOSTSCRIPT_PATH for Imagick
                putenv('MAGICK_GHOSTSCRIPT_PATH=' . $gsPath . '/gs.exe');
                
                Log::info('Ghostscript path configured: ' . $gsPath);
            } else {
                Log::warning('Ghostscript not found at: ' . $gsPath);
            }
        }
    }
}