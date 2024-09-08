<?php

namespace App\Jobs;

use App\Mail\JttHr;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendJttHrEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $imagePath;
    protected $htmlPath;

    public function __construct($email, $imagePath)
    {
        $this->email = $email;
        $this->imagePath = $imagePath;
    }

    public function handle(): void
    {
        Mail::to($this->email)
            ->send(new JttHr($this->imagePath));
    }
}
