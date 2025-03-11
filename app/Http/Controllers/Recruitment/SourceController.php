<?php

namespace App\Http\Controllers\Recruitment;

use App\Common\Traits\ApiResponses;
use App\Enums\GenderEnum;
use App\Repositories\Recruitment\SourceRepository;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Common\FileUploads;

class SourceController extends Controller
{
    use ApiResponses;
    public function __construct(SourceRepository $sourceRepo)
    {
        $this->sourceRepo = $sourceRepo;
    }

    public function index()
    {
        try {
            $datas = $this->sourceRepo->all();
            return view('recruitment/source/index', compact('datas'));
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function getData()
    {
        try {
            $datas = $this->sourceRepo->all();
            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('recruitment/source/create');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function store(Request $request)
    {
        try {
            // dd('top this');
            $input = $request->all();
            $validator = Validator::make($input, $this->sourceRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['created_by'] = Auth::user()->id;
            $input['active'] = isset($input['active']) ? 1 : 0;
            $this->sourceRepo->create($input);
            return redirect()->route('recruitment.source.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->sourceRepo->find($id);
            if ($data) {
                return view('recruitment/source/edit', compact('data'));
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
            $data = $this->sourceRepo->find($id);
            $validator = Validator::make($input, $this->sourceRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }


            $input['active'] = isset($input['active']) ? 1 : 0;
            $res = $data->update($input);
            return redirect()->route('recruitment.source.index')->with('success', 'Thành công');
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
            $this->sourceRepo->destroy($id);
            return redirect()->route('recruitment.source.index')->with('success', 'Xóa thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

}
