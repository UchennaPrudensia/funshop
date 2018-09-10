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

         $categoryName = optional(Category::where('slug', request()->category)->get()->first())->name;
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
    public function store(Request $request)
    {
        //
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
        return view('product', compact('product', 'mightAlsoLike'));
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
}
