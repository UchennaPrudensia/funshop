
@extends('layouts.app')

@section('extra-css')
@endsection



@section('content')
  @component('components.breadcrumbs')
    <a href="/">Home</a>
    <i class="fa fa-chevron-right breadcrumb-separator"></i>
    <span>Search</span>
  @endcomponent

  <div class="container">
    @if (session()->has('success_message'))
    <div class="alert alert-succes">
      <p>{{ session()->get('success_message') }}</p>
      <!-- session message returns false -->
      <!-- what does session message do. -->
    </div>
    @endif

    <!--  CHECK ERRORS-->
    @if(count($errors) > 0)
      <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    @endif

  </div>


  <br>

  <div class="search-container container">

 <h1>Search Results </h1>

   <p> {{$products->total()}} result's for '{{ request()->input('query')}}'</p>
   <br>
  <table class="table table-striped">
   <thead>
     <tr>
       <th scope="col">Product Name</th>
       <th scope="col">Product Details</th>
       <th scope="col">Product Description</th>
       <th scope="col">Product Price</th>
     </tr>
   </thead>

  <div class="container">
    <tbody>

      @foreach($products as $product)
        <tr>
          <!-- <th scope="row">{{$product->count}}</th> -->
          <td><a href="{{route('shop.show', $product->slug)}}">{{$product->name}}</td>
          <td>{{$product->details}}</td>
          <td>{{ str_limit($product->description, 80)}}</td>
          <td>{{presentPrice($product->price)}}</td>
        </tr>
      @endforeach

    </tbody>

  </div>

 </table>
</div>

<nav class="navbar navbar-default">
  <!-- <p>{{ $products->links()}}</p> -->
  <p>{{ $products->appends(request()->input())->links() }}</p>
</nav>
@endsection
