<?php

namespace App\Jobs;

use App\Mail\HtmlEmailMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendHtmlEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $htmlContent;

    public function __construct($to, $htmlContent)
    {
        $this->to = $to;
        $this->htmlContent = $htmlContent;
    }

    public function handle()
    {
        Mail::to($this->to)->send(new HtmlEmailMailable($this->htmlContent));
    }
}