<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeCoilStandard extends Model
{
    protected $table = 'pe_coil_standards';

    protected $fillable = [
        'diameter',
        'length'
    ];

    protected $casts = [
        'diameter' => 'integer',
        'length' => 'integer'
    ];
}
