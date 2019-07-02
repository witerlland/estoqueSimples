<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table    = 'product';
    protected $fillable = [
        'id', 'user_id', 'name', 'description', 'value', 'brand', 'created_at', 'updated_at'
    ];
}
