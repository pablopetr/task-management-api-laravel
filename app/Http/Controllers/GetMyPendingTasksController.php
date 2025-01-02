<?php

namespace App\Http\Controllers;

use App\Http\Resources\MyPendingTasksCollection;
use App\Models\Task;

class GetMyPendingTasksController extends Controller
{
    public function __invoke()
    {
        $tasks = Task::query()
            ->where('user_id', '=', auth()->user()->id)
            ->get();

        return MyPendingTasksCollection::make($tasks);
    }
}
