<?php

namespace Tests\Unit\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Tests\App\Http\Controllers\TaskController;
use Tests\App\Models\Task;
use Tests\App\Services\TaskService;
use Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseControllerTest extends TestCase
{
    /**
     * @var TaskController
     */
    protected $controller;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        $this->controller = new TaskController();
    }

    /** @test */
    public function it_create()
    {
        // arrange
        $data = factory(Task::class)->make()->toArray();
        request()->replace($data);

        // act
        $task = $this->controller->create();

        // assert
        $this->assertInstanceOf(Task::class, $task);
        $this->assertArraySubset($data, $task->toArray());
        $this->assertDatabaseHas('tasks', $data);
    }

    /** @test */
    public function it_update()
    {
        // arrange
        $old = factory(Task::class)->make()->toArray();
        $new = factory(Task::class)->make()->toArray();

        // act
        request()->replace($old);
        $task = $this->controller->create();
        request()->replace($new);
        $task = $this->controller->update($task->id);

        // assert
        $this->assertInstanceOf(Task::class, $task);
        $this->assertArraySubset($new, $task->toArray());
        $this->assertDatabaseHas('tasks', $new);
        $this->assertDatabaseMissing('tasks', $old);
    }

    /** @test */
    public function it_delete()
    {
        // arrange
        $old = factory(Task::class)->make()->toArray();
        request()->replace($old);

        // act
        $task = $this->controller->create();
        $task = $this->controller->delete($task->id);

        // assert
        $this->assertInstanceOf(Task::class, $task);
        $this->assertArraySubset($old, $task->toArray());
        $this->assertDatabaseMissing('tasks', $old);
    }

    /** @test */
    public function it_find()
    {
        // arrange
        $old = factory(Task::class)->make()->toArray();
        request()->replace($old);

        // act
        $task = $this->controller->create();
        $task = $this->controller->find($task->id);

        // assert
        $this->assertInstanceOf(Task::class, $task);
        $this->assertArraySubset($old, $task->toArray());
    }

    /** @test */
    public function it_all()
    {
        // arrange
        $tasks = $this->controller->all();

        // assert
        $this->assertInstanceOf(Collection::class, $tasks);
        $this->assertGreaterThan(0, $tasks->count());
    }

    /** @test */
    public function it_exists()
    {
        // arrange
        $this->expectException(NotFoundHttpException::class);

        // act
        $this->controller->find('270987134908132671956');
    }

    /** @test */
    public function it_validate()
    {
        // arrange
        $this->expectException(ValidationException::class);
        $task = factory(Task::class)->make()->toArray();
        unset($task['title']);
        request()->replace($task);

        // act
        $this->controller->create();
    }

    /** @test */
    public function it_service()
    {
        // arrange
        $service_01 = $this->controller->service();
        $service_02 = $this->controller->service(new TaskService())->service();

        // assert
        $this->assertInstanceOf(TaskService::class, $service_01);
        $this->assertInstanceOf(TaskService::class, $service_02);
    }
}