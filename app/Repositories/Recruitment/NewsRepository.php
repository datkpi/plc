<?php

namespace Repositories;

use Repositories\Support\AbstractRepository;

class NewsRepository extends AbstractRepository {

    public function __construct(\Illuminate\Container\Container $app) {
        parent::__construct($app);
    }

    public function model() {
        return 'App\News';
    }

    public function validateCreate() {
        return $rules = [
            'title' => 'required|unique:news',
            'alias' => 'required',
        ];
    }

    public function validateUpdate($id) {
        return $rules = [
            'title' => 'required|unique:news,title,' . $id . ',id',
            'alias' => 'required',
        ];
    }

    public function getAllByUserId($user_id) {
        return $this->model->where('created_by', $user_id)->orderBy('created_at', 'desc')->get();
    }

    public function readFE($request, $category_id = 0, $limit = 10) {
        $query = $this->model;
        //$query = $query->join('news_category', 'news.id', '=', 'news_category.news_id')->whereNotIn ('news_category.category_id',[238,239])->select('news.*','news_category.category_id');
        if ($request->get('keyword')) {
            $query = $query->where(function($que) use ($request) {
                return $que->where('news.title', 'like', '%' . $request->get('keyword') . '%');
                                //->orWhere('news.description', 'like', '%' . $request->get('keyword') . '%')
                                //->orWhere('news.content', 'like', '%' . $request->get('keyword') . '%');
            });
        }
        if ($category_id > 0) {
            $news_ids = \Db::table('news_category')->where('category_id', $category_id)->whereNotIn ('news_category.category_id',[238,239])->pluck('news_id')->toArray();
            $query = $query->whereIn('news.id', $news_ids);
        }
        
        return $query->where('news.status', 1)->orderBy('news.created_at', 'desc')->paginate($limit);
    }

    public function readFeaturedNews($limit = 10) {
        return $this->model->where('status', 1)->where('is_hot', 1)->orderBy('created_at', 'desc')->take($limit)->get();
    }

    public function readRelatedNews($current_news_id, $news_ids, $limit = 10) {
        return $this->model->where('status', 1)->where('id', '<>', $current_news_id)->whereIn('id', $news_ids)->orderBy('created_at', 'desc')->take($limit)->get();
    }
     public function getConfig($id) {
        return $this->model->select('title', 'description','keywords', 'meta_title', 'meta_keywords', 'meta_description')->where('id', $id)->first();
    }


}
