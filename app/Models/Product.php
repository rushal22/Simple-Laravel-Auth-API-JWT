<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'title', 
        'price', 
        'description', 
        'discountpercentage', 
        'rating', 
        'quantity', 
        'brand', 
        'category',
        'image'
    ];

    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class, 'cart_product', 'product_id', 'cart_id')
        ->withPivot('quantity')
        ->withTimestamps();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
}
