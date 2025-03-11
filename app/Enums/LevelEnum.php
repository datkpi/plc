<?php

namespace App\Enums;

enum LevelEnum: string
{
    case doctor = 'Tiến sĩ';
    case masters = 'Thạc sĩ';
    case university = 'Đại học';
    case college = 'Cao đẳng';
    case mid = 'Trung cấp';
    case primary = 'Sơ cấp';
    case high_school = 'THPT';
    case mid_school = 'THCS';
    case other = 'Yêu cầu khác';
    case x = 'x';
}