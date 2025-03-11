<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionEntry extends Model
{
    protected $table = 'production_entries';

    protected $fillable = [
        'machine_id',
        'date',
        'shift',
        'product_code',
        'output_quantity',
        'good_quantity',
        'defect_weight',
        'waste_weight',
        'operator_team',
        'operator_name',
        'machine_operator',
        'quality_checker',
        'warehouse_staff',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'output_quantity' => 'integer',
        'good_quantity' => 'integer',
        'defect_weight' => 'decimal:2',
        'waste_weight' => 'decimal:2'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_code', 'code');
    }

    // Tính tổng phế
    public function getTotalWasteAttribute()
    {
        return $this->defect_weight + $this->waste_weight;
    }

    // Tính tỷ lệ phế
    public function getWasteRateAttribute()
    {
        $total = $this->good_quantity + $this->defect_weight;
        return $total > 0 ? ($this->defect_weight / $total) * 100 : 0;
    }
}
