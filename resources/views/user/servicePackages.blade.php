@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">

    <div class="pre-made">
        <h1 class="display-1 text-center title " style="font-family: 'Playfair Display', serif;">Promos</h1>
        <p>Choose a package that you desire</p>
        <ul>
        @foreach ($promos as $promo)
          <a href="{{ route('user.reservations.premade', ['packageId' => $promo->id]) }}">
            <li >
              <h1 class="display-4 text-center">{{ $promo->name }} </h1>
              <img src="{{  asset('images/services/packages/' . $promo->service_pckg_image) }}" class="img-fluid" alt="Package 1">
            </li>
          </a>
          @endforeach
          <a href="{{ route('user.reservations.custom_package') }}">
            <li >
              <h1 class="display-4 text-center" style="font-family: 'Playfair Display', serif;">Customize base on your budget </h1>
            </li>
          </a>
          <a href="{{ route('most-ordered-menu', ['category' => $categoryName]) }}">
            <li >
              <h1 class="display-4 text-center"  style="font-family: 'Playfair Display', serif;">Most Ordered Menu</h1>
              
            </li>
          </a>
        </ul>
      </div>
@endsection
