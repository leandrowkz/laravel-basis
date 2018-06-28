<?php

namespace Leandrowkz\Basis\Traits;

trait AccessibleProps
{
    /**
     * This is a magic fluent getter/setter.
     * Automatically intercept any method that has same name of a property and
     * returns prop value.
     *
     * Example:
     * protected $foo = 'bar';
     *
     * $this->foo() // returns 'bar'
     * $this->foo('xyz') // sets $this->foo to 'xyz' and returns $this.
     *
     * @param $name
     * @param $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        if (isset($this->$name) && count($args) <= 0)
            return $this->$name;
        elseif (isset($this->$name) && count($args) > 0) {
            $this->$name = $args[0];
            return $this;
        }
    }
}