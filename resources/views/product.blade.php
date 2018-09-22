
@include('includes/header')

   <div class="product-section">
     <a href="{{route('shop.show', $product->slug)}}">{{$product->name}}</a></br>

     <div>
        <div class="product-section-image">
          <a href="{{route('shop.show', $product->slug)}}">
            <!-- <img src="{{ asset('img/products/'.$product->slug.'.jpg')}}" alt=""> -->
            <img src="{{ productImage($product->image)}}" alt="product" class="active" id="currentImage">
          </a>
        </div>

          <div class="product-section-images">
            @if($product->images)

            <div class="product-section-thumbnail selected">
                <img src="{{ productImage($product->image)}}" alt="product">
            </div>

               <!-- <div class="product-section-thumbnail selected">
                 <img src="{{ asset('img/not-found.jpg')}}" alt="product">
               </div> -->
               @foreach(json_decode($product->images, true) as $image)
                 <div class="product-section-thumbnail selected">
                     <img src="{{ productImage($image)}}" alt="product">
                 </div>
                @endforeach



              <!-- @foreach(json_decode($product->images, true) as $image)
                <img src="{{ Voyager::image($image)}}" alt="product">
              @endforeach -->
             <!-- <hr> -->
            @endif
          </div>

          <p>{{$product->presentPrice()}}</p>
          <p>{{$product->details}}</p>
          <p>{!! $product->description !!}</p>
    </div>

    </div>



    <!-- ADD TO CART  -->
    <div class="">
      <form action="{{route('cart.index')}}" method="post">
        @csrf
        <input type="text" hidden name="id" value="{{$product->id}}">
        <input type="text" hidden name="name" value="{{$product->name}}">
        <input type="text" hidden name="price" value="{{$product->price}}">
      <button type="submit" name="button">Add To Cart</button>
      </form>
    </div>


    <!--  YOU MIGHT ALSO LIKE SECTION -->

    <div class="">
      <p>&nbsp;</p>
      <b><p align="center"> You might also like</p></b>
      <ul>
        @foreach($mightAlsoLike as $product)
        <li>
          <a href="{{route('shop.show', $product->slug)}}">{{$product->name}}</a>
        </br>
          <a href="{{route('shop.show', $product->slug)}}"><img src="{{ productImage($product->image)}}" alt=""></a>
          <div class="nav-items">
            <p>{{$product->presentPrice()}}</p>
            <p>{{$product->details}}</p>
            <p>{!! $product->description !!}</p>
          </div>
        </li>
        @endforeach

        </div>
      </ul>

    </div>


      <!-- Javascript for Images -->
    <script>
        (function(){
          const currentImage = document.querySelector('#currentImage');
          const images = document.querySelectorAll('.product-section-thumbnail')

          images.forEach((element)=>element.addEventListener('click', thumbnailClick));

          function thumbnailClick(e){
            currentImage.src = this.querySelector('img').src;

            // Todo - work on the active states and selected during break

            //currentImage.classList.remove('active');
            // images.forEach((element)=>element.classList.remove('selected');
            //this.classList.add('selected');
          }
        })();

    </script>
