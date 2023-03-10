<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'department_id ',
        'priority_id ',
        'status',
        'is_seen',
        'is_answer',
        'user_type',
    ];
}