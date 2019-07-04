<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table    = 'sale';
    protected $fillable = [
        'id', 'user_id', 'client_id', 'total_value', 'created_at', 'updated_at'
    ];
}
