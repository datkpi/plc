<?php

namespace App\Enums;

enum RecruitmentResultEnum: string
{
    case salary_deal = 'Deal lương';
    case take_job = 'Nhận việc';
    case reject_take_job = 'Không nhận việc';
}
