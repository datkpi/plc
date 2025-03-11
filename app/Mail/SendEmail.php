<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable implements ShouldQueue
// class SendEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $file;
    public $subject;
    public $content;
    public $from;
    public $to;
    public $cc;
    public $bcc;
    /**
     * Create a new message instance.
     */
    public function __construct($subject = null, $content = null, $from = [], $to = [], $cc = [], $bcc = [], $file = [])
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->from = $from;
        $this->to = $to;
        $this->cc = $cc;
        $this->bcc = $bcc;
        $this->file = $file;
    }

    public function build()
    {
        $email = $this->subject($this->subject)->html($this->content);

        // if ($this->to) {
        //     $email->to($this->to);
        // }
        // dd($email);

        // if (!empty($this->cc)) {
        //     foreach($this->cc as $c){
        //        $email->cc($c);
        //     }
        // }

        if (!empty($this->bcc)) {
            $email->bcc($this->bcc);
        }

        if (!empty($this->file)) {
            foreach ($this->file as $file) {
                $email->attach($file);
            }
        }
        // dd($email);
        return $email;
    }
}
