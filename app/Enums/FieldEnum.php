<?php

namespace App\Enums;

enum FieldEnum:string {
    case finance = 'Kế toán';
    case business = 'Kinh doanh';
    case technique = 'Kỹ thuật';
    case other = 'Yêu cầu khác';
}
