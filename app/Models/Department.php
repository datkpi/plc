<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class Department extends BaseModel
{
    use CausesActivity, LogsActivity;
    use HasUuids;

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Chain fluent methods to set configuration options
            ->logOnly(['*'])
            ->useLogName('Phòng ban')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Bảng này đã được {$eventName}";
            });
    }

    protected $table = 'department';

    protected $fillable = [
        'name',
        'uuid',
        'code',
        'parent_id',
        'manager_by',
        'manager_name',
        'level',
        'contact',
        'description',
        'image',
        'active',
        'created_by',
        'created_at',
        'updated_at',
    ];

    protected $fieldMetadata = [
        [
            'field' => 'name',          //Tên cột
            'show' => true,             //Show trong index
            // 'type' => 'text',        //Loại dữ liệu, nếu không khai báo sẽ tự động lấy type
            'label' => 'Tên phòng ban',     //Nhãn
            'isCreate' => true,         //Hiển thị khi tạo mới
            'isEdit' => true,           //Hiển thị khi xem chi tiết
            'search' => true,         //Là trường search trong danh sách dữ liệu
            'required' => true,
        ],
        [
            'field' => 'code',
            'show' => true,
            'label' => 'Mã phòng ban',
            'isCreate' => true,
            'isEdit' => true,
            'search' => true,
            'required' => true,
        ],
        [
            'field' => 'manager_by',
            'show' => false,
            'label' => 'Trưởng phòng /Ban',
            'isCreate' => true,
            'isEdit' => true,
            'search' => false,
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
            'field' => 'contact',
            'show' => false,
            'label' => 'Thông tin liên hệ',
            'isCreate' => true,
            'isEdit' => true,
            'search' => true,
        ],
        [
            'field' => 'parent_id',
            'show' => true,
            'label' => 'Phòng ban cấp trên',
            'isCreate' => true,
            'isEdit' => true,
            'search' => false,
            'required' => true,
            'relation' => 'parent',
            'relationField' => 'name',
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
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class, 'department_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'manager_by', 'id');
    }
}
