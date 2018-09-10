@include('includes/header')

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

<!-- <h1>Thank you for shopping</h1> -->

<br>
<a href="{{ route('landing-page.index') }}">
<h2>Home</h2>
</a>
