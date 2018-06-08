<?php

namespace Leandrowkz\Basis\Tests\App\Http\Controllers;

use Leandrowkz\Basis\Http\Controllers\BaseController;
use Leandrowkz\Basis\Tests\App\Http\Requests\TaskRequest;
use Leandrowkz\Basis\Tests\App\Services\TaskService;

class TaskController extends BaseController
{
    protected $service = TaskService::class;

    protected $request = TaskRequest::class;
}