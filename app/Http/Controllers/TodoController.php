<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TodoController extends Controller
{
    public function getAll()
    {
        if ($cachedTodoList = Redis::get('todolist')) {
            $todoList = json_decode($cachedTodoList);
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched all data from redis',
                'data' => $todoList,
            ]);
        }

        $todoList = Todo::all()->toArray();
        $json = json_encode($todoList);
        Redis::set('todolist', $json);

        return response()->json([
            'status_code' => 200,
            'message' => 'Fetched all data from database',
            'data' => $todoList,
        ]);

    }

    public function create(Request $request)
    {
        $content = trim($request->json('todo'));

        if ($content) {
            Redis::del('todolist');
            Todo::create([
                'todo' => $content,
            ]);
            $id = Todo::query()->orderBy('id', 'desc')->first()->id;
            return response([
                "status_code" => 201,
                "id" => $id,
            ]);
        }

        return response([
            "status_code" => 400,
        ]);

    }

    public function update(Request $request)
    {
        if (!$dbTodo = Todo::find($request->json('id'))) {
            return response([
                "status_code" => 400,
            ]);
        }

        if ($request->json('status')) {
            Redis::del('todolist');
            $current_status = $dbTodo/*->where('id', $request->json('id'))->first()*/->status;
            $dbTodo->
            /*where('id', '=', $request->json('id'))->*/
            update(['status' => ($current_status = $current_status == 0 ? 1 : 0)]);
            return response([
                "status_code" => 200,
                "status" => $current_status,
            ]);
        }

        if ($request->json('todo') === "") {
            return response([
                "status_code" => 204,
            ]);
        }


        $oldTodo = $dbTodo->todo;
        $newTodo = $request->json('todo');

        if (strcmp($newTodo, $oldTodo) != 0) {
            Redis::del('todolist');
            $dbTodo->update([
                'todo' => $newTodo,
            ]);

            return response([
                "status_code" => 200,
            ]);
        }

        return response([
            "status_code" => 304,
        ]);
    }

    public function delete(Request $request)
    {
        if ($todo = Todo::find($request->json('id'))) {
            Redis::del('todolist');
            $todo->delete();
            return response([
                "status_code" => 204,
            ]);
        }
        return response([
            "status_code" => 405,
        ]);
    }
}
