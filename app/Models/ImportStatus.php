<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ImportStatus extends BaseModel
{
    use HasUuids;
    const TYPE_CANDIDATE = 1;
    protected $table = "import_status";
    protected $fillable = [
        'filename',
        'created_by',
        'type',
        'file',
        'note',
        'status',
        'record_imported',
        'record_failed',
        'total_row',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'foreign_id');
    }
}