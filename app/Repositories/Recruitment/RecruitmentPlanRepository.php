<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;
use Illuminate\Support\Facades\DB;

class RecruitmentPlanRepository extends AbstractRepository
{

    public function __construct(\Illuminate\Container\Container $app)
    {
        parent::__construct($app);
    }
    public function model()
    {
        return 'App\Models\RecruitmentPlan';
    }

    public function validateCreate()
    {
        return $rules = [
            'name' => 'required|unique:recruitment_plan',
            'start_date' => 'required',
            'end_date' => 'required|after:start_date|after:today',
        ];
    }
    public function validateUpdate($id)
    {
        return $rules = [
            'name' => 'required|unique:recruitment_plan,name,' . $id . ',id',
            'start_date' => 'required',
            'end_date' => 'required|after:start_date|after:today',
        ];
    }
    public function getWithCountCandidate()
    {
        return DB::table('recruitment_plan')
            ->select('recruitment_plan.id', 'recruitment_plan.name', 'recruitment_plan.status', 'recruitment_plan.start_date', 'recruitment_plan.end_date', DB::raw('count(candidate.id) as candidate_count'), DB::raw('sum(case when candidate.status = "employee" then candidate.id else 0 end) as candidate_employee'), DB::raw('sum(request_form.quantity) as total'))
            ->leftJoin('request_form', 'recruitment_plan.id', '=', 'request_form.recruitment_plan_id')
            ->leftJoin('candidate', 'request_form.id', '=', 'candidate.request_form_id')
            ->groupBy('recruitment_plan.id', 'recruitment_plan.name')
            ->get();
    }


}
