<?php

namespace App\Common;

class ConvertHelpers
{
    public static function arrayObjToArray($data)
    {
        return $data = array_map(function ($status) {
            return [
                'name' => $status->name,
                'value' => $status->value
            ];
        }, $data);
    }


}