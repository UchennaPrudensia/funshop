@include('includes/header')

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
    <a href="{{route('shop.show', $product->slug)}}"><img src="{{ asset('img/products/'.$product->slug.'.jpg')}}" alt=""></a>
    <div class="nav-items">
      <p>{{$product->presentPrice()}}</p>
      <p>{{$product->details}}</p>
      <p>{{$product->description}}</p>
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
