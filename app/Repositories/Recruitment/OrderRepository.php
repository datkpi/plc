<?php
/**
 * Created by PhpStorm.
 * User: Hien
 * Date: 12/09/2019
 * Time: 11:01 AM
 */

namespace App\Repositories\Recruitment;


use Repositories\Support\AbstractRepository;

class OrderRepository extends AbstractRepository
{
    public function __construct(\Illuminate\Container\Container $app) {
        parent::__construct($app);
    }

    public function model() {
        return 'App\Order';
    }
    public function validateCreate() {
        return $rules = [
            'contact' => 'required',
            'email' => 'required',
            'payment_method' => 'required',
            'transport_method' => 'required',
            'mobile' => 'required'
        ];
    }

}
