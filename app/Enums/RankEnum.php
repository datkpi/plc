<?php

namespace App\Enums;

enum RankEnum: string
{
    case excellent = 'Xuất sắc';
    case good = 'Giỏi';
    case rather = 'Khá';
    case above_average = 'Trung bình khá';
    case medium = 'Trung bình';
    case not_graduated = 'Chưa tốt nghiệp';
    case studing = 'Đang học';
    case other = 'x';
}