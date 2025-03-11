<?php

namespace App\Http\Controllers\Recruitment;

use App\Enums\GenderEnum;
use App\Repositories\Recruitment\DepartmentRepository;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\ApproveRepository;
use App\Repositories\Recruitment\UserRepository;
use App\Repositories\Recruitment\PositionRepository;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Common\FileUploads;

class ApproveController extends Controller
{
    public function __construct(DepartmentRepository $departmentRepo, ApproveRepository $approveRepo, UserRepository $userRepo, PositionRepository $positionRepo)
    {
        $this->approveRepo = $approveRepo;
        $this->userRepo = $userRepo;
        $this->positionRepo = $positionRepo;
        $this->departmentRepo = $departmentRepo;
    }

    public function index()
    {
        $datas = $this->approveRepo->all();
        return view('recruitment/approve/index', compact('datas'));
    }

    public function create()
    {
        $users = StringHelpers::getSelectOptions($this->userRepo->all());
        $departments = StringHelpers::getSelectOptions($this->departmentRepo->all());
        // $positions = StringHelpers::getSelectOptions($this->positionRepo->all());
        return view('recruitment/approve/create', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, $this->approveRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['created_by'] = Auth::user()->id;
            $input['active'] = isset($input['active']) ? 1 : 0;
            $this->approveRepo->create($input);
            return redirect()->route('recruitment.approve.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->approveRepo->find($id);
            if ($data) {
                $departmentsSelect = $this->departmentRepo->all();
                $usersApprove = $this->userRepo->all();
                $users = StringHelpers::getSelectOptions($usersApprove);
                $departments = StringHelpers::getSelectOptions($this->departmentRepo->all(), $data->department_id);
                $approve1 = StringHelpers::getSelectOptions($usersApprove, $data->approve_1);
                $approve2 = StringHelpers::getSelectOptions($usersApprove, $data->approve_2);
                $approve3 = StringHelpers::getSelectOptions($usersApprove, $data->approve_3);
                $approve4 = StringHelpers::getSelectOptions($usersApprove, $data->approve_4);
                return view('recruitment/approve/edit', compact('data', 'approve1', 'approve2', 'approve3', 'approve4', 'departments', 'users'));
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
            $data = $this->approveRepo->find($id);
            $validator = Validator::make($input, $this->approveRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['active'] = isset($input['active']) ? 1 : 0;
            $res = $data->update($input);
            return redirect()->route('recruitment.approve.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function delete()
    {
        dd('oke');
        return 1;
    }

    public function destroy($id)
    {
        try {
            $this->approveRepo->destroy($id);
            return redirect()->route('recruitment.approve.index')->with('success', 'Xóa thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

}
