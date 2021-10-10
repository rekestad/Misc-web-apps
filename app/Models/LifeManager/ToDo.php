<?php

namespace App\Models\LifeManager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToDo extends Model
{
    use SoftDeletes;

    protected $table = 'lm_todo_items';
    protected $fillable = [
        'item_name',
        'is_urgent',
        'priority_order',
        'date_deadline',
        'group_id',
        'user_id',
        'is_checked'
    ];
}
