<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineThreshold extends Model
{
    protected $table = 'machine_threshold';

    // Constants cho type
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_RANGE = 'range';
    const TYPE_PERCENT = 'percent';
    const TYPE_AVG = 'avg';

    const TYPE_LIST = [
        self::TYPE_BOOLEAN => 'Boolean',
        self::TYPE_RANGE => 'Min-Max',
        self::TYPE_PERCENT => '% Dao động',
        self::TYPE_AVG => 'Trung bình 10 phút'
    ];

    protected $fillable = [
        'machine_id',
        'name',
        'plc_data_key',
        'type',
        'boolean_value',
        'warning_message',
        'min_value',
        'max_value',
        'base_value',
        'percent',
        'show_on_chart',
        'color',
        'status'
    ];

    protected $casts = [
        'boolean_value' => 'boolean',
        'min_value' => 'float',
        'max_value' => 'float',
        'base_value' => 'float',
        'percent' => 'float',
        'show_on_chart' => 'boolean',
        'status' => 'boolean'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    /**
     * Kiểm tra một giá trị với ngưỡng
     *
     * @param mixed $value Giá trị cần kiểm tra
     * @return array|null Mảng chứa thông tin cảnh báo hoặc null nếu không vượt ngưỡng
     */
    public function checkThreshold($value)
    {
        if (!$this->status || is_null($value)) {
            return null;
        }

        switch ($this->type) {
            case self::TYPE_BOOLEAN:
                if ($value === $this->boolean_value) {
                    return [
                        'type' => 'danger',
                        'message' => $this->warning_message ?: 'Cảnh báo trạng thái boolean'
                    ];
                }
                break;

            case self::TYPE_RANGE:
                if ($this->min_value !== null && $value < $this->min_value) {
                    return [
                        'type' => 'danger',
                        'message' => "Giá trị {$value} dưới ngưỡng cho phép {$this->min_value}"
                    ];
                }
                if ($this->max_value !== null && $value > $this->max_value) {
                    return [
                        'type' => 'danger',
                        'message' => "Giá trị {$value} vượt ngưỡng cho phép {$this->max_value}"
                    ];
                }
                break;

            case self::TYPE_PERCENT:
                if ($this->base_value !== null && $this->percent !== null) {
                    $deviation = abs($value - $this->base_value) / $this->base_value * 100;
                    if ($deviation > $this->percent) {
                        return [
                            'type' => 'danger',
                            'message' => "Giá trị dao động {$deviation}% vượt ngưỡng cho phép {$this->percent}%"
                        ];
                    }
                }
                break;

            case self::TYPE_AVG:
                // Logic kiểm tra trung bình được xử lý ở PlcDataService
                break;
        }

        return null;
    }
}
