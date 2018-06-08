<?php

namespace Leandrowkz\Basis\Tests\App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'id',
        'title',
        'description',
        'status',
        'due_date',
    ];
}