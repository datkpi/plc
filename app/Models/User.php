<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Chain fluent methods to set configuration options
            ->logOnly(['*'])
            ->useLogName('Người dùng')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Bảng này đã được {$eventName}";
            });
    }

    protected $table = "user";
    protected $fillable = [
        'name',
        'user_uid',
        'username',
        'refresh_token',
        'password',
        'password_hash',
        'email',
        'gender',
        'birthday',
        'address',
        'phone_number',
        'email_send',
        'email_reply',
        'email_footer',
        'cccd',
        'avatar',
        'created_by',
        'active',
        'is_root',
        'role_id',
        'department_id',
        'position_id',
        'mail_footer',

        'code',
        'uuid',
        'tax_code',
        'status',
        'contract_signing_date',
        'contract_type',
        'contract_status',
        'contract_start',
        'end_job_date',
        'cccd_issuer',
        'cccd_date',
        'note',
        'contact',
        'remember_token',
        'level',
    ];

    protected $fieldMetadata = [
        [
            'field' => 'name',          //Tên cột
            'show' => true,             //Show trong index
            // 'type' => 'text',        //Loại dữ liệu, nếu không khai báo sẽ tự động lấy type
            'label' => 'Họ và tên',     //Nhãn
            'isCreate' => true,         //Hiển thị khi tạo mới
            'isEdit' => true,           //Hiển thị khi xem chi tiết
            'search' => true,         //Là trường search trong danh sách dữ liệu
            'required' => true,
        ],
        [
            'field' => 'code',
            'show' => true,
            'label' => 'Mã nhân sự',
            'isCreate' => true,
            'isEdit' => true,
            'search' => true,
            'required' => true,
        ],
        [
            'field' => 'password',
            'show' => false,
            'type' => 'password',
            'label' => 'Mật khẩu',
            'create' => true,
            'update' => false,
            'search' => false,
        ],
        [
            'field' => 'tax_code',
            'show' => true,
            'label' => 'Mã số thuế',
            'isCreate' => true,
            'isEdit' => true,
            'search' => false,
            'required' => true,
        ],
        [
            'field' => 'email',
            'show' => true,
            'type' => 'email',
            'label' => 'Email',
            'required' => true,
        ],
        [
            'field' => 'birthday',
            'show' => true,
            'label' => 'Ngày sinh',
            'isCreate' => true,
            'isEdit' => true,
            'search' => true,
            'required' => true,
        ],
        [
            'field' => 'gender',
            'show' => false,
            'label' => 'Giới tính',
            'isCreate' => true,
            'isEdit' => true,
            'search' => true,
            'required' => true,
        ],
        [
            'field' => 'address',
            'show' => false,
            'type' => 'text',
            'label' => 'Địa chỉ',
            'isCreate' => true,
            'isEdit' => true,
            'search' => false,
        ],
        [
            'field' => 'phone_number',
            'show' => true,
            'label' => 'Số điện thoại',
            'isCreate' => true,
            'isEdit' => true,
            'search' => false,
            'required' => true,
        ],
        [
            'field' => 'cccd',
            'show' => false,
            'label' => 'Căn cước công dân',
            'isCreate' => true,
            'isEdit' => true,
            'search' => false,
            'required' => true,
        ],
        [
            'field' => 'cccd_date',
            'show' => false,
            'label' => 'Ngày cấp căn cước',
            'isCreate' => true,
            'isEdit' => true,
            'search' => false,
        ],
        [
            'field' => 'cccd_issuer',
            'show' => false,
            'label' => 'Nơi cấp căn cước',
            'isCreate' => true,
            'isEdit' => true,
            'search' => false,
        ],
        [
            'field' => 'department_id',
            'show' => false,
            'label' => 'Phòng ban',
            'isCreate' => true,
            'isEdit' => true,
            'search' => true,
            'relation' => 'department',
            'relationField' => 'name',
            'required' => true,
        ],
        [
            'field' => 'position_id',
            'show' => false,
            'label' => 'Vị trí',
            'isCreate' => true,
            'isEdit' => true,
            'search' => true,
            'relation' => 'position',
            'relationField' => 'name',
            'required' => true,
        ],
        [
            'field' => 'role_id',
            'show' => true,
            'label' => 'Vai trò',
            'isCreate' => true,
            'isEdit' => true,
            'search' => false,
            'required' => true,
            'multiple' => true,
            'relation' => 'role',
            'relationField' => 'name',
        ],
        [
            'field' => 'note',
            'show' => false,
            'label' => 'Ghi chú',
            'canCreate' => true,
            'canEdit' => true,
            'search' => false,
        ],
        [
            'field' => 'active',
            'show' => true,
            'label' => 'Hoạt động',
            'isCreate' => true,
            'isEdit' => true,
            'search' => false,
        ],
        // [
        //     'field' => 'created_at', //Trường hợp lọc trong khoảng thời gian
        //     'label' => 'Ngày tạo',
        //     'type' => 'date-range',
        //     'search' => true,
        //     'start_field' => [
        //         'name' => 'start_date', // Tên trường ngày bắt đầu trong request
        //         'label' => 'Ngày Bắt Đầu', // Label cho ngày bắt đầu
        //     ],
        //     'end_field' => [
        //         'name' => 'end_date', // Tên trường ngày kết thúc trong request
        //         'label' => 'Ngày Kết Thúc', // Label cho ngày kết thúc
        //     ],
        // ],
    ];

    public function getFieldMetadata()
    {
        foreach ($this->fieldMetadata as $key => &$value) {
            // Đảm bảo rằng 'type' luôn được thiết lập tránh lỗi khi quên isset value
            if (!isset($value['type'])) {
                $value['type'] = $this->getFieldType($value['field']);
            }

            $value['show'] = $value['show'] ?? false;
            $value['isCreate'] = $value['isCreate'] ?? false;
            $value['isEdit'] = $value['isEdit'] ?? false;
            $value['search'] = $value['search'] ?? false;
            $value['required'] = $value['required'] ?? false;

            // Xử lý cho 'date-range'
            if ($value['type'] == 'date-range') {
                $value['start_field']['name'] = $value['start_field']['name'] ?? '';
                $value['start_field']['label'] = $value['start_field']['label'] ?? '';
                $value['end_field']['name'] = $value['end_field']['name'] ?? '';
                $value['end_field']['label'] = $value['end_field']['label'] ?? '';
            }
        }
        return $this->fieldMetadata;
    }
    public function getFieldType($fieldName)
    {
        // Sử dụng $this để thực hiện truy vấn trên bảng của User model
        $columnType = \Schema::getColumnType($this->getTable(), $fieldName);

        switch ($columnType) {
            case 'varchar':
            case 'nvarchar':
            case 'char':
                return 'string';
            case 'text':
                // Kiểm tra xem có phải là email hay không
                if (Str::endsWith($fieldName, 'email')) {
                    return 'email';
                }
                // Kiểm tra xem có phải là URL hay không
                if (Str::endsWith($fieldName, 'url')) {
                    return 'url';
                }
                // Kiểm tra xem có phải là hình ảnh hay không
                if (Str::endsWith($fieldName, 'image')) {
                    return 'image';
                }
                return 'text';

            case 'int':
            case 'bigint':
            case 'smallint':
            case 'mediumint':
            case 'tinyint':
                return 'number';

            case 'date':
                return 'date';

            case 'datetime':
            case 'timestamp':
                return 'datetime-local';

            case 'time':
                return 'time';

            case 'boolean':
                return 'checkbox';

            case 'decimal':
            case 'float':
            case 'double':
                return 'number';
            default:
                return 'text';
        }
    }


    protected $hidden = [
        'password',
        'refresh_token',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getImage()
    {
        $image_arr = explode(',', $this->images);
        return $image_arr[0];
    }

    public function createdAt()
    {
        return date('d/m/Y', strtotime($this->created_at));
    }

    public function updatedAt()
    {
        return date('d/m/Y', strtotime($this->updated_at));
    }

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }
    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'department_id');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Position', 'position_id');
    }


}
