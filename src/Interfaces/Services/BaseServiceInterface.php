<?php

namespace Leandrowkz\Basis\Interfaces\Services;

use Illuminate\Database\Eloquent\Collection;
use Leandrowkz\Basis\Interfaces\Repositories\BaseRepositoryInterface;

interface BaseServiceInterface
{
    public function all();

    public function find(string $id);

    public function create(array $data = []);

    public function update(string $id, array $data = []);

    public function delete(string $id);

    public function repo(BaseRepositoryInterface $repo = null);

    public function filter(Collection $items, array $filters = null);
}
