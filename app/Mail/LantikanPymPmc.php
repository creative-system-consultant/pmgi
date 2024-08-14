<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LantikanPymPmc extends Mailable
{
    use Queueable, SerializesModels;

    public $fileUrl;
    public $type;
    public $filepath;

    public function __construct($fileUrl, $type, $filepath)
    {
        $this->fileUrl = $fileUrl;
        $this->type = $type;
        $this->filepath = $filepath;
    }

    public function envelope(): Envelope
    {
        ($this->type == 'pym') ? $title = 'Pegawai Yang Menilai' : $title = 'Pegawai Mudah Cara';

        return new Envelope(
            subject: 'Lantikan sebagai '. $title .' bagi Pelaksanaan PMGi',
        );
    }

    public function content(): Content
    {
        $imageData = file_get_contents($this->filepath);
        $imageName = basename($this->filepath);
        $imageMime = mime_content_type($this->filepath);

        return new Content(
            view: 'emails.lantikan_pym_pmc_image',
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
            Attachment::fromPath(public_path($this->fileUrl)),
        ];
    }
}
