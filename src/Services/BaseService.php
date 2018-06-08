<?php

namespace Leandrowkz\Basis\Services;

use Leandrowkz\Basis\Traits\Filterable;
use Leandrowkz\Basis\Traits\MutableProps;

abstract class BaseService
{
    use Filterable, MutableProps;

    /**
     * Service repository. Starts with class name, but after
     * construct becomes an instance of this class.
     *
     * @var \Leandrowkz\Basis\Repositories\BaseRepository
     */
    protected $repo;

    /**
     * All basic service events.
     *
     * @var array
     */
    protected $events = [
        'created' => null,
        'deleted' => null,
        'updated' => null,
    ];

    /**
     * Service constructor.
     */
    function __construct()
    {
        $this->mutateProps();
    }

    /**
     * Return all data.
     *
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public function all()
    {
        return $this->repo->all();
    }

    /**
     * Return a single record.
     *
     * @param string $id
     * @return mixed Leandrowkz\Basis\Repositories\BaseRepository::$model
     */
    public function find(string $id)
    {
        return $this->repo->find($id);
    }

    /**
     * Return all data by where conditions.
     *
     * @param array $where
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query($where)
    {
        return $this->repo->query($where);
    }

    /**
     * Creates a single record.
     *
     * @param array $data
     * @return mixed Leandrowkz\Basis\Repositories\BaseRepository::$model
     */
    public function create(array $data)
    {
        $new = $this->repo->create($data);

        if (!is_null($this->events['created']))
            event(new $this->events['created']($new));

        return $new;
    }

    /**
     * Updates a single record.
     *
     * @param string $id
     * @param array $data
     * @return mixed Leandrowkz\Basis\Repositories\BaseRepository::$model
     */
    public function update(string $id, array $data)
    {
        $old = $this->repo->find($id);
        $new = $this->repo->update($id, $data);

        if (!is_null($this->events['updated']))
            event(new $this->events['updated']($new, $old));

        return $new;
    }

    /**
     * Deletes a single record.
     *
     * @param string $id
     * @return mixed Leandrowkz\Basis\Repositories\BaseRepository::$model
     */
    public function delete(string $id)
    {
        $old = $this->repo->find($id);
        $this->repo->delete($id);

        if (!is_null($this->events['deleted']))
            event(new $this->events['deleted']($old));

        return $old;
    }
}