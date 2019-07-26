<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderProduct;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Exception\CardErrorException;
use App\Http\Requests\CheckoutRequest;
use Sample\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $gateway = new \Braintree\Gateway([
        'environment' => config('services.braintree.environment'),
        'merchantId' => config('services.braintree.merchantId'),
        'publicKey' => config('services.braintree.publicKey'),
        'privateKey' => config('services.braintree.privateKey')
        ]);

        $paypalToken = $gateway->ClientToken()->generate();


        return view('checkout', with([
                        'paypalToken' => $paypalToken,
                        'discount' => $this->getNumbers()->get('discount'),
                        'newTax' => $this->getNumbers()->get('newTax'),
                        'newSubtotal' => $this->getNumbers()->get('newSubtotal'),
                        'newTotal' => $this->getNumbers()->get('newTotal'),
                        'tax' => $this->getNumbers()->get('tax'),
                      ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutRequest $request)
    {
        try
        {
          $contents = Cart::content()->map(function($item){
            return $item->model->slug.','.$item->qty;
          })->values()->toJSON();

          $charge = Stripe::charges()->create([
            'amount' => $this->getNumbers()->get('newTotal') / 100,
            'currency' => 'USD',
            'source' => $request->stripeToken,
            'description' => 'Order',
            'receipt_email' => $request->email,
            'metadata' => [
              'contents' => $contents,
              'quantity'  => Cart::instance('default')->count(),
              'discount' => collect([session()->get('coupon')])->toJson(),

            ],
          ]);

          $order = $this->addToOrdersTable($request, null);

         //Send Email
          Mail::send(new OrderPlaced($order));

          //SUCCESSFULL

          Cart::instance('default')->destroy();
          session()->forget('coupon');

          return redirect()->route('thankyou.index')->with('success_message', 'Thank you, your payment was successfully accepted and a confirmation email was sent');
        }

        catch (CardErrorException $e)
        {
           $this->addToOrdersTable($request, $e->getMessage());
           return back()->withErrors('Error! '. $e->getMessage());

        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paypalCheckout(Request $request)
    {

      $gateway = new \Braintree\Gateway([
          'environment' => config('services.braintree.environment'),
          'merchantId' => config('services.braintree.merchantId'),
          'publicKey' => config('services.braintree.publicKey'),
          'privateKey' => config('services.braintree.privateKey')
          ]);


          $nonce =   $request->get('payment_method_nonce');
          $address = $request->get('streetAddress');
          $city =    $request->get('region');
          $state =   $request->get('locality');
          $postal_code = $request->get('postalCode');
          $firstName = $request->get('firstName');
          $lastName = $request->get('lastName');




          $result = $gateway->transaction()->sale([
              'amount' => round($this->getNumbers()->get('newTotal') / 100, 2),
              'merchantAccountId' => 'ifyolo',
              // 'serviceFeeAmount' => "3.00",
              'shippingAmount' => '13',
              'paymentMethodNonce' => $nonce,
              // 'customer' => [
              //   'company' => 'ifYolo',
              //   'email' => 'uchemp@ruby.com',
              //   'firstName' => 'uche',
              //   'lastName' => 'mb',
              //   'id' => 'zxy45'
              // ],
              'shipping' => [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'streetAddress' => $address,
                'region'        => $state,
                'locality'      => $city,
                'postalCode'    => $postal_code,
              ],
              'options' => [
                  'submitForSettlement' => true,
              ]
          ]);

          //$address = $gateway->address()->find('a_customer_id', 'an_address_id');

          $transaction = $result->transaction;
          if ($result->success) {
           //dd($result);

            //Add to orders table
            $order = $this->addToOrdersTablePaypal($transaction->paypal['payerEmail'], $transaction->paypal['payerFirstName'].' '.$transaction->paypal['payerLastName'], $address, $city, $state, $postal_code, null);

            //Send Email
             Mail::send(new OrderPlaced($order));

             //SUCCESSFULL

             Cart::instance('default')->destroy();
             session()->forget('coupon');



              return redirect()->route('thankyou.index')->with('success_message', 'Thank you, your payment was successfully accepted and a confirmation email was sent');
          } else {
            //dd($result->message);
              $order = $this->addToOrdersTablePaypal($transaction->paypal['payerEmail'], $transaction->paypal['payerFirstName'].' '.$transaction->paypal['payerLastName'], $address, $city, $state, $postal_code, $result->message);
              return back()->with('error', 'Error message: ' . $result->message);
          }

    }


    public function addToOrdersTable($request, $error)
    {
      // insert into orders table.
      $order = Order::create([
        'user_id'  => auth()->user() ? auth()->user()->id : null ,
        'billing_email'  => $request->email ,
        'billing_name'  => $request->name,
        'billing_address' => $request->address,
        'billing_city'  => $request->city,
        'billing_state'  => $request->state,
        'billing_zipcode' => $request->zipcode,
        'billing_phone'  => $request->phone,
        'billing_name_on_card'  => $request->name_on_card,
        'billing_discount'  => $this->getNumbers()->get('discount') ,
        'billing_discount_code'  => $this->getNumbers()->get('code'),
        'billing_subtotal'  => $this->getNumbers()->get('newSubtotal'),
        'billing_tax'  => $this->getNumbers()->get('newTax'),
        'billing_total'  => $this->getNumbers()->get('newTotal'),
        'error'  => $error,

      ]);

      //insert into order_product table.

       foreach(Cart::content() as $item)
       {

         OrderProduct::create([
           'order_id' => $order->id,
           'quantity' => $item->qty,
           'product_id' => $item->model->id,
         ]);
       }

       return $order;


    }

    public function addToOrdersTablePaypal($email, $name, $address, $city, $state, $zipcode, $error)
    {
      // insert into orders table.
      $order = Order::create([
        'user_id'  => auth()->user() ? auth()->user()->id : null ,
        'billing_email'  => $email,
        'billing_name'  => $name,
        'billing_address' => $address,
        'billing_city'  => $city,
        'billing_state'  => $state,
        'billing_zipcode' => $zipcode,
        // 'billing_phone'  => $request->phone,
        // 'billing_name_on_card'  => $request->name_on_card,
        'billing_discount'  => $this->getNumbers()->get('discount') ,
        'billing_discount_code'  => $this->getNumbers()->get('code'),
        'billing_subtotal'  => $this->getNumbers()->get('newSubtotal'),
        'billing_tax'  => $this->getNumbers()->get('newTax'),
        'billing_total'  => $this->getNumbers()->get('newTotal'),
        'error'  => $error,

      ]);

      //insert into order_product table.

       foreach(Cart::content() as $item)
       {

         OrderProduct::create([
           'order_id' => $order->id,
           'quantity' => $item->qty,
           'product_id' => $item->model->id,
         ]);
       }

       return $order;


    }


    private function getNumbers()
    {

      $discount = session()->get('coupon')['discount'] ?? 0;
      $tax = config('cart.tax') / 100;
      $newSubtotal = (Cart::subtotal() - $discount);

      if($newSubtotal < 0)
      {
        $newSubtotal = 0;
      }

      $code = session()->get('coupon')['name'] ?? null;
      $newTax = $newSubtotal * $tax;
      //$newTotal = $newSubtotal * (1 + $tax);
      $newTotal = $newSubtotal + $newTax;

      return collect([
        'discount' => $discount,
        'tax' => $tax,
        'newSubtotal' => $newSubtotal,
        'code' => $code,
        'newTax' => $newTax,
        'newTotal' => $newTotal
      ]);
    }
}
