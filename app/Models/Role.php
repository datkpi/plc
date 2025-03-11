<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends BaseModel
{
    const MODULE_RECRUITMENT = 1;
    const MODULE_PERSONNEL = 2;

    use HasUuids;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Chain fluent methods to set configuration options
            ->logOnly(['*'])
            ->useLogName('Vai trò')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Bảng này đã được {$eventName}";
            });
    }
    protected $table = "role";

    protected $fillable = [
        'name',
        'permissons',
        'description',
        'created_by',
        'active',
    ];

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
