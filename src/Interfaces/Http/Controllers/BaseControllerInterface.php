<?php

namespace Leandrowkz\Basis\Interfaces\Http\Controllers;

use Leandrowkz\Basis\Interfaces\Services\BaseServiceInterface;

interface BaseControllerInterface
{
    public function all();

    public function find(string $id);

    public function create();

    public function update(string $id);

    public function delete(string $id);

    public function validate(string $type);

    public function exists(string $id);

    public function service(BaseServiceInterface $service = null);
}
