<?php

namespace App\Http\Controllers\Recruitment;

use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Recruitment\RequestFormConfigRepository;
use Validator;

class RequestFormConfigController extends Controller
{
    public function __construct(RequestFormConfigRepository $requestFormConfigRepo)
    {
        $this->requestFormConfigRepo = $requestFormConfigRepo;
    }

    public function index()
    {
        $datas = $this->requestFormConfigRepo->all();
        return view('recruitment/request_form_config/index', compact('datas'));
    }

    public function create()
    {
        return view('recruitment/request_form_config/create');
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, $this->requestFormConfigRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['pass_approve'] = isset($input['pass_approve']) ? 1 : 0;
            $this->requestFormConfigRepo->create($input);
            return redirect()->route('recruitment.request_form_config.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit()
    {
        try {
            $data = $this->requestFormConfigRepo->findFirst();

            if ($data) {
                return view('recruitment/request_form_config/edit', compact('data'));
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
            $data = $this->requestFormConfigRepo->find($id);
            $validator = Validator::make($input, $this->requestFormConfigRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['pass_approve'] = isset($input['pass_approve']) ? 1 : 0;
            $res = $data->update($input);
            return redirect()->route('recruitment.request_form_config.index')->with('success', 'Thành công');
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
            $this->requestFormConfigRepo->destroy($id);
            return redirect()->route('recruitment.request_form_config.index')->with('success', 'Xóa thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

}
