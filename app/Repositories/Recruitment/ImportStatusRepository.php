<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;

class ImportStatusRepository extends AbstractRepository
{

    public function __construct(\Illuminate\Container\Container $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return 'App\Models\ImportStatus';
    }

    public function validateCreate()
    {
        return $rules = [

        ];
    }
    public function validateUpdate($id)
    {
        return $rules = [

        ];
    }

}
