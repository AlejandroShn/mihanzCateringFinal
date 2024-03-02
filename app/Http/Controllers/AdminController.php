<?php

namespace App\Http\Controllers;

use App\Models\MenuSelection;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function index()
    {

        $status = 'all';


        $userCount = User::count();
        $futureReservationsCount = Reservation::where('event_date', '>=', Carbon::today())->count();
        $pastReservationsCount = Reservation::where('event_date', '<', Carbon::today())->count();
        $pendingReservationsCount = Reservation::where('reservation_status', 'pending')->count();
        $approvedReservationsCount = Reservation::where('reservation_status', 'approved')->count();
        

        $categories = MenuSelection::all();
        $allReservations = Reservation::all();
        $futureReservations = Reservation::where('event_date', '>=', Carbon::today())->get();
       
        $events = [];
        $futureEvents = [];

        foreach ($allReservations as $reservation) {
            $event = [
                'id' => $reservation->id,
                'title' => $reservation->celebrant_name,
                'start' => Carbon::parse($reservation->event_date . ' ' . $reservation->event_time)->format('Y-m-d H:i:s'),

                'name' => $reservation->user->first_name . ' ' . $reservation->user->last_name,
                'email' => $reservation->user->email,
                'contact_number' => $reservation->user->contact_number,
                'event_date' => Carbon::parse($reservation->event_date)->format('Y-m-d'),
                'event_time' => Carbon::parse($reservation->event_time)->format('H:i:s'),
                'venue_address' => $reservation->venue_address,

                'celebrant_name' => $reservation->celebrant_name,
                'celebrant_age' => $reservation->celebrant_age,
                'celebrant_gender' => $reservation->celebrant_gender,
                'event_theme' => $reservation->event_theme,

                'beef_menu' => $reservation->getMenuName('beefMenu'),
                'pork_menu' => $reservation->getMenuName('porkMenu'),
                'chicken_menu' => $reservation->getMenuName('chickenMenu'),
                'fish_menu' => $reservation->getMenuName('fishMenu'),
                'seafood_menu' => $reservation->getMenuName('seafoodMenu'),
                'vegetable_menu' => $reservation->getMenuName('vegetableMenu'),
                'pasta_menu' => $reservation->getMenuName('pastaMenu'),
                'dessert_menu' => $reservation->getMenuName('dessertMenu'),
                'drink_menu' => $reservation->getMenuName('drinkMenu'),

                'choice' => optional($reservation->reservationSelection)->choice,
                'service_category' => optional($reservation->premades)->servicePackage
                    ? $reservation->premades->servicePackage->serviceSelection->services_category
                    : null,
                'category_name' => optional($reservation->reservationSelection)->categoryName,
                'premade_pax' => optional($reservation->premades)->servicePackage
                    ? $reservation->premades->servicePackage->pax
                    : null,
                'customize_pax' => optional($reservation->reservationCustomize)->price
                    ? number_format(floor($reservation->reservationCustomize->price / 350))
                    : null,
                'premade_price' => optional($reservation->premades)->servicePackage
                ? $reservation->premades->servicePackage->price
                : null,
                'customize_price' => optional($reservation->reservationCustomize)->price
                ? $reservation->reservationCustomize->price
                : null,
                'premade_package' => optional($reservation->premades)->servicePackage
                    ? $reservation->premades->servicePackage->name
                    : null,

            ];

            $events[] = $event;
        }

        foreach ($futureReservations as $reservation) {
            $futureEvent = [
                'id' => $reservation->id,
                'title' => $reservation->celebrant_name,
                'start' => Carbon::parse($reservation->event_date . ' ' . $reservation->event_time)->format('Y-m-d H:i:s'),

                'name' => $reservation->user->first_name . ' ' . $reservation->user->last_name,
                'email' => $reservation->user->email,
                'event_date' => Carbon::parse($reservation->event_date)->format('Y-m-d'),
                'event_time' => Carbon::parse($reservation->event_time)->format('H:i:s'),
                'venue_address' => $reservation->venue_address,

                'celebrant_name' => $reservation->celebrant_name,
                'celebrant_age' => $reservation->celebrant_age,
                'celebrant_gender' => $reservation->celebrant_gender,

                'beef_menu' => $reservation->getMenuName('beefMenu'),
                'pork_menu' => $reservation->getMenuName('porkMenu'),
                'chicken_menu' => $reservation->getMenuName('chickenMenu'),
                'fish_menu' => $reservation->getMenuName('fishMenu'),
                'seafood_menu' => $reservation->getMenuName('seafoodMenu'),
                'vegetable_menu' => $reservation->getMenuName('vegetableMenu'),
                'pasta_menu' => $reservation->getMenuName('pastaMenu'),
                'dessert_menu' => $reservation->getMenuName('dessertMenu'),
                'drink_menu' => $reservation->getMenuName('drinkMenu'),
            ];

            $futureEvents[] = $futureEvent;
        }

        return view('admin.dashboard', compact('categories', 'userCount', 'events', 'futureEvents', 'futureReservationsCount', 'pastReservationsCount', 'status', 'pendingReservationsCount', 'approvedReservationsCount'));
    }
}
