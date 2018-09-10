<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>FunShop</title>
    <link rel="stylesheet" href="{{asset('css/payment.css')}}">
    <link rel="stylesheet" href="{{asset('css/checkout.css')}}">
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
  </head>
  <header>
     <div class="">
      <a href="{{route('landing-page.index')}}">Home</a>
       <a href="{{route('shop.index')}}">Shop</a>
       <a href="{{route('cart.index')}}">Cart
       @if(Cart::count() > 0)
       ({{Cart::instance('default')->count()}})
       @endif
       </a>
     </div>


  </header>
