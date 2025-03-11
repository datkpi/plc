<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Mail extends BaseModel
{
    use HasUuids;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Chain fluent methods to set configuration options
            ->logOnly(['*'])
            ->useLogName('mail')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Bảng này đã được {$eventName}";
            });
    }
    protected $table = "mail";
    protected $fillable = [
        'subject',
        'from',
        'to',
        'bcc',
        'cc',
        'total_email',
        'mail_template_id',
        'body',
        'footer',
        'file',
        'mail_status',
        'reply_to',
        'active',
        'created_by',
        'code'
    ];



}
