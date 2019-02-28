<?php

namespace Leandrowkz\Basis\Traits;

use Illuminate\Http\Request;
use ReflectionClass;
use Illuminate\Database\Eloquent\Model;

trait MutatesProps
{
    /**
     * Transforms string properties that are valid existing classes into
     * an instance of the same class name.
     * 
     * Caveats:
     * 1) Except if class is a subclass of \Illuminate\Database\Eloquent\Model.
     * 2) Subclasses of Illuminate\Http\Request are created through new Request
     *    instead of resolving via container.
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
            if (is_string($value) 
                && !is_subclass_of($value, Model::class)
                && (class_exists($value) || interface_exists($value))) {
                $this->{$property->name} = $value == is_subclass_of($value, Request::class)
                    ? new $value()
                    : resolve($value);
            }
        }
    }
}