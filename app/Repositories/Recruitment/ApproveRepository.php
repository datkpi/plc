<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;

class ApproveRepository extends AbstractRepository
{

    public function __construct(\Illuminate\Container\Container $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return 'App\Models\Approve';
    }

    public function validateCreate()
    {
        return $rules = [
            'name' => 'required|unique:approve',
            'department_id' => 'required',
            'approve_1' => 'required',
            'approve_2' => 'required',
            'approve_3' => 'required',
            'approve_4' => 'required',
        ];
    }
    public function validateUpdate($id)
    {
        return $rules = [
            'name' => 'required|unique:approve,name,' . $id . ',id',
            'department_id' => 'required',
            'approve_1' => 'required',
            'approve_2' => 'required',
            'approve_3' => 'required',
            'approve_4' => 'required',
        ];
    }

}
