<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model {

    //
    protected $table = "news";
    protected $fillable = [
        'title',
        'alias',
        'description',
        'content',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'is_hot',
        'status',
        'ordering',
        'keywords',
        'images',
        'created_by',
        'post_schedule'
    ];

    public function categories() {
        return $this->belongsToMany('App\Category', 'news_category', 'news_id', 'category_id');
    }

    public function newsCategories() {
        return $this->hasMany('App\NewsCategory');
    }

    public function getPostSchedule() {
        return date('d/m/Y', strtotime($this->post_schedule != '0000-00-00 00:00:00' ?: $this->created_at));
    }

    public function url() {
        return route('news.detail', ['alias' => $this->alias]);
    }

    public function getImage() {
        $image_arr = explode(',', $this->images);
        return $image_arr[0];
    }

    public function createdAt() {
        return date('d/m/Y', strtotime($this->created_at));
    }

    public function createdBy() {
        return $this->belongsTo('App\User', 'created_by');
    }

}
