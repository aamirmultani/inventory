<?php

namespace App\Models;

use App\User;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'address_id', 'product_id', 'payment_status', 'payment_method', 'total_amount', 'qty'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function category()
    {
        return $this->hasOneThrough(Category::class, Product::class, 'id', 'id', 'product_id', 'category_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
