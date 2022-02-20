<?php

namespace App\GraphQL\Mutations;

use App\Models\Todo;
use Illuminate\Support\Facades\Redis;

class DeleteTodo
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     * @return Todo
     */
    public function __invoke($_, array $args):Todo
    {
        if ($todo = Todo::find($args['id'])) {
            Redis::del('todolist');
            $todo->delete();
        }
        return $todo;
    }
}
