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
     *      'or:column' => 'value'          // or clause for given column
     * ]
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Get/Set filters.
     *
     * @param array $filters
     * @return mixed $this|array
     */
    public function filters(array $filters = null)
    {
        if (isset($filters)) {
            $this->filters = $filters;
            return $this;
        }

        return $this->filters;
    }

    /**
     * Add a filter according with key/value.
     *
     * @param string $key
     * @param mixed $value
     * @return array $this->filters
     */
    public function addFilter(string $key, $value = null)
    {
        $this->filters = array_add($this->filters, $key, $value);
        return $this->filters;
    }

    /**
     * Add a filter according with key/value.
     *
     * @param string $key
     * @return mixed $this->filters[$key]|null
     */
    public function getFilter(string $key)
    {
        return $this->filters[$key] ?? null;
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
            $this->filters($filters);

        $or = [];

        $collection = new Collection(

            $items->filter(function ($item) use (&$or) {

                $proceed = true;
                foreach ($this->filters as $column => $value) {

                    if (starts_with($column, 'or:'))
                        $or = array_add($or, str_replace('or:', '', $column), $value);
                    else {
                        try {
                            $value = is_null($value) ? 'null' : $value;
                            $rules = !is_array($value) ? [$value] : $value;
                            if (isset($item->{$column})) array_push($rules, 'required');
                            $validator = Validator::make($item->toArray(), [$column => $rules]);
                            if ($validator->fails()) $proceed = false;
                        } catch (BadMethodCallException $e) {
                            $needle = $item->{$column};
                            if (is_bool($needle)) $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                            if (!is_array($value) && strtolower($value) == 'null') $value = null;
                            if ($needle != $value) $proceed = false;
                        }
                    }
                }

                return $proceed;

            })->values()->all()
        );

        if (count($or) > 0)
            $collection = $collection->merge($this->filter($items, $or));

        return $collection;
    }
}
