<?php

namespace Leandrowkz\Basis\Interfaces\Services;

interface BaseServiceInterface
{
    public function model(string $model = null);
    
    public function all();

    public function find($id);

    public function create(array $data = []);

    public function update(string $id, array $data = []);

    public function delete(string $id);
}
