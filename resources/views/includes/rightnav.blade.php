

<div class="display-horizontal-right">
  @guest
    <a href="{{route('login')}}">Login</a>&nbsp
    <a href="{{route('register')}}">Register</a>&nbsp
  @else


      <a class="dropdown-item" href="{{ route('logout') }}"
         onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
          {{ __('Logout') }}
      </a>&nbsp

      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
      </form>

      <a href="{{route('cart.index')}}">
        @if(Cart::count() > 0)
          Cart ({{Cart::instance('default')->count()}})
        @endif
      </a>&nbsp

    @endguest
</div>
