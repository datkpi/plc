<?php

namespace App\Http\Controllers\Recruitment;

use App\Common\Traits\ApiResponses;
use App\Enums\GenderEnum;
use App\Enums\PositionRankEnum;
use App\Models\Position;
use App\Repositories\Recruitment\InterviewAddressRepository;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\PositionRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Recruitment\UserRepository;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Common\FileUploads;

class InterviewAddressController extends Controller
{
    use ApiResponses;
    public function __construct(InterviewAddressRepository $interviewAddressRepo, UserRepository $userRepo)
    {
        $this->interviewAddressRepo = $interviewAddressRepo;
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $now = Carbon::now();
        // $categories = Category::whereNull('parent_id')->get();

        //$datas = $this->interviewAddressRepo->all();
        // $datas = $this->interviewAddressRepo->getParent();
        // $depth = 0;
        //return view('recruitment/interview_address/index', compact('datas'));
        return view('recruitment/interview_address/index');
    }

    public function getData()
    {
        try {
            $datas = $this->interviewAddressRepo->all();
            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function create()
    {
        return view('recruitment/interview_address/create');
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, $this->interviewAddressRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['created_by'] = Auth::user()->id;
            $input['active'] = isset($input['active']) ? 1 : 0;

            $this->interviewAddressRepo->create($input);
            return redirect()->route('recruitment.interview_address.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->interviewAddressRepo->find($id);
            if ($data) {
                $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases(), $data->gender);
                return view('recruitment/interview_address/edit', compact('data'));
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
            $data = $this->interviewAddressRepo->find($id);
            $validator = Validator::make($input, $this->interviewAddressRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['active'] = isset($input['active']) ? 1 : 0;
            $res = $data->update($input);
            return redirect()->route('recruitment.interview_address.index')->with('success', 'Thành công');
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
            $this->interviewAddressRepo->destroy($id);
            return $this->success();
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

}
