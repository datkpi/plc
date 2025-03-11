<?php

namespace App\Http\Controllers\Recruitment;

use App\Models\RequestFormDetail;
use App\Repositories\Recruitment\RequestFormDetailRepository;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;

class RequestFormDetailController extends Controller
{
    public function __construct(RequestFormDetailRepository $requestFormDetailRepo)
    {
        $this->requestFormDetailRepo = $requestFormDetailRepo;
    }

    public function index()
    {

    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        try {

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function comment(Request $request, $request_form_id)
    {
        try {
            $input = $request->all();
            $input['content'] = "đã thêm comment";
            $input['created_by'] = Auth::user()->id;
            $input['action'] = RequestFormDetail::ACTION_COMMENT;
            $input['request_form_id'] = $request_form_id;
            $this->requestFormDetailRepo->create($input);
            return back()->with('success', "Thêm mới comment thành công");
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit()
    {
        try {

            return back()->with("error", "Không tìm thấy dữ liệu");
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {

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

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

}
