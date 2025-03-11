<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InterviewAddress extends BaseModel
{
    use HasUuids;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Chain fluent methods to set configuration options
            ->logOnly(['*'])
            ->useLogName('Nơi phỏng vấn')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Bảng này đã được {$eventName}";
            });
    }
    protected $table = "interview_address";
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'active',
    ];

    public function approves()
    {
        return $this->hasMany('App\Models\Approve', 'approve_1', 'id');
    }


}