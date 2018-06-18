<?php

namespace Leandrowkz\Basis\Traits;

use Illuminate\Http\Request;
use ReflectionClass;

trait MutatesProps
{
    /**
     * Transforms string properties that are valid existing classes into
     * an instance/object of the same class name.
     */
    public function mutateProps()
    {
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties();
        foreach ($props as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($this);

            // Resolve through laravel container
            // > Check if class is Request so we can resolve manually
            // > preventing callbacks from Laravel container to be fired
            if (is_string($value) && (class_exists($value) || interface_exists($value)))
                $this->{$property->name} = $value == is_subclass_of($value, Request::class)
                    ? new $value()
                    : resolve($value);
        }
    }
}