<?php

namespace Leandrowkz\Basis\Interfaces\Http\Controllers;

interface BaseControllerInterface
{
    public function all();

    public function find(string $id);

    public function create();

    public function update(string $id);

    public function delete(string $id);

    public function validate();

    public function exists(string $id);

    public function filterRequest();
}
