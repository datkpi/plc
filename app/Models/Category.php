<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    const TYPE_NEWS = 1;
    const TYPE_PRODUCT = 2;

    protected $table = 'category';
    protected $fillable = [
        'title', 'parent_id', 'alias', 'image', 'description', 'type', 'status', 'ordering'
    ];

    public function createdAt() {
        return date("d/m/Y", strtotime($this->created_at));
    }

    public function updatedAt() {
        return date("d/m/Y", strtotime($this->updated_at));
    }

    /* Get all children */

    public function children() {
        return $this->hasMany('\App\Category', 'parent_id');
    }

    /**/
    /* Get all parent */

    public function parents() {
        return $this->belongsTo('\App\Category', 'parent_id');
    }

    public function parentCategories() {
        return $this->belongsTo('\App\Category', 'parent_id')->with('parents');
    }

    /**/

    public function urlNews() {
        return route('news_category.index', ['alias' => $this->alias]);
    }

    public function url() {
        return '#';
    }

    public function products() {
        return $this->belongsToMany('\App\Product', 'product_category', 'category_id', 'product_id');
    }

    public function news() {
        return $this->belongsToMany('\App\News', 'news_category', 'category_id', 'news_id');
    }

}
