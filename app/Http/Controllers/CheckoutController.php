<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Exception\CardErrorException;
use App\Http\Requests\CheckoutRequest;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        return view('checkout', with([
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

          //SUCCESSFULL

          //return back()->with('success_message', 'Thannk you, your payment was successfully accepted');

          Cart::instance('default')->destroy();
          session()->forget('coupon');

          return redirect()->route('thankyou.index')->with('success_message', 'Thank you, your payment was successfully accepted');
        }

        catch (CardErrorException $e)
        {
           return back()->withErrors('Error! '. $e->getMessage());

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getNumbers()
    {
      $discount = session()->get('coupon')['discount'] ?? 0;
      $tax = config('cart.tax') / 100;
      $newSubtotal = (Cart::subtotal() - $discount);
      $newTax = $newSubtotal * $tax;
      //$newTotal = $newSubtotal * (1 + $tax);
      $newTotal = $newSubtotal + $newTax;

      return collect([
        'discount' => $discount,
        'tax' => $tax,
        'newSubtotal' => $newSubtotal,
        'newTax' => $newTax,
        'newTotal' => $newTotal
      ]);
    }
}
