<?php
/**
 * Created by PhpStorm.
 * User: Hien
 * Date: 11/09/2019
 * Time: 9:41 AM
 */

namespace App\Repositories\Recruitment;


use Repositories\Support\AbstractRepository;

class SlideRepository extends AbstractRepository
{
    public function __construct(\Illuminate\Container\Container $app) {
        parent::__construct($app);
    }

    public function model() {
        return 'App\Slide';
    }
    public function validateCreate() {
        return $rules = [
            'title' => 'required',
            'image' => 'required',
        ];
    }
    public function validateUpdate($id) {
        return $rules = [
            'title' => 'required',
        ];
    }
}
