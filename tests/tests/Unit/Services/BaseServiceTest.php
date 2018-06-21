<?php

namespace Tests\Unit\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Tests\App\Events\TaskDeleted;
use Tests\App\Events\TaskUpdated;
use Tests\App\Events\TaskCreated;
use Tests\App\Models\Task;
use Tests\App\Repositories\TaskRepository;
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
    public function it_repo_query()
    {
        // arrange
        $builder = $this->service->repo()->query(['status' => 'todo']);

        // assert
        $this->assertInstanceOf(Builder::class, $builder);
        $this->assertInstanceOf(Collection::class, $builder->get());
    }

    /** @test */
    public function it_filter()
    {
        // arrange
        $data_01 = $this->service->filters(['status' => 'todo'])->filter(
            $this->service->all()
        );
        $data_02 = $this->service->filter(
            $this->service->all(),
            ['status' => 'todo']
        );

        // assert
        $this->assertInstanceOf(Collection::class, $data_01);
        $this->assertInstanceOf(Collection::class, $data_02);

        foreach ($data_01 as $task)
            $this->assertEquals($task->status, 'todo');

        foreach ($data_02 as $task)
            $this->assertEquals($task->status, 'todo');
    }

    /** @test */
    public function it_repo()
    {
        // arrange
        $repo_01 = $this->service->repo();
        $repo_02 = $this->service->repo(new TaskRepository)->repo();

        // assert
        $this->assertInstanceOf(TaskRepository::class, $repo_01);
        $this->assertInstanceOf(TaskRepository::class, $repo_02);
    }
}