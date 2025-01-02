<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\AssignTaskRequest;
use App\Models\Task;
use App\Models\User;

class AssignTaskController extends Controller
{
    public function __invoke(AssignTaskRequest $request)
    {
        $task = Task::findOrFail($request->get('task_id'));
        $user = User::findOrFail($request->get('user_id'));

        $task->update([
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Task assigned successfully!',
        ]);
    }
}
