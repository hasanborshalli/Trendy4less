<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
      use HasFactory;

    protected $fillable = [
    'category_id',
    'name',
    'slug',
    'description',
    'price',
    'sale_price',
    'image_path',
    'is_active',
];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function colors()
{
    return $this->hasMany(ProductColor::class);
}
public function getIsOnSaleAttribute(): bool
{
    return $this->sale_price !== null
        && $this->sale_price > 0
        && $this->sale_price < $this->price;
}

public function getEffectivePriceAttribute(): float
{
    return $this->is_on_sale ? $this->sale_price : $this->price;
}


}