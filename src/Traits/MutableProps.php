<?php

namespace Leandrowkz\Basis\Traits;

use ReflectionClass;

trait MutableProps
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

            if (is_string($value) && class_exists($value)) {
                // Resolve through laravel app or instantiate normally
                $this->{$property->name} = interface_exists($value) ? app($value) : new $value();
            }
        }
    }
}