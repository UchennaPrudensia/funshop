
@extends('layouts/app')


@section('content')
<!-- include('includes/header') add with @ to render -->
<!-- Search -->
@component('components.breadcrumbs')
@endcomponent

<div class="container">
  @if (session()->has('success_message'))
  <div class="alert alert-succes">
    <p>
     {{ session()->get('success_message') }}
    </p>
  </div>
  @endif

  <!--  CHECK ERRORS-->
  @if(count($errors) > 0)
  <ul>
    @foreach($errors->all() as $error)
    <li>
      {{ $error }}
    </li>
    @endforeach

  </ul>
  @endif

</div>

<br>
  <!-- <body> -->
     <div class="">
       <p>&nbsp;</p>
       <h1>Home</h1>
       <p><b>Categories</b></p>
       <ol>
         @foreach($categories as $category)
            <a href="{{route('shop.index', ['category' => $category->slug])}}"><li>{{ $category->name }}</li></a>
         @endforeach
       </ol>

       <h2>
         <p>Products</p>
       </h2>



       <ul>
         @foreach($products as $product)
         <li>
           <a href="{{route('shop.show', $product->slug)}}">{{$product->name}}</a>
         </br>
           <a href="{{route('shop.show', $product->slug)}}"><img src="{{ asset('storage/'.$product->image)}}" alt=""></a>
           <div class="nav-items">
             <p>{{$product->presentPrice()}}</p>
             <p>{{$product->details}}</p>
             <p>{{ $product->description }}</p>
           </div>
         </li>
         @endforeach

         </div>
       </ul>

@endsection

@section('extra-js')
<!-- Include AlgoliaSearch JS Client and autocomplete.js library -->
@endsection

<!--
     </div>
  </body>
</html> -->
