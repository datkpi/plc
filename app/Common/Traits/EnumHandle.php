<?php

namespace App\Common\Traits;

trait EnumHandle
{
    public function getValueInEnum($enums, $name)
    {
        foreach ($enums as $value) {
            if ($value->name == $name) {
                return $value->value;
            }
        }
        return null;
    }

    protected function getNameInEnumByValue($enums, $value)
    {
        foreach ($enums as $enum) {
            if ($enum->value == $value) {
                return $enum->name;
            }
        }
        return null;
    }
    protected function getEnumValuesInRange($enumCases, $selectedStatus)
    {
        $enumNames = $suits = array_column($enumCases, 'name');
        $startIndex = array_search($selectedStatus, $enumNames);
        $endIndex = array_search($selectedStatus->name, $enumNames) + 1;
        return array_slice($enumNames, $startIndex, $endIndex - $startIndex);
    }

    function getEnumNamesPre($enumCases, $currentStatus)
    {
        $allStatuses = array_map(function ($case) {
            return $case->name;
        }, $enumCases);

        $data = 0;
        foreach ($enumCases as $index => $case) {
            if ($case->name === $currentStatus) {
                $data = $index;
                break;
            }
        }
        return array_slice($allStatuses, 0, $data);
    }

}