<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;

class SourceRepository extends AbstractRepository
{

    public function __construct(\Illuminate\Container\Container $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return 'App\Models\Source';
    }

    public function validateCreate()
    {
        return $rules = [
            'name' => 'required',

        ];
    }
    public function validateUpdate($id)
    {
        return $rules = [
            'name' => 'required|unique:source,name,' . $id . ',id',

        ];
    }
}
