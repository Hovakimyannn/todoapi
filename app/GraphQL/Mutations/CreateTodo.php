<?php

namespace App\GraphQL\Mutations;

use App\Models\Todo;
use Illuminate\Support\Facades\Redis;

class CreateTodo
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @return Todo
     */
    public function __invoke($_, array $args): Todo
    {
        Redis::del('todolist');
        return Todo::create([
            'todo' => trim($args['todo']),
        ]);
    }
}
