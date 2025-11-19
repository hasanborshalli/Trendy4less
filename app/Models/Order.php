<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
    'order_number',
    'customer_name',
    'customer_phone',
    'customer_address',
    'status',
    'total_amount',
    'notes',
    'items_json',
];


    // You can later add relationships if you want (e.g. to User or Products)
}