<?php

namespace App\Http\Controllers\Recruitment;

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
        $approveCount = $this->requestFormRepo->totalApprove();
        $candidateCount = $this->candidateRepo->countCandidating();
        $interviewScheduleCount = $this->interviewScheduleRepo->countInterviewing('interviewer', Auth::user()->id);
        $userCount = $this->userRepo->count();
        $users = $this->userRepo->getAll(8);
        return view('recruitment/index', compact('users', 'approveCount', 'candidateCount', 'interviewScheduleCount', 'userCount'));
    }

    public function getData()
    {
        // $recruitmentData = RequestForm::with(['department', 'position'])
        //     ->select(
        //         'id',
        //         'department_id',
        //         'position_id',
        //         'quantity',
        //         'request_date'
        //     )
        //     ->get();

        // $formattedData = $recruitmentData->map(function ($item) {
        //     $formattedDate = Carbon::parse($item->request_date)->format('D M d Y H:i:s \G\M\TO (T)');
        //     return [
        //         'id' => $item->id,
        //         'department' => $item->department->name,
        //         'position' => $item->position->name,
        //         'recruitment' => $item->quantity,
        //         'date' => $formattedDate,
        //     ];
        // });

        $sql = "
            WITH aggregated AS (
                SELECT
                    candidate.department_id,
                    candidate.position_id,
                    candidate.status,
                    COUNT(candidate.id) as total_count,
                    SUM(
                        CASE
                            WHEN candidate.status = 'interview' OR candidate.status = 'new' OR candidate.status = 'interview0' OR candidate.status = 'interview1' THEN 1
                            ELSE 0
                        END
                    ) as interview_count,
                    SUM(CASE WHEN candidate.status = 'employee' THEN 1 ELSE 0 END) as employee_count,
                    SUM(CASE WHEN candidate.status = 'reject' THEN 1 ELSE 0 END) as reject_count,
                    MAX(candidate.created_at) as created_at
                FROM candidate
                GROUP BY candidate.department_id, candidate.position_id, candidate.status
            )

            SELECT
                department.name as department,
                position.name as position,
                aggregated.status,
                aggregated.total_count,
                aggregated.interview_count,
                aggregated.employee_count,
                aggregated.reject_count,
                aggregated.created_at as date
            FROM aggregated
            JOIN department ON aggregated.department_id = department.id
            JOIN position ON aggregated.position_id = position.id
            ";

        $results = DB::select($sql);


        foreach ($results as $key => $result) {
            $date = Carbon::parse($result->date)->setTimezone('Asia/Ho_Chi_Minh')->format('D M d Y H:i:s O');
            $results[$key] = [
                'department' => $result->department,
                'position' => $result->position,
                'status' => $result->status,
                'total_count' => $result->total_count,
                'interview_count' => $result->interview_count,
                'employee_count' => $result->employee_count,
                'reject_count' => $result->reject_count,
                'date' => $date
            ];
        }

        return $this->success($results);
    }

    public function getDataFunnel()
    {
        try {
            $statistics = Candidate::select('status_value', DB::raw('count(*) as value'))
                ->groupBy('status_value')
                ->get()
                ->toArray();
            return $this->success($statistics);
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function getDataPie()
    {
        try {
            $datas = DB::table('candidate')
                ->join('position', 'candidate.position_id', '=', 'position.id')
                ->select('position.name as position', DB::raw('count(candidate.id) as count_candidate'))
                ->groupBy('position.name')
                ->get();
            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }


    public function abort403()
    {
        return view('recruitment/abort/403');
    }


}
