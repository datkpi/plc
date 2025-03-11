<?php
namespace App\Models;

use App\Models\User;
use App\Models\Machine;
use App\Models\PlcData;
use Illuminate\Database\Eloquent\Model;

class PlcAlert extends Model
{
    protected $table = 'plc_alert';

    protected $fillable = [
        'machine_id',
        'plc_data_id',
        'plc_data_key',
        'value',
        'type',
        'message',
        'status',
        'min_value',
        'max_value',
        'resolved_note',
        'resolved_by',
        'resolved_at'
    ];

    protected $casts = [
        'min_value' => 'float',
        'max_value' => 'float',
        'resolved_at' => 'datetime'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function plcData()
    {
        return $this->belongsTo(PlcData::class);
    }

    public function resolvedByUser()
    {
        return $this->belongsTo(User::class, 'resolved_by', 'uuid');
    }

    public function summary()
    {
        return $this->belongsTo(PlcAlertSummary::class, ['machine_id', 'plc_data_key'], ['machine_id', 'plc_data_key'])
            ->where('status', 'new');
    }
}
