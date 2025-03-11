<?php

namespace App\Repositories\Plc;

use App\Models\MachineThreshold;
use App\Repositories\Support\AbstractRepository;
use Illuminate\Support\Arr;

class MachineThresholdRepository extends AbstractRepository
{
    public function model()
    {
        return 'App\Models\MachineThreshold';
    }

   public function validateCreate()
    {
        $rules = [
            'machine_id' => 'required|exists:machine,id',
            'name' => 'required|string|max:255',
            'plc_data_key' => 'required|string',
            'type' => 'required|in:boolean,range,percent,avg',
            'show_on_chart' => 'boolean',
            'status' => 'boolean',
            'color' => 'required|string'
        ];

        switch(request('type')) {
            case 'boolean':
                $rules += [
                    'boolean_value' => 'required|boolean',
                    'warning_message' => 'required|string',
                ];
                break;

            case 'range':
                $rules += [
                    'min_value' => 'nullable|numeric',
                    'max_value' => 'nullable|numeric',
                ];

                // Thêm custom validation khi cả min và max đều null
                // Và validate max > min khi cả 2 cùng có giá trị
                $rules['max_value'] .= '|required_without:min_value';
                $rules['min_value'] .= '|required_without:max_value';

                // Nếu cả 2 cùng có giá trị thì max phải lớn hơn min
                if (request('min_value') !== null && request('max_value') !== null) {
                    $rules['max_value'] .= '|gt:min_value';
                }
                break;

            case 'percent':
            case 'avg':
                $rules += [
                    'base_value' => 'required|numeric',
                    'percent' => 'required|numeric|between:0,100',
                ];
                break;
        }

        return $rules;
    }

    public function validateUpdate($id)
    {
        return $this->validateCreate();
    }

    public function getByMachine($machineId)
    {
        return $this->model
            ->where('machine_id', $machineId)
            ->orderBy('name')
            ->get();
    }

    public function create(array $data)
    {
        // Clear unused fields based on type
        $data['type'] = request('type');
        $data = $this->cleanData($data);
        return parent::create($data);
    }

    public function update(array $data, $id)
    {
        // Clear unused fields based on type
        $data['type'] = request('type');
        $data = $this->cleanData($data);
        // dd($data);
        return parent::update($data, $id);
    }

    protected function cleanData(array $data)
    {
        switch($data['type']) {
            case 'boolean':
                return Arr::except($data, ['min_value', 'max_value', 'base_value', 'percent']);

            case 'range':
                return Arr::except($data, ['boolean_value', 'warning_message', 'base_value', 'percent']);

            case 'percent':
            case 'avg':
                return Arr::except($data, ['boolean_value', 'warning_message', 'min_value', 'max_value']);
        }

        return $data;
    }
}
