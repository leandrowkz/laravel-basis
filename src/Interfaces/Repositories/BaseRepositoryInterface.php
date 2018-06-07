<?php

namespace Leandrowkz\Basis\Interfaces\Repositories;

interface BaseRepositoryInterface
{
    public function relations(Array $relations = null);

    public function all();

    public function query($where);

    public function find(string $id);

    public function filter($filters);

    public function create(array $data = []);

    public function update(string $id, array $data = []);

    public function delete(string $id);
}
