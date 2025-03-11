<?php

namespace App\Repositories\Recruitment;

use App\Enums\RequestFormEnum;
use App\Repositories\Support\AbstractRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestFormRepository extends AbstractRepository
{

    public function __construct(\Illuminate\Container\Container $app)
    {
        parent::__construct($app);
    }
    public function model()
    {
        return 'App\Models\RequestForm';
    }

    public function validateCreate()
    {
        return $rules = [
            // 'name' => 'required|unique:request_form',
            // 'department_id' => 'required',
            // 'request_date' => 'required',
            'position_id' => 'required',
            'recruitment_type' => "required",
            // 'estimate_start' => 'required|after:today',
            'level' => 'required',
            'field' => 'required',
            'experience' => 'required',
            'language_level' => 'required',
            'language_name' => 'required',
            'quantity' => 'required|integer||min:1',
            'staff_quantity' => 'integer||min:0',
            //'recruited' => 'required|integer||min:0',
            //'staffing' => 'required',
            // 'salary_from' => 'numeric|min:0',
            // 'salary_to' => 'numeric|min:0',
        ];
    }
    public function validateUpdate($id)
    {
        return $rules = [
            // 'name' => 'required|unique:request_form,name,' . $id . ',id',
            // 'department_id' => 'required',
            // 'request_date' => 'required',
            'position_id' => 'required',
            'recruitment_type' => "required",
            // 'estimate_start' => 'required|after:today',
            'level' => 'required',
            'field' => 'required',
            'experience' => 'required',
            'language_level' => 'required',
            'language_name' => 'required',
            'quantity' => 'required|integer||min:1',
            'staff_quantity' => 'integer||min:0',
            //'recruited' => 'required|integer||min:0',
            //'staffing' => 'required',
            // 'salary_from' => 'required|numeric|min:0',
            // 'salary_to' => 'required|numeric|min:0',
        ];
    }

    public function countByStatus()
    {
        return $this->model->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
    }

    public function totalApprove()
    {
        return $this->model->where('current_approve', Auth::user()->id)->where('status', 'approving')->count();
    }

    //danh sách phiếu đề xuất đã đc duyệt và đang xử lý
    public function getProcessing()
    {
        return $this->model->where('status', RequestFormEnum::process->name)->get();
    }
    public function getParent()
    {
        return $this->model->whereNull('parent_id')->get();
    }



}
