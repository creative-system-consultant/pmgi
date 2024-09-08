<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JttHr extends Mailable
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
            subject: 'Makluman Permohonan Pelaksanaan Tindakan Tatatertib Berdasarkan Keputusan Jawatankuasa Timbang Tara (PMGi)',
        );
    }

    public function content(): Content
    {
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
