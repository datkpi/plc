<?php

namespace App\Enums;

enum MailStatusEnum:string {
    case draft = 'Nháp';
    case sent = 'Đã gửi';
}
