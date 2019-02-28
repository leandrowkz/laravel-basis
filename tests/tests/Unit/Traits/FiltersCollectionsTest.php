<?php

namespace Tests\Unit\Services;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
use Tests\App\Models\Task;
use Tests\App\Services\TaskService;
use Tests\TestCase;

class FiltersCollectionsTest extends TestCase
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
    public function it_filter_empty_values()
    {
        // arrange
        $task = factory(Task::class)->make(['status' => ''])->toArray();
        $this->service->create($task);
        $data_01 = $this->service->filters(['status' => 'todo'])->filter(
            $this->service->all()
        );

        $task = factory(Task::class)->make(['description' => null])->toArray();
        $this->service->create($task);
        $data_02 = $this->service->filters(['description' => null])->filter(
            $this->service->all()
        );

        $data_03 = $this->service->filters(['description' => 'null'])->filter(
            $this->service->all()
        );

        // assert
        foreach ($data_01 as $task)
            $this->assertNotEquals($task->status, '');

        foreach ($data_02 as $task)
            $this->assertNull($task->description);

        foreach ($data_03 as $task)
            $this->assertNull($task->description);
    }

    /** @test */
    public function it_filter_array_rules()
    {
        // arrange
        $now = Carbon::now();
        $faker = Factory::create();
        foreach (range(1, 5) as $lap) {
            $date = Carbon::create($faker->dateTimeInInterval('-30 years', '+5 years')->getTimestamp());
            $task = factory(Task::class)->make(['due_date' => $date])->toArray();
            $this->service->create($task);
        }
        foreach (range(1, 5) as $lap) {
            $date = $now->copy()->addDays(rand(1, 30));
            $task = factory(Task::class)->make(['due_date' => $date])->toArray();
            $this->service->create($task);
        }
        
        $before = $now->copy()->addDays(30);
        
        $this->service->filters([
            'due_date' => [
                'before_or_equal:' . $before,
                'after_or_equal:' . $now,
            ],
        ]);
        $tasks = $this->service->filter(
            $this->service->all()
        );

        dump($now, $before, $tasks);

        // assert
        foreach ($tasks as $task)
            $this->assertTrue(Carbon::parse($task->due_date)->between($now, $before));
    }
}