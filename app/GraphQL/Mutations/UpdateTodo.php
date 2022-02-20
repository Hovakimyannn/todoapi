<?php

namespace App\GraphQL\Mutations;

use App\Models\Todo;
use Illuminate\Support\Facades\Redis;

class UpdateTodo
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function __invoke($_, array $args): Todo
    {
        $currentTodo = Todo::find($args['id']);
        if ($currentTodo->todo !== $args['todo']) {
            Redis::del('todolist');
            $currentTodo->update([
                'todo' => $args['todo'],
            ]);
        }
        return $currentTodo;
    }
}
