<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AnnualEmployee extends BaseModel
{
    use HasUuids;
    protected $table = "annual_employee";
    protected $fillable = [
        'position_id',
        'month',
        'year',
        'employee_number',
        'created_by',
        'status',
    ];

    /**
     * Get the user that owns the AnnualEmployee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }
}