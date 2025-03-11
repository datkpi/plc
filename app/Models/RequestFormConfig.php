<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RequestFormConfig extends BaseModel
{
    use HasUuids;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Chain fluent methods to set configuration options
            ->logOnly(['*'])
            ->useLogName('Cấu hình phiếu tuyển dụng')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Bảng này đã được {$eventName}";
            });
    }
    protected $table = "request_form_config";
    protected $fillable = [
        'deadline_after',
        //Hết hạn sau ... ngày
        'send_notify_warning',
        'pass_approve', //Cho phép bỏ qua bước duyệt
    ];
}