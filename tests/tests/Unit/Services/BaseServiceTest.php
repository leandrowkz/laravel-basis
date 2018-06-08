<?php

namespace Tests\Tests\Unit\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Tests\App\Events\TaskDeleted;
use Tests\App\Events\TaskUpdated;
use Tests\App\Events\TaskCreated;
use Tests\App\Models\Task;
use Tests\App\Services\TaskService;
use Tests\Tests\TestCase;

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
        $this->expectsEvents(TaskCreated::class);
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
        $this->expectsEvents(TaskUpdated::class);
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
        $this->expectsEvents(TaskDeleted::class);
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
        $tasks = $this->service->all();

        // assert
        $this->assertInstanceOf(Collection::class, $tasks);
        $this->assertGreaterThan(0, $tasks->count());
    }

    /** @test */
    public function it_query()
    {
        // arrange
        $builder = $this->service->query([[
            'status', 'todo'
        ]]);

        // assert
        $this->assertInstanceOf(Builder::class, $builder);
        $this->assertInstanceOf(Collection::class, $builder->get());
    }
}