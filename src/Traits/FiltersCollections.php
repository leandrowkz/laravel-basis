<?php

namespace Leandrowkz\Basis\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use BadMethodCallException;

trait FiltersCollections
{
    /**
     * Use the same validation rules set on:
     * https://laravel.com/docs/5.6/validation#available-validation-rules
     *
     * Array of filters. Ex:
     * [
     *      'column' => 'value',            // equals
     *      'column' => 'gte:value',        // greater than or equal
     *      'column' => 'gt:value',         // greater than
     *      'column' => 'lte:value',        // less than or equal
     *      'column' => 'lt:value',         // less than
     *      'column' => 'between:min,max',  // between
     *      'column' => 'in:a,b,c',         // in
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
     * Set filters.
     *
     * @param array $filters
     * @return $this
     */
    public function setFilters($filters = [])
    {
        $filters = is_array($filters) ? $filters : [];
        $this->filters = $filters;
        return $this;
    }

    /**
     * Add a filter according with key/value.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addFilter(string $key, $value = null)
    {
        $this->filters = array_add($this->filters, $key, $value);
        return $this;
    }

    /**
     * Filter collection according with local filters.
     *
     * @param Collection $items
     * @param array $filters [optional]
     * @return Collection
     */
    public function filter(Collection $items, array $filters = null)
    {
        if ($filters)
            $this->setFilters($filters);

        return new Collection(

            $items->filter(function ($item) {

                $proceed = true;
                foreach ($this->filters as $column => $value) {

                    try {
                        $validator = Validator::make($item->toArray(), [$column => $value]);
                        if ($validator->fails()) $proceed = false;
                    } catch (BadMethodCallException $e) {
                        $needle = $item->{$column};
                        if (is_bool($needle)) $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                        if (strtolower($value) == 'null') $value = null;
                        if ($needle != $value) $proceed = false;
                    }
                }

                return $proceed;

            })->values()->all()
        );
    }
}
