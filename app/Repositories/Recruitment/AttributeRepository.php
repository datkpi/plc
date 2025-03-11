<?php

namespace Repositories;

use Repositories\Support\AbstractRepository;

class AttributeRepository extends AbstractRepository {

    public function __construct(\Illuminate\Container\Container $app) {
        parent::__construct($app);
    }

    public function model() {
        return 'App\Attribute';
    }

    public function validateCreate() {
        return $rules = [
            'title' => 'required|unique:attribute',
            'type' => 'required',
            'module' => 'required'
        ];
    }

    public function validateUpdate($id) {
        return $rules = [
            'title' => 'required|unique:attribute,title,' . $id . ',id',
            'type' => 'required',
            'module' => 'required'
        ];
    }

    public function readAttributeByParentAdmin($module = 'product', $parent_id = 0, $type = '') {
        $model = $this->model;
        if ($type) {
            $model = $model->where('type', 'select');
        }
        $data = $model->where('parent_id', $parent_id)->where('module', $module)->get();
        foreach ($data as $key => $val) {
            $data[$key]->children = $this->model->where('parent_id', $val->id)->get();
        }
        return $data;
    }

    public function readAttributeByParent($module = 'product', $parent_id = 0, $type = '', $ids = '') {
        $model = $this->model;
        if ($type) {
            $model = $model->where('type', 'select');
        }
        $attribute_ids = \Db::table($module . '_attribute')->whereIn($module . '_id', $ids)->pluck('attribute_id');
        $data = $model->where('parent_id', $parent_id)->where('module', $module)->get();
        foreach ($data as $key => $val) {
            $data[$key]->children = $this->model->where('parent_id', $val->id)->whereIn('id', $attribute_ids)->get();
        }
        return $data;
    }

    public function readAttributes($attibutes, $module = 'gallery') {
        $result = array();
        foreach ($attibutes as $val) {
            if ($val['parent_id'] > 0) {
                $parent = $this->model->where('id', $val['parent_id'])->where('module', $module)->first();
                $result[] = ['title' => $parent->title, 'value' => $val->title];
            } else {
                $result[] = ['title' => $val->title, 'value' => $val->pivot->value];
            }
        }
        return $result;
    }

    public function readParentCategory($parent_id) {
        return $this->model->where('id', $parent_id)->first();
    }

    public function all($columns = array('*')) {
        return $this->model->orderBy('id', 'DESC')->get($columns);
    }

    public function getAttrTitle($attr_id, $value) {
        if ($value == '') {
            $attr = $this->model->where('id', $attr_id)->first();
            $parent = $this->model->where('id', $attr->parent_id)->first();
            return $parent->title;
        } else {
            $parent = $this->model->where('id', $attr_id)->first();
            return $parent->title;
        }
    }

    public function getAttrVal($attr_id, $value) {
        if ($value == '') {
            $item = $this->model->where('id', $attr_id)->first();
            return $item->title;
        } else {
            return $value;
        }
    }

}
