<?php

namespace App\Http\Controllers\Recruitment;

use App\Enums\GenderEnum;
use App\Models\Candidate;
use App\Common\Traits\PlaceholderReplacer;
use App\Models\MailTemplate;
use App\Mail\SendEmail;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\MailTemplateRepository;
use Illuminate\Support\Facades\Auth;
use Mail;
use Validator;
use App\Common\FileUploads;

class MailTemplateController extends Controller
{
    use PlaceholderReplacer;
    public function __construct(MailTemplateRepository $mailTemplateRepo)
    {
        $this->mailTemplateRepo = $mailTemplateRepo;
    }

    public function index()
    {
        $datas = $this->mailTemplateRepo->all();
        return view('recruitment/mail_template/index', compact('datas'));
    }

    public function test($id)
    {
        $candidate = Candidate::find('9a2f1241-8e11-4f8d-9e75-38fcf9df1bdf');
        $mail = MailTemplate::find($id);
        $emailContent = $mail->body;
        $emailContent = $this->replaceGender($emailContent, $candidate->gender);
        // $emailContent = $this->replaceTime($emailContent, $candidate);
        $processedEmail = $this->replacePlaceholders($emailContent, $candidate);
        $subject = $mail->namespace;
        $content = $processedEmail;
        $to = 'tiendat982745@gmail.com';

        Mail::to($to)->send(new SendEmail(subject: $subject, content: $content));
        return redirect()->back()->with('success', 'Thành công');
    }

    public function create()
    {
        // Thay thế chuỗi
        $candidate = Candidate::find('9a2f1241-8e11-4f8d-9e75-38fcf9df1bdf');
        $mail = MailTemplate::where('name', 'test')->first();
        // $emailContent = "{{candidate.interview_schedules.name}} ,Xin chào {{candidate.name}}, vị trí của bạn là {{candidate.position.name}}.";
        // $emailContent = $mail->body;
        // $emailContent = $this->replaceGender($emailContent, $candidate->gender);
        // // $emailContent = $this->replaceTime($emailContent, $candidate);
        // $processedEmail = $this->replacePlaceholders($emailContent, $candidate);
        // $subject = 'Thư mời tuyển dụng';
        // $content = $processedEmail;
        // $to = 'tiendat982745@gmail.com';
        // Mail::to($to)->send(new SendEmail(subject: $subject, content: $content));
        //return back();
        // Gửi email
        // Mail::to($recipientEmail)->queue(new CustomDatabaseMail($htmlContent));


        $candidateColunms = Candidate::getTableColumns();
        $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases());
        return view('recruitment/mail_template/create', compact('genders', 'candidateColunms'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, $this->mailTemplateRepo->validateCreate());

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $input['created_by'] = Auth::user()->id;
            $input['active'] = isset($input['active']) ? 1 : 0;
            $appUrl = env('APP_URL', '');
            $input['file'] = str_replace($appUrl, '', $input['file']);
            $this->mailTemplateRepo->create($input);
            return redirect()->route('recruitment.mail_template.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->mailTemplateRepo->find($id);
            if ($data) {
                $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases(), $data->gender);
                $candidateColunms = Candidate::getTableColumns();
                return view('recruitment/mail_template/edit', compact('data', 'genders', 'candidateColunms'));
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
            $data = $this->mailTemplateRepo->find($id);
            $validator = Validator::make($input, $this->mailTemplateRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['active'] = isset($input['active']) ? 1 : 0;
            if ($input['file'] != null) {
                $appUrl = env('APP_URL', '');
                $input['file'] = str_replace($appUrl, '', $input['file']);
            }

            $res = $data->update($input);
            return redirect()->route('recruitment.mail_template.index')->with('success', 'Thành công');
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
            $this->mailTemplateRepo->destroy($id);
            return redirect()->route('recruitment.mail_template.index')->with('success', 'Xóa thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

}
