<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JttMeetingInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $officer;
    public $roleName;
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lantikan Sebagai Ahli Panel Jawatankuasa Timbang Tara',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.jtt-meeting-invitation'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    private function getRoleName($role)
    {
        $roles = [
            '1' => 'PENGERUSI',
            '2' => 'PENGERUSI GANTIAN',
            '3' => 'AHLI-AHLI',
            '4' => 'AHLI-AHLI GANTIAN',
            '5' => 'PEMBENTANG',
            '6' => 'URUSETIA',
            '7' => 'PENGERUSI BERSAMA (PERHEBAT)',
            '8' => 'AHLI (PERHEBAT)',
        ];

        return $roles[$role] ?? 'Unknown Role';
    }
}
