<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class CreateTaskController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);

        $task = Task::query()
            ->create($validated);

        return response()->json($task);
    }
}
