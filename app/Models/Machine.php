<?php

namespace App\Models;

use App\Models\PlcData;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $table='machine';

    protected $fillable = [
        'name',
        'code',
        'ip_address',
        'status',
        'description'
    ];

    // Relationship với PlcData
    public function plcData()
    {
        return $this->hasMany(PlcData::class);
    }

    // Scope để lấy máy đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
