<?php

namespace App\Enums;

enum ProbationResultEnum: string
{
    case obtain = 'Đạt';
    case not_obtain = 'Không đạt';
    case again = 'Kéo dài'; //thực tập thêm vì chưa đạt sau 2 tháng
}