<?php

namespace App\Repositories\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractRepository
{

    private $app;
    protected $model;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     *
     */
    abstract function model();

    /**
     *
     * @return type
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
        return $this->model = $model;
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        return $this->model->orderBy('created_at', 'DESC')->get();
    }

    public function getAll($limit = 10, $offset = 0)
    {
        return $this->model->limit($limit)->offset($offset)->get();
    }

    public function getActive($columns = array('*'))
    {
        return $this->model->where('active', 1)->orderBy('created_at', 'DESC')->get($columns);
    }

    public function getBy($attribute, $value, $orderBy = 'desc', $columns = array('*'))
    {
        return $this->model->where($attribute, '=', $value)->orderBy('created_at', $orderBy)->get($columns);
    }

    public function getExcept($id)
    {
        return $this->model->where('id', '!=', $id)->orderBy('created_at', 'DESC')->get();
    }

    public function count($columns = array('*'))
    {
        return $this->model->count();
    }

    public function countBy($attribute, $value)
    {
        return $this->model->where($attribute, '=', $value)->count();
    }

    public function allOrder($columns = array('*'))
    {
        return $this->model->orderBy('order', 'ASC')->orderBy('created_at', 'DESC')->get($columns);
    }

    public function selectArr()
    {
        $arr = [];
        $models = self::all();
        foreach ($models as $model) {
            $arr[$model->id] = $model->name;
        }
        return $arr;
    }

    public function queryAll()
    {
        return $this->model;
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($request = null, $perPage = 15, $columns = array('*'))
    {
        $query = $this->queryAll();
        if ($request !== NULL) {
            $sortBy = $request->get('sortBy');
            $orderBy = $request->get('orderBy');
            $searchBy = $request->get('searchBy');
            $searchText = $request->get('searchText');
            if (!is_null($sortBy) && $this->checkColumn($sortBy)) {
                $orderBy = in_array($orderBy, ['asc', 'desc']) ? $orderBy : 'asc';
                $query = $query->orderBy($sortBy, $orderBy);
            }
            if (!is_null($searchBy) && $this->checkColumn($searchBy)) {
                $query = $query->where($searchBy, 'LIKE', "%$searchText%");
            }
        }
        return $query->orderBy('id', 'DESC')->paginate($perPage, $columns);
    }

    /**
     * Delete a Eloquent model
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $model = $this->model->find($id);
        $model->delete();
    }

    public function destroy($id)
    {
        $model = $this->model->find($id);
        $model->delete();
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }

    public function findFirst($columns = array('*'))
    {
        return $this->model->first($columns);
    }

    public function getIn($attribute, $array = array('*'), $columns = array('*'))
    {
        return $this->model->whereIn($attribute, $array)->get($columns);
    }

    // public function findIn($attribute, $array = array('*'), $columns = array('*'))
    // {
    //     return $this->model->whereIn($attribute, $array)->get($columns);
    // }
    public function findBy($attribute, $value)
    {
        return $this->model->where($attribute, $value)->first();
    }
    public function findByPluck($attribute, $value, $pluck = '')
    {
        return $this->model->where($attribute, $value)->pluck($pluck)->first();
    }
    public function updateViewCount($id, $view_count)
    {
        return $this->model->where('id', $id)->update(['view_count' => $view_count + 1]);
    }
    public function findByAlias($alias)
    {
        return $this->model->where('alias', '=', $alias)->first();
    }
    /**
     * Clean data before use
     * @param array $data
     * @param array $unsetList
     * @return array
     */
    public function clean(array $data, array $unsetList = [], array $checkboxs = [])
    {
        foreach ($unsetList as $u) {
            unset($data[$u]);
        }

        unset($data['_method']);
        unset($data['_token']);

        foreach ($checkboxs as $checkbox) {
            if (!isset($data[$checkbox])) {
                $data[$checkbox] = 0;
            }
        }
        return $data;
    }

    /**
     *
     * @param type $length
     * @return type
     */
    function generateRandomString($code, $length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' . $code;
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    protected function getTableColumns()
    {
        $modelColumns = $this->model->getTableColumns();
        return $modelColumns;
    }

    protected function checkColumn($col)
    {
        if (in_array($col, $this->getTableColumns())) {
            return true;
        } else {
            return false;
        }
    }

    public function create(array $data)
    {
        $model = $this->model->create($data);
        return $model;
    }

    public function createMany(array $data)
    {
        $model = $this->model->createMany($data);
        return $model;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id)
    {
        $model = true;
        if (isset($data)) {
            $model = $this->model->find($id)
                ->update($data);
        }
        return $model;
    }

    public function weekdays()
    {
        return array(
            1 => trans('base.monday'),
            2 => trans('base.tuesday'),
            3 => trans('base.wednesday'),
            4 => trans('base.wednesday'),
            5 => trans('base.thursday'),
            6 => trans('base.friday'),
            7 => trans('base.saturday'),
            8 => trans('base.sunday'),
        );
    }

    public function toggle($id, $field = 'status')
    {
        $model = $this->find($id);
        $this->model->where('id', '=', $id)->update([$field => (string) (1 - ($model->$field))]);
        return true;
    }
    public function search(Request $request, $perPage = 15)
    {
        $query = $this->model->query();

        //Nhồi các tham số search vào query
        $this->applySearch($query, $request);

        $sqlWithPlaceholders = $query->toSql();
        $bindings = $query->getBindings();
        $realSql = $this->replacePlaceholdersWithBindings($sqlWithPlaceholders, $bindings);
        //dd($realSql);

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }
    //Check query
    protected function replacePlaceholdersWithBindings($sql, $bindings)
    {
        foreach ($bindings as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . addslashes($binding) . "'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }

        return $sql;
    }

    protected function applySearch(Builder $query, Request $request)
    {
        $fieldMetadata = $this->model->getFieldMetadata();

        foreach ($request->all() as $field => $value) {
            if (!empty($value)) {
                foreach ($fieldMetadata as $metadata) {
                    if ($metadata['field'] == $field && isset($metadata['search']) && $metadata['search']) {
                        $type = $metadata['type'] ?? 'text';

                        switch ($type) {
                            case 'string':
                            case 'text':
                                $query->where($field, 'like', '%' . $value . '%');
                                break;

                            case 'number':
                                $query->where($field, $value);
                                break;

                            case 'email':
                                $query->where($field, 'like', '%' . $value . '%');
                                break;

                            case 'date':
                                $query->whereDate($field, '=', $value);
                                break;

                            case 'datetime-local':
                            case 'datetime':
                                $query->where($field, '=', $value);
                                break;

                            case 'boolean':
                            case 'checkbox':
                                $value = ($value == 'true' || $value == '1') ? 1 : 0;
                                $query->where($field, '=', $value);
                                break;

                            case 'date-range':
                                if (isset($metadata['start_field']) && isset($metadata['end_field'])) {
                                    $startDate = $request->get($metadata['start_field']);
                                    $endDate = $request->get($metadata['end_field']) ?: now()->toDateString();
                                    if ($startDate && $endDate) {
                                        $query->whereBetween($field, [$startDate, $endDate]);
                                    }
                                }
                                break;

                            default:
                                $query->where($field, 'like', '%' . $value . '%');
                                break;
                        }
                        break;
                    }
                }
            }
        }
    }
}
