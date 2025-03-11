<?php

namespace App\Http\Controllers\Recruitment;

use App\Common\Traits\ApiResponses;
use App\Enums\GenderEnum;
use App\Enums\PositionRankEnum;
use App\Enums\RecruitmentPlanEnum;
use App\Enums\RequestFormEnum;
use App\Repositories\Recruitment\RecruitmentPlanRepository;
use App\Repositories\Recruitment\RequestFormRepository;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Recruitment\UserRepository;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Common\FileUploads;

class RecruitmentPlanController extends Controller
{
    use ApiResponses;
    public function __construct(RecruitmentPlanRepository $recruitmentPlanRepo, RequestFormRepository $requestFormRepo)
    {
        $this->recruitmentPlanRepo = $recruitmentPlanRepo;
        $this->requestFormRepo = $requestFormRepo;
    }

    public function index()
    {
        return view('recruitment/recruitment_plan/index');
    }

    public function getData()
    {
        try {
            $datas = $this->recruitmentPlanRepo->getWithCountCandidate();
            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->error($datas);
        }
    }

    public function create()
    {
        $selectRequestForms = $this->requestFormRepo->queryAll()->where('recruitment_plan_id', null)->where('status', RequestFormEnum::approved->name)->get();
        return view('recruitment/recruitment_plan/create', compact('selectRequestForms'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->except('requestForm');
            $validator = Validator::make($input, $this->recruitmentPlanRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['created_by'] = Auth::user()->id;
            $input['status'] = RecruitmentPlanEnum::process->name;
            $input['active'] = isset($input['active']) ? 1 : 0;

            $res = $this->recruitmentPlanRepo->create($input)->id;
            if ($res) {
                $inputRequestForm = $request->requestForm;
                $this->requestFormRepo->queryAll()->where('recruitment_plan_id', null)->where('status', RequestFormEnum::approved->name)->whereIn('id', $inputRequestForm)->update(['recruitment_plan_id' => $res, 'status' => RequestFormEnum::process->name]);
            }
            return redirect()->route('recruitment.recruitment_plan.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->recruitmentPlanRepo->find($id);
            if ($data) {
                $requestFormApproveds = $this->requestFormRepo->getBy('status', RequestFormEnum::approved->name);
                $requestForms = $this->requestFormRepo->getBy('recruitment_plan_id', $data->id);
                return view('recruitment/recruitment_plan/edit', compact('data', 'requestForms', 'requestFormApproveds'));
            }
            return back()->with("error", "Không tìm thấy dữ liệu");
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            $data = $this->recruitmentPlanRepo->find($id);
            $validator = Validator::make($input, $this->recruitmentPlanRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['active'] = isset($input['active']) ? 1 : 0;
            $res = $data->update($input);
            return redirect()->route('recruitment.recruitment_plan.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function addForm(Request $request, $id)
    {
        try {
            $input = $request->all();
            foreach ($input['requestForm'] ?? [] as $form) {
                $this->requestFormRepo->find($form)->update(['recruitment_plan_id' => $id]);
            }
            return redirect()->back()->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
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
            $res = $this->recruitmentPlanRepo->destroy($id);
            $this->requestFormRepo->queryAll()->where('recruitment_plan_id', $id)->update(['recruitment_plan_id' => null, 'status' => RequestFormEnum::approving->name]);

            return redirect()->route('recruitment.recruitment_plan.index')->with('success', 'Xóa thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

}
