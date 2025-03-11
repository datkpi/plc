<?php

namespace App\Enums;

enum ExperienceEnum:string {
    case none = 'Không cần';
    case one_to_two = '1 đến 2 năm';
    case three_to_five = '3 đến 5 năm';
    case other = 'Yêu cầu khác';
}
