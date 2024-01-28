<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = ['slug','name','original_price','selling_price','meta_title','meta_description','meta_keywords','brand','featured','popular','stauts','qty'];

    protected $with = ['category'];
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
}