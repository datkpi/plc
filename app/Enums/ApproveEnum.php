<?php

namespace App\Enums;

enum ApproveEnum: string
{
    case plan = 'Lên kế hoạch';
    case approve = 'Duyệt';
    case undo = 'Hoàn tác';
    case cancel = 'Huỷ';
    case process = 'Đang xử lý';
    case success = 'Hoàn thành';
}
