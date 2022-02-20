<?php

namespace App\GraphQL\Mutations;

use App\Models\Todo;
use Illuminate\Support\Facades\Redis;

class UpdateStatus
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @return Todo
     */
    public function __invoke($_, array $args): Todo
    {
        Redis::del('todolist');
        $currentTodo = Todo::find($args['id']);
        $currentTodo->update([
            'status' => $currentTodo->status == 0 ? 1 : 0
        ]);
        return $currentTodo;
    }
}
