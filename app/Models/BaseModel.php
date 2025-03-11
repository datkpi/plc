<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    public function getFieldMetadata()
    {
        $tempMetadata = $this->fieldMetadata;
        if ($tempMetadata === null) {
            $tempMetadata = [];
        }

        foreach ($tempMetadata as $key => &$value) {
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
        $this->fieldMetadata = $tempMetadata;
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

    public function createdAt()
    {
        return date('d/m/Y', strtotime($this->created_at));
    }

    public function createdDatetime()
    {
        return date('d/m/Y H:i:s', strtotime($this->created_at));
    }

    public function displayDate($datetime)
    {
        return date('Y-m-d', strtotime($datetime));
    }

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

}
