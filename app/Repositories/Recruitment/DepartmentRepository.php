<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;

class DepartmentRepository extends AbstractRepository
{

    public function __construct(\Illuminate\Container\Container $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return 'App\Models\Department';
    }

    public function validateCreate()
    {
        return $rules = [
            'name' => 'required|unique:department',
            'code' => 'required|unique:department',
        ];
    }
    public function validateUpdate($id)
    {
        return $rules = [
            'name' => 'required|unique:department,name,' . $id . ',id',
            'code' => 'required|unique:department,code,' . $id . ',id',
        ];
    }
    public function getParent()
    {
        return $this->model->whereNull('parent_id')->get();
    }

}
