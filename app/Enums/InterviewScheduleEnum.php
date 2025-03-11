<?php

namespace App\Enums;

enum InterviewScheduleEnum: string
{
    case active = 'Hoạt động';
    case feedback = 'Đánh giá';
    case success = 'Hoàn thành';
    case cancel = 'Huỷ';
}