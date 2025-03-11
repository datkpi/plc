<?php

namespace App\Http\Controllers\Recruitment;

use App\Common\Traits\ApiResponses;
use App\Common\Traits\ConvertHelpers;
use App\Common\Traits\EnumHandle;
use App\Common\Traits\PlaceholderReplacer;
use App\Exports\ExportCandidate;
use App\Mail\SendEmail;
use App\Enums\BranchEnum;
use App\Enums\CandidateEnum;
use App\Enums\ExamResultEnum;
use App\Enums\GenderEnum;
use App\Enums\InterviewResultEnum;
use App\Enums\InterviewScheduleEnum;
use App\Enums\LevelEnum;
use App\Enums\NowResultEnum;
use App\Enums\ProbationResultEnum;
use App\Enums\RankEnum;
use App\Common\Traits\GetUniqueUid;
use App\Enums\RecruitmentResultEnum;
use App\Enums\ScheduleMailEnum;
use App\Models\Candidate;
use App\Models\Department;
use App\Models\ImportStatus;
use App\Models\InterviewSchedule;
use App\Models\Position;
use App\Models\RequestForm;
use App\Repositories\Recruitment\CandidateStatusHistoryRepository;
use App\Repositories\Recruitment\ImportStatusRepository;
use App\Repositories\Recruitment\InterviewAddressRepository;
use App\Repositories\Recruitment\InterviewScheduleRepository;
use App\Repositories\Recruitment\MailTemplateRepository;
use App\Repositories\Recruitment\RecruitmentPlanRepository;
use App\Repositories\Recruitment\RequestFormRepository;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\DepartmentRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Recruitment\UserRepository;
use App\Repositories\Recruitment\CandidateRepository;
use App\Repositories\Recruitment\PositionRepository;
use App\Repositories\Recruitment\SourceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Spatie\SimpleExcel\SimpleExcelReader;
use Validator;
use App\Common\FileUploads;

class CandidateController extends Controller
{
    use ApiResponses;
    use GetUniqueUid;
    use EnumHandle;
    use ConvertHelpers;
    use PlaceholderReplacer;

    public function __construct(MailTemplateRepository $mailTemplateRepo, InterviewAddressRepository $interviewAddressRepo, InterviewScheduleRepository $interviewScheduleRepo, CandidateStatusHistoryRepository $candidateStatusHistoryRepo, RequestFormRepository $requestFormRepo, RecruitmentPlanRepository $recruitmentPlanRepo, ImportStatusRepository $importStatusRepo, CandidateRepository $candidateRepo, UserRepository $userRepo, DepartmentRepository $departmentRepo, PositionRepository $positionRepo, SourceRepository $sourceRepo)
    {
        $this->candidateRepo = $candidateRepo;
        $this->userRepo = $userRepo;
        $this->departmentRepo = $departmentRepo;
        $this->positionRepo = $positionRepo;
        $this->sourceRepo = $sourceRepo;
        $this->importStatusRepo = $importStatusRepo;
        $this->recruitmentPlanRepo = $recruitmentPlanRepo;
        $this->requestFormRepo = $requestFormRepo;
        $this->candidateStatusHistoryRepo = $candidateStatusHistoryRepo;
        $this->interviewScheduleRepo = $interviewScheduleRepo;
        $this->interviewAddressRepo = $interviewAddressRepo;
        $this->mailTemplateRepo = $mailTemplateRepo;
    }

    public function index()
    {
        $positions = StringHelpers::getSelectOptions($this->positionRepo->all());
        $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases());
        $totalCandidate = $this->candidateRepo->countBy('active', true);
        $countByStatus = $this->candidateRepo->countByStatus();
        $status = CandidateEnum::cases();
        $datas = $this->candidateRepo->queryAll()
            ->select('languague', 'skill', 'name', 'gender_value', 'active', 'id', 'received_time', 'address', 'email', 'phone_number', 'birthday', 'household', 'form_token', 'is_submit', 'can_edit') // Replace with the columns you need from the main table
            // ->with([
            //     'createdBy:id,name',
            //     'position:id,name',
            //     'source:id,name',
            //     'address:code,name',
            //     'household:code,name',
            // ])
            ->where('active','=',false)
            ->orderBy('received_time', 'desc')
            ->get();
        return view('recruitment/candidate/index', compact('datas', 'positions', 'genders', 'status','totalCandidate','countByStatus','status'));
    }

    public function updateGenderValue()
    {
        $datas = $this->candidateRepo->all(['gender', 'id', 'gender_value']);
        foreach ($datas as $data) {
            if ($data->gender_value != null) {
                continue;
            }
            if ($data->gender == null) {
                continue;
            }

            $input['gender_value'] = $this->getValueInEnum(GenderEnum::cases(), $data->gender);
            $this->candidateRepo->find($data->id)->update($input);
        }
        return "update gender value success";
    }
    public function getData()
    {
        try {
            //$datas = $this->candidateRepo->queryAll()->with(['createdBy', 'department', 'position', 'source', 'receive', 'household', 'address', 'interviewer', 'interviewer0', 'interviewer1', 'interviewer2', 'interviewer3'])->orderBy('received_time', 'desc')->get();
            //$datas = $this->candidateRepo->queryAll()->with(['createdBy', 'department', 'position', 'source', 'receive', 'address'])->orderBy('received_time', 'desc')->get();

            $datas = $this->candidateRepo->queryAll()
                ->select('active', 'id', 'interview_comment0','experience', 'created_by', 'position_id', 'source_id', 'gender_value', 'name', 'status', 'status_value', 'created_at', 'receiver_id', 'received_time', 'info2', 'email', 'phone_number', 'birthday', 'household') // Replace with the columns you need from the main table
                ->with([
                    'createdBy:id,name',
                    'position:id,name',
                    'source:id,name',
                    'household:code,name',
                ])
                // ->where('active','=', true)
                ->orderBy('received_time', 'desc')
                ->get();

            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function importHistory()
    {
        try {
            $datas = $this->importStatusRepo->getBy('created_by', Auth::user()->id);
            return view('recruitment/candidate/import_history', compact('datas'));
        } catch (Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function createForm(Request $request)
    {
        try {
            $input = $request->except('is_send_mail');
            $formToken = Str::random(30);
            if (count($input) <= 0) {
                $form = [];
                $form['form_token'] = $formToken;
                $form['active'] = false;
                $validator = Validator::make($form, $this->candidateRepo->validateCreateForm());
                if ($validator->fails()) {
                    return $this->error($validator);
                }
                $res = $this->candidateRepo->create($form);
            } else {
                $input['form_token'] = $formToken;
                $validator = Validator::make($input, $this->candidateRepo->validateCreateForm());
                if ($validator->fails()) {
                    return $this->error($validator);
                }
                $input['active'] = false;
                $res = $this->candidateRepo->create($input);
                if ($request->is_send_mail) {
                    $candidate = $this->candidateRepo->find($res->id);
                    $mailTemplate = $this->mailTemplateRepo->findBy('code', ScheduleMailEnum::thu_moi_ung_tuyen->name);
                    if ($mailTemplate) {
                        $emailContent = $mailTemplate->body;
                        $emailContent = $this->replaceGender($emailContent, $candidate->gender);
                        // $emailContent = $this->replaceTime($emailContent, $candidate);
                        $processedEmail = $this->replacePlaceholders($emailContent, $candidate);
                        $subject = $mailTemplate->name;
                        $content = $processedEmail;
                        $to = $candidate->email;
                        Mail::to($to)->send(new SendEmail(subject: $subject, content: $content));
                    }
                }
            }
            $mainUrl = config('app.url');
            $url = $mainUrl . '/thong-tin-ung-vien/' . $formToken;
            return $this->success($url);
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function create()
    {
        $candidateStatus = StringHelpers::getSelectEnumOptions(CandidateEnum::cases());
        $users = StringHelpers::getSelectOptions($this->userRepo->getActive());
        $departments = StringHelpers::getSelectOptions($this->departmentRepo->all());
        $positions = StringHelpers::getSelectOptions($this->positionRepo->all());
        $sources = StringHelpers::getSelectOptions($this->sourceRepo->all());
        $requestForms = StringHelpers::getSelectOptions($this->requestFormRepo->getProcessing());
        $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases());
        $provinces = DB::table('provinces')->get();
        $selectProvinces = StringHelpers::getSelectProvinceOptions($provinces);
        $levels = StringHelpers::getSelectEnumOptions(LevelEnum::cases());
        $branchs = StringHelpers::getSelectEnumOptions(BranchEnum::cases());
        $ranks = StringHelpers::getSelectEnumOptions(RankEnum::cases());

        $examResults = StringHelpers::getSelectEnumOptions(ExamResultEnum::cases());
        $recruitmentResults = StringHelpers::getSelectEnumOptions(RecruitmentResultEnum::cases());
        $probationResults = StringHelpers::getSelectEnumOptions(ProbationResultEnum::cases());
        // $status = StringHelpers::getSelectEnumOptions(CandidateEnum::cases());
        $interviewResults = StringHelpers::getSelectEnumOptions(InterviewResultEnum::cases());
        //$candidateStatus = StringHelpers::getSelectEnumOptions($status);
        return view('recruitment/candidate/create', compact('examResults', 'recruitmentResults', 'probationResults', 'requestForms', 'interviewResults', 'selectProvinces', 'candidateStatus', 'users', 'genders', 'sources', 'positions', 'departments', 'provinces', 'levels', 'branchs', 'ranks'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            //$input['status'] = CandidateEnum::new ->name;
            // $input['user_uid'] = $this->renderUid();
            $validator = Validator::make($input, $this->candidateRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            //Check trùng trong database
            $strName = '%' . $input['name'] . '%';
            $checkDuplicate = Candidate::where([
                ['name', 'LIKE', $strName],
                ['birthday', '=', $input['birthday']],
                ['phone_number', '=', $input['phone_number']],
                ['email', '=', $input['email']],
            ])->pluck('id')->first();

            if ($checkDuplicate) {
                return back()->withError("Dữ liệu ứng viên này đã tồn tại trong hệ thống");
            }

            // $requestForm = $this->requestFormRepo->find($input['request_form_id']);
            // if (!$requestForm) {
            //     return back()->withError("Vui lòng chọn phiếu tuyển dụng của ứng viên này");
            // }

            // $input['department_id'] = $requestForm->department_id;
            // $input['position_id'] = $requestForm->position_id;
            // $input['recruitment_plan_id'] = $requestForm->recruitment_plan_id;
            if (isset($input['relationer'])) {
                $input['relationer'] = implode(',', $input['relationer']);
            }

            // $input['level_value'] = $this->getValueInEnum(LevelEnum::cases(), $input['level']);
            $input['gender_value'] = $this->getValueInEnum(GenderEnum::cases(), $input['gender']);
            // $input['branch_value'] = $this->getValueInEnum(BranchEnum::cases(), $input['branch']);
            // $input['rank_value'] = $this->getValueInEnum(RankEnum::cases(), $input['rank']);
            //$input['status_value'] = $this->getValueInEnum(CandidateEnum::cases(), $input['status']);
            $input['created_by'] = Auth::user()->id;
            $input['active'] = true;

            $input['interview_result_value'] = isset($input['interview_result']) ? $this->getValueInEnum(InterviewResultEnum::cases(), $input['interview_result']) : null;
            // $input['interview_result0_value'] = $this->getValueInEnum(InterviewResultEnum::cases(), $input['interview_result0']);
            // $input['interview_result1_value'] = $this->getValueInEnum(InterviewResultEnum::cases(), $input['interview_result1']);
            // $input['interview_result2_value'] = $this->getValueInEnum(InterviewResultEnum::cases(), $input['interview_result2']);
            // $input['interview_result3_value'] = $this->getValueInEnum(InterviewResultEnum::cases(), $input['interview_result3']);

            $input['exam_result_value'] = isset($input['exam_result']) ? $this->getValueInEnum(ExamResultEnum::cases(), $input['exam_result']) : null;
            $input['recruitment_result_value'] = isset($input['recruitment_result']) ? $this->getValueInEnum(RecruitmentResultEnum::cases(), $input['recruitment_result']) : null;
            $input['probation_result_value'] = isset($input['probation_result']) ? $this->getValueInEnum(ProbationResultEnum::cases(), $input['probation_result']) : null;

            $input['stage'] = $this->getCurrentStage($input);
            $input['status'] = $this->getCurrentStatus($input);
            $input['status_value'] = $this->getValueInEnum(CandidateEnum::cases(), $input['status']);
            $this->candidateRepo->create($input);
            return redirect()->back()->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $candidate = $this->candidateRepo->find($id);
            if ($candidate) {
                $candidateStatus = StringHelpers::getSelectCandidateEnumOptions(CandidateEnum::cases(), $candidate->status);
                $users = $this->userRepo->getActive();
                $departments = StringHelpers::getSelectOptions($this->departmentRepo->all(), $candidate->department_id);
                $positions = StringHelpers::getSelectOptions($this->positionRepo->all(), $candidate->position_id);
                $sources = StringHelpers::getSelectOptions($this->sourceRepo->all(), $candidate->source_id);
                $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases(), $candidate->gender);
                $provinces = DB::table('provinces')->get();
                $address = StringHelpers::getSelectProvinceOptions($provinces, $candidate->address);
                $household = StringHelpers::getSelectProvinceOptions($provinces, $candidate->household);
                $levels = StringHelpers::getSelectEnumOptions(LevelEnum::cases(), $candidate->level);
                $branchs = StringHelpers::getSelectEnumOptions(BranchEnum::cases(), $candidate->branch);
                $ranks = StringHelpers::getSelectEnumOptions(RankEnum::cases(), $candidate->rank);
                $status = CandidateEnum::cases();
                $receivers = StringHelpers::getSelectOptions($users, $candidate->receiver_id);
                $relationerCandidates = StringHelpers::getSelectOptions($users, explode(',', $candidate->relationer));
                $recruiters = StringHelpers::getSelectOptions($users, $candidate->recruiter);
                $interviewer = StringHelpers::getSelectOptions($users, $candidate->interviewer);
                $interviewer0 = StringHelpers::getSelectOptions($users, $candidate->interviewer0);

                $listInterviewResults = StringHelpers::getSelectEnumOptions(InterviewResultEnum::cases(), $candidate->interview_result);
                $listInterviewResults0 = StringHelpers::getSelectEnumOptions(InterviewResultEnum::cases(), $candidate->interview_result0);

                $examResults = StringHelpers::getSelectEnumOptions(ExamResultEnum::cases(), $candidate->exam_result);
                $recruitmentResults = StringHelpers::getSelectEnumOptions(RecruitmentResultEnum::cases(), $candidate->recruitment_result);
                $probationResults = StringHelpers::getSelectEnumOptions(ProbationResultEnum::cases(), $candidate->probation_result);

                $datas = $this->interviewScheduleRepo->getScheduleByCandidate($id);

                $canCreateSchedule = true;
                if (Str::contains($candidate->status, '_fail') == true) {
                    $canCreateSchedule = false;
                }

                $sampleData = new InterviewSchedule();
                $sampleData->id = $id;

                $count = count($datas);
                // if ($count == 0) {

                //     $datas[0] = clone $sampleData;
                //     $datas[1] = clone $sampleData;
                //     $datas[2] = clone $sampleData;

                //     $datas[0]['stage'] = 1;
                //     $datas[1]['stage'] = 2;
                //     $datas[2]['stage'] = 3;
                //     // dd('run');
                // }
                // if ($count == 1) {

                //     $datas[1] = clone $sampleData;
                //     $datas[2] = clone $sampleData;

                //     $datas[1]['interviewer'] = null;
                //     $datas[2]['interviewer'] = null;
                //     $datas[1]['stage'] = 2;
                //     $datas[2]['stage'] = 3;

                // }
                // if ($count == 2) {
                //     $datas[2] = clone $sampleData;
                //     $datas[2]['interviewer'] = null;
                //     $datas[2]['stage'] = 1;
                // }

                $status = InterviewScheduleEnum::cases();
                $listInterviewStatus = ['interview0_success', 'interview1_success', 'interview2_success'];
                // Danh sách phỏng vấn
                $interviewers = $this->userRepo->getActive();
                $listInterviewers = StringHelpers::getSelectOptions($interviewers);
                // Người liên quan
                // $relationers = StringHelpers::getSelectOptions($this->userRepo->getActive(), $data->relationer);
                $relationers = $this->userRepo->getActive();
                $interviewResults = InterviewResultEnum::cases();

                // Danh sách phỏng vấn
                $candidates = $this->candidateRepo->getIsInterview($listInterviewStatus);
                $stage = $candidate->stage;
                $candidate_id = $id;
                // $interviewAddress = StringHelpers::getSelectOptions($this->interviewAddressRepo->getActive(), $data->candidate_id);
                $interviewAddress = $this->interviewAddressRepo->getActive();
                // return view('recruitment.interview_schedule.edit', compact('candidate', 'datas', 'interviewResults', 'status', 'relationers', 'interviewers', 'data', 'candidates', 'interviewAddress'));
                // 'interviewResults', 'interviewResults0', 'interviewResults1', 'interviewResults2', 'interviewResults3','interviewer', 'interviewer0', 'interviewer1', 'interviewer2', 'interviewer3'
                return view('recruitment/candidate/edit', compact('interviewer', 'interviewer0', 'listInterviewResults', 'listInterviewResults0', 'canCreateSchedule', 'listInterviewers', 'candidate_id', 'stage', 'relationerCandidates', 'count', 'interviewers', 'candidate', 'candidates', 'interviewAddress', 'relationers', 'datas', 'examResults', 'recruitmentResults', 'probationResults', 'status', 'interviewResults', 'address', 'household', 'candidateStatus', 'users', 'genders', 'sources', 'positions', 'departments', 'provinces', 'levels', 'branchs', 'ranks', 'receivers', 'relationers', 'recruiters'));


            } else {
                return back()->with("error", "Không tìm thấy dữ liệu");
            }
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $input = $request->except(['is_sendmail_slhs']);
            $data = $this->candidateRepo->find($id);
            if (isset($input["schedule"])) {
                if($data->stage < 4){
                    if(strpos($data->status, '_success') === false){
                        return redirect()->back()->with('error', 'Ứng viên này chưa có kết quả phỏng vấn');
                    }
                }

                //$input['stage'] = $this->getCurrentStage($input);
                $input['status'] = $this->getCurrentStatus($input);
                if($input['status'] == CandidateEnum::new->name){
                    unset($input['status']);
                }
                else
                {
                    $input['exam_result_value'] = isset($input['exam_result']) ? $this->getValueInEnum(ExamResultEnum::cases(), $input['exam_result']) : null;
                    $input['recruitment_result_value'] = isset($input['recruitment_result']) ? $this->getValueInEnum(RecruitmentResultEnum::cases(), $input['recruitment_result']) : null;
                    $input['probation_result_value'] = isset($input['probation_result']) ? $this->getValueInEnum(ProbationResultEnum::cases(), $input['probation_result']) : null;
                    $input['stage'] = 4;
                    $input['status_value'] = $this->getValueInEnum(CandidateEnum::cases(), $input['status']);
                }

                $validator = Validator::make($input, $this->candidateRepo->validateTakeJob());
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $res = $data->update($input);
                return redirect()->back()->with('success', 'Thành công');
            }

            $validator = Validator::make($input, $this->candidateRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // $input['level_value'] = $this->getValueInEnum(LevelEnum::cases(), $input['level']);
            $input['gender_value'] = $this->getValueInEnum(GenderEnum::cases(), $input['gender']);
            // $input['branch_value'] = $this->getValueInEnum(BranchEnum::cases(), $input['branch']);
            // $input['rank_value'] = $this->getValueInEnum(RankEnum::cases(), $input['rank']);
            $input['interview_result_value'] = isset($input['interview_result']) ? $this->getValueInEnum(InterviewResultEnum::cases(), $input['interview_result']) : null;

            // $input['interview_result1_value'] = $this->getValueInEnum(InterviewResultEnum::cases(), $input['interview_result1']);
            // $input['interview_result2_value'] = $this->getValueInEnum(InterviewResultEnum::cases(), $input['interview_result2']);
            // $input['interview_result3_value'] = $this->getValueInEnum(InterviewResultEnum::cases(), $input['interview_result3']);

            $input['exam_result_value'] = isset($input['exam_result']) ? $this->getValueInEnum(ExamResultEnum::cases(), $input['exam_result']) : null;
            $input['recruitment_result_value'] = isset($input['recruitment_result']) ? $this->getValueInEnum(RecruitmentResultEnum::cases(), $input['recruitment_result']) : null;
            $input['probation_result_value'] = isset($input['probation_result']) ? $this->getValueInEnum(ProbationResultEnum::cases(), $input['probation_result']) : null;

            $input['stage'] = $this->getCurrentStage($input);
            $input['status'] = $this->getCurrentStatus($input);
            $input['status_value'] = $this->getValueInEnum(CandidateEnum::cases(), $input['status']);

            if (isset($request->is_sendmail_slhs)) {
                if (isset($input['interview_result']) && $input['interview_result'] != InterviewResultEnum::obtain) {
                    $mailTemplate = $this->mailTemplateRepo->findBy('code', ScheduleMailEnum::thu_cam_on_ung_tuyen->name);
                    if ($mailTemplate) {
                        $emailContent = $mailTemplate->body;
                        $emailContent = $this->replaceGender($emailContent, $data->gender);
                        // $emailContent = $this->replaceTime($emailContent, $candidate);
                        $processedEmail = $this->replacePlaceholders($emailContent, $data);
                        $subject = $mailTemplate->name;
                        $content = $processedEmail;
                        $to = $data->email;
                        // $cc = $this->userRepo->getMailByListId($interviewDatas);
                        //dd($cc);
                        Mail::to($to)->send(new SendEmail(subject: $subject, content: $content));
                    }
                }
            }

            if (isset($input['relationer'])) {
                $input['relationer'] = implode(",", $input['relationer']);
            }
            $res = $data->update($input);
            return redirect()->back()->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function exportCandidate()
    {
        $candidates = $this->candidateRepo->queryAll()
            ->with([
                'createdBy:id,name',
                'position:id,name',
                'department:id,name',
                'source:id,name',
                'receive:id,name',
                'address:code,name',
                'household:code,name',
                'interviewer',
                'interviewer0:name',
                'interviewer1:name',
                'interviewer2:name',
                'interviewer3:name',
            ])
            ->where('active', '=', true)
            ->orderBy('received_time', 'desc')
            ->get();

            $datas = [];
            foreach($candidates as $item)  {
                $datas[] = [
                    $item->name ?? null,
                    $item->birthday ?? null,
                    $item->status_value ?? null,
                    $item->position->name ?? null,
                    $item->department->name ?? null,
                    $item->received_time ?? null,
                    $item->receive->name ?? null,
                    $item->source->name ?? null,
                    $item->relationship_note ?? null,
                    $item->gender_value ?? null,
                    $item->phone_number ?? null,
                    $item->email ?? null,
                    $item->household->name ?? null,
                    $item->address->name ?? null,
                    $item->address_detail ?? null,
                    $item->training_process ?? null,
                    $item->experience ?? null,
                    $item->languague ?? null,
                    $item->skill ?? null,
                    $item->info1 ?? null,

                    //SLHS
                    $item->interview_date ?? null,
                    $item->interviewer->name ?? null,
                    $item->interview_comment ?? null,
                    $item->interview_result_value ?? null,
                    //PVSB
                    $item->interview_date0 ?? null,
                    $item->interviewer0->name ?? null,
                    $item->interview_comment0 ?? null,
                    $item->interview_result0_value ?? null,
                    //PVV1
                    $item->interview_date1 ?? null,
                    $item->interviewer1->name ?? null,
                    $item->interview_comment1 ?? null,
                    $item->interview_result1_value ?? null,
                    //PVV2
                    $item->interview_date2 ?? null,
                    $item->interviewer2->name ?? null,
                    $item->interview_comment2 ?? null,
                    $item->interview_result2_value ?? null,
                    //PVV3
                    $item->interview_date3 ?? null,
                    $item->interviewer3->name ?? null,
                    $item->interview_comment3 ?? null,
                    $item->interview_result3_value ?? null,
                    //Điểm thi tuyển
                    // $item->score ?? null,
                    // $item->exam_result ?? null,
                    // $item->exam_result_value ?? null,
                    $item->recruitment_result_value ?? null,
                    $item->probation_from ?? null,
                    $item->probation_to ?? null,
                    $item->probation_result_value ?? null,

                    // $item->stage ?? null,
                    // $item->status ?? null,
                    // $item->status_value ?? null,
                    // $item->created_by ?? null,
                ];
            }

        return Excel::download(new ExportCandidate($datas), 'Danh sách ứng viên - ' . now() .'.xlsx');
    }

    public function comment(Request $request, $candidate_id)
    {
        try {
            $candidate = $this->candidateRepo->find($candidate_id);
            $timeline = $candidate->timeline ?? [];
            $input = $request->all();
            $input['content'] = "đã thêm comment";
            $input['created_by'] = Auth::user()->id;
            $input['created_at'] = Carbon::now();
            $input['created_name'] = Auth::user()->name;
            $input['action'] = Candidate::ACTION_COMMENT;
            $input['candidate_id'] = $candidate_id;
            $timeline[] = $input;
            $candidate->timeline = $timeline;
            $candidate->save();

            return back()->with('success', "Thêm mới comment thành công");
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function getCurrentStage($input)
    {
        $stage = -2;

        if (isset($input['interview_result']) || isset($input['interviewer'])) {
            $stage = -1;
        }

        if (isset($input['interview_result0']) || isset($input['interviewer0'])) {
            $stage = 0;
        }

        if (isset($input['interview_result1']) || isset($input['interviewer1'])) {
            $stage = 1;
        }

        if (isset($input['interview_result2']) || isset($input['interviewer2'])) {
            $stage = 2;
        }

        if (isset($input['interview_result3']) || isset($input['interviewer3'])) {
            $stage = 3;
        }

        if (isset($input['recruitment_result']) || isset($input['recruitment_result'])) {
            $stage = 4;
        }

        if (isset($input['probation_result']) || isset($input['probation_result'])) {
            $stage = 5;
        }

        return $stage;
    }

    public function syncCurrentStage()
    {
        $candidates = $this->candidateRepo->all();
        foreach ($candidates as $candidate) {
            $stage = $this->getCurrentStage($candidate);
            $this->candidateRepo->find($candidate->id)->update(['stage' => $stage]);
        }
        return 'Sync thành công';
    }
    public function getCurrentStatus($input)
    {
        $status = CandidateEnum::new ->name;
        $stage = 0;

        if (isset($input['interview_result']) && $input['interview_result'] != null) {
            if ($input['interview_result'] == InterviewResultEnum::obtain->name) {
                $status = CandidateEnum::interview_success->name;
            } else {
                $status = CandidateEnum::interview_fail->name;
            }
        }

        if (isset($input['interview_result0']) && $input['interview_result0'] != null) {
            if ($input['interview_result0'] == InterviewResultEnum::obtain->name) {
                $status = CandidateEnum::interview0_success->name;
            } else {
                $status = CandidateEnum::interview0_fail->name;
            }
        }

        if (isset($input['interview_result1']) && $input['interview_result1'] != null) {
            if ($input['interview_result1'] == InterviewResultEnum::obtain->name) {
                $status = CandidateEnum::interview1_success->name;
            } else {
                $status = CandidateEnum::interview1_fail->name;
            }
        }

        if (isset($input['interview_result2']) && $input['interview_result2'] != null) {
            if ($input['interview_result2'] == InterviewResultEnum::obtain->name) {
                $status = CandidateEnum::interview2_success->name;
            } else {
                $status = CandidateEnum::interview2_fail->name;
            }
        }

        if (isset($input['interview_result3']) && $input['interview_result3'] != null) {
            if ($input['interview_result3'] == InterviewResultEnum::obtain->name) {
                $status = CandidateEnum::interview3_success->name;
            } else {
                $status = CandidateEnum::interview3_fail->name;
            }
        }

        if (isset($input['exam_result']) && $input['exam_result'] != null) {
            if ($input['exam_result'] == ExamResultEnum::obtain->name) {
                $status = CandidateEnum::exam_success->name;
            } else {
                $status = CandidateEnum::exam_fail->name;
            }
        }

        if (isset($input['recruitment_result']) && $input['recruitment_result'] != null) {
            if ($input['recruitment_result'] == RecruitmentResultEnum::salary_deal->name) {
                $status = CandidateEnum::salary_deal->name;
            }
            elseif ($input['recruitment_result'] == RecruitmentResultEnum::take_job->name) {
                $status = CandidateEnum::recruitment_success->name;
            } else {
                $status = CandidateEnum::recruitment_fail->name;
            }
        }

        if (isset($input['probation_result']) && $input['probation_result']) {
            if ($input['probation_result'] == ProbationResultEnum::obtain->name) {
                $status = CandidateEnum::probation_success->name;
            } elseif ($input['probation_result'] == ProbationResultEnum::again->name) {
                $status = CandidateEnum::probation_long->name;
            } else {
                $status = CandidateEnum::probation_fail->name;
            }
        }

        return $status;
    }

    public function changeCanEdit($id)
    {
        try {
            $data = $this->candidateRepo->find($id);
            if ($data->is_submit == true) {
                $data->update(['can_edit' => true]);
            } else {
                return $this->error('Ứng viên phải hoàn thiện form biểu mẫu trước khi thực hiện chức năng này');
            }
            return $this->success('Thành công');
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function changeToCandidate($id)
    {
        try {
            $data = $this->candidateRepo->find($id);
            if ($data->is_submit == true) {
                $data->update(['active' => true]);
            } else {
                return $this->error('Ứng viên phải hoàn thiện form biểu mẫu trước khi thực hiện chức năng này');
            }
            return $this->success('Thành công');
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function importExcel(Request $request)
    {
        try {
            //$test = $this->getEnumNamesPre(CandidateEnum::cases(), 'employee');
            $recordImported = 0;
            $recordFailed = 0;
            $file = $request->file('excel');
            $validatedData = $request->validate([
                'excel' => 'required|mimes:xlsx,csv,xls|max:10120',
                // 10MB
            ]);
            //$rows = SimpleExcelReader::create($file->getPathName())->skipRows(1)->getRows();
            $spreadsheet = IOFactory::load($file->getPathName());
            $worksheet = $spreadsheet->getActiveSheet();

            //Lấy các style của file import
            $headerStyle = $worksheet->getStyle('A1')->getFont()->getBold();
            $colunmStyle = $worksheet->getStyle('A2')->getFont()->setSize(10);
            //Lấy cột cuối cùng và thêm tiêu đề
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
            $noteColumn = Coordinate::stringFromColumnIndex($highestColumnIndex + 1);
            $worksheet->getStyle($noteColumn . '1')->getFont()->setBold($headerStyle);
            $worksheet->setCellValue($noteColumn . '1', 'Trạng thái import');

            $rows = $worksheet->toArray();
            foreach ($rows as $index => $row) {
                $input = [];
                if ($index <= 1)
                    continue;
                // $input['duplicate_code'] = $row[1];
                $input['name'] = $row[1];
                $input['birthday'] = $row[2] != null ? date('Y-m-d', strtotime($row[2])) : null;
                $input['position_id'] = $this->positionRepo->findByPluck('name', $row[3], 'id');
                $input['department_id'] = $this->departmentRepo->findByPluck('uid', $row[4], 'id');
                $input['received_time'] = $row[5] != null ? date('Y-m-d', strtotime($row[5])) : null;
                $input['receiver_id'] = $this->userRepo->findByPluck('name', $row[6], 'id');
                $input['source_id'] = $this->sourceRepo->findByPluck('name', $row[7], 'id');
                $input['relationship_note'] = $row[8];
                $input['gender_value'] = str_replace(' ', '', $row[9]);
                $input['gender'] = $this->getNameInEnumByValue(GenderEnum::cases(), $input['gender_value']);
                $input['phone_number'] = str_replace(' ', '', $row[10]);
                $input['email'] = str_replace(' ', '', $row[11]);
                $input['household'] = DB::table('provinces')->where('name', $row[12])->pluck('code')->first();
                $input['address'] = DB::table('provinces')->where('name', $row[13])->pluck('code')->first();
                $input['address_detail'] = $row[14];
                $input['training_process'] = $row[15];
                $input['languague'] = $row[16];
                $input['skill'] = $row[17];
                $input['info1'] = $row[18];
                $input['experience'] = $row[19];
                //SLHS
                $input['interviewer'] = $this->userRepo->findByPluck('name', $row[20], 'id');
                $input['interview_comment'] = $row[21];
                $input['interview_result'] = $this->getNameInEnumByValue(InterviewResultEnum::cases(), $row[22]);
                $input['interview_result_value'] = $row[22];
                //PVSB
                $input['interview_date0'] = $row[23] != null ? date('Y-m-d', strtotime($row[23])) : null;
                $input['interviewer0'] = $this->userRepo->findByPluck('name', $row[24], 'id');
                $input['interview_comment0'] = $row[25];
                $input['interview_result0'] = $this->getNameInEnumByValue(InterviewResultEnum::cases(), $row[26]);
                $input['interview_result0_value'] = $row[26];
                //PVV1
                $input['interview_date1'] = $row[27] != null ? date('Y-m-d', strtotime($row[27])) : null;
                $input['interviewer1'] = $this->userRepo->findByPluck('name', $row[28], 'id');
                $input['interview_comment1'] = $row[29];
                $input['interview_result1'] = $this->getNameInEnumByValue(InterviewResultEnum::cases(), $row[30]);
                $input['interview_result1_value'] = $row[30];
                //PVV2
                $input['interview_date2'] = $row[31] != null ? date('Y-m-d', strtotime($row[31])) : null;
                $input['interviewer2'] = $this->userRepo->findByPluck('name', $row[32], 'id');
                $input['interview_comment2'] = $row[33];
                $input['interview_result2'] = $this->getNameInEnumByValue(InterviewResultEnum::cases(), $row[34]);
                $input['interview_result2_value'] = $row[34];
                //PVV3
                $input['interview_date3'] = $row[35] != null ? date('Y-m-d', strtotime($row[35])) : null;
                $input['interviewer3'] = $this->userRepo->findByPluck('name', $row[36], 'id');
                $input['interview_comment3'] = $row[37];
                $input['interview_result3'] = $this->getNameInEnumByValue(InterviewResultEnum::cases(), $row[38]);
                $input['interview_result3_value'] = $row[38];
                //Điểm thi tuyển
                $input['score'] = $row[39];
                $input['exam_result'] = $row[40] != null ? $this->getNameInEnumByValue(ExamResultEnum::cases(), $row[40]) : null;
                $input['exam_result_value'] = $row[40];
                $input['recruitment_result'] = $row[41] != null ? $this->getNameInEnumByValue(RecruitmentResultEnum::cases(), $row[41]) : null;
                $input['recruitment_result_value'] = $row[41];
                $input['probation_result'] = $row[43] != null ? $this->getNameInEnumByValue(ProbationResultEnum::cases(), $row[43]) : null;
                $input['probation_result_value'] = $row[43];

                $input['stage'] = $this->getCurrentStage($input);
                $input['status'] = $this->getCurrentStatus($input);
                $input['status_value'] = $this->getValueInEnum(CandidateEnum::cases(), $input['status']);

                $input['created_by'] = Auth::user()->id;
                $input['active'] = 1;
                // dd($input);
                //Validate
                $validator = Validator::make($input, $this->candidateRepo->validateImport());
                // if ($validator->fails()) {
                //     continue;
                //     return $this->error(implode(",", $validator->messages()->all()));
                // }
                //Check trùng trong database
                $strName = '%' . $input['name'] . '%';
                $checkDuplicate = Candidate::where([
                    ['name', 'LIKE', $strName],
                    ['birthday', '=', $input['birthday']],
                    ['phone_number', '=', $input['phone_number']],
                    ['email', '=', $input['email']],
                ])->pluck('id')->first();

                //Thêm trạng thái vào dòng lỗi trùng data
                if ($checkDuplicate) {
                    $worksheet->setCellValue($noteColumn . $index + 1, 'Dữ liệu đã tồn tại');
                    $worksheet->getStyle($noteColumn . $index + 1)->getFont()->setSize(8);
                    // Đặt màu nền cho dòng bị lỗi
                    $worksheet->getStyle('A' . $index + 1 . ':' . 'AS' . $index + 1)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFFF0000');
                    $recordFailed++;
                    continue;
                }

                //Thêm trạng thái vào dòng lỗi validate
                if ($validator->fails()) {
                    $worksheet->setCellValue($noteColumn . $index + 1, $validator->errors()->first());
                    $worksheet->getStyle($noteColumn . $index + 1)->getFont()->setSize(8);

                    // Đặt màu nền cho dòng bị lỗi
                    $worksheet->getStyle('A' . $index + 1 . ':' . 'AS' . $index + 1)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFFF0000');
                    $recordFailed++;
                    continue;
                }
                $res = $this->candidateRepo->create($input);
                $recordImported++;
                $worksheet->setCellValue($noteColumn . $index + 1, "Thành công");
                $worksheet->getStyle($noteColumn . $index + 1)->getFont()->setSize(8);
                $worksheet->getStyle('A' . $index + 1 . ':' . 'AS' . $index + 1)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('ff59ce72');
                //$inputStatusHistory = [];
                // $listStatusPrevs = $this->getEnumNamesPre(CandidateEnum::cases(), $input['status']);
                //
                // foreach ($listStatusPrevs as $listStatusPrev) {
                //     $inputStatusHistory['candidate_id'] = $res->id;
                //     $inputStatusHistory['new_status'] = $listStatusPrev;
                //     $this->candidateStatusHistoryRepo->create($inputStatusHistory);
                // }
            }
            $filename = Str::random(40) . '.xlsx';
            $savePath = public_path('imports/excel/' . $filename);
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($savePath);

            $inputImport['created_by'] = Auth::user()->id;
            $inputImport['type'] = ImportStatus::TYPE_CANDIDATE;
            $inputImport['status'] = 1;
            $inputImport['note'] = "Đã xử lý";
            $inputImport['record_imported'] = $recordImported;
            $inputImport['record_failed'] = $recordFailed;
            $inputImport['total_row'] = $worksheet->getHighestDataRow() - 2;
            $inputImport['filename'] = $file->getClientOriginalName();
            $inputImport['file'] = '/imports/excel/' . $filename;
            $this->importStatusRepo->create($inputImport);
            return $this->success($inputImport, "Thành công");

        } catch (\Illuminate\Validation\ValidationException $e) {
            // $worksheet->setCellValue($noteColumn . $index + 1, $e->validator->errors()->first());
            // $worksheet->getStyle($noteColumn . $index + 1)->getFont()->setSize(8);

            // // Đặt màu nền cho dòng bị lỗi
            // $worksheet->getStyle('A' . $index + 1 . ':' . 'AS' . $index + 1)
            //     ->getFill()
            //     ->setFillType(Fill::FILL_SOLID)
            //     ->getStartColor()
            //     ->setARGB('FFFF0000');
            // $recordFailed++;

            $filename = Str::random(40) . '.xlsx';
            $savePath = public_path('imports/excel/' . $filename);
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($savePath);

            $inputImport['created_by'] = Auth::user()->id;
            $inputImport['type'] = ImportStatus::TYPE_CANDIDATE;
            $inputImport['status'] = 0;
            $inputImport['note'] = $e->validator->errors()->first();
            $inputImport['record_imported'] = $recordImported;
            $inputImport['record_failed'] = $recordFailed;
            $inputImport['total_row'] = 0;
            $inputImport['filename'] = $file->getClientOriginalName();
            $inputImport['file'] = '/imports/excel/' . $filename;
            $this->importStatusRepo->create($inputImport);
            return $this->error($e->validator->errors()->first());
        } catch (Exception $e) {
            // $worksheet->setCellValue($noteColumn . $index + 1, $e->getMessage());
            // $worksheet->getStyle($noteColumn . $index + 1)->getFont()->setSize(8);

            // // Đặt màu nền cho dòng bị lỗi
            // $worksheet->getStyle('A' . $index + 1 . ':' . 'AS' . $index + 1)
            //     ->getFill()
            //     ->setFillType(Fill::FILL_SOLID)
            //     ->getStartColor()
            //     ->setARGB('FFFF0000');
            // $recordFailed++;

            $filename = Str::random(40) . '.xlsx';
            $savePath = public_path('imports/excel/' . $filename);
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($savePath);

            $inputImport['created_by'] = Auth::user()->id;
            $inputImport['type'] = ImportStatus::TYPE_CANDIDATE;
            $inputImport['status'] = 0;
            $inputImport['note'] = $e->getMessage();
            $inputImport['record_imported'] = $recordImported;
            $inputImport['record_failed'] = $recordFailed;
            $inputImport['total_row'] = 0;
            $inputImport['filename'] = $file->getClientOriginalName();
            $inputImport['file'] = '/imports/excel/' . $filename;
            $this->importStatusRepo->create($inputImport);
            return $this->error($e->getMessage());
        }
    }

    public function importExcelUpdateData(Request $request)
    {
        try {
            //$test = $this->getEnumNamesPre(CandidateEnum::cases(), 'employee');
            $recordImported = 0;
            $recordFailed = 0;
            $file = $request->file('excel');
            $validatedData = $request->validate([
                'excel' => 'required|mimes:xlsx,csv,xls|max:10120',
                // 10MB
            ]);
            //$rows = SimpleExcelReader::create($file->getPathName())->skipRows(1)->getRows();
            $spreadsheet = IOFactory::load($file->getPathName());
            $worksheet = $spreadsheet->getActiveSheet();

            //Lấy các style của file import
            $headerStyle = $worksheet->getStyle('A1')->getFont()->getBold();
            $colunmStyle = $worksheet->getStyle('A2')->getFont()->setSize(10);
            //Lấy cột cuối cùng và thêm tiêu đề
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
            $noteColumn = Coordinate::stringFromColumnIndex($highestColumnIndex + 1);
            $worksheet->getStyle($noteColumn . '1')->getFont()->setBold($headerStyle);
            $worksheet->setCellValue($noteColumn . '1', 'Trạng thái import');

            $rows = $worksheet->toArray();
            foreach ($rows as $index => $row) {
                $input = [];
                if ($index < 1)
                    continue;
                // $input['duplicate_code'] = $row[1];
                $input['user_uid'] = $row[0];

                $strName = '%' . $row[1] . '%';
                $candidateId = Candidate::where([
                    ['name', 'LIKE', $strName],
                    ['phone_number', '=', $row[5]],
                ])->pluck('id')->first();

                if ($candidateId) {
                    $input['user_uid'] = $row[0];
                    $input['recruitment_result'] = $row[7] != null ? $this->getNameInEnumByValue(RecruitmentResultEnum::cases(), $row[7]) : null;
                    $input['recruitment_result_value'] = $row[7];
                    $input['probation_result'] = $row[8] != null ? $this->getNameInEnumByValue(ProbationResultEnum::cases(), $row[8]) : null;
                    $input['probation_result_value'] = $row[8];
                    $input['probation_from'] = $row[10] != null ? date('Y-m-d', strtotime($row[10])) : null;
                    $input['probation_to'] = $row[11] != null ? date('Y-m-d', strtotime($row[11])) : null;
                    $input['status'] = $this->getCurrentStatus($input);
                    $input['status_value'] = $this->getValueInEnum(CandidateEnum::cases(), $input['status']);
                    $input['stage'] = $this->getCurrentStage($input);
                    Candidate::where('id', $candidateId)->update($input);
                } else {
                    $worksheet->setCellValue($noteColumn . $index + 1, 'Dữ liệu không tồn tại');
                    $worksheet->getStyle($noteColumn . $index + 1)->getFont()->setSize(8);
                    // Đặt màu nền cho dòng bị lỗi
                    $worksheet->getStyle('A' . $index + 1 . ':' . 'AS' . $index + 1)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFFF0000');
                    $recordFailed++;
                    continue;
                }

                $recordImported++;
                $worksheet->setCellValue($noteColumn . $index + 1, "Thành công");
                $worksheet->getStyle($noteColumn . $index + 1)->getFont()->setSize(8);
                $worksheet->getStyle('A' . $index + 1 . ':' . 'AS' . $index + 1)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('ff59ce72');
            }
            $filename = Str::random(40) . '.xlsx';
            $savePath = public_path('imports/excel/' . $filename);
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($savePath);

            $inputImport['created_by'] = Auth::user()->id;
            $inputImport['type'] = ImportStatus::TYPE_CANDIDATE;
            $inputImport['status'] = 1;
            $inputImport['note'] = "Đã xử lý";
            $inputImport['record_imported'] = $recordImported;
            $inputImport['record_failed'] = $recordFailed;
            $inputImport['total_row'] = $worksheet->getHighestDataRow() - 2;
            $inputImport['filename'] = $file->getClientOriginalName();
            $inputImport['file'] = '/imports/excel/' . $filename;
            $this->importStatusRepo->create($inputImport);
            return $this->success($inputImport, "Thành công");

        } catch (\Illuminate\Validation\ValidationException $e) {
            // $worksheet->setCellValue($noteColumn . $index + 1, $e->validator->errors()->first());
            // $worksheet->getStyle($noteColumn . $index + 1)->getFont()->setSize(8);

            // // Đặt màu nền cho dòng bị lỗi
            // $worksheet->getStyle('A' . $index + 1 . ':' . 'AS' . $index + 1)
            //     ->getFill()
            //     ->setFillType(Fill::FILL_SOLID)
            //     ->getStartColor()
            //     ->setARGB('FFFF0000');
            // $recordFailed++;

            $filename = Str::random(40) . '.xlsx';
            $savePath = public_path('imports/excel/' . $filename);
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($savePath);

            $inputImport['created_by'] = Auth::user()->id;
            $inputImport['type'] = ImportStatus::TYPE_CANDIDATE;
            $inputImport['status'] = 0;
            $inputImport['note'] = $e->validator->errors()->first();
            $inputImport['record_imported'] = $recordImported;
            $inputImport['record_failed'] = $recordFailed;
            $inputImport['total_row'] = 0;
            $inputImport['filename'] = $file->getClientOriginalName();
            $inputImport['file'] = '/imports/excel/' . $filename;
            $this->importStatusRepo->create($inputImport);
            return $this->error($e->validator->errors()->first());
        } catch (Exception $e) {
            // $worksheet->setCellValue($noteColumn . $index + 1, $e->getMessage());
            // $worksheet->getStyle($noteColumn . $index + 1)->getFont()->setSize(8);

            // // Đặt màu nền cho dòng bị lỗi
            // $worksheet->getStyle('A' . $index + 1 . ':' . 'AS' . $index + 1)
            //     ->getFill()
            //     ->setFillType(Fill::FILL_SOLID)
            //     ->getStartColor()
            //     ->setARGB('FFFF0000');
            // $recordFailed++;

            $filename = Str::random(40) . '.xlsx';
            $savePath = public_path('imports/excel/' . $filename);
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($savePath);

            $inputImport['created_by'] = Auth::user()->id;
            $inputImport['type'] = ImportStatus::TYPE_CANDIDATE;
            $inputImport['status'] = 0;
            $inputImport['note'] = $e->getMessage();
            $inputImport['record_imported'] = $recordImported;
            $inputImport['record_failed'] = $recordFailed;
            $inputImport['total_row'] = 0;
            $inputImport['filename'] = $file->getClientOriginalName();
            $inputImport['file'] = '/imports/excel/' . $filename;
            $this->importStatusRepo->create($inputImport);
            return $this->error($e->getMessage());
        }
    }

    public function delete()
    {
        dd('Xoá tạm');
        return 1;
    }

    public function destroy($id)
    {
        try {
            $this->candidateRepo->destroy($id);
            return $this->success("Thành công");
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

}
