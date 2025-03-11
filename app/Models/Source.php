<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Source extends BaseModel
{
    use HasUuids;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Chain fluent methods to set configuration options
            ->logOnly(['*'])
            ->useLogName('Nguồn tuyển dụng')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Bảng này đã được {$eventName}";
            });
    }
    protected $table = "source";
    protected $fillable = [
        'name',
        'link',
        'contact',
        'phone_contact',
        'email_register',
        'email_contact',
        'description',
        'image',
        'active',
        'created_by',
    ];

    public function candidate()
    {
        return $this->hasMany('App\Models\Candidate', 'source_id', 'id');
    }



}