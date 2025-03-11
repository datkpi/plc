<?php

namespace App\Http\Controllers\Recruitment;

use App\Common\Traits\ApiResponses;
use App\Enums\RecruitmentPlanEnum;
use App\Enums\RequestFormEnum;
use App\Repositories\Recruitment\AnnualEmployeeRepository;
use App\Repositories\Recruitment\PositionRepository;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Common\FileUploads;

class AnnualEmployeeController extends Controller
{
    use ApiResponses;
    public function __construct(AnnualEmployeeRepository $annualEmployeeRepo, PositionRepository $positionRepo)
    {

        $this->annualEmployeeRepo = $annualEmployeeRepo;
        $this->positionRepo = $positionRepo;
    }

    public function index(Request $request)
    {
        $search = $request->all();
        $selected = '';
        $datas = $this->annualEmployeeRepo->queryAll();
        if (isset($search['position_id'])) {
            $datas = $datas->where('position_id', $search['position_id']);
            $selected = $search['position_id'];
        }
        $positions = StringHelpers::getSelectOptions($this->positionRepo->all(), $selected);
        $datas = $datas->with('position')->get()->groupBy('position_id');
        return view('recruitment/annual_employee/index', compact('datas', 'positions'));
    }

    public function getData()
    {
        try {
            $datas = $this->annualEmployeeRepo->all();
            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->error($datas);
        }
    }

    public function create()
    {
        $positions = StringHelpers::getSelectOptions($this->positionRepo->all());
        return view('recruitment/annual_employee/create', compact('positions'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, $this->annualEmployeeRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $checkDuplicate = $this->annualEmployeeRepo->checkDuplicate($input['position_id'], $input['month'], $input['year']);
            if ($checkDuplicate != true) {
                return back()->withError("Dữ liệu đinh biên tháng " . $input['month'] . "/" . $input['year'] . " cho vị trí này đã tồn tại");
            }

            $res = $this->annualEmployeeRepo->create($input)->id;
            return redirect()->route('recruitment.annual_employee.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->annualEmployeeRepo->find($id);
            $positions = StringHelpers::getSelectOptions($this->positionRepo->all(), $data->position_id);
            if ($data) {
                return view('recruitment/annual_employee/edit', compact('data', 'positions'));
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
            $validator = Validator::make($input, $this->annualEmployeeRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $checkDuplicate = $this->annualEmployeeRepo->checkDuplicateUpdate($id, $input['position_id'], $input['month'], $input['year']);
            if ($checkDuplicate != true) {
                return back()->withError("Dữ liệu đinh biên tháng " . $input['month'] . "/" . $input['year'] . " cho vị trí này đã tồn tại");
            }

            $res = $this->annualEmployeeRepo->update($input, $id);
            return redirect()->route('recruitment.annual_employee.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function approve(Request $request)
    {
        try {
            $input = $request->all();
            dd($input);
            foreach ($input['listAnnualIds'] as $id) {

            }

            $res = $this->annualEmployeeRepo->update($input, $id);
            return redirect()->route('recruitment.annual_employee.index')->with('success', 'Thành công');
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
            $res = $this->annualEmployeeRepo->destroy($id);
            $this->requestFormRepo->queryAll()->where('recruitment_plan_id', $id)->update(['recruitment_plan_id' => null, 'status' => RequestFormEnum::approving->name]);

            return redirect()->route('recruitment.annual_employee.index')->with('success', 'Xóa thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

}
