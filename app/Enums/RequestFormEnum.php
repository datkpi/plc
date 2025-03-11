<?php

namespace App\Enums;

enum RequestFormEnum: string
{
    case plan = 'Lên kế hoạch';
    case approving = 'Đang duyệt';
    case approved = 'Đã duyệt';
    case process = 'Đang thực hiện';
    case success = 'Hoàn thành';
    case cancel = 'Huỷ';
}