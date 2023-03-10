<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_methods_id',
        'amount',
        'amount',
        'transactionid',
        'description',
        'image',
        'status',
        'nameoncard',
        'email',
        'country',
        'company',
        'address',
        'website',
        'phone',
    ];
}
