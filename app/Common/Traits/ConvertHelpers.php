<?php

namespace App\Common\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ConvertHelpers
{
    protected function convertStringToDate($string)
    {
        $date = \DateTime::createFromFormat('d/m/Y', $string);
        $errors = \DateTime::getLastErrors();

        if ($errors['warning_count'] > 0 || $errors['error_count'] > 0) {
            return null; // Chuỗi không hợp lệ
        }
        return $date;
    }
}
