<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Notifications extends BaseModel
{
    use HasFactory, HasUuids;

    protected $table = "notifications";
    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'push_id',
        //Người đẩy thông báo
        'user_ids',
        //Người nhận thông báo
        'data',
        'read_ats',
        'message',
        'link',
        'read_ats',
        'user_ids',
    ];
}