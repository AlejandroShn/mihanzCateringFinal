@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/Services.css') }}">

<div class="premade-form">

      </div>
      <div class="form-container">

        <h1 class="display-1 text-center mb-5 fw-bolder" style=" font-family: 'Playfair Display', serif;">Summary</h1>
        <table>
          <tr>
            <td class="fw-bolder"> Name:</td>

            <td>
            {{ $reservations->user->first_name . ' ' . $reservations->user->last_name }}
            </td>

            <td class="fw-bolder"> Email:</td>

            <td>
            {{$reservations->user->email}}
            </td>

          </tr>


            <tr>
              <td class="fw-bolder">Date of Event:</td>
            <td>
            {{$reservations->event_date}}
            </td>
            <td class="fw-bolder"> Time of Event:</td>
            <td>
            {{$reservations->event_time}}
            </td>

          </tr>
          <tr>
            <td class="fw-bolder"> Event Location:</td>
            <td>{{$reservations->venue_address}}</td>
            <td class="fw-bold">Contact No.:</td>
            <td>{{$reservations->user->contact_number}}</td>

          </tr>


        </table>
        <hr>
        <h1 class="text-start mb-5" style=" font-family: 'Playfair Display', serif;">Package Information</h1>
        <table>
          <tr>
            <td class="fw-bolder"> Event:</td>

            <td>
        {{ $reservations->premades->servicePackage->serviceSelection->services_category }}

            </td>

            <td class="fw-bolder"> Package type:</td>

            <td>
            @if ($reservations->premades && $reservations->premades->servicePackage)
        {{ $reservations->premades->servicePackage->name }}
    @else
        N/A
    @endif
            </td>

          </tr>


            <tr>
              <td class="fw-bolder"> Pax:</td>
            <td>
            {{ $reservations->premades->servicePackage->pax }}
            </td>
            <td class="fw-bolder"> Price:</td>
            <td>
            {{ $reservations->premades->servicePackage->price }}
            </td>

          </tr>
          <tr>
            <td class="fw-bolder">Theme: </td>
            <td>{{ $reservations->event_theme }}</td>


          </tr>


        </table>
        <hr>
        <!-- Celebrants detail -->
        <h1 class="text-start mb-5" style=" font-family: 'Playfair Display', serif;">Celebrant Information</h1>
        <table>
          <tr>
            <td class="fw-bolder"> Name:</td>
            <td>
              {{$reservations->celebrant_name}}
            </td>
          </tr>
          <td class="fw-bolder"> Age:</td>
          <td>
          {{$reservations->celebrant_age}}
          </td>

            <tr>
              <td class="fw-bolder"> Gender:</td>
            <td>
            {{$reservations->celebrant_gender}}
            </td>

          </tr>


        </table>
        <hr>
<!-- Menu -->
        <div class="menu-selection">
          <h1 class="text-start mb-5" style=" font-family: 'Playfair Display', serif;">Menu</h1>
          <table>
            <tr>
              <td class="fw-bolder ">Beef:</td>
              <td>{{ $reservations->getMenuName('beefMenu') }}</td>
              <td class="fw-bolder"> Pork:</td>
              <td>{{ $reservations->getMenuName('porkMenu') }}</td>
            </tr>


            <tr>
              <td class="fw-bolder">Chicken:</td>
              <td>{{ $reservations->getMenuName('chickenMenu') }}</td>
              <td class="fw-bolder">Vegetables:</td>
              <td>{{ $reservations->getMenuName('vegetableMenu') }}</td>
            </tr>

            <tr>
              <td class="fw-bolder">Fish:</td>
              <td>{{ $reservations->getMenuName('fishMenu') }}</td>
              <td class="fw-bolder">Seafood:</td>
              <td>{{ $reservations->getMenuName('seafoodMenu') }}</td>
            </tr>

            <tr>
              <td class="fw-bolder">Dessert:</td>
              <td>{{ $reservations->getMenuName('dessertMenu') }}</td>
              <td class="fw-bolder">Drinks:</td>
              <td>{{ $reservations->getMenuName('drinkMenu') }}</td>
            </tr>

            <tr>
              <td class="fw-bolder">Pasta:</td>
              <td>{{ $reservations->getMenuName('pastaMenu') }}</td>
            </tr>

          </table>
          <hr>
        <!-- Celebrants detail -->
        <h1 class="text-start mb-5" style=" font-family: 'Playfair Display', serif;">Package Included</h1>
        <table>
          <tr>
            <td > BASIC SETUP OF BACKDROP</td>
            <td>COMPLETE SETUP OF BUFFET</td>
          </tr>
          <tr>
            <td>GIFT TABLE</td>
            <td>CAKE TABLE</td>
          </tr>
          <tr>
            <td>SKIRTING</td>
            <td>CHAIRS & TABLE WITH COVER</td>
          </tr>
          <tr>
            <td>WAITER & FOOD ATTENDANT</td>
            <td>UTENSILS</td>
          </tr>


        </table>
        <hr>
        </div>
        <div class="btn-position justify-content-center m-5">
        <button class="btn btn-primary" onclick="window.location='{{ url('/generate-summary-pdf/' . $reservations->id) }}'">Download PDF File</button>
        </div>
        <div class="fs-5 mb-3 fst-italic">Disclaimer: For any changes please contact the catering.</div>
      </div>
@endsection
