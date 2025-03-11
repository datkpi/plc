<?php

namespace App\Enums;

enum InterviewResultEnum:string {
    case obtain = 'Đạt';
    case not_obtain = 'Không đạt';
    case save = 'Lưu hồ sơ';
    case consider = 'Xem xét';
    case remove = 'Loại hồ sơ';
}
