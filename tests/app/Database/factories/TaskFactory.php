<?php

use Faker\Generator as Faker;
use Tests\App\Models\Task;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'title' => $faker->text(100),
        'description' => $faker->text,
        'status' => $faker->randomElement(['todo', 'done']),
        'due_date' => $faker->date(),
    ];
});