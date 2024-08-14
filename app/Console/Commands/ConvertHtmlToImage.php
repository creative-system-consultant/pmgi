<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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

        // Construct the command to run wkhtmltoimage with the desired options
        $command = '"C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltoimage.exe" --quality ' . $quality . ' --format ' . $format . ' "' . $htmlPath . '" "' . $imagePath . '"';

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info("Image created successfully at {$imagePath}");
        } else {
            $this->error("Failed to create image. Error: " . implode("\n", $output));
        }
    }
}
