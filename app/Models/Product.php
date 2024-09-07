<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'quantity', 'category_id'];
    protected $table = 'product';

    public function category()
    {
        return $this->belongsTo(Category::class);   
    }
}
