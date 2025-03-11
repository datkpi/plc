<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;
use Illuminate\Support\Facades\DB;

class CandidateRepository extends AbstractRepository
{

    public function __construct(\Illuminate\Container\Container $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return 'App\Models\Candidate';
    }

    public function validateCreate()
    {
        return $rules = [
            'name' => 'required',
            // 'user_uid' => 'unique:candidate',
            'birthday' => 'required|before:today',
            'received_time' => 'required',
            'source_id' => 'required',
            'receiver_id' => 'required',
            'position_id' => 'required',
            // 'relationship_note' => 'required',
            'gender' => 'required',
            'email' => 'email',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'household' => 'required',
            // 'address' => 'required',
            // 'address_detail' => 'required',
            // 'level' => 'required',
            // 'branch' => 'required',
            // 'major' => 'required',
            // 'training_place' => 'required',
            // 'recruiter' => 'required',
            // 'request_form_id' => 'required',
        ];
    }

    public function validateSubmitForm()
    {
        return $rules = [
            'name' => 'required',
            // 'user_uid' => 'unique:candidate',
            'birthday' => 'required|before:today',
            'source_id' => 'required',
            'position_id' => 'required',
            // 'relationship_note' => 'required',
            'gender' => 'required',
            'email' => 'nullable|email',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'household' => 'required',
            // 'address' => 'required',
            // 'address_detail' => 'required',
            // 'level' => 'required',
            // 'branch' => 'required',
            // 'major' => 'required',
            // 'training_place' => 'required',
            // 'recruiter' => 'required',
            // 'request_form_id' => 'required',
        ];
    }

    public function validateCreateForm()
    {
        return $rules = [
            'form_token' => 'required|unique:candidate',
        ];
    }

    public function validateUpdate($id)
    {
        return $rules = [
            'name' => 'required',
            // 'user_uid' => 'unique:candidate,user_uid,' . $id . ',id',
            'birthday' => 'required|before:today',
            // 'department_id' => 'required',
            'position_id' => 'required',
            'received_time' => 'required',
            'source_id' => 'required',
            'receiver_id' => 'required',
            // 'relationship_note' => 'required',
            'gender' => 'required',
            'email' => 'nullable|email',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            // 'cccd' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'household' => 'required',
            // 'address' => 'required',
            // 'address_detail' => 'required',
            // 'level' => 'required',
            // 'branch' => 'required',
            // 'major' => 'required',
            // 'training_place' => 'required',
            // 'recruiter' => 'required',
            // 'request_form_id' => 'required',
        ];
    }

    public function validateTakeJob()
    {
        return $rules = [
            'salary_from' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'salary_to' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }

    public function validateImport()
    {
        return $rules = [
            'name' => 'required',
            // 'user_uid' => 'unique:candidate',
            // 'received_time' => 'required',
            // 'source_id' => 'required',
            // 'receiver_id' => 'required',
            // 'position_id' => 'required',
            // 'relationship_note' => 'required',
            'gender' => 'required',
            // 'email' => 'email',
            'phone_number' => 'required_if:data,null|regex:/^([0-9\s\-\+\(\)]*)$/|min:8',
            //'cccd' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            // 'household' => 'required',
            // 'address' => 'required',
            // 'address_detail' => 'required',
            // 'level' => 'required',
            // 'branch' => 'required',
            // 'major' => 'required',
            // 'training_place' => 'required',
            // 'recruiter' => 'required',
            // 'request_form_id' => 'required',
        ];
    }

    //Lấy danh sách sẵn sàng phỏng vấn
    public function getIsInterview($listInterviewStatus = array())
    {
        return $this->model->where('interview_schedule_id', null)->whereIn('status', $listInterviewStatus)->get();
    }

    //đếm số lượng ứng viên đang ứng tuyển
    public function countCandidating()
    {
        return $this->model->whereNotIn('status', ['employee', 'reject'])->count();
    }

    public function getInterviewSchedule()
    {
        return $this->model->whereIn('stage', [0, 1, 2, 3])->get();
    }

    //Danh sách đang phỏng vấn
    public function getInterviewing($listInterviewStatus = array(), $interview_schedule_id)
    {
        $listIsInterview = $this->model->where('int erview_schedule_id', null)->whereIn('status', $listInterviewStatus)->get();
        $listCandidateInSchedule = $this->model->where('interview_schedule_id', $interview_schedule_id)->get();

        $merged = $listIsInterview->merge($listCandidateInSchedule);
        return $merged;
        // return $this->model->where(function ($query) use ($interview_schedule_id) {
        //     return $query->where('interview_schedule_id', '=', null)
        //         ->orWhere('interview_schedule_id', '=', $interview_schedule_id);

        // })->where(function ($query) use ($listInterviewStatus) {
        //     return $query->whereIn('status', $listInterviewStatus);
        // })->get();
        // return $this->model->where('interview_schedule_id', $interview_schedule_id)->orWhereIn('status', $listInterviewStatus)->get();
    }

    //Danh sách có thể pv + nv phỏng vấn trong lịch
    public function getIsInterViewById($listInterviewStatus = array(), $interview_schedule_id)
    {
        $listIsInterview = $this->model->where('interview_schedule_id', null)->whereIn('status', $listInterviewStatus)->get();
        $listCandidateInSchedule = $this->model->where('interview_schedule_id', $interview_schedule_id)->get();

        $merged = $listIsInterview->merge($listCandidateInSchedule);
        return $merged;
    }

    public function countByStatus()
    {
        return $this->model->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
    }


}
