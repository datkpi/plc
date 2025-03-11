<?php

namespace App\Http\Controllers\Personnel;

use App\Common\Traits\ApiResponses;
use App\Models\Candidate;
use App\Models\RequestForm;
use App\Repositories\Recruitment\CandidateRepository;
use App\Repositories\Recruitment\DepartmentRepository;
use App\Repositories\Recruitment\InterviewScheduleRepository;
use App\Repositories\Recruitment\RequestFormRepository;
use App\Repositories\Recruitment\UserRepository;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\TestRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    use ApiResponses;
    public function __construct(CandidateRepository $candidateRepo, InterviewScheduleRepository $interviewScheduleRepo, DepartmentRepository $departmentRepo, UserRepository $userRepo, RequestFormRepository $requestFormRepo)
    {
        $this->departmentRepo = $departmentRepo;
        $this->userRepo = $userRepo;
        $this->requestFormRepo = $requestFormRepo;
        $this->interviewScheduleRepo = $interviewScheduleRepo;
        $this->candidateRepo = $candidateRepo;
    }

    public function index()
    {
        return view('personnel/index');
    }

}
