

@include('includes/header')


  <body>
     <div class="">
       <p>&nbsp;</p>
       <h1>Home</h1>
       <h2>Welcome to Codeletter</h2>
       <p><b>Categories</b></p>
       <ol>
         @foreach($categories as $category)
            <a href="{{route('shop.index', ['category' => $category->slug])}}"><li>{{ $category->name }}</li></a>
         @endforeach
       </ol>

       <h2>
         <p>Products</p>
       </h2>



       <ul>
         @foreach($products as $product)
         <li>
           <a href="{{route('shop.show', $product->slug)}}">{{$product->name}}</a>
         </br>
           <a href="{{route('shop.show', $product->slug)}}"><img src="{{ asset('img/products/'.$product->slug.'.jpg')}}" alt=""></a>
           <div class="nav-items">
             <p>{{$product->presentPrice()}}</p>
             <p>{{$product->details}}</p>
             <p>{{$product->description}}</p>
           </div>
         </li>
         @endforeach

         </div>
       </ul>


     </div>
  </body>
</html>
