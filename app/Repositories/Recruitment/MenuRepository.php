<?php

namespace Repositories;

use Repositories\Support\AbstractRepository;

class MenuRepository extends AbstractRepository {

    public function __construct(\Illuminate\Container\Container $app) {
        parent::__construct($app);
    }

    public function model() {
        return 'App\Menu';
    }

    public function validate() {
        return $rules = [
            'title' => 'required'
        ];
    }

    public function readParentCategory($parent_id = 0) {
        return $this->model->where('id', $parent_id)->where('status', 1)->first();
    }

}
