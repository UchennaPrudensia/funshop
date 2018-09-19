<!-- <div class="">
 <a href="{{route('landing-page.index')}}">Home</a>
  <a href="{{route('shop.index')}}">Shop</a>
  <a href="{{route('cart.index')}}">Cart
  @if(Cart::count() > 0)
  ({{Cart::instance('default')->count()}})
  @endif
  </a>
</div> -->


<ul>
    @foreach($items as $menu_item)
        <li class="display-horizontal">
          <a href="{{ $menu_item->link() }}">
            {{ $menu_item->title }}

            @if($menu_item->title == 'Cart')
              @if(Cart::count() > 0)
                ({{Cart::instance('default')->count()}})
              @endif
            @endif

          </a>
        </li>


    @endforeach
</ul>
