<?php
namespace App\Enums;

enum AlertType: string {
    case BOOLEAN = 'boolean';  // Cho giá trị true/false
    case PARAMETER = 'parameter'; // Cho các tham số
}
