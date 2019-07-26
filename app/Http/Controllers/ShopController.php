<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;
use App\Category;
use Carbon\Carbon;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $categories = Category::all();
      $paginate = 9;


      if( request()->category)
      {
        $products = Product::with('categories')->whereHas('categories', function($query){
          $query->where('slug', request()->category);
        });

         $categoryName = optional(Category::where('slug', request()->category )->get()->first())->name;
       }

       else
       {
         $products = Product::where('featured', true);
         $categoryName = 'Featured';
       }

       if (request()->sort == 'low_high')
       {
         $products = $products->orderBy('price');
       }

       elseif (request()->sort == 'high_low')
       {
         $products = $products->orderBy('price', 'desc');
       }

       if (request()->sort == 'oldest')
       {
         $products = $products->orderBy('created_at')->paginate($paginate);
       }
       elseif (request()->sort == 'newest')
       {
         $products = $products->orderBy('created_at', 'desc')->paginate($paginate);
       }

       else
       {
        $products = $products->paginate($paginate);
       }

       return view('shop', compact('products', 'categoryName', 'categories'));

     }







      public function search(Request $request)
      {
        $request->validate([
          'query' => 'required|min:3',
        ]);

        $query = $request->input('query');

        // $products = Product::where('name', 'like', "%$query%")->orWhere('details', 'like', "%$query%")->orWhere('description', 'like', "%$query%")->orderBy('name')->paginate(10);

        $products = Product::search($query)->paginate(10);



        return view('search-results', compact('products'));

        //return view('search-results');
      }


    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::where('slug', '=', $slug)->firstorfail();
        $mightAlsoLike = Product::inRandomOrder()->take(6)->get();

        if( $product->quantity > setting('site.stock_threshold'))
        {
          $stockLevel = 'in stock';
        }
        elseif($product->quantity <= setting('site.stock_threshold') && $product->quantity > 0 )
        {
          $stockLevel = 'low stock';
        }
        else
        {
          $stockLevel = 'out of stock';
        }


        return view('product', compact('product', 'mightAlsoLike', 'stockLevel'));
    }


}
