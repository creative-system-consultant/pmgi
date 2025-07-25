<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LantikanUrusetiaNegeri extends Mailable
{
    use Queueable, SerializesModels;

    protected $base64ImageData;
    protected $fileName;
    protected $mimeType;

    public function __construct($base64ImageData, $fileName, $mimeType)
    {
        $this->base64ImageData = $base64ImageData;
        $this->fileName = $fileName;
        $this->mimeType = $mimeType;
        
        Log::info("LantikanUrusetiaNegeri Mail created with image data length: " . strlen($base64ImageData));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Urusetia Pelaksanaan PMGi di peringkat Cawangan',
        );
    }

    public function content(): Content
    {
        Log::info("LantikanUrusetiaNegeri Mail content() called");
        
        return new Content(
            view: 'emails.image_email_base',
            with: [
                'imageData' => $this->base64ImageData,
                'imageName' => $this->fileName,
                'imageMime' => $this->mimeType,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
