<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RecruitmentNeed extends BaseModel
{
    use HasUuids;
    // use LogsActivity;

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         // Chain fluent methods to set configuration options
    //         ->logOnly(['*'])
    //         ->useLogName('Kế hoạch tuyển dụng report')
    //         ->setDescriptionForEvent(function (string $eventName) {
    //             return "Bảng này đã được {$eventName}";
    //         });
    // }
    protected $table = "recruitment_need";

    protected $fillable = [
        'id',
        'time',
        'position_id',
        'need',
        //Nhu cầu đầu kỳ
        'more',
        //Cần thêm
        'sub',
        //Giảm trừ
        'total',
        //Tổng cuối
        'sub_reason',
        //Lý do giảm trừ
        'recruitmented',
        //Đã tuyển theo tháng
        'created_by',
        'active',
    ];

    /**
     * Get all of the comments for the RecruitmentPlan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }
}
