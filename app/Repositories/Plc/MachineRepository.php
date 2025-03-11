<?php

namespace App\Repositories\Plc;

use App\Repositories\Support\AbstractRepository;

class MachineRepository extends AbstractRepository {

    public function __construct(\Illuminate\Container\Container $app) {
        parent::__construct($app);
    }

    public function model() {
        return 'App\Models\Machine';
    }

    public function validateCreate() {
        return $rules = [
            'name' => 'required|max:100',
            'code' => 'required|max:50|unique:machine',
            'ip_address' => 'required|unique:machine',
            'description' => 'nullable',
            'status' => 'boolean'
        ];
    }

    public function validateUpdate($id) {
        return $rules = [
            'name' => 'required|max:100',
            'code' => 'required|max:50|unique:machine,code,' . $id . ',id',
            'ip_address' => 'required|unique:machine,ip_address,' . $id . ',id',
            'description' => 'nullable',
            'status' => 'boolean'
        ];
    }

    public function getActiveMachines()
    {
        return $this->model->active()->orderBy('name')->get();
    }

    public function toggleStatus($id)
    {
        $machine = $this->find($id);
        $machine->status = !$machine->status;
        $machine->save();
        return $machine;
    }
}
