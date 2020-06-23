<?php

namespace Leandrowkz\Basis\Services;

use Leandrowkz\Basis\Interfaces\Services\BaseServiceInterface;
use Leandrowkz\Basis\Traits\AccessibleProps;
use Leandrowkz\Basis\Traits\MutatesProps;

abstract class BaseService implements BaseServiceInterface
{
    use AccessibleProps, MutatesProps;

    /**
     * Repository Model class.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Service constructor.
     */
    function __construct()
    {
        $this->mutateProps();
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
     * @return \Illuminate\Database\Eloquent\Collection
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
