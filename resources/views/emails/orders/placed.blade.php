
@component('mail::message')

# Order Received

Thank you for your Order.

**Order ID:** {{ $order->id }}<br>
**Order Name:** {{ $order->billing_name }}<br>
**Order Email:** {{ $order->billing_email }}<br>
**Order Total:** ${{ round($order->billing_total/100, 2) }}<br>

**Items Ordered**<br>
@foreach($order->products as $product)
**Name:** {{ $product->name }}<br>
**Price:** ${{ round($product->price/100, 2)}}<br>
**Quantity:** {{ $product->pivot->quantity}}<br>
@endforeach

You can get further details about your order by logging into our website.

@component('mail::button', ['color'=>'green', 'url' => config('app.url')])
Go To Website
@endcomponent

Thank you fo choosing us,

Regard,<br>
{{ config('app.name')}}
@endcomponent
