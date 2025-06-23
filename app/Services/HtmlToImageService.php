<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Exception;

class HtmlToImageService
{
    public function generate(string $view, array $viewData, string $directory, string $fileName): array
    {
        try {
            // Generate the HTML content from the Blade view
            $htmlContent = view($view, $viewData)->render();

            // Define the directory and file paths with unique filenames
            $directoryPath = storage_path('app/public/' . $directory);
            $htmlPath = $directoryPath . $fileName . '.html';
            $imagePath = $directoryPath . $fileName . '.png';

            // Check if the directory exists, and create it if it doesn't
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }

            // Save the HTML content to a file
            file_put_contents($htmlPath, $htmlContent);

            // Verify HTML file was created
            if (!file_exists($htmlPath)) {
                throw new Exception("Failed to create HTML file: $htmlPath");
            }

            Log::info("HTML file created: $htmlPath");

            // Call the Artisan command to convert HTML to image
            $exitCode = Artisan::call('convert:html-to-image', [
                'htmlPath' => $htmlPath,
                'imagePath' => $imagePath,
                '--quality' => 85,
                '--format' => 'png'
            ]);

            // Check if image file was created (ignore exit code since wkhtmltoimage often returns 1 even on success)
            if (!file_exists($imagePath)) {
                throw new Exception("Image file was not created: $imagePath");
            }

            // Verify image file has content
            if (filesize($imagePath) == 0) {
                throw new Exception("Image file is empty: $imagePath");
            }

            Log::info("Image conversion successful: $imagePath");

            return [
                'html' => $htmlPath,
                'image' => $imagePath
            ];

        } catch (Exception $e) {
            Log::error('HtmlToImageService error: ' . $e->getMessage());
            throw $e;
        }
    }
}
