<?php

namespace App\Http\Controllers;

use App\Enum\TaskStatusEnum;
use App\Http\Resources\MyPendingTasksCollection;
use App\Models\Task;

class GetMyPendingTasksController extends Controller
{
    public function __invoke()
    {
        $tasks = Task::query()
            ->where('user_id', '=', auth()->user()->id)
            ->whereIn('status', [TaskStatusEnum::TO_DO->value, TaskStatusEnum::IN_PROGRESS->value])
            ->get();

        return MyPendingTasksCollection::make($tasks);
    }
}
