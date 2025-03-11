<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;

class AnnualEmployeeRepository extends AbstractRepository
{

    public function __construct(\Illuminate\Container\Container $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return 'App\Models\AnnualEmployee';
    }

    public function validateCreate()
    {
        return $rules = [
            'position_id' => 'required',
            'month' => 'required',
            'year' => 'required',
            'employee_number' => 'required|integer|min:0',
        ];
    }
    public function validateUpdate($id)
    {
        return $rules = [
            'position_id' => 'required',
            'month' => 'required',
            'year' => 'required',
            'employee_number' => 'required|integer|min:0',
        ];
    }
    public function checkDuplicate($position_id, $month, $year)
    {
        $check = $this->model->where('position_id', $position_id)->where('month', $month)->where('year', $year)->first();
        if ($check) {
            return false;
        }
        return true;
    }

    public function checkDuplicateUpdate($id, $position_id, $month, $year)
    {
        $check = $this->model->where('id', '!=', $id)->where('position_id', $position_id)->where('month', $month)->where('year', $year)->first();
        if ($check) {
            return false;
        }
        return true;
    }

}
