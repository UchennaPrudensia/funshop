<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Nicolaslopezj\Searchable\SearchableTrait;


class Product extends Model
{
  //use Searchable;
  use SearchableTrait;


  //
  // public function searchableAs()
  //  {
  //      return 'prod_Name';
  //  }


    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'products.name' => 10,
            'products.details' => 5,
            'products.description' => 5,
        ],

    ];







   public function presentPrice()
   {
     return money_format('$%i', ($this->price)/100);
   }

   public function categories()
   {
     return $this->belongsToMany('App\Category');
   }
}
