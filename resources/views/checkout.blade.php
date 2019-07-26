
@include('includes/header')

<!-- Search -->
@component('components.breadcrumbs')
@endcomponent


<!-- @include('includes/header') -->

<!-- <script src="https://www.paypal.com/sdk/js?client-id=AVt90hwtCL3b-oOawT7wKUX0jOtUL8U6kJ5Isk6IhDRSUP-4IrLMGh62wmbTq_ykapu7eNOf_KDsnEbs"></script> -->

<h1>Checkout</h1>


<!--  CHECK ERRORS-->
@if(count($errors) > 0)
<ul>
  @foreach($errors->all() as $error)
  <li>
    <p style="color:red">{!! $error !!}</p>
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


<!-- Paypal-payment Form  -->
  <form method="post" id="paypal-payment-form" action="{{route('checkout.paypal')}}">
  @csrf
    <section>
        <div id="paypal-container"></div>
        <!-- <div id="paypal-button-container"></div> -->

    </section>
    <input id="nonce" name="payment_method_nonce" type="hidden" />
    <input name="streetAddress" id="streetAddress" type="hidden" />
    <input name="firstName" id="firstName" type="hidden" />
    <input name="lastName" id="lastName" type="hidden" />
    <input name="region"    id="region" type="hidden"/>
    <input name="locality"   id="locality" type="hidden"/>
    <input name="postalCode" id="postalCode" type="hidden"/>
    <button class="btn btn-primary" type="submit"><span>Confirm and Paypal</span></button>
  </form>




  <script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script>
  <script>
  //Braintree Paypal Starts Here
      const form = document.querySelector('#paypal-payment-form');
      const client_token = "{{$paypalToken}}";


   //START HERE V2
      braintree.setup(client_token, 'custom', {
        paypal: {
          container: 'paypal-container',
          singleUse: true, // Required
          amount: 10.00, // Required
          currency: 'USD', // Required
          locale: 'en_US',
          enableShippingAddress: true,
          shippingAddressOverride: {
            recipientName: '',
            streetAddress: '',
            extendedAddress: '',
            locality: '',
            countryCodeAlpha2: '',
            postalCode: '',
            region: '',
            phone: '',
           editable: false
         }
        },
        onPaymentMethodReceived: function (obj) {
         console.log(obj);
         console.log(obj.details.shippingAddress);
          form.addEventListener('submit', function (event) {
            event.preventDefault();
              //Add the nonce to the form and submit
              document.querySelector('#nonce').value = obj.nonce;
              document.querySelector('#streetAddress').value = obj.details.shippingAddress.streetAddress;
              document.querySelector('#firstName').value = obj.details.firstName;
              document.querySelector('#lastName').value = obj.details.lastName;
              document.querySelector('#region').value = obj.details.shippingAddress.region;
              document.querySelector('#postalCode').value = obj.details.shippingAddress.postalCode;
              document.querySelector('#locality').value = obj.details.shippingAddress.locality;
            form.submit();
        })
      },
    })
  // </script>
