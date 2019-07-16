<?php

namespace app\api\model;

use think\Model;

class Book extends Model
{

    public function bookType()
    {
        return $this->hasOne("BookType", 'book_type_id');
    }

    public function collect()
    {
        return $this->hasMany("Collect", "book_id");
    }
}
