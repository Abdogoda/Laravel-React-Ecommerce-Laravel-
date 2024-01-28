<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = ['user_id',"first_name","last_name",'email','phone','address','city','state','zipcode','payment_id','payment_mode','tracking_no','remark'];

    protected $with = ['user', 'orderItems'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}