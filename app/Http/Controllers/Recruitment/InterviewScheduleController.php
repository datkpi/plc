<?php

namespace App\Http\Controllers\Recruitment;

use Exception;
use Validator;
use App\Mail\SendEmail;
use App\Models\Candidate;
use App\Enums\CandidateEnum;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use Illuminate\Support\Carbon;
use App\Enums\ScheduleMailEnum;
use App\Common\Traits\EnumHandle;
use App\Models\InterviewSchedule;
use App\Enums\InterviewResultEnum;
use Illuminate\Support\Facades\DB;
use App\Common\Traits\ApiResponses;
use App\Enums\InterviewScheduleEnum;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Repositories\Recruitment\CandidateRepository;
use App\Common\Traits\PlaceholderReplacer;
use App\Repositories\Recruitment\RequestFormRepository;
use App\Repositories\Recruitment\MailTemplateRepository;
use App\Repositories\Recruitment\RecruitmentPlanRepository;
use App\Repositories\Recruitment\InterviewAddressRepository;
use App\Repositories\Recruitment\InterviewScheduleRepository;

class InterviewScheduleController extends Controller
{
    use ApiResponses, EnumHandle, PlaceholderReplacer;
    public function __construct(MailTemplateRepository $mailTemplateRepo, InterviewAddressRepository $interviewAddressRepo, CandidateRepository $candidateRepo, InterviewScheduleRepository $interviewScheduleRepo, UserRepository $userRepo, RequestFormRepository $requestFormRepo, RecruitmentPlanRepository $recruitmentPlanRepo)
    {
        $this->interviewScheduleRepo = $interviewScheduleRepo;
        $this->requestFormRepo = $requestFormRepo;
        $this->recruitmentPlanRepo = $recruitmentPlanRepo;
        $this->candidateRepo = $candidateRepo;
        $this->interviewAddressRepo = $interviewAddressRepo;
        $this->userRepo = $userRepo;
        $this->mailTemplateRepo = $mailTemplateRepo;
    }

    public function index()
    {
        $listInterviewStatus = ['interview0_success', 'interview1_success', 'interview2_success'];
        //Lấy danh sách ứng viên đã được pvsb đạt thì mới được mời phỏng vấn
        $candidates = $this->candidateRepo->getIsInterview($listInterviewStatus);
        // $datas = $this->interviewScheduleRepo->queryAll()->with('candidates')->get();
        return view('recruitment/interview_schedule/index', compact('candidates'));
    }

    public function getData()
    {
        try {
            $currentDate = Carbon::now()->toDateString();
            $datas = $this->interviewScheduleRepo->queryAll()->select(
                'candidate_id as candidate_id',
                'name as text',
                'id as id',
                'interview_from as startDate',
                'interview_to as endDate',
            )->selectRaw('CASE WHEN interview_from = ? THEN true ELSE false END AS now', [$currentDate])->with('candidates')->get();
            return $this->success($datas);
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function getInterviewerData(Request $request, $id)
    {
        try {
            $datas = InterviewSchedule::where('interviewer', 'LIKE', "%,$id,%") // middle of the string
                ->orWhere('interviewer', 'LIKE', "$id,%") // start of the string
                ->orWhere('interviewer', 'LIKE', "%,$id") // end of the string
                ->orWhere('interviewer', $id) // just the interviewer_id
                ->get();
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function evaluate(Request $request, $id)
    {
        try {
            $data = $this->interviewScheduleRepo->find($id);
            if ($data) {
                $input = $request->all();
                $items = $data->interviewer;

                foreach ($items as $key => $item) {
                    if ($item['user_id'] == $input['userId']) {
                        if ($item['can_evaluate'] == true) {
                            $items[$key]['comment'] = $input['comment'];
                            $items[$key]['result'] = $input['selectedValue'];
                            $data->update(['interviewer' => $items]);
                            break;
                        } else {
                            return $this->success('Không thành công do người này không có quyền đánh giá');
                        }
                    }
                }
                return $this->success($data);
            }
            return $this->error('Không tìm thấy data');
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function create(Request $request)
    {

        $start_date = isset($request->start) ? date('Y-m-d\TH:i', strtotime($request->start)) : date('Y-m-d\TH:i', strtotime(Carbon::now()));
        $end_date = isset($request->end) ? date('Y-m-d\TH:i', strtotime($request->end)) : date('Y-m-d\TH:i', strtotime(Carbon::now()));
        //$selectedStatus = CandidateEnum::interview2_success;
        // $listInterviewStatus = $this->getEnumValuesInRange(CandidateEnum::cases(), $selectedStatus);
        $listInterviewStatus = ['interview0_success', 'interview1_success', 'interview2_success'];
        //Lấy danh sách ứng viên đã được pvsb đạt thì mới được mời phỏng vấn
        $users = StringHelpers::getSelectOptions($this->userRepo->getActive());
        $candidates = $this->candidateRepo->getIsInterview($listInterviewStatus);
        $interviewers = StringHelpers::getSelectOptions($this->userRepo->getActive());
        $interviewAddress = StringHelpers::getSelectOptions($this->interviewAddressRepo->getActive());
        return view('recruitment/interview_schedule/create', compact('interviewers', 'interviewAddress', 'start_date', 'end_date', 'users', 'candidates'));
    }

    //Thêm lịch pvsb vào lịch phỏng

    public function syncPvsb(Request $request)
    {
        $candidates = \App\Models\Candidate::whereIn('stage', [0, 1, 2,3])->get();
        foreach ($candidates as $candidate) {
            $stage = 0;
            $i = 0;
            $attribute_date = "interview_date" . $i;
            $attribute_comment = "interview_comment" . $i;
            $attribute_result = "interview_result" . $i;
            $attribute_interviewer = "interviewer" . $i;
            $interview_date = $candidate->$attribute_date;
            $interview_comment = $candidate->$attribute_comment;
            $interview_result = $candidate->$attribute_result;
            $interviewer = $candidate->$attribute_interviewer;

            $inputNew = [];
            $key = 0;
            $inputNew[$key]['comment'] = $interview_comment;
            $inputNew[$key]['result'] = $interview_result;
            $inputNew[$key]['interview_date'] = $interview_date;
            $inputNew[$key]['user_id'] = $interviewer;
            $inputNew[$key]['can_evaluate'] = true;
            $inputNew[$key]['final_evaluate'] = true;

            $input['candidate_id'] = $candidate->id;
            $input['name'] = 'Phỏng vấn ' . $candidate->name . ' lần ' . $i;
            $input['interview_from'] = $interview_date;
            $input['interview_to'] = $interview_date;
            $input['address'] = null;
            $input['stage'] = $i;

            $input['active'] = 1;

            $input['interviewer'] = $inputNew;
            $input['can_evaluate'] = $interviewer;
            $input['final_evaluate'] = $interviewer;
            $input['created_by'] = Auth::user()->id;
            if ($interview_result == null) {
                $input['status'] = InterviewScheduleEnum::active->name;
            } else {
                $input['status'] = InterviewScheduleEnum::success->name;
            }
            $res = $this->interviewScheduleRepo->create($input);
        }

        $candidates = $this->candidateRepo->getInterviewSchedule();

    }

    // Vì 1 ứng viên có thể chưa có dữ liệu lịch phỏng vấn cũ nên hiển thị ra view bị sai vị trí tab => đồng bộ lại or tạo mới dữ liệu lịch phỏng vấn
    public function sync(Request $request)
    {
        $candidates = $this->candidateRepo->all();
        // $candidates = Candidate::where('id', '9a2f126f-903f-417e-981a-4859f730aa5b')->get();
        foreach ($candidates as $candidate) {
            $stage = $this->getCurrentStage($candidate);
            $interviewStage = $this->getInterviewStage($candidate);

            if($stage != $candidate->stage){
                Candidate::where('id', $candidate->id)->update(['stage' => $stage]);
            }

            if($interviewStage < 0)
            {
                continue;
            }

            for ($i = 0; $i <= $interviewStage; $i++) {
                //dd($candidate->id);
                $checkHasData = InterviewSchedule::where('candidate_id', $candidate->id)->where('stage', $i)->first();
                if($checkHasData)
                {
                    continue;
                }
                $attribute_date = "interview_date" . $i;
                $attribute_comment = "interview_comment" . $i;
                $attribute_result = "interview_result" . $i;
                $attribute_interviewer = "interviewer" . $i;
                $interview_date = $candidate->$attribute_date;
                $interview_comment = $candidate->$attribute_comment;
                $interview_result = $candidate->$attribute_result;
                $interviewer = $candidate->$attribute_interviewer;

                $inputNew = [];
                $key = 0;
                $inputNew[$key]['comment'] = $interview_comment;
                $inputNew[$key]['result'] = $interview_result;
                $inputNew[$key]['interview_date'] = $interview_date;
                $inputNew[$key]['user_id'] = $interviewer;
                $inputNew[$key]['can_evaluate'] = true;
                $inputNew[$key]['final_evaluate'] = true;

                $input['candidate_id'] = $candidate->id;
                if($i > 0){
                    $input['name'] = 'Phỏng vấn ứng viên ' . $candidate->name . ' lần ' . $i;
                }
                else{
                    $input['name'] = 'Phỏng vấn sơ bộ ứng viên ' . $candidate->name;
                }
                $input['interview_from'] = $interview_date;
                $input['interview_to'] = $interview_date;
                $input['address'] = null;
                $input['stage'] = $i;

                $input['active'] = 1;

                $input['interviewer'] = $inputNew;
                $input['can_evaluate'] = $interviewer;
                $input['final_evaluate'] = $interviewer;
                $input['created_by'] = Auth::user()->id;
                if ($interview_result == null) {
                    $input['status'] = InterviewScheduleEnum::active->name;
                } else {
                    $input['status'] = InterviewScheduleEnum::success->name;
                }
                $res = $this->interviewScheduleRepo->create($input);
            }
        }
        return 'success';
    }

    public function getInterviewStage($input)
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

        return $stage;
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

    public function store(Request $request)
    {
        try {
            $input = $request->except(['is_sendmail','is_interviewed']);

            $candidate = $this->candidateRepo->find($input['candidate_id']);
            if (!$candidate) {
                return back()->with('error', 'Ứng viên không tồn tại');
            }
            // if($candidate->interview_result != InterviewResultEnum::obtain || $candidate->interview_result == null) {
            //     return back()->with('error', 'Ứng viên không đạt hoặc chưa sơ lược hồ sơ');
            // }

            $validator = Validator::make($input, $this->interviewScheduleRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            // dd($input);
            //$enumValuesBeforeSelected = array_slice($enumValues, 0, array_search($selectedBranch, $enumValues));
            //$inputCandidate = $request->all();
            // $count = count($inputCandidate['candidate_id']);

            $interviewDatas = $input['interviewer'] ?? []; // Lấy dữ liệu hiện tại hoặc khởi tạo mảng rỗng nếu null
            $inputNew = [];

            if (count($interviewDatas) > 0) {
                foreach ($interviewDatas as $key => $interviewData) {
                    $inputNew[$key]['comment'] = null;
                    $inputNew[$key]['result'] = null;
                    $inputNew[$key]['interview_date'] = $input['interview_from'];
                    $inputNew[$key]['user_id'] = $interviewData;
                    $inputNew[$key]['can_evaluate'] = in_array($interviewData, $input['can_evaluate']) ? true : false;
                    $inputNew[$key]['final_evaluate'] = $interviewData == $input['final_evaluate'] ? true : false;
                }
            }
            $input['interviewer'] = $inputNew;
            $input['can_evaluate'] = implode(',', $input['can_evaluate']);
            $input['created_by'] = Auth::user()->id;
            $input['active'] = isset($input['active']) ? 1 : 0;
            $input['status'] = InterviewScheduleEnum::active->name;
            $input['is_interviewed'] = isset($input['is_interviewed']) ? 1 : 0;

            $stage = $candidate->stage;
            $input['stage'] = $stage + 1;
            $res = $this->interviewScheduleRepo->create($input);
            $inputData = [];

            if ($res) {
                if (strpos($candidate->status, 'success')) {
                    $stage = $candidate->stage;
                    $inputData['stage'] = $stage + 1;
                    $inputData['status'] = 'interview' . $inputData['stage'];
                    $inputData['interview_date' . $inputData['stage']] = $input['interview_from'];
                    $inputData['interviewer' . $inputData['stage']] = $input['final_evaluate'];
                    $inputData['status_value'] = $this->getValueInEnum(CandidateEnum::cases(), $inputData['status']);
                }
                $candidate->update($inputData);
            }

            if(isset($request->is_sendmail)) {
                $mailTemplate = $this->mailTemplateRepo->findBy('code', ScheduleMailEnum::thu_moi_phong_van->name);
                if ($mailTemplate) {
                    $emailContent = $mailTemplate->body;
                    $emailContent = $this->replaceGender($emailContent, $candidate->gender);
                    // $emailContent = $this->replaceTime($emailContent, $candidate);
                    $processedEmail = $this->replacePlaceholders($emailContent, $candidate);
                    $subject = $mailTemplate->name;
                    $content = $processedEmail;
                    $to = $candidate->email;
                    $cc = $this->userRepo->getMailByListId($interviewDatas);
                    //dd($cc);
                    Mail::to($to)->cc($cc)->send(new SendEmail(subject: $subject, content: $content));
                }
            }
            if (isset($request->is_interviewed)) {
                $mailTemplate = $this->mailTemplateRepo->findBy('code', ScheduleMailEnum::thu_cam_on_phong_van->name);
                if ($mailTemplate) {
                    $emailContent = $mailTemplate->body;
                    $emailContent = $this->replaceGender($emailContent, $candidate->gender);
                    // $emailContent = $this->replaceTime($emailContent, $candidate);
                    $processedEmail = $this->replacePlaceholders($emailContent, $candidate);
                    $subject = $mailTemplate->name;
                    $content = $processedEmail;
                    $to = $candidate->email;
                    // $cc = $this->userRepo->getMailByListId($interviewDatas);
                    //dd($cc);
                    Mail::to($to)->send(new SendEmail(subject: $subject, content: $content));
                }
            }

            return redirect()->back()->with('success', 'Thành công');
            // return redirect()->route('recruitment.interview_schedule.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function checkDuplicate()
    {

    }

    public function edit($id)
    {
        try {
            $data = $this->interviewScheduleRepo->find($id);
            $candidate = $this->candidateRepo->find($data->candidate_id);

            if ($data) {
                $datas = $this->interviewScheduleRepo->getBy('candidate_id', $data->candidate_id);

                $count = count($datas);
                if ($count == 1) {
                    // $datas[1] = clone $datas[0];
                    // $datas[2] = clone $datas[0];

                    // $datas[1]['interviewer'] = null;
                    // $datas[2]['interviewer'] = null;
                    $datas[1] = null;
                    $datas[2] = null;

                    // $datas[1]['interviewer'] = null;
                    // $datas[2]['interviewer'] = null;

                }
                if ($count == 2) {
                    // $datas[2] = clone $datas[1];
                    // $datas[2]['interviewer'] = null;
                    $datas[2] = null;
                }

                $status = InterviewScheduleEnum::cases();
                $listInterviewStatus = ['interview0_success', 'interview1_success', 'interview2_success'];
                // Danh sách phỏng vấn
                $interviewers = $this->userRepo->getActive();
                // Người liên quan
                // $relationers = StringHelpers::getSelectOptions($this->userRepo->getActive(), $data->relationer);
                $relationers = $this->userRepo->getActive();
                $interviewResults = InterviewResultEnum::cases();

                // Danh sách phỏng vấn
                $candidates = $this->candidateRepo->getIsInterview($listInterviewStatus);
                // $interviewAddress = StringHelpers::getSelectOptions($this->interviewAddressRepo->getActive(), $data->candidate_id);
                $interviewAddress = $this->interviewAddressRepo->getActive();
                return view('recruitment.interview_schedule.edit', compact('candidate', 'datas', 'interviewResults', 'status', 'relationers', 'interviewers', 'data', 'candidates', 'interviewAddress'));
            }
            return back()->with("error", "Không tìm thấy dữ liệu");
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $input = $request->except('is_sendmail');
            $data = $this->interviewScheduleRepo->find($id);
            $validator = Validator::make($input, $this->interviewScheduleRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $interviewDatas = $input['interviewer'] ?? [];
            $inputNew = [];

            if (count($interviewDatas) > 0) {
                foreach ($interviewDatas as $key => $interviewData) {
                    $inputNew[$key]['comment'] = isset($input['comment'][$key]) ? $input['comment'][$key] : null;
                    $inputNew[$key]['result'] = isset($input['interviewResult'][$key]) ? $input['interviewResult'][$key] : null;
                    $inputNew[$key]['interview_date'] = $input['interview_from'];
                    $inputNew[$key]['user_id'] = $interviewData;
                    $inputNew[$key]['can_evaluate'] = in_array($interviewData, $input['can_evaluate']) ? true : false;
                    $inputNew[$key]['final_evaluate'] = $interviewData == $input['final_evaluate'] ? true : false;
                }
            }

            $input['interviewer'] = $inputNew;
            $input['can_evaluate'] = implode(',', $input['can_evaluate']);
            $input['active'] = isset($input['active']) ? 1 : 0;
            $input['status'] = InterviewScheduleEnum::active->name;
            $input['is_interviewed'] = isset($input['is_interviewed']) ? 1 : 0;
            $candidate = $this->candidateRepo->find($data->candidate_id);
            unset($input['comment']);
            unset($input['interviewResult']);

            if ($input['schedule'] == 'save') {
                //dd($input);
                unset($input['schedule']);
                // if ($input['is_interviewed'] == true && $data->is_send_mail_interviewed != true) {
                if (isset($request->is_sendmail)) {
                    $input['is_send_mail_interviewed'] = true;
                    $mailTemplate = $this->mailTemplateRepo->findBy('code', ScheduleMailEnum::thu_moi_phong_van->name);
                    if ($mailTemplate) {
                        $emailContent = $mailTemplate->body;
                        $emailContent = $this->replaceGender($emailContent, $candidate->gender);
                        // $emailContent = $this->replaceTime($emailContent, $candidate);
                        $processedEmail = $this->replacePlaceholders($emailContent, $candidate);
                        $subject = $mailTemplate->name;
                        $content = $processedEmail;
                        // $to = 'tiendat982745@gmail.com';
                        $to = $candidate->email;
                        $cc = $this->userRepo->getMailByListId($interviewDatas);
                        Mail::to($to)->cc($cc)->send(new SendEmail(subject: $subject, content: $content));
                    }
                    // }
                    // dd('run oke');
                }
                if (isset($request->is_interviewed)) {
                    $mailTemplate = $this->mailTemplateRepo->findBy('code', ScheduleMailEnum::thu_cam_on_phong_van->name);
                    if ($mailTemplate) {
                        $emailContent = $mailTemplate->body;
                        $emailContent = $this->replaceGender($emailContent, $candidate->gender);
                        // $emailContent = $this->replaceTime($emailContent, $candidate);
                        $processedEmail = $this->replacePlaceholders($emailContent, $candidate);
                        $subject = $mailTemplate->name;
                        $content = $processedEmail;
                        $to = $candidate->email;
                        //$to = 'tiendat982745@gmail.com';
                        // $cc = $this->userRepo->getMai   lByListId($interviewDatas);
                        //dd($cc);
                        Mail::to($to)->send(new SendEmail(subject: $subject, content: $content));
                    }
                }

                $interviewDatas = $input['interviewer'] ?? [];
                $inputNew = [];
                $checkEvaluate = false;
                if (count($interviewDatas) > 0) {
                    foreach ($interviewDatas as $key => $interviewData) {
                        if($interviewData['result'] != null){
                            if ($interviewData['final_evaluate'] == true) {
                                $checkEvaluate = true;
                                $inputCandidate['interview_comment' . $data->stage] = $interviewData['comment'];
                                $inputCandidate['interview_result' . $data->stage] = $interviewData['result'];
                                $inputCandidate['interview_result' . $data->stage . '_value'] = $this->getValueInEnum(InterviewResultEnum::cases(), $interviewData['result']);
                                $inputCandidate['status'] = $this->getCurrentStatus($inputCandidate, $data->stage);
                                $inputCandidate['status_value'] = $this->getValueInEnum(CandidateEnum::cases(), $inputCandidate['status']);
                                $res = $candidate->update($inputCandidate);

                                if ($inputCandidate['status'] != InterviewResultEnum::obtain->name && isset($request->is_interview_result)) {
                                    $mailTemplate = $this->mailTemplateRepo->findBy('code', ScheduleMailEnum::thu_ket_qua_phong_van->name);
                                    if ($mailTemplate) {
                                        $emailContent = $mailTemplate->body;
                                        $emailContent = $this->replaceGender($emailContent, $candidate->gender);
                                        // $emailContent = $this->replaceTime($emailContent, $candidate);
                                        $processedEmail = $this->replacePlaceholders($emailContent, $candidate);
                                        $subject = $mailTemplate->name;
                                        $content = $processedEmail;
                                        $to = $candidate->email;
                                        if (isset($request->is_sendmail)) {
                                            Mail::to($to)->send(new SendEmail(subject: $subject, content: $content));
                                        }
                                    }
                                }
                                break;
                            }
                        }
                    }
                    //Update trạng thái lịch phỏng vấn nếu đã có kết quả
                    $input['status'] = InterviewScheduleEnum::success->name;
                }
                $res = $data->update($input);
            } else if ($input['schedule'] == 'cancel') {
                unset($input['schedule']);
                //Unactive or xoá lịch phỏng vấn
                //Lùi stage - 1
                $stage = 0;
                if ($data->stage == 1) {
                    $stage = null;
                } else {
                    $stage = $data->stage - 1;
                }

                $stage = $data->stage - 1;
                $res = $data->update(['status' => InterviewScheduleEnum::cancel->name, 'stage' => $stage]);
            } else if ($input['schedule'] == 'evaluate_submit') {
                unset($input['schedule']);
                $res = $data->update($input);
                //dd($data->stage);
                //Lấy kết quả đánh giá cuối gán ngược lại cho ứng viên
                $interviewDatas = $input['interviewer'] ?? [];
                $inputNew = [];
                $checkEvaluate = false;
                if (count($interviewDatas) > 0) {
                    foreach ($interviewDatas as $key => $interviewData) {
                        if ($interviewData['final_evaluate'] == true) {
                            $checkEvaluate = true;
                            $inputCandidate['interview_comment' . $data->stage] = $interviewData['comment'];
                            $inputCandidate['interview_result' . $data->stage] = $interviewData['result'];
                            $inputCandidate['interview_result' . $data->stage . '_value'] = $this->getValueInEnum(InterviewResultEnum::cases(), $interviewData['result']);
                            $inputCandidate['status'] = $this->getCurrentStatus($inputCandidate, $data->stage);
                            $inputCandidate['status_value'] = $this->getValueInEnum(CandidateEnum::cases(), $inputCandidate['status']);
                            $res = $candidate->update($inputCandidate);

                            if ($inputCandidate['status'] != InterviewResultEnum::obtain->name) {
                                $mailTemplate = $this->mailTemplateRepo->findBy('code', ScheduleMailEnum::thu_ket_qua_phong_van->name);
                                if ($mailTemplate) {
                                    $emailContent = $mailTemplate->body;
                                    $emailContent = $this->replaceGender($emailContent, $candidate->gender);
                                    // $emailContent = $this->replaceTime($emailContent, $candidate);
                                    $processedEmail = $this->replacePlaceholders($emailContent, $candidate);
                                    $subject = $mailTemplate->name;
                                    $content = $processedEmail;
                                    $to = $candidate->email;
                                    if(isset($request->is_sendmail)){
                                        Mail::to($to)->send(new SendEmail(subject: $subject, content: $content));
                                    }
                                }
                            }
                            break;
                        }
                    }
                    if ($checkEvaluate == false) {
                        return redirect()->back()->with('error', 'Chưa có đánh giá cuối cho ứng viên này');
                    } else {
                        //Update trạng thái lịch phỏng vấn
                        $res = $data->update(['status' => InterviewScheduleEnum::success->name]);
                    }
                } else {
                    return redirect()->back()->with('error', 'Chưa có đánh giá cho ứng viên này');
                }
            } else if ($input['schedule'] == 'create_schedule') {
                unset($input['schedule']);
                $queryParams = ['is_create' => true];
                return redirect()->route('recruitment.candidate.edit', ['id' => $data->candidate_id])->withQueryParams($queryParams);
            }
            // }
            // return redirect()->route('recruitment.interview_schedule.index')->with('success', 'Thành công');
            return redirect()->back()->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function getCurrentStatus($input, $stage)
    {
        $status = CandidateEnum::new ->name;

        if(isset($input['interview_result0']) && $input['interview_result0'] != null) {
            if($input['interview_result0'] == InterviewResultEnum::obtain->name) {
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

        return $status;
    }

    public function deleteCandidate(Request $request, $candidateId)
    {
        try {
            $data = $this->candidateRepo->find($candidateId);
            $data->update(['interview_schedule_id' => null]);
            return $this->success('Thành công');
        } catch (Exception $ex) {
            return $this->error('Có lỗi xảy ra hoặc bạn không có quyền này');
        }
    }

    public function destroy($id)
    {
        try {
            $this->interviewScheduleRepo->destroy($id);
            return redirect()->route('recruitment.interview_schedule.index')->with('success', 'Xóa thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }


}
