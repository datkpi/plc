<?php
/**
 * Created by PhpStorm.
 * User: Hien
 * Date: 12/10/2019
 * Time: 7:18 AM
 */

namespace App\Repositories\Recruitment;


use Repositories\Support\AbstractRepository;

class NewsCategoryRepository extends AbstractRepository
{
    public function __construct(\Illuminate\Container\Container $app) {
        parent::__construct($app);
    }

    public function model() {
        return 'App\NewsCategory';
    }
    public function getNewsIds($category_ids)
    {
        return $this->model->whereIn('category_id', $category_ids)->pluck('news_id');
    }

}
