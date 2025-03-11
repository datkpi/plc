<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RecruitmentPlan extends BaseModel
{
    use HasUuids;
    // use LogsActivity;

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         // Chain fluent methods to set configuration options
    //         ->logOnly(['*'])
    //         ->useLogName('Kế hoạch tuyển dụng')
    //         ->setDescriptionForEvent(function (string $eventName) {
    //             return "Bảng này đã được {$eventName}";
    //         });
    // }
    protected $table = "recruitment_plan";

    protected $fillable = [
        'request_form_id',
        'name',
        'time',
        'status',
        'stage',
        'created_by',
        'approve_id',
        'approve_status',
        'start_date',
        'end_date',
    ];

    /**
     * Get all of the comments for the RecruitmentPlan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requestForm()
    {
        return $this->hasMany(RequestForm::class, 'recruitment_plan_id', 'id');
    }
}
