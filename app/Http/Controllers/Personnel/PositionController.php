<?php

namespace App\Http\Controllers\Personnel;

use App\Common\Traits\ApiResponses;
use App\Enums\GenderEnum;
use App\Enums\PositionRankEnum;
use App\Models\Position;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\PositionRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Recruitment\UserRepository;
use App\Repositories\Recruitment\DepartmentRepository;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Common\FileUploads;

class PositionController extends Controller
{
    use ApiResponses;
    public function __construct(PositionRepository $positionRepo, UserRepository $userRepo, DepartmentRepository $departmentRepo)
    {
        $this->positionRepo = $positionRepo;
        $this->userRepo = $userRepo;
        $this->departmentRepo = $departmentRepo;
    }

    public function index(Request $request)
    {
        $model = new \App\Models\Position();

        // Hiển thị dữ liệu
        $fieldMetadata = $model->getFieldMetadata();
        $selectOptions = [
            'position_id' => StringHelpers::getSelectOptions($this->positionRepo->all(), $request->get('position_id')),
            'manager_by' => StringHelpers::getSelectOptions($this->userRepo->all(), $request->get('manager_by')),
            'gender' => StringHelpers::getSelectEnumOptions(GenderEnum::cases()),
            'department_id' => StringHelpers::getSelectOptions($this->departmentRepo->all(), $request->get('department_id')),
        ];
        $datas = $this->positionRepo->search($request, 20);

        return view('personnel/position/index', compact('datas', 'model', 'selectOptions', 'fieldMetadata'));
    }

    public function getData()
    {
        try {
            $datas = $this->positionRepo->all();
            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function create()
    {
        $positions = StringHelpers::getSelectOptions($this->positionRepo->all());
        $users = StringHelpers::getSelectOptions($this->userRepo->getActive());
        $departments = StringHelpers::getSelectOptions($this->departmentRepo->all());
        return view('personnel/position/create', compact('departments', 'users', 'positions'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, $this->positionRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput()->with('action', 'store');;
            }

            $input['created_by'] = Auth::user()->id;
            $input['active'] = isset($input['active']) ? 1 : 0;

            $this->positionRepo->create($input);
            return redirect()->route('personnel.position.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    { {
            try {
                $data = $this->positionRepo->find($id);

                if ($data) {
                    $departments = StringHelpers::getSelectOptions($this->departmentRepo->all(), $data->department_id);
                    $users = StringHelpers::getSelectOptions($this->userRepo->getActive());
                    // return view('personnel/user/edit', compact('data', 'genders', 'departments', 'positions', 'roles'));
                    return $this->success($data);
                }
                return with("error", "Không tìm thấy dữ liệu");
            } catch (Exception $ex) {
                return back()->withError($ex->getMessage())->withInput();
            }
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            $data = $this->positionRepo->find($id);
            $validator = Validator::make($input, $this->positionRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput()->with('action', 'update')->with('editId', $id);
            }

            $input['active'] = isset($input['active']) ? 1 : 0;
            unset($input['code']);
            $res = $data->update($input);
            return redirect()->route('personnel.position.index')->with('success', 'Thành công');
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
            $this->positionRepo->destroy($id);
            return redirect()->route('personnel.position.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
}
