<?php

namespace App\Repositories\Recruitment;

use App\Repositories\Support\AbstractRepository;

class UserRepository extends AbstractRepository
{

    public function __construct(\Illuminate\Container\Container $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return 'App\Models\User';
    }

    public function validateCreate()
    {
        return $rules = [
            'name' => 'required',
            'email' => 'email|required|unique:user',
            'gender' => 'required',
            'birthday' => 'required|before:today',
            // 'username' => 'required|unique:user',
            // 'password' => 'required|min:6|max:32',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'department_id' => 'required',
            'position_id' => 'required',
            'code' => 'required|unique:user',
            'cccd' => 'required|unique:user|regex:/^([0-9\s\-\+\(\)]*)$/',
        ];
    }
    public function validateUpdate($id)
    {
        return $rules = [
            'name' => 'required',
            'email' => 'email|required|unique:user,email,' . $id . ',id',
            'code' => 'required|unique:user,code,' . $id . ',id',
            'gender' => 'required',
            'birthday' => 'required|before:today',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'department_id' => 'required',
            'position_id' => 'required',
            'cccd' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|unique:user,cccd,' . $id . ',id',
        ];
    }

    public function validateChangePassword()
    {
        return $rules = [
            'password' => 'required|min:6|max:32',
            're_password' => 'required|min:6|max:32|same:password',
        ];
    }

    public function getDictionary($attribute, $array = array('*'), $columns = array('*'))
    {
        $data = $this->model->whereIn($attribute, $array)->get($columns)->getDictionary();
        return collect($array)->map(function ($id) use ($data) {
            return $data->get($id)->toArray();
        })->all();
    }

    function getAllUser()
    {
        $users = $this->model->where('role_id', '<>', \App\Models\User::ROLE_ADMIN)->get();
        return $users;
    }

    function getMailByListId($listId = array('*')){
        return $this->model->whereIn('id', $listId)->pluck('email')->toArray();
    }
}
