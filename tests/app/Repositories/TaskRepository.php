<?php

namespace Tests\App\Repositories;

use Leandrowkz\Basis\Repositories\BaseRepository;
use Tests\App\Models\Task;

class TaskRepository extends BaseRepository
{
    protected $model = Task::class;
}