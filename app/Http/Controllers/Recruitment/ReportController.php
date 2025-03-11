<?php

namespace App\Http\Controllers\Recruitment;

use App\Common\Traits\ApiResponses;
use App\Enums\CandidateEnum;
use App\Models\Candidate;
use App\Models\Position;
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
        $departments = $this->departmentRepo->all();
        $sources = $this->sourceRepo->all();
        $positions = $this->positionRepo->all();
        $users = $this->userRepo->all();
        return view('recruitment.report.index', compact('users', 'departments', 'sources', 'positions'));
    }

    public function getDataIndex(Request $request)
    {
        try {
            $startDate = $request->date_from;
            $endDate = $request->date_to;
            $positionId = $request->position_id;
            $displayType = $request->display_type ?? 'group'; // Mặc định là 'group'

            $query = RecruitmentNeed::with('position');

            if ($startDate && $endDate) {
                $startDate = Carbon::parse($startDate . '-01');
                $endDate = Carbon::parse($endDate)->endOfMonth();
                $query->whereBetween('time', [$startDate, $endDate]);
            }

            if ($positionId) {
                $query->whereIn('position_id', $positionId);
            }

            $query->where(function ($query) {
                $query->where('need', '<>', 0)
                    ->orWhere('more', '<>', 0);
            });

            $data = $query->get();

            if ($displayType == 'group') {
                $groupedData = $data->groupBy('position_id')->map(function ($group) {
                    // Tính toán tổng hoặc xử lý dữ liệu tổng hợp tại đây
                    return [
                        'position_id' => $group->first()->position_id,
                        'position_name' => $group->first()->position->name,
                        'more' => $group->sum('more'),
                        'sub' => $group->sum('sub'),
                        'recruitmented' => $group->sum('recruitmented'),
                        'total' => $group->sum('total'),
                    ];
                });
            } else { // 'month'
                $groupedData = $data->groupBy(function ($item) {
                    return $item->position_id . '-' . Carbon::parse($item->time)->format('Y-m');
                })->map(function ($group, $key) {
                    $lastItem = $group->sortByDesc('time')->first();
                    $lastItem->time = Carbon::parse($lastItem->time)->format('Y-m');
                    return $lastItem;
                });
            }

            return $this->success($groupedData->values()->all());

        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }



    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        // List of attributes to check
        $attributesToCheck = ['need', 'more', 'sub', 'total'];

        if (in_array($key, $attributesToCheck) && $value == 0) {
            return null;
        }
        return $value;
    }

    public function getDataIndexChart(Request $request)
    {
        try {
            $startDate = $request->date_from;
            $endDate = $request->date_to;
            $positionId = $request->position_id;
            // $displayType = $request->display_type;
            $displayType = 'month';

            if ($displayType == 'quarter') {
                $query = RecruitmentNeed::selectRaw('
                MAX(time) as time,
                CAST(SUM(need) AS SIGNED) as total_need,
                CAST(SUM(sub) AS SIGNED) as total_sub,
                CAST(SUM(more) AS SIGNED) as total_more,
                CAST(SUM(total) AS SIGNED) as total,
                CAST(SUM(recruitmented) AS SIGNED) as recruited_total
            ')
                    ->whereRaw('
                MONTH(time) IN (
                    SELECT MAX(MONTH(time)) FROM recruitment_need
                    GROUP BY YEAR(time), CEILING(MONTH(time) / 3)
                )'
                    );
            } elseif ($displayType == 'year') {
                $query = RecruitmentNeed::selectRaw('
                MAX(time) as time,
                CAST(SUM(need) AS SIGNED) as total_need,
                CAST(SUM(sub) AS SIGNED) as total_sub,
                CAST(SUM(more) AS SIGNED) as total_more,
                 CAST(SUM(total) AS SIGNED) as total,
                CAST(SUM(recruitmented) AS SIGNED) as recruited_total
            ')
                    ->whereRaw('MONTH(time) = 12');
            } else {
                $query = RecruitmentNeed::selectRaw('
                DATE_FORMAT(time, "%m-%Y") as time,
                CAST(SUM(need) AS SIGNED) as total_need,
                CAST(SUM(sub) AS SIGNED) as total_sub,
                CAST(SUM(more) AS SIGNED) as total_more,
                 CAST(SUM(total) AS SIGNED) as total,
                CAST(SUM(recruitmented) AS SIGNED) as recruited_total
            ');
            }

            if ($startDate && $endDate) {
                $startDate = Carbon::parse($startDate . '-01');
                $endDate = Carbon::parse($endDate)->endOfMonth();
                $query->whereBetween('time', [$startDate, $endDate]);
            }
            if ($positionId) {
                $query->whereIn('position_id', $positionId);
            }

            $datas = $query->groupBy('time')->get();
            return $this->success($datas);
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }


    public function convertRate()
    {
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

            $sql = "
            SELECT
                COUNT(CASE WHEN " . ($startDate && $endDate ? "c.received_time BETWEEN ? AND ?" : "1=1") . " THEN c.id ELSE NULL END) AS 'Ứng viên',
                SUM(CASE WHEN c.interview_result IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') " . ($startDate && $endDate ? "AND c.interview_date BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS 'SLHS',
                SUM(CASE WHEN c.interview_result = 'obtain' " . ($startDate && $endDate ? "AND c.interview_date BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS 'Đạt SLHS',
                SUM(CASE WHEN c.interview_result0 IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') " . ($startDate && $endDate ? "AND c.interview_date0 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS 'PVSB',
                SUM(CASE WHEN c.interview_result0 = 'obtain' " . ($startDate && $endDate ? "AND c.interview_date0 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS 'Đạt PVSB',
                SUM(CASE WHEN c.interview_result1 IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') " . ($startDate && $endDate ? "AND c.interview_date1 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS 'PVV1',
                SUM(CASE WHEN c.interview_result1 = 'obtain' " . ($startDate && $endDate ? "AND c.interview_date1 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS 'Đạt PVV1',
                SUM(CASE WHEN c.interview_result2 IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') " . ($startDate && $endDate ? "AND c.interview_date2 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS 'PVV2',
                SUM(CASE WHEN c.interview_result2 = 'obtain' " . ($startDate && $endDate ? "AND c.interview_date2 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS 'Đạt PVV2',
                SUM(CASE WHEN c.interview_result3 IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') " . ($startDate && $endDate ? "AND c.interview_date3 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS 'PVV3',
                SUM(CASE WHEN c.interview_result3 = 'obtain' " . ($startDate && $endDate ? "AND c.interview_date3 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS 'Đạt PVV3',
                SUM(CASE WHEN c.recruitment_result = 'take_job' " . ($startDate && $endDate ? "AND c.probation_from BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS 'Nhận việc',
                SUM(CASE WHEN c.recruitment_result = 'reject_take_job'  " . ($startDate && $endDate ? "AND c.reject_job_date BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS 'Không nhận việc'
            FROM
                candidate c
        ";
            $placeholders = [];

            // Tạo một mảng để lưu trữ điều kiện WHERE
            $whereConditions = [];

            // Thêm điều kiện ngày tháng
            if ($startDate && $endDate) {
                // $placeholders = array_merge($placeholders, array_fill(0, 8, $startDate), array_fill(0, 8, $endDate));
                for ($i = 0; $i < 13; $i++) {
                    array_push($placeholders, $startDate, $endDate);
                }
            }

            //Kiểm tra xem positionId không rỗng và thêm vào điều kiện WHERE nếu cần
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
            $startDate = $request->date_from;
            $endDate = $request->date_to;
            $positionIds = $request->position_id;

            // Khởi tạo biến cho bindings
            $bindings = [];
            if ($startDate && $endDate) {
                // Mỗi CASE sẽ cần một cặp ngày
                $bindings = array_merge($bindings, array_fill(0, 14, $startDate), array_fill(0, 14, $endDate));
            }

            // Câu truy vấn cơ bản
            $sql = "
                SELECT
                    p.id AS position_id,
                    p.name AS position_name,
                    COUNT(CASE WHEN " . ($startDate && $endDate ? "c.received_time BETWEEN ? AND ?" : "1=1") . " THEN c.id ELSE NULL END) AS total_candidates,
                    SUM(CASE WHEN c.interview_result IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') " . ($startDate && $endDate ? "AND c.interview_date BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS slhs,
                    SUM(CASE WHEN c.interview_result = 'obtain' " . ($startDate && $endDate ? "AND c.interview_date BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS slhs_obtain,
                    SUM(CASE WHEN c.interview_result0 IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') " . ($startDate && $endDate ? "AND c.interview_date0 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS pvsb,
                    SUM(CASE WHEN c.interview_result0 = 'obtain' " . ($startDate && $endDate ? "AND c.interview_date0 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS pvsb_obtain,
                    SUM(CASE WHEN c.interview_result1 IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') " . ($startDate && $endDate ? "AND c.interview_date1 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS pvv1,
                    SUM(CASE WHEN c.interview_result1 = 'obtain' " . ($startDate && $endDate ? "AND c.interview_date1 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS pvv1_obtain,
                    SUM(CASE WHEN c.interview_result2 IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') " . ($startDate && $endDate ? "AND c.interview_date2 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS pvv2,
                    SUM(CASE WHEN c.interview_result2 = 'obtain' " . ($startDate && $endDate ? "AND c.interview_date2 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS pvv2_obtain,
                    SUM(CASE WHEN c.interview_result3 IN ('obtain', 'not_obtain', 'save', 'consider', 'remove') " . ($startDate && $endDate ? "AND c.interview_date3 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS pvv3,
                    SUM(CASE WHEN c.interview_result3 = 'obtain' " . ($startDate && $endDate ? "AND c.interview_date3 BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS pvv3_obtain,
                    SUM(CASE WHEN c.recruitment_result = 'take_job' " . ($startDate && $endDate ? "AND c.probation_from BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS take_job,
                    SUM(CASE WHEN c.recruitment_result = 'reject_take_job' " . ($startDate && $endDate ? "AND c.reject_job_date BETWEEN ? AND ?" : "") . " THEN 1 ELSE 0 END) AS reject_take_job
                FROM
                    position p
                JOIN
                    candidate c ON p.id = c.position_id
            ";

            // Khởi tạo biến cho bindings
            $bindings = [];

            // Thêm bindings cho mỗi phần của CASE WHEN
            if($startDate && $endDate) {
                // Mỗi CASE sẽ cần một cặp ngày
                for($i = 0; $i < 13; $i++) { // 14 là số lần xuất hiện của cặp ngày trong truy vấn
                    $bindings[] = $startDate;
                    $bindings[] = $endDate;
                }
            }

            // Thêm điều kiện ID vị trí nếu có
            if ($positionIds) {
                $placeholders = implode(',', array_fill(0, count($positionIds), '?'));
                $sql .= " WHERE p.id IN ($placeholders)";
                $bindings = array_merge($bindings, $positionIds);
            }

            // Nhóm và sắp xếp kết quả
            $sql .= "
                GROUP BY
                    p.id, p.name
                ORDER BY
                    p.id;
            ";

            // Thực thi truy vấn
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
        //$status = CandidateEnum::cases();

        return view('recruitment.report.candidate_source', compact('positions', 'departments', 'sources'));
    }

    public function getCandidateSourceChart(Request $request)
    {
        try {
            $startDate = $request->date_from;
            $endDate = $request->date_to;
            $sourceId = $request->source_id;
            $positionId = $request->position_id;
            $status = $request->status;

            $query = Candidate::join('source', 'candidate.source_id', '=', 'source.id')
                ->join('position', 'candidate.position_id', '=', 'position.id')
                ->select('source.name as source_name', DB::raw('count(candidate.id) as total_candidate'));
            // ->get();
            if ($startDate && $endDate) {
                // $startDate = Carbon::parse($startDate . '-01');
                // $endDate = Carbon::parse($endDate)->endOfMonth();
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
            if ($status && $status != null) {
                $query->whereIn('candidate.status', ['recruitment_success','probation_success','probation_fail']);
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
            $status = $request->status;
            //dd($startDate);
            //dd($startDate . $endDate . $sourceId . $departmentId);
            // if ($startDate) {
            //     $startDate = Carbon::parse($startDate . '-01');
            // }
            // if ($endDate) {
            //     $endDate = Carbon::parse($endDate)->endOfMonth();
            // }
            $query = DB::table('position as p')
                ->crossJoin('source as s')
                ->join('candidate as c', function ($join) use ($startDate, $endDate, $sourceId, $positionId, $status) {
                    $join->on('c.position_id', '=', 'p.id')
                        ->on('c.source_id', '=', 's.id');
                    // Chuyển điều kiện ngày vào phần on của leftJoin
                    if ($startDate && $endDate) {
                        $join->whereBetween('c.received_time', [$startDate, $endDate]);
                    }
                    if ($sourceId && $sourceId != null) {
                        $sourceId = array_filter($sourceId);
                        $join->whereIn('s.id', $sourceId);
                    }
                    if ($positionId && $positionId != null) {
                        $positionId = array_filter($positionId);
                        $join->whereIn('p.id', $positionId);
                    }
                    if ($status && $status != null) {
                        $join->whereIn('c.status', ['recruitment_success', 'probation_success', 'probation_fail']);
                    }
                })
                ->select(
                    'p.name as position',
                    's.name as source',
                    DB::raw('COALESCE(COUNT(c.id), 0) as count'),
                    DB::raw('MAX(c.created_at) as date')
                )
                ->groupBy('p.name', 's.name');

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

    public function getRecruitmentKpi(Request $request) {
        try {
            $userIds = $request->userIds;
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            //$departmentId = $request->departmentId;

            // Tạo truy vấn
            $query = DB::table('candidate as c')
                ->join('user as u', function ($join) {
                    $join->on('c.interviewer', '=', 'u.id')
                        ->orOn('c.interviewer0', '=', 'u.id')
                        ->orOn('c.interviewer1', '=', 'u.id');
                })
                ->join('position as p', 'c.position_id', '=', 'p.id')
                ->select('u.name as user', 'p.name as position')
                ->selectRaw("
                SUM(CASE WHEN c.interviewer = u.id AND ".($startDate && $endDate ? "c.interview_date BETWEEN '$startDate' AND '$endDate'" : "1=1")." THEN 1 ELSE 0 END) as slhs_total,
                SUM(CASE WHEN c.interviewer0 = u.id AND ".($startDate && $endDate ? "c.interview_date0 BETWEEN '$startDate' AND '$endDate'" : "1=1")." THEN 1 ELSE 0 END) as pvsb_total,
                SUM(CASE WHEN c.interviewer1 = u.id AND ".($startDate && $endDate ? "c.interview_date1 BETWEEN '$startDate' AND '$endDate'" : "1=1")." THEN 1 ELSE 0 END) as pvv1_total,
                SUM(CASE WHEN c.interviewer0 = u.id AND c.interview_result0 = 'obtain' AND ".($startDate && $endDate ? "c.interview_date0 BETWEEN '$startDate' AND '$endDate'" : "1=1")." THEN 1 ELSE 0 END) as pvsb_obtain_total,
                SUM(CASE WHEN c.interviewer1 = u.id AND c.interview_result1 = 'obtain' AND ".($startDate && $endDate ? "c.interview_date1 BETWEEN '$startDate' AND '$endDate'" : "1=1")." THEN 1 ELSE 0 END) as pvv1_obtain_total
            ");

            // $query->whereNotNull('c.interviewer')
            //     ->whereNotNull('c.interviewer0');
            // Áp dụng các điều kiện lọc khác nếu có
            if(request('departmentId') && request('departmentId') != null) {
                $query->where('c.department_id', request('departmentId'));
            }

            if(request('positionIds') && request('positionIds') != null) {
                $query->whereIn('c.position_id', request('positionIds'));
            }

            if(request('userIds')) {
                $userIds = request('userIds');
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
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $userIds = $request->userIds;

            $query = DB::table('candidate as c')
                ->join('user as u', function ($join) {
                    $join->on('c.interviewer', '=', 'u.id')
                        ->orOn('c.interviewer0', '=', 'u.id')
                        ->orOn('c.interviewer1', '=', 'u.id');
                })
                ->select('u.name as user')
                ->selectRaw("
                SUM(CASE WHEN c.interviewer = u.id AND ".($startDate && $endDate ? "c.interview_date BETWEEN '$startDate' AND '$endDate'" : "1=1")." THEN 1 ELSE 0 END) as slhs_total,
                SUM(CASE WHEN c.interviewer0 = u.id AND ".($startDate && $endDate ? "c.interview_date0 BETWEEN '$startDate' AND '$endDate'" : "1=1")." THEN 1 ELSE 0 END) as pvsb_total,
                SUM(CASE WHEN c.interviewer1 = u.id AND ".($startDate && $endDate ? "c.interview_date1 BETWEEN '$startDate' AND '$endDate'" : "1=1")." THEN 1 ELSE 0 END) as pvv1_total,
                SUM(CASE WHEN c.interviewer0 = u.id AND c.interview_result0 = 'obtain' AND ".($startDate && $endDate ? "c.interview_date0 BETWEEN '$startDate' AND '$endDate'" : "1=1")." THEN 1 ELSE 0 END) as pvsb_obtain_total,
                SUM(CASE WHEN c.interviewer1 = u.id AND c.interview_result1 = 'obtain' AND ".($startDate && $endDate ? "c.interview_date1 BETWEEN '$startDate' AND '$endDate'" : "1=1")." THEN 1 ELSE 0 END) as pvv1_obtain_total
            ");

            if (request('departmentId') && request('departmentId') != null) {
                $query->where('u.department_id', request('departmentId'));
            }

            if (request('positionIds') && request('positionId') != null) {
                $query->whereIn('c.position_id', request('positionId'));
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
                ->whereIn('status', ['recruitment_success', 'probation_success', 'probation_fail', 'probation_long', 'employee'])
                ->orderBy('received_time', 'desc')->get();

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
