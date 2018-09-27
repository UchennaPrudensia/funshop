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

    @if(!(request()->is('checkout') || request()->is('guestcheckout')))

     {{ menu('main', 'includes.menus.main') }}
     @include('includes/rightnav')

    @endif


  </header>
