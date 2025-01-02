<?php

namespace Database\Factories;

use App\Enum\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'user_id' => User::factory(),
        ];
    }

    public function toDo(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatusEnum::TO_DO->value,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
           'status' => TaskStatusEnum::IN_PROGRESS->value,
        ]);
    }

    public function done(): static
    {
        return $this->state(fn (array $attributes) => [
           'status' => TaskStatusEnum::DONE->value,
        ]);
    }
}
