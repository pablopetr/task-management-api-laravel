<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::query()
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json($tasks->toArray(), 200);
    }

    public function store(StoreRequest $request)
    {
        $task = Task::query()->create($request->validated());

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return response()->json($task->toArray(), 200);
    }

    public function update(UpdateRequest $request, Task $task)
    {
        $validated = $request->validated();

        $task->update($validated);

        return response()->json($task->toArray(), 200);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(null, 204);
    }
}
