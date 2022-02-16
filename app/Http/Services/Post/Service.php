<?php

namespace App\Http\Services\Post;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class Service
{
    public function getAll()
    {
        if ($cachedTodoList = Redis::get('todolist')) {
            return json_decode($cachedTodoList);
        }

        $todoList = Todo::all()->toArray();
        $json = json_encode($todoList);
        Redis::set('todolist', $json);
        return $todoList;
    }

    public function store(Request $request)
    {
        $content = trim($request->json('todo'));

        if ($content) {
            Redis::del('todolist');
            Todo::create([
                'todo' => $content,
            ]);
            $id = Todo::query()->orderBy('id', 'desc')->first()->id;
            return $id;
        }
        return null;
    }

    public function updateData($dbTodo, Request $request)
    {
        $oldTodo = $dbTodo->todo;
        $newTodo = $request->json('todo');

        if (strcmp($newTodo, $oldTodo) != 0) {
            Redis::del('todolist');
            $dbTodo->update([
                'todo' => $newTodo,
            ]);
            return true;
        }
        return false;
    }

    public function updateStatus($dbTodo)
    {
        Redis::del('todolist');
        $current_status = $dbTodo->status;
        $dbTodo->update([
            'status' => ($current_status = $current_status == 0 ? 1 : 0)
        ]);
        return $current_status;
    }

    public function destroy(Request $request)
    {
        if ($todo = Todo::find($request->json('id'))) {
            Redis::del('todolist');
            $todo->delete();
            return true;
        }
        return false;
    }

}
