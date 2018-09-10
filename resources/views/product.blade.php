
@include('includes/header')
  <li>
    <a href="{{route('shop.show', $product->slug)}}">{{$product->name}}</a>
    <a href="{{route('shop.show', $product->slug)}}"><img src="{{ asset('img/products/'.$product->slug.'.jpg')}}" alt=""></a>
    <div class="nav-items">
      <p>{{$product->presentPrice()}}</p>
      <p>{{$product->details}}</p>
      <p>{{$product->description}}</p>
    </div>

    <div class="">
      <form action="{{route('cart.index')}}" method="post">
        @csrf
        <input type="text" hidden name="id" value="{{$product->id}}">
        <input type="text" hidden name="name" value="{{$product->name}}">
        <input type="text" hidden name="price" value="{{$product->price}}">
      <button type="submit" name="button">Add To Cart</button>
      </form>
    </div>

    <div class="">
      <p>&nbsp;</p>
      <b><p align="center"> You might also like</p></b>
      <ul>
        @foreach($mightAlsoLike as $product)
        <li>
          <a href="{{route('shop.show', $product->slug)}}">{{$product->name}}</a>
        </br>
          <a href="{{route('shop.show', $product->slug)}}"><img src="{{ asset('img/products/'.$product->slug.'.jpg')}}" alt=""></a>
          <div class="nav-items">
            <p>{{$product->presentPrice()}}</p>
            <p>{{$product->details}}</p>
            <p>{{$product->description}}</p>
          </div>
        </li>
        @endforeach

        </div>
      </ul>

    </div>
  </li>
