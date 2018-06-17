<?php

namespace Leandrowkz\Basis\Traits;

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

            // Resolve through laravel app or instantiate normally
            if (is_string($value))
                if (class_exists($value))
                    $this->{$property->name} = new $value();
                elseif (interface_exists($value))
                    $this->{$property->name} = app($value);
        }
    }
}