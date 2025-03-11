<?php

namespace App\Http\Controllers\Personnel;

use Exception;
use Validator;
use App\Models\User;
use App\Models\Position;
use App\Enums\GenderEnum;
use App\Models\Department;
use App\Common\FileUploads;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use Illuminate\Support\Facades\DB;
use App\Common\Traits\ApiResponses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use App\Repositories\Recruitment\RoleRepository;
use App\Repositories\Recruitment\UserRepository;
use App\Repositories\Recruitment\PositionRepository;
use App\Repositories\Recruitment\DepartmentRepository;

class UserController extends Controller
{
    use ApiResponses;
    public function __construct(RoleRepository $roleRepo, UserRepository $userRepo, DepartmentRepository $departmentRepo, PositionRepository $positionRepo)
    {
        $this->userRepo = $userRepo;
        $this->departmentRepo = $departmentRepo;
        $this->positionRepo = $positionRepo;
        $this->roleRepo = $roleRepo;
    }

    public function sync()
    {
        $sangkien_users = DB::connection('sangkien')->table('users')->get();

        foreach ($sangkien_users as $user) {
            //dd($user);
            $input = [];
            $nameExists = User::where('name', $user->display_name)->where('code', $user->username)->exists();

            $input['code'] = $user->username;
            $input['tax_code'] = $user->taxCode;
            $input['name'] = $user->display_name;
            $input['gender'] =  ($user->sex === 'Nam') ? 'male' : (($user->sex === 'Nữ') ? 'female' : null);
            $input['birthday'] = $user->date_of_birth;
            $input['username'] = $user->username;
            $input['cccd'] = $user->cccd;
            $input['cccd_date'] = $user->cccd_date;
            $input['cccd_issuer'] = $user->cccd_issuer;
            $input['phone_number'] = $user->phone_Number;
            $input['contract_start'] = $user->contract_start;
            // $input['end_job'] = $user->termination_retirement;
            $input['contract_end'] = $user->contract_end;
            $input['email'] = $user->email;
            if ($nameExists) {
                User::where('name', $user->display_name)->where('code', $user->username)->update($input);
            } else {
                $this->userRepo->create($input);
            }
        }
        return 'success';
    }

    public function syncPosition()
    {
        $sangkien_users = DB::connection('sangkien')->table('users')->get();

        foreach ($sangkien_users as $sangkien_user) {
            $input = [];
            $position = Position::where('name', 'like', '%' . $sangkien_user->jobTitle . '%')->first();
            $input['position_id'] = $position ? $position->id : null;
            User::where('code', $sangkien_user->username)->update($input);
        }

        return 'success';
    }

    public function syncDepartment()
    {
        $sangkien_users = DB::connection('sangkien')->table('users')->get();

        foreach ($sangkien_users as $sangkien_user) {
            $input = [];
            $department = Department::where('code', $sangkien_user->department)->first();
            $input['department_id'] = $department ? $department->id : null;
            User::where('code', $sangkien_user->username)->update($input);
        }

        return 'success';
    }


    public function index(Request $request)
    {

        $model = new \App\Models\User();
        // $fieldsOrder = $model->getFieldOrder();
        $fieldMetadata = $model->getFieldMetadata();

        $selectOptions = [
            'department_id' => StringHelpers::getSelectOptions($this->departmentRepo->all(), $request->get('department_id')),
            'position_id' => StringHelpers::getSelectOptions($this->positionRepo->all(), $request->get('position_id')),
            'role_id' => StringHelpers::getSelectOptions($this->roleRepo->all(), $request->get('role_id')),
            'gender' => StringHelpers::getSelectEnumOptions(GenderEnum::cases(), $request->get('gender')),
            // Thêm các tùy chọn khác nếu cần
        ];

        $datas = $this->userRepo->search($request, 15);
        //$datas = $this->userRepo->paginate($request);
        return view('personnel/user/index', compact('datas', 'model', 'selectOptions', 'fieldMetadata'));
    }

    public function create()
    {
        $departments = StringHelpers::getSelectOptions($this->departmentRepo->all());
        $positions = StringHelpers::getSelectOptions($this->positionRepo->all());
        $roles = StringHelpers::getSelectOptions($this->roleRepo->getActive());
        $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases());
        return view('recruitment/user/create', compact('genders', 'positions', 'departments', 'roles'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            if($input['role_id'] != null){
                $input['role_id'] = implode(",", $input['role_id']);
            }
            else
            {
                unset($input['role_id']);
            }

            $validator = Validator::make($input, $this->userRepo->validateCreate());
            if ($validator->fails()) {
                // dd($validator->errors());
                return redirect()->back()->withErrors($validator)->withInput()->with('action', 'store');
            }

            $input['created_by'] = Auth::user()->id;
            if (isset($input['avatar'])) {
                $input['avatar'] = FileUploads::uploadImage($request, "avatars", "avatar");
            }
            $input['active'] = isset($input['active']) ? 1 : 0;

            $this->userRepo->create($input);
            return redirect()->route('personnel.user.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->userRepo->find($id);
            if ($data) {
                $departments = StringHelpers::getSelectOptions($this->departmentRepo->all(), $data->department_id);
                $positions = StringHelpers::getSelectOptions($this->positionRepo->all(), $data->position_id);
                $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases(), $data->gender);
                $roles = StringHelpers::getSelectOptions($this->roleRepo->getActive(), explode(',', $data->role_id));
                return $this->success($data);
            }
            return $this->error();
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            if($input['password'] == null)
            {
                unset($input['password']);
            }

             if($input['role_id'] != null){
                $input['role_id'] = implode(",", $input['role_id']);
            }
            else
            {
                unset($input['role_id']);
            }

            $data = $this->userRepo->find($id);
            $validator = Validator::make($input, $this->userRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput()->with('action', 'update')->with('editId', $id);
            }

            if (isset($input['avatar'])) {
                $input['avatar'] = FileUploads::uploadImage($request, "avatars", "avatar", $data->avatar);
            }
            $input['active'] = isset($input['active']) ? 1 : 0;
            unset($input['code']);
            $res = $data->update($input);
            return redirect()->route('personnel.user.index')->with('success', 'Thành công');
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
            $this->userRepo->destroy($id);
            return redirect()->route('personnel.user.index')->with('success', 'Xóa thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function getChangePassword($id)
    {
        try {
            $data = $this->userRepo->find($id);
            return view('personnel.user.change_password', compact('data'));
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function changePassword(Request $request, $id)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, $this->userRepo->validateChangePassword());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $res = $this->userRepo->update($request->only('password'), $id);
            return redirect()->route('personnel.user.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    public function getByDepartment(Request $request)
    {
        try {
            $departmentId = $request->departmentId;
            if ($departmentId != null) {
                $datas = $this->userRepo->getBy('department_id', $departmentId);
            } else {
                $datas = $this->userRepo->all();
            }
            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->success($ex->getMessage());
        }
    }
    public function profile(Request $request)
    {
        $model = new \App\Models\User();
        $fieldMetadata = $model->getFieldMetadata();
        $data_user = Auth::user();
        // dd($data_user);
        $selectOptions = [
            'department_id' => StringHelpers::getSelectOptions($this->departmentRepo->all(), $request->get('department_id')),
            'position_id' => StringHelpers::getSelectOptions($this->positionRepo->all(), $request->get('position_id')),
            'role_id' => StringHelpers::getSelectOptions($this->roleRepo->all(), $request->get('role_id')),
            'gender' => StringHelpers::getSelectEnumOptions(GenderEnum::cases(), $request->get('gender')),
            // Thêm các tùy chọn khác nếu cần
        ];
        // dd($data_user);
        $compact = [
            'model' => $model,
            'fieldMetadata' => $fieldMetadata,
            'selectOptions' => $selectOptions,
            'data_user' => $data_user,
        ];
        return view('personnel/user/profile', $compact);
    }
}
