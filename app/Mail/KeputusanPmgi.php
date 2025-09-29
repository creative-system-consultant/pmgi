<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KeputusanPmgi extends Mailable
{
    use Queueable, SerializesModels;

    public $filepath;
    public $reportPaths;
    
    public function __construct($filepath, $reportPaths)
    {
        $this->filepath = $filepath;
        $this->reportPaths = $reportPaths;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $pmgi_level = [
            [
                'type' => 'PM1',
                'name' => 'PMGI 1',
            ],
            [
                'type' => 'PM2',
                'name' => 'PMGI 2',
            ],
            [
                'type' => 'PM3',
                'name' => 'PMGI 3',
            ],
            [
                'type' => 'JT1',
                'name' => 'JKPI 1',
            ],
            [
                'type' => 'JT2',
                'name' => 'JKPI 2',
            ],
            [
                'type' => 'HRD',
                'name' => 'HRD',
            ],
        ];

        return new Envelope(
            subject: 'Keputusan Pmgi',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $imageData = file_get_contents($this->filepath);
        $imageName = basename($this->filepath);
        $imageMime = mime_content_type($this->filepath);

        return new Content(
            view: 'emails.keputusan_pmgi',
            with: [
                'imageData' => base64_encode($imageData),
                'imageName' => $imageName,
                'imageMime' => $imageMime,
            ]
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath(public_path($this->filepath)),
        ];
    }
}
