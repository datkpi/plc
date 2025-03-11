<?php

namespace App\Http\Controllers\Recruitment;

use App\Enums\GenderEnum;
use App\Repositories\Recruitment\MailTemplateRepository;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\MailRepository;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Common\FileUploads;

class MailController extends Controller
{
    public function __construct(MailRepository $mailRepo, MailTemplateRepository $mailTemplateRepo)
    {
        $this->mailRepo = $mailRepo;
        $this->mailTemplateRepo = $mailTemplateRepo;
    }

    public function index()
    {
        $datas = $this->mailRepo->all();
        return view('recruitment/mail/index', compact('datas'));
    }

    public function create()
    {
        $templates = StringHelpers::getSelectOptions($this->mailTemplateRepo->all());
        $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases());
        return view('recruitment/mail/create', compact('genders', 'templates'));
    }

    public function store(Request $request)
    {
        try {
            // dd('top this');
            $input = $request->all();

            $validator = Validator::make($input, $this->mailRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['created_by'] = Auth::user()->id;
            $input['active'] = isset($input['active']) ? 1 : 0;
            // $appUrl = env('APP_URL','');
            // $input['file'] = str_replace($appUrl, '', $input['file']);
            $this->mailRepo->create($input);
            return redirect()->route('recruitment.mail.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->mailRepo->find($id);
            if ($data) {
                $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases(), $data->gender);
                return view('recruitment/mail/edit', compact('data', 'genders'));
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
            $data = $this->mailRepo->find($id);
            $validator = Validator::make($input, $this->mailRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['active'] = isset($input['active']) ? 1 : 0;
            // if($input['file'] != null)
            // {
            //     $appUrl = env('APP_URL','');
            //     $input['file'] = str_replace($appUrl, '', $input['file']);
            // }

            $res = $data->update($input);
            return redirect()->route('recruitment.mail.index')->with('success', 'Thành công');
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
            $this->mailRepo->destroy($id);
            return redirect()->route('recruitment.mail.index')->with('success', 'Xóa thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

}
