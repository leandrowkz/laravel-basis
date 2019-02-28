<?php

namespace Tests\Unit\Services;

use Illuminate\Database\Eloquent\Collection;
use Tests\App\Models\Task;
use Tests\App\Services\TaskService;
use Tests\TestCase;

class BaseServiceTest extends TestCase
{
    /**
     * @var TaskService
     */
    protected $service;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        $this->service = new TaskService();
    }

    /** @test */
    public function it_create()
    {
        // arrange
        $data = factory(Task::class)->make()->toArray();

        // act
        $task = $this->service->create($data);

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
        $task = $this->service->create($old);
        $task = $this->service->update($task->id, $new);

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

        // act
        $task = $this->service->create($old);
        $task = $this->service->delete($task->id);

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

        // act
        $task = $this->service->create($old);
        $task = $this->service->find($task->id);

        // assert
        $this->assertInstanceOf(Task::class, $task);
        $this->assertArraySubset($old, $task->toArray());
    }

    /** @test */
    public function it_all()
    {
        // arrange
        factory(Task::class)->create();
        $tasks = $this->service->all();

        // assert
        $this->assertInstanceOf(Collection::class, $tasks);
        $this->assertGreaterThan(0, $tasks->count());
    }

    /** @test */
    public function it_model()
    {
        // arrange
        $model_01 = $this->service->model();
        $model_02 = $this->service->model(Task::class)->model();

        // assert
        $this->assertEquals(Task::class, $model_01);
        $this->assertEquals(Task::class, $model_02);
    }
}