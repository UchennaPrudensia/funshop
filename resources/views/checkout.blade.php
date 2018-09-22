@include('includes/header')
<script src="https://js.stripe.com/v3/">

</script>



<h1>Checkout</h1>

<!--  CHECK ERRORS-->
@if(count($errors) > 0)
<ul>
  @foreach($errors->all() as $error)
  <li>
    <p style="color:red">{{ $error }}</p>
  </li>
  @endforeach
</ul>
@endif

@if(session()->has('success_message'))
 <p>{{ session()->get('success_message')}}</p>
@endif

<div align="center">
  <p><b>Your Order</b></p>

  @foreach(Cart::content() as $item)
      <a href="{{route('shop.show', $item->model->slug)}}">{{$item->model->name}}</a>
      <br>
      <a href="{{route('shop.show', $item->model->slug)}}"><img src="{{ asset('storage/'.$item->model->image)}}" alt=""></a>
      <br>
      <!-- <p>Quantity:{{ $item->qty}} </p>
      <br> -->
      <p>Price: {{presentPrice($item->model->price)}}</p>

  @endforeach
  <hr>

      <p>Subtotal: {{ presentPrice(Cart::subtotal()) }}</p>
      <p>
        @if(session()->has('coupon'))
            Discounts ({{session()->get('coupon')['name']}}) : -{{presentPrice($discount)}}
            <form class="" action="{{route('coupon.destroy')}}" method="post" style="display:inline">
              @csrf
              {{method_field('delete')}}
              <button type="submit" style="font-size:9px">Remove Discount </button>
            </form>
            <hr>
            New Subtotal: {{ presentPrice($newSubtotal) }}
            <hr>
        @endif
      </p>
      <p>Tax(10%): {{ presentPrice($newTax)}}</p>
      <p>Total: {{ presentPrice($newTotal)}}</p>

      @if(!session()->has('coupon'))
        <form class="" action="{{ route('coupon.store')}}" method="post">
          @csrf
          <input type="text" name="coupon_code" value="" placeholder="Enter Coupon Code">
          <button type="submit" name="submit">Submit</button>
        </form>
      @endif
  <hr>
</div>

<h3>Billing Details</h3>

<form action="{{route('checkout.store')}}"  id="payment-form" method="POST">
  @csrf
  <input type="email" name="email" value="{{ old('email')}}" placeholder="Email Address" required><br>
  <input type="text" name="name" value="{{ old('name')}}" placeholder="Name" required><br>
  <input type="text" id="address" name="address" value="{{ old('address')}}" placeholder="Address" required><br>
  <input type="text" id="city" name="city" value="{{ old('city')}}" placeholder="City" required><br>
  <input type="text" id="state" name="state" value="{{ old('state')}}" placeholder="State" required><br>
  <input type="text" id="zipcode" name="zipcode" value="{{ old('zipcode')}}" placeholder="Zip Code" required><br>
  <input type="phone" name="phone" value="{{ old('phone')}}" placeholder="Phone" required><br>


  <p></p>
  <label for="">Payment Details</label><br>
  <input type="text" id="name_on_card" name="name_on_card" value="" placeholder="Name on Card"><br>

  <div class="form-row">
    <label for="card-element">
      Credit or debit card
    </label>
    <div id="card-element">
      <!-- A Stripe Element will be inserted here. -->
    </div>

    <!-- Used to display form errors. -->
    <div id="card-errors" role="alert"></div>
  </div>

  <button class="submitbutton" type="submit" id="complete-order" name="button" >Submit Payment</button>
</form>

<script>

  (function(){
    // Create a Stripe client.
var stripe = Stripe('pk_test_0kZM9zZYjlXDjW3TJhsK40EX');

// Create an instance of Elements.
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
 base: {
   color: '#32325d',
   lineHeight: '18px',
   fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
   fontSmoothing: 'antialiased',
   fontSize: '16px',
   '::placeholder': {
     color: '#aab7c4'
   }
 },
 invalid: {
   color: '#fa755a',
   iconColor: '#fa755a'
 }
};

// Create an instance of the card Element.
var card = elements.create('card', {
  style: style,
  hidePostalCode: true


});

// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');

// Handle real-time validation errors from the card Element.
card.addEventListener('change', function(event) {
 var displayError = document.getElementById('card-errors');
 if (event.error) {
   displayError.textContent = event.error.message;
 } else {
   displayError.textContent = '';
 }
});

// Handle form submission.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
 event.preventDefault();

 //Disable the submit button to prevent repeated clicks.
 document.getElementById('complete-order').disabled = true;

 var options = {
   name: document.getElementById('name_on_card').value,
   address_line1: document.getElementById('address').value,
   address_city: document.getElementById('city').value,
   address_state: document.getElementById('state').value,
   address_zip: document.getElementById('zipcode').value
 };

 stripe.createToken(card, options).then(function(result) {
   if (result.error) {
     // Inform the user if there was an error.
     var errorElement = document.getElementById('card-errors');
     errorElement.textContent = result.error.message;
     document.getElementById('complete-order').disabled = false;
   } else {
     // Send the token to your server.
     stripeTokenHandler(result.token);
   }
 });
});

  function stripeTokenHandler(token) {
  // Insert the token ID into the form so it gets submitted to the server
  var form = document.getElementById('payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form
  form.submit();
  }


  })();

</script>
