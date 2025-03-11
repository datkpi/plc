<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;
use Illuminate\Support\Carbon;

class InterviewScheduleRepository extends AbstractRepository
{

    public function __construct(\Illuminate\Container\Container $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return 'App\Models\InterviewSchedule';
    }

    public function validateCreate()
    {
        return $rules = [
            // 'request_form_id' => 'required',
            'interviewer' => 'required',
            // 'name' => 'required|unique:interview_schedule',
            'interview_from' => 'required',
            'interview_to' => 'required|after:interview_from',
            'address' => 'required',
        ];
    }

    public function validateUpdate($id)
    {
        return $rules = [
            //'request_form_id' => 'required',
            // 'name' => 'required|unique:interview_schedule,name,' . $id . ',id',
            'interviewer' => 'required',
            'interview_from' => 'required',
            'address' => 'required',
            // 'interview_to' => 'required|after:interview_from',
        ];
    }

    public function getScheduleByCandidate($candidate_id)
    {
        $datas = $this->model->where('candidate_id', $candidate_id)->orderBy('stage', 'asc')->get();
        return $datas;
    }

    public function countInterviewing($userId)
    {
        $today = Carbon::now()->toDateString();
        $count = $this->model->where('interviewer', $userId)->orWhere('relationer', 'like', "%{$userId}%")->whereDate('interview_from', '>=', $today)->count();
        return $count;
    }


}
