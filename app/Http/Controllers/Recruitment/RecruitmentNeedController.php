<?php

namespace App\Http\Controllers\Recruitment;

use App\Common\Traits\ApiResponses;
use App\Enums\RecruitmentPlanEnum;
use App\Enums\RequestFormEnum;
use App\Models\Candidate;
use App\Models\Position;
use App\Models\RecruitmentNeed;
use App\Models\RequestForm;
use App\Repositories\Recruitment\PositionRepository;
use App\Repositories\Recruitment\RecruitmentNeedRepository;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Common\FileUploads;

class RecruitmentNeedController extends Controller
{
    use ApiResponses;
    public function __construct(RecruitmentNeedRepository $recruitmentNeedRepo, PositionRepository $positionRepo)
    {

        $this->recruitmentNeedRepo = $recruitmentNeedRepo;
        $this->positionRepo = $positionRepo;

    }

    public function syncCurrentMonth(){
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');
        $curentDate = Carbon::now()->startOfMonth();

        foreach (Position::all() as $position) {
            $check = RecruitmentNeed::where('position_id', $position->id)->whereDate('time', $curentDate)->first();

            $position_id = $position->id;

            // Tính nhau cầu phát sinh (số lượng trong phiếu đề xuất trong tháng)
            $more = RequestForm::whereYear('request_date', $currentYear)
                ->whereMonth('request_date', $currentMonth)
                ->where('position_id', $position_id)
                ->sum('quantity');

            // Tính số lượng đã tuyển trong tháng
            $recruitmented = Candidate::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->where('position_id', $position_id)
                ->whereIn('status', ['probation_success', 'recruitment_success'])
                ->count();

            // Tính nhu cầu đầu kì = số lượng ứng viên chưa tuyển được tháng trước
            // Giả định ban đầu không có nhu cầu nếu là tháng 1 năm 2015
            $need = 0;
            if ($currentMonth != 12 || $currentYear != 2022) {
                //Nếu tháng hiện tại là tháng 1 ($currentMonth == 1), thì tháng trước là tháng 12 và năm trước đó sẽ giảm đi 1.
                //Nếu không, tháng trước sẽ bằng tháng hiện tại trừ đi 1 và năm trước sẽ bằng năm hiện tại.
                $prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
                $prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
                $previousNeed = RecruitmentNeed::whereYear('time', $prevYear)
                    ->whereMonth('time', $prevMonth)
                    ->where('position_id', $position_id)
                    ->first();
                $need = $previousNeed ? ($previousNeed->need + $previousNeed->more - $previousNeed->recruitmented) : 0;
            }
            // check xem có data ko
            // if có
            // chia 2 case: có nhưng mà k đổi => ko update
            //              Có + data đổi => update data mới
            // else kèo
            // Thêm mới
            if ($check) {
                // Nếu k có gì thay đổi
                if($check->need == $need && $check->more == $more && $check->$recruitmented == $recruitmented){
                    continue;
                }
                // Nếu data thay đổi
                if ($check->need != $need && $check->more != $more && $check->$recruitmented != $recruitmented) {
                    $check->update([
                        'need' => $need,
                        'more' => $more,
                        '$check->more' => $recruitmented,
                        'total' => $need + $more,
                    ]);
                }
            }

            else{
                // Nếu chưa thì tạo mới
                RecruitmentNeed::create([
                    'time' => "$currentYear-$currentMonth-01",
                    'position_id' => $position_id,
                    'need' => $need,
                    'more' => $more,
                    '$check->more' => $recruitmented,
                    'total' => $need + $more,
                    'created_by' => Auth::user()->id,
                    // giả sử
                    'active' => true
                ]);
            }

        }
    }

    //sync data từ trước tới giờ
    public function sync()
    {

        // Khởi đầu từ tháng 1 năm 2018
        $currentMonth = 12;
        $currentYear = 2022;

        // Lấy tháng và năm hiện tại
        $endMonth = now()->month;
        $endYear = now()->year;

        while (($currentYear < $endYear) || ($currentYear == $endYear && $currentMonth <= $endMonth)) {
            foreach (Position::all() as $position) {
                $position_id = $position->id;

                // Tính nhau cầu phát sinh (số lượng trong phiếu đề xuất trong tháng)
                $more = RequestForm::whereYear('request_date', $currentYear)
                    ->whereMonth('request_date', $currentMonth)
                    ->where('position_id', $position_id)
                    ->sum('quantity');

                // Tính số lượng đã tuyển trong tháng
                $recruitmented = Candidate::whereYear('received_time', $currentYear)
                    ->whereMonth('received_time', $currentMonth)
                    ->where('position_id', $position_id)
                    ->whereIn('status', ['probation_success', 'recruitment_success'])
                    ->count();

                // Tính nhu cầu đầu kì = số lượng ứng viên chưa tuyển được tháng trước
                // Giả định ban đầu không có nhu cầu nếu là tháng 1 năm 2015
                $need = 0;
                // if ($currentMonth != 12 || $currentYear != 2022) {
                    //Nếu tháng hiện tại là tháng 1 ($currentMonth == 1), thì tháng trước là tháng 12 và năm trước đó sẽ giảm đi 1.
                    //Nếu không, tháng trước sẽ bằng tháng hiện tại trừ đi 1 và năm trước sẽ bằng năm hiện tại.
                    $prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
                    $prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
                    $previousNeed = RecruitmentNeed::whereYear('time', $prevYear)
                        ->whereMonth('time', $prevMonth)
                        ->where('position_id', $position_id)
                        ->first();
                    $need = $previousNeed ? ($previousNeed->total - $previousNeed->recruitmented) : 0;
                // }

                $check = $previousNeed = RecruitmentNeed::whereYear('time', $currentYear)
                    ->whereMonth('time', $currentMonth)
                    ->where('position_id', $position_id)
                    ->first();

                if(!$check){
                    //Nếu chưa thì tạo mới
                    RecruitmentNeed::create([
                        'time' => "$currentYear-$currentMonth-01",
                        'position_id' => $position_id,
                        'need' => $need,
                        'more' => $more,
                        'recruitmented' => $recruitmented,
                        'total' => $need + $more,
                        'created_by' => Auth::user()->id,
                        // giả sử
                        'active' => true
                    ]);
                }
                else{
                    $check->update([
                        'time' => "$currentYear-$currentMonth-01",
                        'position_id' => $position_id,
                        'need' => $need,
                        'more' => $more,
                        'recruitmented' => $recruitmented,
                        'total' => $need + $more,
                        'created_by' => Auth::user()->id,
                        // giả sử
                        'active' => true
                    ]);
                }
            }

            // Tăng tháng
            $currentMonth++;
            if ($currentMonth > 12) {
                $currentMonth = 1;
                $currentYear++;
            }
        }
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
