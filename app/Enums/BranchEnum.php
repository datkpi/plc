<?php

namespace App\Enums;

enum BranchEnum: string
{
    case formal = 'Chính quy';
    case level_transfer = 'Chuyển cấp';
    case bachelor = 'Cử nhân';
    case remote = 'Đào tạo từ xa qua mạng';
    case in_service = 'Tại chức';
    case concentration = 'Học tập trung';
    case mid_level = 'Trung cấp';
    case university_transfer = 'Liên thông';
    case e_learning = 'Vừa học vừa làm';
    case complementary = 'Bổ túc';
    case x = 'x';
}
