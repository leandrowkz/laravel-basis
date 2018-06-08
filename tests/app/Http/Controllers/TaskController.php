<?php

namespace Tests\App\Http\Controllers;

use Leandrowkz\Basis\Http\Controllers\BaseController;
use Tests\App\Http\Requests\TaskRequest;
use Tests\App\Services\TaskService;

class TaskController extends BaseController
{
    protected $service = TaskService::class;

    protected $request = TaskRequest::class;
}