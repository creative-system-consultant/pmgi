<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class PdfToImageService
{
    public function generate(string $pydId, string $pathSessionId, string $pdfFile, string $pdfFileName): array
    {
        try {
            // Verify PDF file exists
            if (!file_exists($pdfFile)) {
                throw new Exception("PDF file not found: $pdfFile");
            }

            Log::info("PDF file found: $pdfFile");
            
            // Configure Ghostscript before conversion
            GhostscriptConfigService::configure();
            
            $outputPath = storage_path('app/public/pdfToImage/pmgi_session/' . $pydId . DIRECTORY_SEPARATOR . $pathSessionId);

            // Check if the directory exists, create it if it doesn't
            if (!file_exists($outputPath)) {
                mkdir($outputPath, 0777, true);
            }

            // Call the Artisan command to convert PDF to images
            $exitCode = Artisan::call('app:convert-pdf-to-image', [
                'pdfFile' => $pdfFile,
                'imagePath' => $outputPath,
                'fileName' => $pdfFileName,
            ]);

            // Check if command was successful
            if ($exitCode !== 0) {
                throw new Exception("PDF conversion command failed with exit code: $exitCode");
            }

            // Collect all generated image paths
            $pdfImages = [];
            $i = 1;
            
            while (true) {
                $imagePath = $outputPath . DIRECTORY_SEPARATOR . $pdfFileName . '_' . $i . '.jpg';
                
                if (file_exists($imagePath)) {
                    $pdfImages[] = $imagePath;
                    $i++;
                } else {
                    break; // No more pages
                }
            }

            if (empty($pdfImages)) {
                throw new Exception("No images were generated from PDF");
            }

            Log::info("Successfully collected " . count($pdfImages) . " image(s) from PDF conversion");

            return [
                'image' => $pdfImages,
                'extension' => 'pdf',
            ];

        } catch (Exception $e) {
            Log::error('PdfToImageService error: ' . $e->getMessage());
            throw $e;
        }
    }
}