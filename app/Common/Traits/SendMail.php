<?php

namespace App\Common\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait SendEmail
{
     // $emailThis = $this->sendMail($emailTemplate, ['emailData' => $emailData], $emailSubject, $emailTo);
     public function sendMail ($emailTemplate,$emailData, $emailSubject, $emailTo, $emailBcc, $emailCc) {
        Mail::send($emailTemplate,  $emailData, function($message) use ($emailData, $emailSubject, $emailTo)
            {
                $message->to($emailTo)
                ->subject($emailSubject);
            });

        if (Mail::failures()) {
            return false;
        } else {
            return true;
        }
     }

     public function sendMailWithHtml ($emailTemplate,$emailData, $emailSubject, $emailTo) {
        Mail::send($emailTemplate,  $emailData, function($message) use ($emailData, $emailSubject, $emailTo)
            {
                $message->to($emailTo)
                ->subject($emailSubject);
            });

        if (Mail::failures()) {
            return false;
        } else {
            return true;
        }
     }
}
