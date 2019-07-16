<?php

namespace app\api\model;

use think\Model;

class ShopCart extends Model
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
}
