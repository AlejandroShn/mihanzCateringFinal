@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/themes.css') }}">

    <main>
        @foreach ($serviceSelections as $serviceSelection)
            <section class="p-5" style="margin-bottom:100px">
                <h1 class="display-1 text-center" style="font-family: 'Playfair Display', serif;">{{ $serviceSelection->services_category }}</h1>
                <div id="carousel{{ ucfirst($serviceSelection->services_category) }}" class="carousel carousel-dark slide">
                    <div class="carousel-indicators">
                        @foreach ($serviceSelection->themeSelections as $key => $themeItem)
                            <button type="button" data-bs-target="#carousel{{ ucfirst($serviceSelection->services_category) }}" data-bs-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}" aria-current="{{ $key === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $key + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach ($serviceSelection->themeSelections as $key => $themeItem)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}" data-bs-interval="10000">
                                <img src="{{ $themeItem->theme_image }}" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <p>{{ $themeItem->theme_name }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{ ucfirst($serviceSelection->services_category) }}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousel{{ ucfirst($serviceSelection->services_category) }}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </section>
        @endforeach
    </main>
@endsection
