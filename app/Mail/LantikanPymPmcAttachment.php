<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LantikanPymPmcAttachment extends Mailable
{
    use Queueable, SerializesModels;

    public $fileUrl;
    public $type;
    protected $imagePath;

    public function __construct($fileUrl, $type, $imagePath)
    {
        $this->fileUrl = $fileUrl;
        $this->type = $type;
        $this->imagePath = $imagePath;
        
        Log::info("LantikanPymPmcAttachment Mail created with image: {$imagePath}");
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
        return new Content(
            html: '
                <h2>Lantikan PMGi</h2>
                <p>Sila lihat lampiran untuk maklumat lantikan anda.</p>
                <p>Terima kasih.</p>
            '
        );
    }

    public function attachments(): array
    {
        $attachments = [
            Attachment::fromPath(public_path($this->fileUrl)),
        ];
        
        // Add image as attachment if it exists
        if (file_exists($this->imagePath)) {
            $attachments[] = Attachment::fromPath($this->imagePath)
                ->as('Lantikan_Details.jpg')
                ->withMime('image/jpeg');
        }
        
        return $attachments;
    }
} 