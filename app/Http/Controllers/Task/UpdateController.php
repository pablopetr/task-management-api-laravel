<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;

class UpdateController extends Controller
{
    public function __invoke(Task $task, UpdateRequest $request)
    {
        $validated = $request->validated();

        $task->update($validated);

        return response()->json($task->toArray(), 200);
    }
}
