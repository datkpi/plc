<?php

namespace App\Enums;

enum RecruitmentTypeEnum:string {
    case change = 'Tuyển thay thế';
    case new = 'Tuyển mới';
    case parttime = 'Thời vụ';
}
