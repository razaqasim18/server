<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_id',
        'from_id',
        'to_id',
        'package_id',
        'package_price',
        'message',
        'is_attachment',
        'attachment',
        'user_type',
    ];
}
