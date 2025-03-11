<?php

namespace App\Http\Controllers\Module;

use App\Enums\GenderEnum;
use App\Repositories\Recruitment\DepartmentRepository;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\ApproveRepository;
use App\Repositories\Recruitment\UserRepository;
use App\Repositories\Recruitment\PositionRepository;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Common\FileUploads;

class ModuleController extends Controller
{
    public function __construct(DepartmentRepository $departmentRepo, ApproveRepository $approveRepo, UserRepository $userRepo, PositionRepository $positionRepo)
    {
        $this->approveRepo = $approveRepo;
        $this->userRepo = $userRepo;
        $this->positionRepo = $positionRepo;
        $this->departmentRepo = $departmentRepo;
    }

    public function index()
    {
        return view('module/index');
    }

}
