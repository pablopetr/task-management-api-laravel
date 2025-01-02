<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentCollection;
use App\Models\Comment;
use App\Models\Task;

class IndexController extends Controller
{
    public function __invoke(Task $task)
    {
        $comments = Comment::query()
            ->where('task_id', '=', $task->id)
            ->get();

        return CommentCollection::make($comments);
    }
}
