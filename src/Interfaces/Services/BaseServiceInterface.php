<?php

namespace Leandrowkz\Basis\Interfaces\Services;

use Illuminate\Database\Eloquent\Collection;

interface BaseServiceInterface
{
    public function all();

    public function find($id);

    public function create(array $data = []);

    public function update(string $id, array $data = []);

    public function delete(string $id);

    public function filter(Collection $items, array $filters = null);

    public function model(string $model = null);

    public function applyRequestFilters();
}
