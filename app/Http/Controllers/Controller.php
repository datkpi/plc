<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Common\Traits\GetEnumDatas;
use App\Common\Traits\UploadFiles;
use App\Common\StringHelpers;

class Controller extends BaseController
{
    //use UploadImage, UploadImages;
    use AuthorizesRequests, ValidatesRequests;
}
