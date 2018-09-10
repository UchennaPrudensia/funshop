@include('includes/header')
<h1> Cart </h1>
<a href="{{route('shop.index')}}">
<button type="button" name="button">Continue Shopping</button>
</a>

<!--  CHECK MESSAGE-->
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


<!--  CHECK ITEMS IN CART-->
@if (Cart::count() > 0 )

<p><b>{{Cart::count()}}</b> items in your cart</p>

@foreach(Cart::content() as $item)
<a href="{{route('shop.show', $item->model->slug)}}">{{$item->model->name}}</a>
<br>
<a href="{{route('shop.show', $item->model->slug)}}"><img src="{{ asset('img/products/'.$item->model->slug.'.jpg')}}" alt=""></a>
<br>


<!--  Quantity -->
<select class="quantity"  data-set="{{ $item->rowId }}">
  @for($i=1; $i < 5 + 1; $i++)
    <option {{ $item->qty == $i ? 'selected' : ''}} >{{$i}}</option>
  @endfor



</select>

<p>Price: {{presentPrice($item->subtotal)}}</p>

<!-- DELETE FROM CART -->

<form action="{{route('cart.destroy', $item->rowId)}}" method="POST">
  @method('DELETE')
  @csrf

<button type="submit" name="button">Remove</button>
</form>

<!--  Add To Save For Later -->

  <form action="{{route('cart.saveForLater', $item->rowId)}}" method="POST">
    @csrf

  <button type="submit" name="button" >Save For Later</button>
  </form>

<hr>
@endforeach

<!--  Calculation  Total Price-->

<hr>
<p align="center">&nbsp;
<b>Calculation</b>
</p>

<hr>
<p>Subtotal: {{ presentPrice(Cart::subtotal()) }}</p>

<p>Tax: {{ presentPrice(Cart::tax())}}</p>
<p>Total: {{ presentPrice(Cart::total())}}</p>

<p align="center">
 <a href="{{route('checkout.index')}}">
    <button  type="button" name="button">Checkout</button>
</a>
</p>

@else
<p> 0 items in your cart</p>
@endif



<!-- Save For Later -->
<hr>
<p align="center">&nbsp;
<b>Save For Later Items</b>
</p>
@if (!empty(Cart::instance('saveForLater')->count()) )
<p><b>{{Cart::instance('saveForLater')->count()}}</b> items in save for later</p>
@foreach(Cart::instance('saveForLater')->content() as $item)

    <a href="{{route('shop.show', $item->model->slug)}}">{{$item->model->name}}</a>
    <br>
    <a href="{{route('shop.show', $item->model->slug)}}"><img src="{{ asset('img/products/'.$item->model->slug.'.jpg')}}" alt=""></a>
    <br>

    <p>Price: {{presentPrice($item->model->price)}}</p>

    <form action="{{ route('saveforlater.destroy' , $item->rowId) }}" method="POST">
      @method('DELETE')
      @csrf
    <button type="submit" name="button">Remove</button>
    </form>

      <form action="{{route('saveforlater.movetocart', $item->rowId)}}" method="POST">
        @csrf
      <button type="submit" name="button" >move to cart</button>
      </form>

<p>&nbsp;</p>

@endforeach

<hr>
@else
<p>No items in save for later</p>

@endif

<script src="{{ asset('js/app.js')}}"></script>

<script>
  (function(){

    const classname = document.querySelectorAll('.quantity');


    Array.from(classname).forEach(function(element){
      element.addEventListener('change', function() {
        const id = element.getAttribute('data-set');

          axios.patch(`/cart/${id}`, {
           quantity: this.value,
            // console.log(response);

          })
          .then(function (response) {
            window.location.href = '{{ route('cart.index')}}';

          })
          .catch(function (error) {
            console.log(error);
            window.location.href = '{{ route('cart.index')}}';
          });
        });

      });

  })();
</script>
