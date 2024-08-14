<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;

class HtmlToImageService
{
    public function generate(string $view, array $viewData, string $directory, string $fileName): array
    {
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

        // Save the HTML content to a temporary file
        file_put_contents($htmlPath, $htmlContent);

        // Convert the HTML to an image using wkhtmltoimage
        Artisan::call('convert:html-to-image', [
            'htmlPath' => $htmlPath,
            'imagePath' => $imagePath,
            '--quality' => 85,
            '--format' => 'png',
        ]);

        // Return both paths as an array
        return [
            'html' => $htmlPath,
            'image' => $imagePath,
        ];
    }
}