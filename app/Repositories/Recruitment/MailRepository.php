<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;

class MailRepository extends AbstractRepository {

    public function __construct(\Illuminate\Container\Container $app) {
        parent::__construct($app);
    }

    public function model() {
        return 'App\Models\Mail';
    }

    public function validateCreate() {
        return $rules = [
            'subject' => 'required',
            //'from' => 'required',
            //'to' => 'required',
            'body' => 'required',
        ];
    }
    public function validateUpdate($id) {
        return $rules = [
            'subject' => 'required',
            //'from' => 'required',
            //'to' => 'required',
            'body' => 'required',
        ];
    }

}
