<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //

    public function bible_studies()
    {
        return $this->hasMany('App\BibleStudy', 'category_id', 'id');
    }
}
