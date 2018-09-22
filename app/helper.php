
<?php

 function presentPrice($price)
 {
   //return money_format('$%i', $price/100);

   return money_format('$%i' , $price / 100);
 }

function productImage($path)
{
  return ($path && file_exists('storage/'.$path)) ? asset('storage/'.$path) : asset('img/not-found.jpg');

}
