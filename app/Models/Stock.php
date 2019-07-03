<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table    = 'stok';
    protected $fillable = [
        'id', 'user_id', 'product_id', 'stok', 'created_at', 'updated_at'
    ];
}
