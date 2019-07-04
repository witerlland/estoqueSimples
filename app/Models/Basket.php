<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    protected $table    = 'basket';
    protected $fillable = [
        'id', 'sale_id', 'product_id', 'amount', 'total_value', 'created_at', 'updated_at'
    ];
}
