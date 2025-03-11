<?php

namespace App\Http\Controllers\Recruitment;

use App\Common\Traits\ApiResponses;
use App\Models\Candidate;
use App\Models\RecruitmentNeed;
use App\Models\RequestForm;
use App\Repositories\Recruitment\CandidateRepository;
use App\Repositories\Recruitment\DepartmentRepository;
use App\Repositories\Recruitment\PositionRepository;
use App\Repositories\Recruitment\RecruitmentPlanRepository;
use App\Repositories\Recruitment\RequestFormRepository;
use App\Repositories\Recruitment\SourceRepository;
use App\Repositories\Recruitment\UserRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\TestRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    use ApiResponses;
    public function __construct(SourceRepository $sourceRepo, DepartmentRepository $departmentRepo, UserRepository $userRepo, PositionRepository $positionRepo, CandidateRepository $candidateRepo, RequestFormRepository $requestFormRepo, RecruitmentPlanRepository $recruitmentPlanRepo)
    {
        $this->departmentRepo = $departmentRepo;
        $this->sourceRepo = $sourceRepo;
        $this->userRepo = $userRepo;
        $this->positionRepo = $positionRepo;
        $this->recruitmentPlanRepo = $recruitmentPlanRepo;
        $this->candidateRepo = $candidateRepo;
        $this->requestFormRepo = $requestFormRepo;
    }

    public function index()
    {
        return view('recruitment.report.index');
    }

    public function getDataIndex()
    {
        try {
            $datas = RecruitmentNeed::with('position')->get();
            return $this->success($datas);
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    // public function getDataIndex()
    // {
    //     try {
    //         //  WHERE CONCAT(year, '-', IF(month<10, CONCAT('0', month), month), '-01') BETWEEN '2023-01-01' AND '2023-12-31'
    //         //  WHERE created_at BETWEEN '2023-01-01' AND '2023-12-31'

    //         $sql = "
    //             SELECT
    //                     p.name as position,
    //                     COALESCE(ae.employee_count, 0) as anual_employee_count_month,
    //                     COALESCE(c.candidate_count, 0) as candidate_count_month,
    //                     COALESCE(ae.employee_count, 0) + COALESCE(c.candidate_count, 0) as total_month,
    //                     COALESCE(ae.select_date, c.select_date) as date
    //                 FROM
    //                     position p
    //                 LEFT JOIN (
    //                     SELECT
    //                         position_id,
    //                         COUNT(*) as employee_count,
    //                         CONCAT(year, '-', IF(month<10, CONCAT('0', month), month), '-01') as select_date
    //                     FROM
    //                         annual_employee
    //                     GROUP BY
    //                         position_id, year, month
    //                 ) ae ON ae.position_id = p.id
    //                 LEFT JOIN (
    //                     SELECT
    //                         position_id,
    //                         COUNT(*) as candidate_count,
    //                         DATE_FORMAT(created_at, '%Y-%m-01') as select_date
    //                     FROM
    //                         candidate
    //                     GROUP BY
    //                         position_id, YEAR(created_at), MONTH(created_at)
    //                 ) c ON c.position_id = p.id AND c.select_date = ae.select_date
    //                 ORDER BY
    //                     p.name, COALESCE(ae.select_date, c.select_date);
    //     ";

    //         $datas = DB::select($sql);
    //         return $this->success($datas);
    //     } catch (\Exception $ex) {
    //         return $this->error($ex->getMessage());
    //     }
    // }



    public function convertRate()
    {
        // $departments = $this->departmentRepo->all();
        // $sources = $this->sourceRepo->all();
        $positions = $this->positionRepo->all();
        return view('recruitment.report.convert_rate', compact('positions'));
    }

    public function getConvertRateChart(Request $request)
    {
        try {
            //Lọc lấy tất cả danh sách vị trí và source
            $startDate = $request->date_from;
            $endDate = $request->date_to;
            $positionId = $request->position_id;

            // if ($startDate) {
            //     $startDate = Carbon::parse($startDate . '-01');
            // }
            // if ($endDate) {
            //     $endDate = Carbon::parse($endDate)->endOfMonth();
            // }

            $sql = "
                SELECT
                    COUNT(c.id) AS 'Ứng viên',
                    SUM(CASE WHEN c.interview_result IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') THEN 1 ELSE 0 END) AS 'SLHS',
                    SUM(CASE WHEN c.interview_result = 'obtain' THEN 1 ELSE 0 END) AS 'Đạt SLHS',
                    SUM(CASE WHEN c.interview_result0 IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') THEN 1 ELSE 0 END) AS 'PVSB',
                    SUM(CASE WHEN c.interview_result0 = 'obtain' THEN 1 ELSE 0 END) AS 'Đạt PVSB',
                    SUM(CASE WHEN c.interview_result1 IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') THEN 1 ELSE 0 END) AS 'PVV1',
                    SUM(CASE WHEN c.interview_result1 = 'obtain' THEN 1 ELSE 0 END) AS 'Đạt PVV1',
                    SUM(CASE WHEN c.interview_result2 IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') THEN 1 ELSE 0 END) AS 'PVV2',
                    SUM(CASE WHEN c.interview_result2 = 'obtain' THEN 1 ELSE 0 END) AS 'Đạt PVV2',
                    SUM(CASE WHEN c.interview_result3 IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') THEN 1 ELSE 0 END) AS 'PVV3',
                    SUM(CASE WHEN c.interview_result3 = 'obtain' THEN 1 ELSE 0 END) AS 'Đạt PVV3',
                    SUM(CASE WHEN c.exam_result = 'obtain' THEN 1 ELSE 0 END) AS 'Đạt thi tuyển',
                    SUM(CASE WHEN c.recruitment_result = 'take_job' THEN 1 ELSE 0 END) AS 'Nhận việc',
                    SUM(CASE WHEN c.recruitment_result = 'reject_take_job' THEN 1 ELSE 0 END) AS 'Không nhận việc'
                FROM
                    candidate c
            ";
            $placeholders = [];

            // Tạo một mảng để lưu trữ điều kiện WHERE
            $whereConditions = [];

            // Kiểm tra xem startDate và endDate có giá trị không và thêm vào điều kiện WHERE nếu cần
            if ($startDate && $endDate) {
                $startDate = Carbon::parse($startDate . '-01');
                $endDate = Carbon::parse($endDate)->endOfMonth();
                $whereConditions[] = "(c.received_time BETWEEN ? AND ?)";
                $placeholders[] = $startDate;
                $placeholders[] = $endDate;
            }

            // Kiểm tra xem positionId không rỗng và thêm vào điều kiện WHERE nếu cần
            if (!empty($positionId)) {
                $positionIdPlaceholders = implode(',', array_fill(0, count($positionId), '?'));
                $whereConditions[] = "(c.position_id IN ($positionIdPlaceholders))";
                $placeholders = array_merge($placeholders, $positionId);
            }

            // Nếu có ít nhất một điều kiện WHERE, thêm chúng vào câu truy vấn SQL
            if (!empty($whereConditions)) {
                $sql .= " WHERE " . implode(" AND ", $whereConditions);
            }

            $queryResult = DB::select($sql, $placeholders);

            // Biến đổi kết quả thành dạng data, total
            $resultArray = [];
            foreach ($queryResult[0] as $key => $value) {
                $resultArray[] = ['data' => $key, 'value' => $value];
            }

            return $this->success($resultArray);
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }

    }

    public function getConvertRate(Request $request)
    {
        try {
            //  WHERE CONCAT(year, '-', IF(month<10, CONCAT('0', month), month), '-01') BETWEEN '2023-01-01' AND '2023-12-31'
            //  WHERE created_at BETWEEN '2023-01-01' AND '2023-12-31'

            //     $sql = "
            //         SELECT
            //             p.id AS id,
            //             p.name AS position,
            //             SUM(CASE WHEN c.status = 'new' THEN 1 ELSE 0 END) AS new,
            //             SUM(CASE WHEN c.status = 'interview_success' THEN 1 ELSE 0 END) AS interview_success,
            //             SUM(CASE WHEN c.status = 'interview_success0' THEN 1 ELSE 0 END) AS interview_success0,
            //             SUM(CASE WHEN c.status = 'interview_success1' THEN 1 ELSE 0 END) AS interview_success1,
            //             SUM(CASE WHEN c.status = 'interview_success2' THEN 1 ELSE 0 END) AS interview_success2,
            //             SUM(CASE WHEN c.status = 'interview_success3' THEN 1 ELSE 0 END) AS interview_success3,
            //             SUM(
            //                 CASE
            //                     WHEN c.status = 'invite_onboard' OR c.status = 'invite_employee' THEN 1
            //                     ELSE 0
            //                 END) AS invite,
            //             SUM(
            //                 CASE
            //                     WHEN c.status = 'onboarding' OR c.status = 'employee' THEN 1
            //                     ELSE 0
            //                 END
            //             ) AS employee,
            //             SUM(CASE WHEN c.status = 'reject' THEN 1 ELSE 0 END) AS reject,
            //             DATE_FORMAT(c.created_at, '%Y-%m-%d') AS date
            //         FROM
            //             position p
            //         LEFT JOIN
            //             candidate c ON p.id = c.position_id
            //         GROUP BY
            //             p.id, p.name, DATE(c.created_at)
            //         ORDER BY
            //             p.id, DATE(c.created_at);
            // ";

            // $sql = "
            //     WITH CandidateCounts AS (
            //         SELECT
            //             c.position_id,
            //             CASE WHEN csh.new_status = 'new' THEN 1 ELSE 0 END AS new,
            //             CASE WHEN csh.new_status = 'interview_success' THEN 1 ELSE 0 END AS interview_success,
            //             CASE WHEN csh.new_status = 'interview0_success0' THEN 1 ELSE 0 END AS interview_success0,
            //             CASE WHEN csh.new_status = 'interview1_success' THEN 1 ELSE 0 END AS interview_success1,
            //             CASE WHEN csh.new_status = 'interview2_success' THEN 1 ELSE 0 END AS interview_success2,
            //             CASE WHEN csh.new_status = 'interview3_success' THEN 1 ELSE 0 END AS interview_success3,
            //             CASE WHEN csh.new_status = 'reject' THEN 1 ELSE 0 END AS reject,
            //             CASE
            //                 WHEN csh.new_status = 'invite_onboard' OR csh.new_status = 'invite_employee' THEN 1 ELSE 0
            //             END AS invite,
            //             CASE
            //                 WHEN csh.new_status = 'onboarding' OR csh.new_status = 'employee' THEN 1
            //                 ELSE 0
            //             END AS employee,
            //             c.created_at AS date
            //         FROM candidate c
            //         JOIN candidate_status_history csh ON c.id = csh.candidate_id
            //     )

            //     SELECT
            //         p.id,
            //         p.name,
            //         COALESCE(SUM(cc.new), 0) AS new,
            //         COALESCE(SUM(cc.interview_success), 0) AS interview_success,
            //         COALESCE(SUM(cc.interview_success0), 0) AS interview_success0,
            //         COALESCE(SUM(cc.interview_success1), 0) AS interview_success1,
            //         COALESCE(SUM(cc.interview_success2), 0) AS interview_success2,
            //         COALESCE(SUM(cc.interview_success3), 0) AS interview_success3,
            //         COALESCE(SUM(cc.invite), 0) AS invite,
            //         COALESCE(SUM(cc.reject), 0) AS reject,
            //         COALESCE(SUM(cc.employee), 0) AS employee,
            //         cc.date
            //     FROM position p
            //     LEFT JOIN CandidateCounts cc ON p.id = cc.position_id
            //     GROUP BY p.id, cc.date
            //     ORDER BY p.id, cc.date;
            // ";

            // $datas = DB::select($sql);
            $startDate = $request->date_from;
            $endDate = $request->date_to;
            $positionIds = $request->position_id;

            $sql = "
                SELECT
                    p.id AS position_id,
                    p.name AS position_name,
                    COUNT(c.id) AS total_candidates,
                    SUM(CASE WHEN c.interview_result = 'obtain' OR c.interview_result = 'not_obtain' OR c.interview_result = 'save' OR c.interview_result = 'consider' OR c.interview_result = 'remove' THEN 1 ELSE 0 END) AS 'slhs',
                    SUM(CASE WHEN c.interview_result = 'obtain' THEN 1 ELSE 0 END) AS 'slhs_obtain',
                    SUM(CASE WHEN c.interview_result0 = 'obtain' OR c.interview_result0 = 'not_obtain' OR c.interview_result0 = 'save' OR c.interview_result0 = 'consider' OR c.interview_result0 = 'remove' THEN 1 ELSE 0 END) AS 'pvsb',
                    SUM(CASE WHEN c.interview_result0 = 'obtain' THEN 1 ELSE 0 END) AS 'pvsb_obtain',
                    SUM(CASE WHEN c.interview_result1 = 'obtain' OR c.interview_result1 = 'not_obtain' OR c.interview_result1 = 'save' OR c.interview_result1 = 'consider' OR c.interview_result1 = 'remove' THEN 1 ELSE 0 END) AS 'pvv1',
                    SUM(CASE WHEN c.interview_result1 = 'obtain' THEN 1 ELSE 0 END) AS 'pvv1_obtain',
                    SUM(CASE WHEN c.interview_result2 = 'obtain' OR c.interview_result2 = 'not_obtain' OR c.interview_result2 = 'save' OR c.interview_result2 = 'consider' OR c.interview_result2 = 'remove' THEN 1 ELSE 0 END) AS 'pvv2',
                    SUM(CASE WHEN c.interview_result2 = 'obtain' THEN 1 ELSE 0 END) AS 'pvv2_obtain',
                    SUM(CASE WHEN c.interview_result3 = 'obtain' OR c.interview_result3 = 'not_obtain' OR c.interview_result3 = 'save' OR c.interview_result3 = 'consider' OR c.interview_result3 = 'remove' THEN 1 ELSE 0 END) AS 'pvv3',
                    SUM(CASE WHEN c.interview_result3 = 'obtain' THEN 1 ELSE 0 END) AS 'pvv3_obtain',
                    SUM(CASE WHEN c.exam_result = 'obtain' THEN 1 ELSE 0 END) AS 'exam_obtain',
                    SUM(CASE WHEN c.recruitment_result = 'take_job' THEN 1 ELSE 0 END) AS 'take_job',
                    SUM(CASE WHEN c.recruitment_result = 'reject_take_job' THEN 1 ELSE 0 END) AS 'reject_take_job'
                FROM
                    position p
                JOIN
                    candidate c ON p.id = c.position_id
            ";

            $bindings = [];

            if ($startDate && $endDate) {
                $startDate = Carbon::parse($startDate . '-01');
                $endDate = Carbon::parse($endDate)->endOfMonth();
                $filters[] = "c.received_time BETWEEN ? AND ?";
                $bindings[] = $startDate;
                $bindings[] = $endDate;
            }

            if ($positionIds) {
                $placeholders = implode(',', array_fill(0, count($positionIds), '?'));
                $filters[] = "p.id IN ($placeholders)";
                $bindings = array_merge($bindings, $positionIds);
            }

            if (!empty($filters)) {
                $sql .= " WHERE " . implode(" AND ", $filters);
            }

            $sql .= "
                GROUP BY
                    p.id, p.name
                ORDER BY
                    p.id;
            ";

            $datas = DB::select($sql, $bindings);
            return $this->success($datas);
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function candidateSource()
    {
        $departments = $this->departmentRepo->all();
        $positions = $this->positionRepo->all();
        $sources = $this->sourceRepo->all();

        return view('recruitment.report.candidate_source', compact('positions', 'departments', 'sources'));
    }

    public function getCandidateSourceChart(Request $request)
    {
        try {
            $startDate = $request->date_from;
            $endDate = $request->date_to;
            $sourceId = $request->source_id;
            $positionId = $request->position_id;

            $query = Candidate::join('source', 'candidate.source_id', '=', 'source.id')
                ->join('position', 'candidate.position_id', '=', 'position.id')
                ->select('source.name as source_name', DB::raw('count(candidate.id) as total_candidate'));
            // ->get();
            if ($startDate && $endDate) {
                $startDate = Carbon::parse($startDate . '-01');
                $endDate = Carbon::parse($endDate)->endOfMonth();
                $query->whereBetween('candidate.received_time', [$startDate, $endDate]);
            }
            if ($sourceId && $sourceId != null) {
                $sourceId = array_filter($sourceId);
                $query->whereIn('source.id', $sourceId);
            }
            if ($positionId && $positionId != null) {
                $positionId = array_filter($positionId);
                $query->whereIn('position.id', $positionId);
            }

            $datas = $query->groupBy('source.id', 'source.name')->get();
            return $this->success($datas);


        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }

    }

    public function getCandidateSource(Request $request)
    {
        try {
            //Lọc lấy tất cả danh sách vị trí và source
            $startDate = $request->date_from;
            $endDate = $request->date_to;
            $sourceId = $request->source_id;
            $positionId = $request->position_id;
            //dd($startDate);
            //dd($startDate . $endDate . $sourceId . $departmentId);
            if ($startDate) {
                $startDate = Carbon::parse($startDate . '-01');
            }
            if ($endDate) {
                $endDate = Carbon::parse($endDate)->endOfMonth();
            }
            $query = DB::table('position as p')
                ->crossJoin('source as s')
                ->join('candidate as c', function ($join) use ($startDate, $endDate, $sourceId, $positionId) {
                    $join->on('c.position_id', '=', 'p.id')
                        ->on('c.source_id', '=', 's.id');
                    // Chuyển điều kiện ngày vào phần on của leftJoin
                    if ($startDate && $endDate) {
                        $join->whereBetween('c.created_at', [$startDate, $endDate]);
                    }
                    if ($sourceId && $sourceId != null) {
                        $sourceId = array_filter($sourceId);
                        $join->whereIn('s.id', $sourceId);
                    }
                    if ($positionId && $positionId != null) {
                        $positionId = array_filter($positionId);
                        $join->whereIn('p.id', $positionId);
                    }
                })
                ->select(
                    'p.name as position',
                    's.name as source',
                    DB::raw('COALESCE(COUNT(c.id), 0) as count'),
                    DB::raw('MAX(c.created_at) as date')
                )
                ->groupBy('p.name', 's.name');
            // ->having('count', '>', 0);

            // if ($startDate && $endDate) {
            //     $query->whereBetween('c.created_at', [$startDate, $endDate]);
            // }
            // if ($sourceId) {
            //     $query->where('s.id', $sourceId);
            // }
            // if ($departmentId) {
            //     $query->where('d.id', $departmentId);
            // }
            //dd($query->toSql());
            $datas = $query->get();

            return $this->success($datas);
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function recruitmentKpi()
    {
        $positions = $this->positionRepo->all();
        $departments = $this->departmentRepo->all();
        $users = $this->userRepo->all();

        return view('recruitment.report.recruitment_kpi', compact('departments', 'users', 'positions'));
    }

    public function getRecruitmentKpi(Request $request)
    {
        try {
            $input = $request->all();
            $userIds = $request->userIds;
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $departmentId = $request->departmentId;

            $query = DB::table('candidate as c')
                ->join('user as u', function ($join) {
                    $join->on('c.interviewer', '=', 'u.id')
                        ->orOn('c.interviewer0', '=', 'u.id')
                        ->orOn('c.interviewer1', '=', 'u.id');
                })
                ->join('position as p', 'c.position_id', '=', 'p.id')
                ->select('u.name as user', 'p.name as position')
                ->selectRaw('
                SUM(CASE WHEN c.interviewer = u.id THEN 1 ELSE 0 END) as slhs_total,
                SUM(CASE WHEN c.interviewer0 = u.id THEN 1 ELSE 0 END) as pvsb_total,
                SUM(CASE WHEN c.interviewer1 = u.id THEN 1 ELSE 0 END) as pvv1_total,
                SUM(CASE WHEN c.interviewer0 = u.id AND c.interview_result0 = "obtain" THEN 1 ELSE 0 END) as pvsb_obtain_total,
                SUM(CASE WHEN c.interviewer1 = u.id AND c.interview_result1 = "obtain" THEN 1 ELSE 0 END) as pvv1_obtain_total
            ');

            // Áp dụng các điều kiện lọc
            if (request('startDate')) {
                $startDate = Carbon::parse(request('startDate'))->startOfMonth();
                $query->where('c.received_time', '>=', $startDate);
            }

            if (request('endDate')) {
                $endDate = Carbon::parse(request('endDate'))->endOfMonth();
                $query->where('c.received_time', '<=', $endDate);
            }

            // if (request('departmentId') && request('departmentId') != null) {
            //     $query->where('u.department_id', request('departmentId'));
            // }

            if (request('positionId') && request('positionId') != null) {
                $query->whereIn('c.position_id', request('positionId'));
            }

            if (request('userIds')) {
                $userIds = request('userIds'); // Vì đây là multiple select, nó có thể trả về một mảng
                $query->whereIn('u.id', $userIds);
            }
            // Group và lấy kết quả
            $datas = $query->groupBy('u.name', 'p.name')->get();

            return $this->success($datas);
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function getRecruitmentKpiChart(Request $request)
    {
        try {
            $query = DB::table('candidate as c')
                ->join('user as u', function ($join) {
                    $join->on('c.interviewer', '=', 'u.id')
                        ->orOn('c.interviewer0', '=', 'u.id')
                        ->orOn('c.interviewer1', '=', 'u.id');
                })
                ->select('u.name as user')
                ->selectRaw('
                SUM(CASE WHEN c.interviewer = u.id THEN 1 ELSE 0 END) as slhs_total,
                SUM(CASE WHEN c.interviewer0 = u.id THEN 1 ELSE 0 END) as pvsb_total,
                SUM(CASE WHEN c.interviewer1 = u.id THEN 1 ELSE 0 END) as pvv1_total,
                SUM(CASE WHEN c.interviewer0 = u.id AND c.interview_result0 = "obtain" THEN 1 ELSE 0 END) as pvsb_obtain_total,
                SUM(CASE WHEN c.interviewer1 = u.id AND c.interview_result1 = "obtain" THEN 1 ELSE 0 END) as pvv1_obtain_total
            ');

            if (request('startDate')) {
                $startDate = Carbon::parse(request('startDate'))->startOfMonth();
                $query->where('c.received_time', '>=', $startDate);
            }

            if (request('endDate')) {
                $endDate = Carbon::parse(request('endDate'))->endOfMonth();
                $query->where('c.received_time', '<=', $endDate);
            }

            if (request('departmentId') && request('departmentId') != null) {
                $query->where('u.department_id', request('departmentId'));
            }

            if (request('userIds')) {
                $userIds = request('userIds');
                $query->whereIn('u.id', $userIds);
            }
            // Group và lấy kết quả
            $datas = $query->groupBy('u.name')->get();

            return $this->success($datas);
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function candidate()
    {
        return view('recruitment.report.candidate');
    }

    public function getCandidate()
    {
        try {
            $datas = $this->candidateRepo->queryAll()
                ->with(['department', 'position', 'source'])
                ->whereIn('status', ['recruitment_success', 'probation_success'])
                ->orderBy('received_time', 'desc')->get();
            // $datas = DB::table('candidate as e')
            //     ->join('position as p', 'e.position_id', '=', 'p.id')
            //     ->join('department as d', 'e.department_id', '=', 'd.id')
            //     ->select('e.status_value', 'e.user_uid', 'e.name', 'p.name as position', 'e.birthday', 'e.status', 'e.created_at', 'e.updated_at', 'e.recruitment_result_value', 'e.probation_result_value', 'e.email', 'e.phone_number')
            //     ->where('e.recruitment_result', 'take_job')
            //     ->orderBy('e.created_at')
            //     ->get();
            return $this->success($datas);
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function effect()
    {
        return view('recruitment/abort/403');
    }

    public function expense()
    {
        return view('recruitment/abort/403');
    }


}
