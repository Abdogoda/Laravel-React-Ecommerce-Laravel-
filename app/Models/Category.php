<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['slug','meta_title', 'meta_description', 'meta_keywords', 'name','status','description'];

    public function products(){
        return $this->hasMany(Product::class, 'category_id');
    }
}