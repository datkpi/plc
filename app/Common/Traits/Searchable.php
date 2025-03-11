<?php

namespace App\Traits;

trait Searchable
{
    public static function search($conditions)
    {
        $query = static::query();

        foreach ($conditions as $condition) {
            if (!empty($condition['value'])) {
                switch ($condition['type']) {
                    case 'like':
                        $query->where($condition['field'], 'like', '%' . $condition['value'] . '%');
                        break;
                    case 'date':
                        $query->whereDate($condition['field'], $condition['value']);
                        break;
                    // Thêm nhiều trường hợp khác tại đây
                    default:
                        $query->where($condition['field'], $condition['value']);
                        break;
                }
            }
        }

        return $query;
    }
}