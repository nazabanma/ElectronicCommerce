<?php

namespace app\api\model;

use think\Model;

class User extends Model
{
    public function shopCart()
    {
        return $this->hasMany('ShopCart', 'user_id');
    }

    public function orderItem()
    {
        return $this->hasManyThrough('OrderItem', 'Order', 'user_id', 'order_id');
    }

    public function book()
    {
        return $this->belongsToMany('Book', 'collect', 'user_id');
    }

    public function collect()
    {
        return $this->hasMany('Collect', 'user_id');
    }
}
