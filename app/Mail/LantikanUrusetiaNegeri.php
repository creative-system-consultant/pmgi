<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LantikanUrusetiaNegeri extends Mailable
{
    use Queueable, SerializesModels;

    public $filepath;

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Urusetia Pelaksanaan PMGi di peringkat Cawangan',
        );
    }

    public function content(): Content
    {
        // Check if file exists before trying to read it
        if (!file_exists($this->filepath)) {
            throw new \Exception("Image file not found: {$this->filepath}");
        }

        // Check if file is readable
        if (!is_readable($this->filepath)) {
            throw new \Exception("Image file is not readable: {$this->filepath}");
        }

        $imageData = file_get_contents($this->filepath);
        $imageName = basename($this->filepath);
        $imageMime = mime_content_type($this->filepath);

        return new Content(
            view: 'emails.image_email_base',
            with: [
                'imageData' => base64_encode($imageData),
                'imageName' => $imageName,
                'imageMime' => $imageMime,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
