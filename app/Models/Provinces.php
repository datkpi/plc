<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Provinces extends BaseModel
{
    use HasUuids;
    protected $table = "provinces";
    protected $fillable = [
        'code',
        'name',
        'full_name',
    ];
}