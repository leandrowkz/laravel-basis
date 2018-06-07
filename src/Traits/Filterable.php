<?php

namespace Leandrowkz\Basis\Traits;

trait Filterable
{
    /**
     * Array of filters. We encourage to set this attribute like:
     * [
     *      ['id', '=', 'something'],
     *      ['id', 'in', [1, 2, 3]],
     *      ['id', 'or', 'another-value'],
     *      ...
     * ]
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Returns filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Set filters for current using class. Returns class instance.
     *
     * Example of usage:
     * $this->service->filter([...])->tickets();
     * $this->repository->filter([...])->all();
     *
     * On both examples, tickets() and all() should use $this->filters
     * to filter results.
     *
     * @param array $filters
     * @return $this
     */
    public function filter($filters = [])
    {
        $filters = is_array($filters) ? $filters : [];
        $this->filters = $filters;
        return $this;
    }
}