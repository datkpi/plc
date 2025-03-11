<?php

namespace App\Enums;

enum RecruitmentChangeEnum:string {
    case rest = 'Nghỉ việc';
    case promote = 'Thăng chức';    //Thăng chức
    case layoff = 'Bị sa thải';     //Sa thải
    case transfer = 'Điều chuyển';  //Điều chuyển
    case retirement = 'Nghỉ hưu';   //Nghỉ hưu
    case other = 'Lý do khác';      //Lý do khác
}
