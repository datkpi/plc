<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;

class AccountRepository extends AbstractRepository {

    public function __construct(\Illuminate\Container\Container $app) {
        parent::__construct($app);
    }

    public function model() {
        return 'App\Models\Account';
    }

    public function validateCreate() {
        return $rules = [
            'username' => 'required|unique:account',
            'password' => 'required|min:6|max:32',
            // regex:/^([0-9\s\-\+\(\)]*)$/|min:10
        ];
    }

    public function validateUpdate($id) {
        return $rules = [
            'username' => 'required|unique:account,username,' . $id . ',id',
        ];
    }

}
