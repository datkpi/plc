<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CandidateStatusHistory extends BaseModel
{
    protected $table = "candidate_status_history";
    protected $fillable = [
        'candidate_id',
        'old_status',
        'new_status',
        'changed_at',
    ];
}