<?php

namespace Tests\App\Services;

use Leandrowkz\Basis\Services\BaseService;
use Tests\App\Events\TaskCreated;
use Tests\App\Events\TaskDeleted;
use Tests\App\Events\TaskUpdated;
use Tests\App\Repositories\TaskRepository;

class TaskService extends BaseService
{
    protected $repo = TaskRepository::class;

    protected $events = [
        'created' => TaskCreated::class,
        'updated' => TaskUpdated::class,
        'deleted' => TaskDeleted::class,
    ];
}