<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Leandrowkz\Basis\Tests\App\Models\Task;

class CreateTestbenchTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function ($table) {
            $table->timestamps();
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('status');
            $table->dateTime('due_date');
        });

        $now = Carbon::now();
        factory(Task::class, rand(10, 50))->create();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
