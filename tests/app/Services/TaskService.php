<?php

namespace Leandrowkz\Basis\Tests\App\Services;

use Leandrowkz\Basis\Services\BaseService;
use Leandrowkz\Basis\Tests\App\Events\TaskCreated;
use Leandrowkz\Basis\Tests\App\Events\TaskDeleted;
use Leandrowkz\Basis\Tests\App\Events\TaskUpdated;
use Leandrowkz\Basis\Tests\App\Repositories\TaskRepository;

class TaskService extends BaseService
{
    protected $repo = TaskRepository::class;

    protected $events = [
        'created' => TaskCreated::class,
        'updated' => TaskUpdated::class,
        'deleted' => TaskDeleted::class,
    ];
}