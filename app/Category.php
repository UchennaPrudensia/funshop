<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
   protected $table = 'category'; // this is changed due to naming collisons with voyager Categories Table
  //relationship between category and product
    public function products()
    {
      return $this->belongsToMany('App\Product');
    }
}
