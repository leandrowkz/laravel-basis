<?php

namespace Leandrowkz\Basis\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function all();

    public function query($where = null);

    public function find(string $id);

    public function create(array $data = []);

    public function update(string $id, array $data = []);

    public function delete(string $id);

    public function model(string $model);
}
