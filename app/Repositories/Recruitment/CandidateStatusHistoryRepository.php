<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;
use Illuminate\Support\Facades\DB;

class CandidateStatusHistoryRepository extends AbstractRepository
{

    public function __construct(\Illuminate\Container\Container $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return 'App\Models\CandidateStatusHistory';
    }



}
