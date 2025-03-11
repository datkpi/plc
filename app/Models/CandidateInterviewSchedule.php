<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CandidateInterviewSchedule extends BaseModel
{
    use HasUuids;
    protected $table = "candidate_interview_schedule";
    protected $fillable = [
        'interview_schedule_id',
        'candidate_id',
        'interview_result',
        'interview_at',
        'created_by',
    ];
}