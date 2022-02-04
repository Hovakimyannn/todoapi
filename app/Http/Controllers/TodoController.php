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
        $content = $request->toArray()['todo'];
        if ($content) {
            Redis::set('updated',true);
            Todo::create([
                'todo' => $content,
            ]);
            return response(true);
        } else {
            return response(false);
        }
    }

    public function update(Request $request)
    {
        $reqTodo = $request->toArray();
        if (!$dbTodo = Todo::find($reqTodo['id'])) {
            return response(false);
        }
        if ($reqTodo['todo'] == null) {
            return $this->remove($request);
        }
        $oldTodo = $dbTodo->todo;
        $newTodo = $reqTodo['todo'];
        if (strcmp($newTodo, $oldTodo) != 0) {
            Redis::set('updated',true);
            $dbTodo->update([
                'todo' => $newTodo,
                'updated_at' => date('Y/m/d H:i:s'),
            ]);
            return response(true);
        } else {
            return response(false);
        }
    }

    public function remove(Request $request)
    {
        $id = $request->toArray()['id'];
        if ($todo = Todo::find($id)) {
            Redis::set('updated',true);
            $todo->delete($id);
            return response(true);
        } else {
            return response(false);
        }
    }
}