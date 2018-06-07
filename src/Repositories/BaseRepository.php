<?php

namespace Leandrowkz\Basis\Repositories;

use Leandrowkz\Basis\Interfaces\Repositories\BaseRepositoryInterface;
use Leandrowkz\Basis\Traits\Filterable;

abstract class BaseRepository implements BaseRepositoryInterface
{
    use Filterable;

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
     * Relations to be loaded.
     *
     * @var array
     */
    protected $relations = [];

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
     *      ['client_id', '=', 'saraiva-v5'],
     *      ['start,end', 'range', ['2017-01-01', '2018-05-01']]
     *      ['id', 'in', [1,2,3]]
     * ])->orderBy()->get()
     *
     * @param array|callable
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query($filters)
    {
        $this->builder = $this->model::query();

        if (!is_array($filters)) $filters = [$filters];

        foreach ($filters as $index => $where) {
            $column = null;
            $type = null;
            $value = null;

            if (is_array($where)) {
                $column = $where[0];
                if (count($where) >= 3) {
                    $type = $where[1];
                    $value = $where[2];
                } else {
                    $type = false;
                    $value = $where[1];
                }
            }

            if (is_callable($where))
                $type = 'callable';

            switch ($type) {

                case 'in':
                    $this->builder->whereIn($column, $value);
                    break;

                case 'or':
                    $this->builder->orWhere($column, $value);
                    break;

                case 'range':
                    $columns = explode(',', $column);
                    $this->builder->where([
                        [$columns[0], '>=', $value[0]],
                        [$columns[1], '<=', $value[1]],
                    ]);
                    break;

                case 'between':
                    $this->builder->whereBetween($column, $value);
                    break;

                case 'callable':
                    $this->builder->where($where);
                    break;

                default:
                    if (!$type)
                        $this->builder->where($column, $value);
                    else
                        $this->builder->where($column, $type, $value);
                    break;
            }
        }

        return $this->builder;
    }

    /**
     * Gets/sets repository relations
     *
     * @param mixed $relations
     * @return mixed array|$this
     */
    public function relations(array $relations = null)
    {
        if (is_null($relations))
            return $this->relations;
        else
            $this->relations = $relations;

        return $this;
    }

    /**
     * Gets a Collection of all data from model.
     *
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public function all()
    {
        return $this->query($this->filters)->with($this->relations)->get();
    }

    /**
     * Finds a single record. Returns with its relations
     *
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Model|false
     */
    public function find(string $id)
    {
        if (!$model = $this->model::find($id))
            return false;

        return $model->load($this->relations);
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
            $model->$column = $data[$column] ?? $model->$column;
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
