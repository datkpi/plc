<?php

namespace App\Enums;

enum LanguageLevelEnum:string {
    case none = 'Không cần';
    case medium = 'Trung bình';
    case rather = 'Khá';
    case competently = 'Thành thạo';
}
