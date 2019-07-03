<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table    = 'client';
    protected $fillable = [
        'id', 'user_id', 'name', 'email', 'phone', 'created_at', 'updated_at'
    ];
}
