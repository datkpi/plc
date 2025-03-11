<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomDatabaseMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $htmlContent;

    public function __construct($htmlContent)
    {
        $this->htmlContent = $htmlContent;
    }

    public function build()
    {
        return $this->subject('Your Subject Here') // Optional
            ->html($this->htmlContent); // Here we use the HTML content directly
    }
}