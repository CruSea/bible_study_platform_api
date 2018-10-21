<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BsYear extends Model
{
    //

    public function bible_studies()
    {
        return $this->hasMany('App\BsYear', 'year_id', 'id');
    }
}
