<?php

namespace Tests\App\Services;

use Leandrowkz\Basis\Services\BaseService;
use Tests\App\Models\Task;

class TaskService extends BaseService
{
    protected $model = Task::class;
}