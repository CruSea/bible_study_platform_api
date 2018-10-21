<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BibleStudy extends Model
{
    //
    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    public function year()
    {
        return $this->hasOne('App\BsYear', 'id', 'year_id');
    }
}
