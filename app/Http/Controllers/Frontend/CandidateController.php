<?php

namespace App\Http\Controllers\Frontend;

use App\Common\Traits\ApiResponses;
use App\Common\Traits\EnumHandle;
use App\Enums\BranchEnum;
use App\Enums\CandidateEnum;
use App\Enums\GenderEnum;
use App\Enums\InterviewResultEnum;
use App\Enums\LevelEnum;
use App\Enums\RankEnum;
use App\Common\Traits\GetUniqueUid;
use App\Models\Candidate;
use App\Models\Department;
use App\Models\ImportStatus;
use App\Models\Position;
use App\Models\RequestForm;
use App\Repositories\Recruitment\CandidateStatusHistoryRepository;
use App\Repositories\Recruitment\ImportStatusRepository;
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
use Illuminate\Support\Str;
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
    public function __construct(CandidateStatusHistoryRepository $candidateStatusHistoryRepo, RequestFormRepository $requestFormRepo, RecruitmentPlanRepository $recruitmentPlanRepo, ImportStatusRepository $importStatusRepo, CandidateRepository $candidateRepo, UserRepository $userRepo, DepartmentRepository $departmentRepo, PositionRepository $positionRepo, SourceRepository $sourceRepo)
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
    }

    public function getForm($token)
    {
        if ($token == "" || $token == null) {
            abort(404, "Trang này không tồn tại");
        }
        $checkFormToken = $this->candidateRepo->findBy('form_token', $token);
        if (!$checkFormToken) {
            abort(404, "Trang này không tồn tại");
        }
        if ($checkFormToken->is_submit == true && $checkFormToken->can_edit == false) {
            return view('frontend/candidate/submited', compact('token'));
        }
        $candidateStatus = StringHelpers::getSelectEnumOptions(CandidateEnum::cases());
        $users = StringHelpers::getSelectOptions($this->userRepo->getActive());
        $departments = StringHelpers::getSelectOptions($this->departmentRepo->all());
        $positions = StringHelpers::getSelectOptions($this->positionRepo->all(), $checkFormToken->position_id);
        $sources = StringHelpers::getSelectOptions($this->sourceRepo->all(), $checkFormToken->source_id);
        $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases(), $checkFormToken->gender);
        $provinces = DB::table('provinces')->get();
        $selectHousehold = StringHelpers::getSelectProvinceOptions($provinces, $checkFormToken->household);
        $selectAddress = StringHelpers::getSelectProvinceOptions($provinces, $checkFormToken->address);
        $interviewResults = StringHelpers::getSelectEnumOptions(InterviewResultEnum::cases());
        return view('frontend/candidate/google_form', compact('checkFormToken', 'interviewResults', 'selectAddress','selectHousehold', 'candidateStatus', 'users', 'genders', 'sources', 'positions', 'departments', 'provinces'));
    }

    public function submitForm(Request $request, $token)
    {
        try {
            $input = $request->all();
            $data = $this->candidateRepo->findBy('form_token', $token);
            $input['received_time'] = Carbon::now();
            if (!$data) {
                return redirect()->back()->with('error', 'Có lỗi xảy ra');
            }
            $validator = Validator::make($input, $this->candidateRepo->validateSubmitForm($data->id));
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

            $input['gender_value'] = $this->getValueInEnum(GenderEnum::cases(), $input['gender']);
            $input['name'] = StringHelpers::convertName($input['name']);
            $input['active'] = false;
            //Đang ứng tuyển, chưa sàng lọc
            $input['is_submit'] = true;
            $input['stage'] = -2;
            $input['status'] = CandidateEnum::new ->name;
            $input['status_value'] = $this->getValueInEnum(CandidateEnum::cases(), $input['status']);
            $res = $data->update($input);
            return redirect()->route('frontend.candidate.getForm', compact('token'))->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function update(Request $request, $token)
    {
        try {
            $input = $request->all();
            $data = $this->candidateRepo->findBy('form_token', $token);
            if (!$data) {
                return redirect()->back()->with('error', 'Có lỗi xảy ra');
            }
            if ($data->can_edit != true) {
                return redirect()->back()->with('error', 'Có lỗi xảy ra');
            }
            $validator = Validator::make($input, $this->candidateRepo->validateSubmitForm());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['gender_value'] = $this->getValueInEnum(GenderEnum::cases(), $input['gender']);

            //Đang ứng tuyển, chưa sàng lọc
            $input['can_edit'] = false;
            $res = $data->update($input);
            return redirect()->route('frontend.candidate.getForm', compact('token'))->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
}
