<?php

namespace App\Enums;

enum MailSystemEnum: string
{
    case recruitment = "Thư mời ứng tuyển";
    case invite_interview = 'Thư mời phỏng vấn';
    case thank_interview = 'Thư cám ơn phỏng vấn';
    case interview_result_success = 'Thư đạt phỏng vấn';
    case interview_result_fail = 'Thư không đạt phỏng vấn';
    case take_job = 'Thư mời nhận việc';
}