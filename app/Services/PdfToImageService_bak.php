<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Spatie\PdfToImage\Pdf;

class PdfToImageService
{
    public function generate(string $pydId, string $pathSessionId, string $pdfFile, string $pdfFileName): array
    {
        try {
            // Verify HTML file was created
            if (!file_exists($pdfFile)) {
                throw new Exception("PDF file not found: $pdfFile");
            }

            Log::info("PDF file found: $pdfFile");
            
            $outputPath = storage_path('app/public/pdfToImage/pmgi_session/' . $pydId . DIRECTORY_SEPARATOR . $pathSessionId);

            // Check if the directory exists, and create it if it doesn't
            if (!file_exists($outputPath)) {
                mkdir($outputPath, 0777, true);
            }

            // Call the Artisan command to convert PDF to image
            $exitCode = Artisan::call('app:convert-pdf-to-image', [
                'pdfFile' => $pdfFile,
                'imagePath' => $outputPath,
                'fileName' => $pdfFileName,
            ]);

            // Convert PDF to images
            $pdf = new Pdf($pdfFile);
            $numberOfPages = $pdf->pageCount();
            
            $pdfImages = [];
            for ($i = 1; $i <= $numberOfPages; $i++) {
                $outputImage = $outputPath . DIRECTORY_SEPARATOR . $pdfFileName . '_' . $i . '.jpg';

                if(!file_exists($outputImage))
                {
                    $pdf->selectPage($i)->save($outputImage);
                    $pdfImages[] = $outputPath . DIRECTORY_SEPARATOR . $pdfFileName . '_' . $i . '.jpg';
                    Log::info("Image conversion successful: $outputImage");
                }
                else
                {
                    $pdfImages[] = $outputPath . DIRECTORY_SEPARATOR . $pdfFileName . '_' . $i . '.jpg';
                    Log::info("Same image conversion existed. Just load the existing one.");
                }
            }

            // Check if image file was created (ignore exit code since wkhtmltoimage often returns 1 even on success)
            if (!file_exists($outputImage)) {
                throw new Exception("Image file was not created: $outputImage");
            }

            // Verify image file has content
            if (filesize($outputImage) == 0) {
                throw new Exception("Image file is empty: $outputImage");
            }

            $pdfFile = $pdfImages;
            $extension = 'pdf';

            return [
                'image' => $pdfFile,
                'extension' => $extension,
            ];

        } catch (Exception $e) {
            Log::error('PdfToImageService error: ' . $e->getMessage());
            throw $e;
        }
    }
}