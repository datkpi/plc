<?php

namespace App\Http\Controllers\Personnel;

use App\Common\Traits\ApiResponses;
use App\Enums\GenderEnum;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\DepartmentRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Recruitment\UserRepository;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Common\FileUploads;
use App\Models\Department;

class DepartmentController extends Controller
{
    use ApiResponses;
    public function __construct(DepartmentRepository $departmentRepo, UserRepository $userRepo)
    {
        $this->departmentRepo = $departmentRepo;
        $this->userRepo = $userRepo;
    }
    public function convertUidToCode()
    {
        try {
            Department::query()->update(['code' => DB::raw('uid')]);
            return response()->json(['success' => true, 'message' => 'Conversion successful.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function sync()
    {
        $hrmDepartments = $this->departmentRepo->all();
        foreach ($hrmDepartments as $key => $department) {
            $input = [];
            $input['name'] = $department->code;
            $input['display_name'] = $department->name;
            Department::on('sangkien')
                ->where('name', $department->code)
                ->update($input);
        }
    }

    public function index(Request $request)
    {
        $model = new \App\Models\Department();
        $fieldMetadata = $model->getFieldMetadata();
        $selectOptions = [
            // 'position_id' => StringHelpers::getSelectOptions($this->positionRepo->all()),
            'manager_by' => StringHelpers::getSelectOptions($this->userRepo->all(),$request->get('manager_by')),
            'gender' => StringHelpers::getSelectEnumOptions(GenderEnum::cases()),
            'parent_id' => StringHelpers::getSelectOptions($this->departmentRepo->all(),$request->get('parent_id')),
            'department_id' => StringHelpers::getSelectOptions($this->departmentRepo->all(), $request->get('department_id')),
        ];
        $datas = $this->departmentRepo->search($request, 20);
        return view('personnel/department/index', compact('datas', 'model', 'selectOptions', 'fieldMetadata'));
    }

    public function getData()
    {
        try {
            $datas = $this->departmentRepo->all();
            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function create()
    {
        $departments = StringHelpers::getSelectOptions($this->departmentRepo->all());
        $users = StringHelpers::getSelectOptions($this->userRepo->getActive());
        $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases());
        $parents =  StringHelpers::getSelectOptions($this->userRepo->all());
        $manager = StringHelpers::getSelectOptions($this->userRepo->all());
        return view('personnel/department/create', compact('genders', 'users', 'departments','parents','manager'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, $this->departmentRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput()->with('action', 'store');;
            }

            $input['active'] = isset($input['active']) ? 1 : 0;
            $input['created_by'] = Auth::user()->id;
            $this->departmentRepo->create($input);
            return redirect()->route('personnel.department.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->departmentRepo->find($id);

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

    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            $data = $this->departmentRepo->find($id);
            $validator = Validator::make($input, $this->departmentRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput()->with('action', 'update')->with('editId', $id);
            }
            $input['active'] = isset($input['active']) ? 1 : 0;
            unset($input['code']);
            $res = $data->update($input);
            return redirect()->route('personnel.department.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            //dd($ex->getMessage());
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
            $this->departmentRepo->destroy($id);
            return redirect()->route('personnel.department.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
}
