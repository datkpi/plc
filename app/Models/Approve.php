<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Approve extends BaseModel
{
    use HasUuids;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Chain fluent methods to set configuration options
            ->logOnly(['*'])
            ->useLogName('Cấu hình duyệt')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Bảng này đã được {$eventName}";
            });
    }

    protected $table = "approve";
    protected $fillable = [
        'is_auto',
        'name',
        'department_id',
        'approve_1',
        'approve_1_required',
        'approve_2',
        'approve_2_required',
        'approve_3',
        'approve_3_required',
        'approve_4',
        'approve_4_required',
        'approve_5',
        'approve_5_required',
        'parent_id',
        'in_sequence',
        //Có cần duyệt theo trình tự không
        'level',
        'min_approve',
        //Lượt duyệt tối thiểu để pass
        'description',
        'type',
        'active',
        'created_by',
    ];

    public function approve1()
    {
        return $this->belongsTo('App\Models\User', 'approve_1', 'id');
    }
    public function approve2()
    {
        return $this->belongsTo('App\Models\User', 'approve_2', 'id');
    }
    public function approve3()
    {
        return $this->belongsTo('App\Models\User', 'approve_3', 'id');
    }
    public function approve4()
    {
        return $this->belongsTo('App\Models\User', 'approve_4', 'id');
    }


}