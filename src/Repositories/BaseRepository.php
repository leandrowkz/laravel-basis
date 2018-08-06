<?php

namespace Leandrowkz\Basis\Repositories;

use Illuminate\Database\Eloquent\Model;
use Leandrowkz\Basis\Interfaces\Repositories\BaseRepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * Repository Model class.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Builder for magic methods.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * Allowed methods.
     *
     * @var array
     */
    protected $starts = ['where', 'orWhere'];

    /**
     * Automatically intercept any method that starts with
     * name where... or orWhere... and handle this method with a
     * builder instance created from $this->model.
     *
     * This magic __call allows you to use builder methods inside a
     * repository class, like:
     *
     * $repoInstance->where(...)->whereIn(...)->orderBy(...)->get();
     *
     * @param $name
     * @param $args
     * @return \Illuminate\Database\Eloquent\Builder|null
     */
    public function __call($name, $args)
    {
        if (starts_with($name, $this->starts) && $this->model) {

            if (!$this->builder)
                $this->builder = $this->model::query();

            call_user_func_array(array($this->builder, $name), $args);

            return $this->builder;
        }
    }

    /**
     * Builds multiple where clauses within single method.
     *
     * Ex:
     * ->query([
     *      'column' => 'value',            // equals
     *      'column' => 'gte:value',        // greater than or equal
     *      'column' => 'gt:value',         // greater than
     *      'column' => 'lte:value',        // less than or equal
     *      'column' => 'lt:value',         // less than
     *      'column' => 'between:min,max',  // between
     *      'column' => 'in:a,b,c',         // in
     *      'column' => function() {},      // callable
     *      'column' => 'or:value',         // whereOr
     * ])->orderBy()->get()
     *
     * @param array
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query($filters = null)
    {
        $this->builder = $this->model::query();

        if (!is_array($filters))
            return $this->builder;

        $columns = with(new $this->model())->getFillable();

        foreach ($filters as $column => $value) {

            if (!in_array($column, $columns))
                continue;

            if (starts_with($value, 'boolean:')) {
                $val = str_replace_first('boolean:', '', $value);
                $this->builder->where($column, $val);
                continue;
            }

            if (starts_with($value, 'gte:')) {
                $val = str_replace_first('gte:', '', $value);
                $this->builder->where($column, '>=', $val);
                continue;
            }

            if (starts_with($value, 'gt:')) {
                $val = str_replace_first('gt:', '', $value);
                $this->builder->where($column, '>', $val);
                continue;
            }

            if (starts_with($value, 'lte:')) {
                $val = str_replace_first('lte:', '', $value);
                $this->builder->where($column, '<=', $val);
                continue;
            }

            if (starts_with($value, 'lt:')) {
                $val = str_replace_first('lt:', '', $value);
                $this->builder->where($column, '<', $val);
                continue;
            }

            if (starts_with($value, 'between:')) {
                $val = str_replace_first('between:', '', $value);
                $val = stristr($val, ',') ? explode(',', $val) : $val;
                $this->builder->whereBetween($column, $val);
                continue;
            }

            if (starts_with($value, 'in:')) {
                $val = str_replace_first('in:', '', $value);
                $val = stristr($val, ',') ? explode(',', $val) : $val;
                $this->builder->whereIn($column, $val);
                continue;
            }

            if (starts_with($value, 'or:')) {
                $this->builder->orWhere($column, $value);
                continue;
            }

            $this->builder->where($column, $value);
        }

        return $this->builder;
    }

    /**
     * Gets/sets model
     *
     * @param string $model
     * @return mixed $this->model|$this
     */
    public function model(string $model = null)
    {
        if ($model) {
            $this->model = $model;
            return $this;
        }

        return $this->model;
    }

    /**
     * Gets a Collection of all data from model.
     *
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public function all()
    {
        return $this->model::all();
    }

    /**
     * Finds a single record.
     *
     * @param mixed string|array $id
     * @return mixed \Illuminate\Database\Eloquent\Model|
     *               \Illuminate\Database\Eloquent\Collection|
     *               false
     */
    public function find($id)
    {
        if (!$model = $this->model::find($id))
            return false;

        return $model;
    }

    /**
     * Create a single record for model.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data = [])
    {
        $model = new $this->model();
        foreach ($model->getFillable() as $column) {
            if ($column != $model->getKeyName())
                $model->$column = $data[$column] ?? null;
        }
        $model->save();
        return $model;
    }

    /**
     * Update a single record for model.
     *
     * @param string $id
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(string $id, array $data = [])
    {
        $model = $this->find($id);
        foreach ($model->getFillable() as $column) {
            $model->$column = array_key_exists($column, $data) ? $data[$column] : $model->$column;
        }
        $model->save();
        return $model;
    }

    /**
     * Deletes a single record.
     *
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function delete(string $id)
    {
        $model = $this->find($id);
        $this->model::destroy($id);
        return $model;
    }
}
