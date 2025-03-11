<?php
namespace App\Models;

use App\Models\Machine;
use App\Models\PlcAlert;
use Illuminate\Database\Eloquent\Model;

class PlcAlertSummary extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_RESOLVED = 'resolved';

    const STATUS_LIST = [
        self::STATUS_NEW => 'Chưa xử lý',
        self::STATUS_RESOLVED => 'Đã xử lý'
    ];

    const TYPE_WARNING = 'warning';
    const TYPE_DANGER = 'danger';

    const TYPE_LIST = [
        self::TYPE_WARNING => 'Cảnh báo',
        self::TYPE_DANGER => 'Nghiêm trọng'
    ];

    protected $table = 'plc_alert_summary';

    protected $fillable = [
        'machine_id',
        'plc_data_key',
        'current_value',
        'min_value',
        'max_value',
        'type',
        'status',
        'message',
        'occurrence_count',
        'first_occurred_at',
        'last_occurred_at',
        'resolved_by',
        'resolved_at'
    ];

    protected $casts = [
        'current_value' => 'float',
        'min_value' => 'float',
        'max_value' => 'float',
        'occurrence_count' => 'integer',
        'first_occurred_at' => 'datetime',
        'last_occurred_at' => 'datetime',
        'resolved_at' => 'datetime'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function alerts()
    {
        return $this->hasMany(PlcAlert::class, 'plc_data_key', 'plc_data_key')
            ->where('machine_id', $this->machine_id);
    }

    public function resolvedByUser()
    {
        return $this->belongsTo(User::class, 'resolved_by', 'uuid');
    }
}
