<?php

namespace App\GraphQL\Queries;

use App\Models\Todo;
use Illuminate\Support\Facades\Redis;

class Todos
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args):array
    {
        if ($cachedTodoList = Redis::get('todolist')) {
            return json_decode($cachedTodoList);
        }
        $todoList = Todo::all()->toArray();
        $json = json_encode($todoList);
        Redis::set('todolist', $json);
        return Todo::all();
    }
}
