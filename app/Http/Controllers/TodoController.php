<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

date_default_timezone_set('Asia/Yerevan');

class TodoController extends Controller
{
    public function getAll()
    {
        $cachedTodoList = Redis::get('todolist');
        $updated = Redis::get('updated');
        if ($cachedTodoList && !$updated) {
            $todoList = json_decode($cachedTodoList);
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched all data from redis',
                'data' => $todoList,
            ]);
        } else {
            $todoList = Todo::all()->toArray();
            $json = json_encode($todoList);
            Redis::set('todolist',$json);
            Redis::set('updated',false);
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched all data from database',
                'data' => $todoList,
            ]);
        }
    }

    public function store(Request $request)
    {
        $content = $request->todo;
        if ($content) {
            Redis::set('updated',true);
            Todo::create([
                'todo' => $content,
            ]);
            return response([
                "status_code" => 201,
            ]);
        } else {
            return response([
                "status_code" => 400,
            ]);
        }
    }

    public function update(Request $request)
    {
        if (!$dbTodo = Todo::find($request->id)) {
            return response([
                "status_code" => 400,
            ]);
        }
        if ($request->todo == null) {
            return $this->remove($request);
        }
        $oldTodo = $dbTodo->todo;
        $newTodo = $request->todo;
        if (strcmp($newTodo, $oldTodo) != 0) {
            Redis::set('updated',true);
            $dbTodo->update([
                'todo' => $newTodo,
                'updated_at' => date('Y/m/d H:i:s'),
            ]);
            return response([
                "status_code" => 200,
            ]);
        } else {
            return response([
                "status_code" => 304,
            ]);
        }
    }

    public function remove(Request $request)
    {
        if ($todo = Todo::find($request->id)) {
            Redis::set('updated',true);
            $todo->delete();
            return response([
                "status_code" => 204,
            ]);
        } else {
            return response([
                "status_code" => 405,
            ]);
        }
    }
}
