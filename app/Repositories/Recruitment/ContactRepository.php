<?php
/**
 * Created by PhpStorm.
 * User: Hien
 * Date: 12/09/2019
 * Time: 9:16 AM
 */

namespace App\Repositories\Recruitment;


use Repositories\Support\AbstractRepository;

class ContactRepository extends AbstractRepository
{
    public function __construct(\Illuminate\Container\Container $app) {
        parent::__construct($app);
    }

    public function model() {
        return 'App\Contact';
    }
}
