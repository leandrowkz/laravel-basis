<?php

namespace Leandrowkz\Basis\Tests\App\Repositories;

use Leandrowkz\Basis\Repositories\BaseRepository;
use Leandrowkz\Basis\Tests\App\Models\Task;

class TaskRepository extends BaseRepository
{
    protected $model = Task::class;
}