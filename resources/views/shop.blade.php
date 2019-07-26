@extends('layouts/app')




@section('content')

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


@component('components.breadcrumbs')
@endcomponent

<br>

<p><b>Categories</b></p>
<ol>
  @foreach($categories as $category)
     <li id="{{ request()->category == $category->slug ? 'active' : '' }}"><a href="{{route('shop.index', ['category' => $category->slug])}}">{{ $category->name }}</a></li>
  @endforeach
</ol>

<h1>
<p>{{$categoryName}}</p>
</h1>
<div class="">
  <div style="text-align:center">
    <p><strong>Price: </strong>
      <a href="{{ route('shop.index', ['category' => request()->category, 'sort' => 'low_high'])}}">
        Low to High |
      </a>
       <a href="{{ route('shop.index', ['category' => request()->category, 'sort' => 'high_low'])}}">
         High to Low
       </a></p>
  </div>

  <div style="text-align:center">
    <p><strong>Time: </strong>
      <a href="{{ route('shop.index', ['category' => request()->category, 'sort' => 'oldest'])}}">
        Oldest |
      </a>
       <a href="{{ route('shop.index', ['category' => request()->category, 'sort' => 'newest'])}}">
         Newest
       </a></p>
  </div>

</div>

<ul>
  @forelse($products as $product)
  <li>
    <a href="{{route('shop.show', $product->slug)}}">{{$product->name}}</a>
  </br>
    <a href="{{route('shop.show', $product->slug)}}"><img src="{{ asset('storage/'.$product->image)}}" alt=""></a>
    <div class="nav-items">
      <p>{{$product->presentPrice()}}</p>
      <p>{{$product->details}}</p>
      <!-- <p>{!! $product->description !!}</p> -->
      <p><strong>Time : </strong> {{$product->created_at}}</p>
    </div>
  </li>
  @empty
   <div style="text-align:left">
     <p>Not Available yet!</p>
   </div>
  @endforelse

  <nav style="text-align:center">

    <!-- <p>{{ $products->links()}}</p> -->

    <p>{{ $products->appends(request()->input())->links() }}</p>
  </nav>



  </div>
</ul>
@endsection

@section('extra-js')
<!-- Include AlgoliaSearch JS Client and autocomplete.js library -->
<script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
<script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
<script src="{{asset('js/algolia.js')}}"></script>
@endsection
