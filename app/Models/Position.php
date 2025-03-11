<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Position extends BaseModel
{
    use HasUuids;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Chain fluent methods to set configuration options
            ->logOnly(['*'])
            ->useLogName('Vị trị chức danh')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Bảng này đã được {$eventName}";
            });
    }
    protected $table = "position";
    protected $fillable = [
        'name',
        'code',
        'department_id',
        'parent_id',
        'manager_by',
        'level',
        'description',
        'image',
        'active',
        'created_by',
    ];
    protected $fieldMetadata = [
        [
            'field' => 'name',
            'show' => true,
            'label' => 'Tên vị trí',
            'isCreate' => true,
            'isEdit' => true,
            'search' => true,
            'required' => true,
        ],
        [
            'field' => 'code',
            'show' => false,
            'label' => 'Mã vị trí',
            'isCreate' => true,
            'isEdit' => true,
            'search' => true,
            'required' => true,
        ],
        [
            'field' => 'department_id',
            'show' => true,
            'label' => 'Vị trí cấp trên',
            'isCreate' => true,
            'isEdit' => true,
            'search' => false,
            'required' => true,
            'relation' => 'department',
            'relationField' => 'name',
        ],
        [
            'field' => 'manager_by',
            'show' => false,
            'type' => 'select',
            'label' => 'Trưởng phòng /Ban',
            'isCreate' => true,
            'isEdit' => true,
            'search' => true,
            'required' => true,
            'relation' => 'role',
            'relationField' => 'name',
        ],
        [
            'field' => 'description',
            'show' => true,
            'label' => 'Mô tả',
            'isCreate' => true,
            'isEdit' => true,
            'search' => false,
        ],
        [
            'field' => 'parent_id',
            'show' => false,
            'label' => 'Thông tin',
            'isCreate' => false,
            'isEdit' => false,
            'search' => false,
        ],
        [
            'field' => 'created_at', //Trường hợp lọc trong khoảng thời gian
            'label' => 'Ngày tạo',
            'type' => 'date-range',
            'search' => true,
            'start_field' => [
                'name' => 'start_date', // Tên trường ngày bắt đầu trong request
                'label' => 'Ngày Bắt Đầu', // Label cho ngày bắt đầu
            ],
            'end_field' => [
                'name' => 'end_date', // Tên trường ngày kết thúc trong request
                'label' => 'Ngày Kết Thúc', // Label cho ngày kết thúc
            ],
        ],
        [
            'field' => 'updated_at',
            'label' => 'Thời gian thay đổi gần nhất',
            'type' => 'date-range',
            'search' => false,
            'start_field' => [
                'name' => 'start_date',
                'label' => 'Ngày Bắt Đầu',
            ],
            'end_field' => [
                'name' => 'end_date',
                'label' => 'Ngày Kết Thúc',
            ],
        ],
                [
            'field' => 'active',
            'show' => true,
            'label' => 'Hoạt động',
            'isCreate' => true,
            'isEdit' => true,
            'search' => false,
        ],
    ];


    public function parent()
    {
        return $this->belongsTo(Position::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Position::class, 'parent_id');
    }

    public function approves()
    {
        return $this->hasMany('App\Models\Approve', 'approve_1', 'id');
    }

    public function manager()
    {
        return $this->belongsTo('App\Models\User', 'manager_by', 'id');
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'manager_by', 'id');
    }
}
