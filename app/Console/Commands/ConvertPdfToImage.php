<?php

namespace App\Console\Commands;

use App\Services\GhostscriptConfigService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Spatie\PdfToImage\Pdf;

class ConvertPdfToImage extends Command
{
    protected $signature = 'app:convert-pdf-to-image {pdfFile} {imagePath} {fileName}';

    protected $description = 'Convert PDF to Image using Imagick and Ghostscript';

    public function handle()
    {
        try {
            // Configure Ghostscript ONLY when this command runs
            GhostscriptConfigService::configure();
            
            $pdfFile = $this->argument('pdfFile');
            $imagePath = $this->argument('imagePath');
            $fileName = $this->argument('fileName');
            
            // Verify PDF file exists
            if (!file_exists($pdfFile)) {
                $this->error("PDF file not found: $pdfFile");
                Log::error("PDF file not found: $pdfFile");
                return Command::FAILURE;
            }
            
            $this->info("PDF file found: $pdfFile");
            Log::info("PDF file found: $pdfFile");
            
            // Check if the output directory exists, create it if it doesn't
            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0777, true);
                $this->info("Created output directory: $imagePath");
            }
            
            // Convert PDF to images using Spatie
            $pdf = new Pdf($pdfFile);
            $numberOfPages = $pdf->pageCount();
            
            $this->info("PDF has {$numberOfPages} pages");
            Log::info("PDF has {$numberOfPages} pages");
            
            $convertedCount = 0;
            $skippedCount = 0;
            
            for ($i = 1; $i <= $numberOfPages; $i++) {
                $outputImage = $imagePath . DIRECTORY_SEPARATOR . $fileName . '_' . $i . '.jpg';
                
                if (!file_exists($outputImage)) {
                    // Convert page to image
                    $pdf->selectPage($i)->save($outputImage);
                    
                    // Verify image was created and has content
                    if (!file_exists($outputImage)) {
                        throw new Exception("Image file was not created: $outputImage");
                    }
                    
                    if (filesize($outputImage) == 0) {
                        throw new Exception("Image file is empty: $outputImage");
                    }
                    
                    $convertedCount++;
                    $this->info("Converted page {$i}: $outputImage");
                    Log::info("Image conversion successful: $outputImage");
                } else {
                    $skippedCount++;
                    $this->info("Skipped page {$i}: Image already exists");
                    Log::info("Same image conversion existed. Just load the existing one: $outputImage");
                }
            }
            
            $this->info("Conversion complete!");
            $this->info("Converted: {$convertedCount} pages");
            $this->info("Skipped: {$skippedCount} pages");
            
            Log::info("PDF conversion completed. Converted: {$convertedCount}, Skipped: {$skippedCount}");
            
            return Command::SUCCESS;
            
        } catch (Exception $e) {
            $this->error('PDF conversion failed: ' . $e->getMessage());
            Log::error('ConvertPdfToImage command error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}
