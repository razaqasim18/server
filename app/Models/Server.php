<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;
    protected $fillable = [
        "data_center_id",
        "user_id",
        "ticket_id",
        "package_id",
        "server_ip",
        "sale_price",
        "server_cost",
        "setup_cost",
        "web_user",
        "web_password",
        "uuid",
        "client_user",
        "client_password",
        "expired_at",
        "is_expired",
    ];
}
