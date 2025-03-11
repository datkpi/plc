<?php

namespace App\Http\Controllers\Recruitment;

use App\Common\ConvertHelpers;
use App\Common\Traits\ApiResponses;
use App\Enums\ExperienceEnum;
use App\Enums\FieldEnum;
use App\Enums\GenderEnum;
use App\Enums\LanguageLevelEnum;
use App\Enums\LevelEnum;
use App\Enums\RecruitmentChangeEnum;
use App\Enums\RecruitmentPlanEnum;
use App\Enums\RecruitmentTypeEnum;
use App\Enums\RequestFormEnum;
use App\Models\Approve;
use App\Models\Notification;
use App\Models\RequestForm;
use App\Models\RequestFormDetail;
use App\Notifications\RequestFormNotification;
use App\Repositories\Recruitment\AnnualEmployeeRepository;
use App\Repositories\Recruitment\ApproveRepository;
use App\Repositories\Recruitment\RecruitmentPlanRepository;
use App\Repositories\Recruitment\RequestFormConfigRepository;
use App\Repositories\Recruitment\RequestFormDetailRepository;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Recruitment\RequestFormRepository;
use App\Repositories\Recruitment\UserRepository;
use App\Repositories\Recruitment\DepartmentRepository;
use App\Repositories\Recruitment\PositionRepository;
use Validator;

class RequestFormController extends Controller
{
    use ApiResponses;
    public function __construct(AnnualEmployeeRepository $annualEmployeeRepo, RecruitmentPlanRepository $recruitmentPlanRepo, RequestFormDetailRepository $requestFormDetailRepo, ApproveRepository $approveRepo, RequestFormConfigRepository $requestFormConfigRepo, RequestFormRepository $requestFormRepo, DepartmentRepository $departmentRepo, PositionRepository $positionRepo, UserRepository $userRepo)
    {
        $this->departmentRepo = $departmentRepo;
        $this->userRepo = $userRepo;
        $this->positionRepo = $positionRepo;
        $this->requestFormRepo = $requestFormRepo;
        $this->requestFormConfigRepo = $requestFormConfigRepo;
        $this->approveRepo = $approveRepo;
        $this->requestFormDetailRepo = $requestFormDetailRepo;
        $this->recruitmentPlanRepo = $recruitmentPlanRepo;
        $this->annualEmployeeRepo = $annualEmployeeRepo;
    }
    public function send(Request $request)
    {
        // Lưu thông báo vào database
        $notification = Notification::create([
            'user_id' => $request->user_id,
            'content' => $request->content,
            'is_read' => false,
        ]);

        // Gửi thông báo realtime qua Pusher
        event(new \App\Events\NewNotification($notification));

        return response()->json(['message' => 'Notification sent!']);
    }
    public function index()
    {
        $totalRequestForm = $this->requestFormRepo->count();
        $countByStatus = $this->requestFormRepo->countByStatus();
        $totalApprove = $this->requestFormRepo->totalApprove(); //Đếm số lượng đợi duyệt của user đang online
        $status = RequestFormEnum::cases();
        return view('recruitment/request_form/index', compact('totalRequestForm', 'countByStatus', 'status', 'totalApprove'));
    }

    public function getData()
    {
        try {
            $datas = [];
            $requestFormEnum = RequestFormEnum::cases();
            $requestFormEnum = ConvertHelpers::arrayObjToArray($requestFormEnum);
            $now = Carbon::now();
            $records = $this->requestFormRepo->queryAll()->get();

            foreach ($records as $record) {
                if ($record->status == RequestFormEnum::approving->name || $record->status == RequestFormEnum::plan->name) {
                    $record->status = RequestFormEnum::cancel->name;
                    // $record->save();
                    $res = $this->requestFormRepo->find($record->id)->update(['status' => RequestFormEnum::cancel->name]);
                    if ($res) {
                        $inputDetail = [];
                        $inputDetail['content'] = "Hệ thống đã tự động huỷ do hết hạn duyệt!";
                        $inputDetail['action'] = RequestFormDetail::ACTION_DATABASE;
                        $inputDetail['request_form_id'] = $record->id;
                        $this->requestFormDetailRepo->create($inputDetail);
                    }
                }
            }

            $records = $this->requestFormRepo->queryAll()->with('position', 'department')->orderBy('created_at', 'desc')->get();
            $datas['datas'] = $records;
            $datas['requestFormEnum'] = $requestFormEnum;
            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function create()
    {
        $annualEmployees = $this->annualEmployeeRepo->all();
        $departments = StringHelpers::getSelectOptions($this->departmentRepo->all(), Auth::user()->department_id);
        $positions = StringHelpers::getSelectOptions($this->positionRepo->all());
        $users = StringHelpers::getSelectOptions($this->userRepo->getActive());
        $levels = StringHelpers::getSelectEnumOptions(LevelEnum::cases());
        $fields = StringHelpers::getSelectEnumOptions(FieldEnum::cases());
        $experiences = StringHelpers::getSelectEnumOptions(ExperienceEnum::cases());
        $language_levels = StringHelpers::getSelectEnumOptions(LanguageLevelEnum::cases());
        $recruitment_changes = StringHelpers::getSelectEnumOptions(RecruitmentChangeEnum::cases());
        $recruitment_types = StringHelpers::getSelectEnumOptions(RecruitmentTypeEnum::cases());
        return view('recruitment/request_form/create', compact('annualEmployees', 'departments', 'positions', 'users', 'levels', 'fields', 'experiences', 'language_levels', 'recruitment_changes', 'recruitment_types'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->except("button");
            $validator = Validator::make($input, $this->requestFormRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $deadline = $this->requestFormConfigRepo->findFirst();
            if ($deadline) {
                $input['approve_deadline'] = Carbon::now()->addDays($deadline->deadline_after);
            } else {
                $input['approve_deadline'] = Carbon::now()->addDays(5);
            }

            $input['recruitment_type'] = implode(',', $input['recruitment_type']);
            $input['created_by'] = Auth::user()->id;
            $approveList = $this->approveRepo->findBy('department_id', $input['department_id']);
            if ($approveList == null) {
                return redirect()->back()->with('error', 'Vui lòng cấu hình duyệt phiếu tuyển dụng cho phòng ban trước khi thêm phiếu tuyển dụng');
            }

            $managerId1 = optional($approveList->approve1)->id;
            if ($managerId1 == null) {
                return redirect()->back()->with('error', 'Vui lòng chọn người duyệt cho vị trí này');
            }
            //$userApprove1 = $userRepo->find();

            $managerId2 = optional($approveList->approve2)->id;
            if ($managerId2 == null) {
                return redirect()->back()->with('error', 'Vui lòng chọn người duyệt cho vị trí này');
            }
            $managerId3 = optional($approveList->approve3)->id;
            if ($managerId3 == null) {
                return redirect()->back()->with('error', 'Vui lòng chọn người duyệt cho vị trí này');
            }

            $managerId4 = optional($approveList->approve4)->id;
            if ($managerId4 == null) {
                return redirect()->back()->with('error', 'Vui lòng chọn người duyệt cho vị trí này');
            }

            $input['current_approve'] = $managerId1;
            $input['stage'] = 0;
            if ($request->button == 1) {
                $input['status'] = RequestFormEnum::plan->name;
            } else {
                $input['status'] = RequestFormEnum::approving->name;
                if ($managerId1 == Auth::user()->id) {
                    $input['current_approve'] = $managerId2;
                    $input['stage'] = 1;
                } else if ($managerId2 == Auth::user()->id) {
                    $input['current_approve'] = $managerId3;
                    $input['stage'] = 2;
                } else if ($managerId3 == Auth::user()->id) {
                    $input['current_approve'] = $managerId4;
                    $input['stage'] = 3;
                } else if ($managerId4 == Auth::user()->id) {
                    $input['stage'] = 4;
                    $input['status'] = RequestFormEnum::approved->name;
                }
            }

            $input['list_approve'] = $managerId1 . "," . $managerId2 . "," . $managerId3 . "," . $managerId4;
            $res = $this->requestFormRepo->create($input);

            return redirect()->route('recruitment.request_form.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->requestFormRepo->find($id);
            //dd(Approve::where('department_id', $data->department_id)->first());
            $approveIds = explode(",", $data->list_approve);

            $approveList = [];
            foreach ($approveIds as $approveId) {
                $approve = $this->userRepo->find($approveId);
                if ($approve) {
                    array_push($approveList, $approve);
                }
            }

            //$recruitment_types
            $staffing = $this->userRepo->getBy('position_id', $data->position_id)->count();
            $annualEmployees = $this->annualEmployeeRepo->all();
            $requestFormDetails = $this->requestFormDetailRepo->queryAll()->where('request_form_id', $id)->orderBy('created_at', 'ASC')->get();
            $departments = StringHelpers::getSelectOptions($this->departmentRepo->all(), $data->department_id);
            $positions = StringHelpers::getSelectOptions($this->positionRepo->all(), $data->position_id);
            $users = StringHelpers::getSelectOptions($this->userRepo->getActive(), $data->manager_id);
            $levels = StringHelpers::getSelectEnumOptions(LevelEnum::cases(), $data->level);
            $fields = StringHelpers::getSelectEnumOptions(FieldEnum::cases(), $data->field);
            $status = RequestFormEnum::cases();
            $experiences = StringHelpers::getSelectEnumOptions(ExperienceEnum::cases(), $data->experience);
            $language_levels = StringHelpers::getSelectEnumOptions(LanguageLevelEnum::cases(), $data->language_level);
            $recruitment_changes = StringHelpers::getSelectEnumOptions(RecruitmentChangeEnum::cases(), $data->recruitment_change);
            $recruitment_types = StringHelpers::getSelectEnumOptions(RecruitmentTypeEnum::cases(), $data->recruitment_type);
            $recruitment_types = explode(',', $data->recruitment_type);
            if ($data) {
                return view('recruitment/request_form/edit', compact('staffing', 'annualEmployees', 'requestFormDetails', 'status', 'approveList', 'data', 'departments', 'positions', 'users', 'levels', 'fields', 'experiences', 'language_levels', 'recruitment_changes', 'recruitment_types'));
            }
            return back()->with("error", "Không tìm thấy dữ liệu");
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $input = $request->except('type');
            $data = $this->requestFormRepo->find($id);
            if ($request->type == 'update_status') {
                $res = $data->update(['status' => RequestFormEnum::approving->name]);
                $inputRequestFormDetail['content'] = "đã chuyển trạng thái sang yêu cầu duyệt";
                $inputRequestFormDetail['created_by'] = Auth::user()->id;
                $inputRequestFormDetail['action'] = RequestFormDetail::ACTION_DATABASE;
                $inputRequestFormDetail['request_form_id'] = $id;
                $this->requestFormDetailRepo->create($inputRequestFormDetail);
                return redirect()->back()->with('success', 'Thành công');
            }
            $validator = Validator::make($input, $this->requestFormRepo->validateUpdate($id));
            if ($validator->fails()) {
                dd($validator->errors());
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['recruitment_type'] = implode(',', $input['recruitment_type']);
            $res = $data->update($input);
            return redirect()->back()->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function getAnnual(Request $request, $id)
    {
        try {
            $datas = $this->annualEmployeeRepo->getBy('position_id', $id);
            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function approveAll(Request $request, $id)
    {
        try {
            $input = $request->all();
            $data = $this->requestFormRepo->find($id);
            //Nếu user không phải người duyệt ở bước hiện tại
            if ($data->stage >= 4) {
                return redirect()->back()->with('error', 'Phiếu đề nghị này đã được duyệt');
            }
            $input['stage'] = 4;
            $input['status'] = RequestFormEnum::approved->name;

            $res = $data->update($input);
            if ($res) {
                $inputRequestFormDetail = [];
                $inputRequestFormDetail['content'] = "đã duyệt toàn bộ các bước duyệt trong phiếu đề xuất tuyển dụng";
                $inputRequestFormDetail['created_by'] = Auth::user()->id;
                $inputRequestFormDetail['action'] = RequestFormDetail::ACTION_APPROVE;
                $inputRequestFormDetail['request_form_id'] = $id;
                $this->requestFormDetailRepo->create($inputRequestFormDetail);
            }
            return redirect()->back()->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function approve(Request $request, $type, $id)
    {
        try {
            $input = $request->all();
            $data = $this->requestFormRepo->find($id);
            //Nếu user không phải người duyệt ở bước hiện tại
            // if (Auth::user()->id != $data->current_approve) {
            //     return redirect()->back()->with('error', 'Bạn không có quyền duyệt ở bước này');
            // }
            if ($type == RequestForm::REJECT) {

                $res = $data->update(['status' => RequestFormEnum::cancel->name]);
                $inputRequestFormDetail['content'] = "đã từ chối duyệt";
                $inputRequestFormDetail['created_by'] = Auth::user()->id;
                $inputRequestFormDetail['action'] = RequestFormDetail::ACTION_APPROVE;
                $inputRequestFormDetail['request_form_id'] = $id;
                $this->requestFormDetailRepo->create($inputRequestFormDetail);
                return redirect()->back()->with('success', 'Thành công');
            }
            $input['stage'] = $data->stage + 1;
            $approveIds = explode(",", $data->list_approve);

            if ($data->stage < 3) {
                $input['current_approve'] = $approveIds[$input['stage']];
                if ($input['current_approve'] == $data->current_approve) {
                    $input['current_approve'] = $approveIds[$input['stage'] + 1];
                    $input['stage']++;
                }
            }
            if ($input['stage'] >= 4) {
                $input['status'] = RequestFormEnum::approved->name;
            }
            $res = $data->update($input);
            if ($res) {
                $inputRequestFormDetail = [];
                $inputRequestFormDetail['content'] = "<b>" . Auth::user()->name . "</b><p>đã duyệt phiếu đề xuất tuyển dụng!</p>";
                $inputRequestFormDetail['created_by'] = Auth::user()->id;
                $inputRequestFormDetail['action'] = RequestFormDetail::ACTION_APPROVE;
                $this->requestFormDetailRepo->create($inputRequestFormDetail);
            }
            return redirect()->back()->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function addPlan(Request $request)
    {
        try {
            //dd($request->listRequestForm);
            $inputPlan = $request->data;
            $inputPlan['start_date'] = Carbon::parse($inputPlan['start_date']);
            $inputPlan['end_date'] = Carbon::parse($inputPlan['end_date']);
            $validator = Validator::make($inputPlan, $this->recruitmentPlanRepo->validateCreate());
            if ($validator->fails()) {
                return $this->error(implode(",", $validator->messages()->all()));
            }

            $inputPlan['created_by'] = Auth::user()->id;
            $inputPlan['active'] = isset($input['active']) ? 1 : 0;
            $inputPlan['status'] = RecruitmentPlanEnum::process->name;

            $res = $this->recruitmentPlanRepo->create($inputPlan)->id;
            if ($res) {
                $inputRequestForm = $request->listRequestForm;
                $this->requestFormRepo->queryAll()->where('recruitment_plan_id', null)->where('status', RequestFormEnum::approved->name)->whereIn('id', $inputRequestForm)->update(['recruitment_plan_id' => $res, 'status' => RequestFormEnum::process->name]);
            }
            return $this->success("Thành công");
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
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
            $this->requestFormRepo->destroy($id);
            return $this->success();
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    // protected function addRequestFormDetail($input)
    // {
    //     try {
    //         $this->requestFormDetailRepo->create($$input);
    //     } catch (Exception $ex) {
    //         return back()->withError($ex->getMessage())->withInput();
    //     }
    // }

}
