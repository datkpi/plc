<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;

class RequestFormConfigRepository extends AbstractRepository
{

    public function __construct(\Illuminate\Container\Container $app)
    {
        parent::__construct($app);
    }
    public function model()
    {
        return 'App\Models\RequestFormConfig';
    }

    public function validateCreate()
    {
        return $rules = [
            'deadline_after' => 'required|integer|min:1',
        ];
    }
    public function validateUpdate($id)
    {
        return $rules = [
            'deadline_after' => 'required|integer|min:1',
        ];
    }

}
