<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MailTemplate extends BaseModel
{
    use HasUuids;
    protected $table = "mail_template";
    protected $fillable = [
        'code',
        'name',
        'body',
        'footer',
        'file',
        'is_send_with_file',
        'count',
        'description',
        'active',
        'created_by',
        'is_publish',
    ];



}
