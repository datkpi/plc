<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'code',
        'name',
        'gm_spec',
        'min_productivity'
    ];

    protected $casts = [
        'gm_spec' => 'decimal:2',
        'min_productivity' => 'decimal:2'
    ];

    public function productionEntries()
    {
        return $this->hasMany(ProductionEntry::class, 'product_code', 'code');
    }
}
