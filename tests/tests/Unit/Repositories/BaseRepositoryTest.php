<?php

namespace Leandrowkz\Basis\Tests\Tests\Unit\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Leandrowkz\Basis\Tests\App\Models\Task;
use Leandrowkz\Basis\Tests\App\Repositories\TaskRepository;
use Leandrowkz\Basis\Tests\Tests\TestCase;

class BaseRepositoryTest extends TestCase
{
    /**
     * @var TaskRepository
     */
    protected $repo;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        $this->repo = new TaskRepository();
    }

    /** @test */
    public function it_create()
    {
        // arrange
        $data = factory(Task::class)->make()->toArray();

        // act
        $task = $this->repo->create($data);

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
        $task = $this->repo->create($old);
        $task = $this->repo->update($task->id, $new);

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
        $task = $this->repo->create($old);
        $task = $this->repo->delete($task->id);

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
        $task = $this->repo->create($old);
        $task = $this->repo->find($task->id);

        // assert
        $this->assertInstanceOf(Task::class, $task);
        $this->assertArraySubset($old, $task->toArray());
    }

    /** @test */
    public function it_all()
    {
        // arrange
        $tasks = $this->repo->all();

        // assert
        $this->assertInstanceOf(Collection::class, $tasks);
        $this->assertGreaterThan(0, $tasks->count());
    }

    /** @test */
    public function it_relations_empty()
    {
        // arrange
        $relations = $this->repo->relations();

        // assert
        $this->assertEquals([], $relations);
    }

    /** @test */
    public function it_relations_filled()
    {
        // arrange
        $relations = $this->repo->relations(['some'])->relations();

        // assert
        $this->assertEquals(['some'], $relations);
    }

    /** @test */
    public function it_query()
    {
        // arrange
        $builder = $this->repo->query([[
            'status', 'todo'
        ]]);

        // assert
        $this->assertInstanceOf(Builder::class, $builder);
        $this->assertInstanceOf(Collection::class, $builder->get());
    }

    /** @test */
    public function it__call()
    {
        // arrange
        $builder = $this->repo->where('status', 'todo');

        // assert
        $this->assertInstanceOf(Builder::class, $builder);
        $this->assertInstanceOf(Collection::class, $builder->get());
    }
}