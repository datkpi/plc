<?php

namespace App\Enums;

enum PositionRankEnum: string
{
    case president = 'Chủ tịch';
    case director = 'Tổng giám đốc';
    case vice_director = 'Phó giám đốc';
    case manager = 'Trưởng ban';
    case vice_manager = 'Phó trưởng ban';
    case leader = 'Trưởng nhóm';
    case employee = 'Nhân viên';
    case other = 'Khác';
}