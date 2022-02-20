<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Todo
 *
 * @property int $id
 * @property string $todo
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder|Todo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Todo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Todo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereTodo($value)
 * @mixin \Eloquent
 */
class Todo extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'todo',
        'status',
        'id'
    ];
}
