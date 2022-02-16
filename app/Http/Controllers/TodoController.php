<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends BaseController
{
    public function getAll()
    {
        $todoList = $this->service->getAll();
        return response()->json([
            'status_code' => 200,
            'data' => $todoList,
        ]);

    }

    public function store(Request $request)
    {
        if ($id = $this->service->store($request)) {
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
            $current_status = $this->service->updateStatus($dbTodo);
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

        if ($this->service->updateData($dbTodo, $request)) {
            return response([
                "status_code" => 200,
            ]);
        }

        return response([
            "status_code" => 304,
        ]);
    }

    public function destroy(Request $request)
    {
        if ($this->service->destroy($request)) {
            return response([
                "status_code" => 204,
            ]);
        }

        return response([
            "status_code" => 405,
        ]);
    }
}
