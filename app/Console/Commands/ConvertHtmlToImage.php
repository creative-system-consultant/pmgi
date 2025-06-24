<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class ConvertHtmlToImage extends Command
{
    protected $signature = 'convert:html-to-image {htmlPath} {imagePath} {--quality=90} {--format=png}';

    protected $description = 'Convert HTML to Image using wkhtmltoimage';

    public function handle()
    {
        $htmlPath = $this->argument('htmlPath');
        $imagePath = $this->argument('imagePath');
        $quality = $this->option('quality');
        $format = $this->option('format');

        // Check if HTML file exists
        if (!file_exists($htmlPath)) {
            $this->error("HTML file not found: {$htmlPath}");
            return 1;
        }

        // Check if wkhtmltoimage exists
        $wkhtmlPath = 'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltoimage.exe';
        if (!file_exists($wkhtmlPath)) {
            $this->error("wkhtmltoimage not found at: {$wkhtmlPath}");
            return 1;
        }

        // Create the process with proper command structure
        $command = [
            $wkhtmlPath,
            '--quality', $quality,
            '--format', $format,
            '--width', '1400',
            '--disable-smart-width',
            '--enable-local-file-access',
            '--minimum-font-size', '12',
            '--javascript-delay', '1000',
            '--no-stop-slow-scripts',
            '--debug-javascript',
            $htmlPath,
            $imagePath
        ];

        try {
            // Create process with 30 second timeout
            $process = new Process($command);
            $process->setTimeout(30);
            
            $this->info("Starting image conversion...");
            $process->run();

            // Check if process was successful
            if ($process->isSuccessful()) {
                // Verify the image file was actually created
                if (file_exists($imagePath) && filesize($imagePath) > 0) {
                    $this->info("Image created successfully at {$imagePath}");
                    return 0;
                } else {
                    $this->error("Image file was not created or is empty");
                    return 1;
                }
            } else {
                $this->error("Process failed with exit code: " . $process->getExitCode());
                $this->error("Error output: " . $process->getErrorOutput());
                return 1;
            }

        } catch (ProcessTimedOutException $e) {
            $this->error("Process timed out after 30 seconds");
            return 1;
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }
}
