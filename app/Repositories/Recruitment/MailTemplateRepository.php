<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;

class MailTemplateRepository extends AbstractRepository {

    public function __construct(\Illuminate\Container\Container $app) {
        parent::__construct($app);
    }

    public function model() {
        return 'App\Models\MailTemplate';
    }

    public function validateCreate() {
        return $rules = [
            'name' => 'required|unique:mail_template',
            'body' => 'required',
        ];
    }
    public function validateUpdate($id) {
        return $rules = [
            'name' => 'required|unique:mail_template,name,' . $id . ',id',
            'body' => 'required',
        ];
    }

}
