@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/Services.css') }}">

    <!-- Info -->
    <section class="info mb-5">
        <div class="info-container">
            <div>
                <div class="display-2 p-3 text-center" style="font-family: 'Playfair Display', serif;">Top foods per category!</div>
                <div class="display-6 text-center">Are you not sure what to pick for your reservation?</div>
                <div class="display-6 text-center mb-5">Come and take a look at client's favorites</div>
                <div class="btn-pos"><a href="{{ url('/menus') }}"><button type="button" class="btn btn-primary btn-lg">Check Our Menu</button></a></div>
            </div>
        </div>
    </section>

    <div class="display-1 mb-5" style="font-family: 'Playfair Display', serif;">Most Ordered Menu</div>
    <section class="most-ordered-menu w-100 mb-5">
        @isset($result)
            @php
                $currentCategory = null;
            @endphp

            @foreach ($result as $menuItem)
                @if ($currentCategory !== $menuItem->category_name)
                    @if ($currentCategory !== null)
                        </ul>
                    @endif
                    <div class="display-3 m-5" style="border-bottom: solid; text-align: center;">{{ $menuItem->category_name }}:</div>
                    
                    <ul class="list-unstyled d-flex justify-content-around flex-wrap">
                @endif
                @if (isset($menuItem->menu_id))
                <li class="mb-3" data-bs-toggle="modal" data-bs-target="#menuModal{{ $menuItem->menu_id }}">
                    <div class="food-container">
                    <img src="{{ asset('images/menu/' . $menuItem->menus_image) }}" alt="{{ $menuItem->menu_name }}" class="img-fluid rounded">
                            <div class="food-name text-center">{{ $menuItem->menu_name }}</div>
                        </div>
                    </div>
                </li>
                @else
                            <li class="text-center">No menu available for this category</li>
                        @endif
                @php
                    $currentCategory = $menuItem->category_name;
                @endphp

                  <!-- Modal -->
                  <div class="modal fade" id="menuModal{{ $menuItem->menu_id }}" tabindex="-1" aria-labelledby="menuModalLabel{{ $menuItem->menu_id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="menuModalLabel{{ $menuItem->menu_id }}">{{ $menuItem->menu_name }}</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="modal-food-info">
                                    <img src="{{ asset('images/menu/' . $menuItem->menus_image) }}" alt="{{ $menuItem->menu_name }}" class="img-fluid rounded" style="width: 400px; height: 400px;">
                                    <div class="foodinfo">
                                        <h1 class="display-5 text-center fw-semibold">{{ $menuItem->menu_name }}</h1>
                                        <div class="info-pos">
                                            <p class="text-center fw-bolder">Price: ₱{{ $menuItem->price }}</p>
                                            <p class="text-center fw-bolder mb-5">Serving: {{ $menuItem->serving }}</p>
                                        </div>
                                        <p class="text-center" style="font-size: 20px;">
                                            {{ $menuItem->description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Additional modal footer content if needed -->
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($currentCategory === null)
                </ul>
            @endif
        @else
            <p>No most ordered menus available</p>
        @endisset
    </section>

    

@endsection
