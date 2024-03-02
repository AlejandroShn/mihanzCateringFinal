<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MenuSelection;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function landingpage()
    {
        $futureReservations = Reservation::all();

        $futureEvents = [];

        foreach ($futureReservations as $reservation) {
            $eventDate = Carbon::parse($reservation->event_date)->format('Y-m-d');
        
            $futureEvent = [
                'id' => $reservation->id,
                'title' => 'Not Available',
                'start' => $eventDate, // Use event_date directly for start
                'availability' => 'not-available', // Assume not available by default
            ];
        
            $futureEvents[] = $futureEvent;
        }
        
        // Add an available event for every day without a reservation
        $availableDates = Reservation::whereNull('event_date')->distinct('event_date')->pluck('event_date');
        foreach ($availableDates as $availableDate) {
            $availableEvent = [
                'id' => uniqid(), // Generate a unique ID for the available date
                'title' => 'Available',
                'start' => $availableDate,
                'availability' => 'available',
            ];
        
            $futureEvents[] = $availableEvent;
        }

        return view('welcome', compact('futureEvents'));
    }

    private function isDateAvailable($date)
    {
        // Check if there is an event on the given date
        return !Reservation::where('event_date', $date)->exists();
    }

    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();


    // Retrieve reservations for the authenticated user where event_date is less than today's date
    $pastReservations = $user->reservations()
        ->where('event_date', '<', Carbon::now()->toDateString())
        ->get();

        $latestReservations = $user->reservations()
        ->where('event_date', '>=', Carbon::now()->subDays(7)->toDateString()) // within the last 7 days
        ->orderBy('event_date', 'desc') // order by event_date in descending order
        ->get();

        // Fetch reservations related to the authenticated user
        $allReservations = $user->reservations;

        // Fetch only future reservations
        $futureReservations = $user->reservations()->where('event_date', '>=', Carbon::today())->get();

        $events = [];
        $futureEvents = [];

        foreach ($allReservations as $reservation) {
            $event = [
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

        return view('user.dashboard', compact('user', 'events', 'futureEvents', 'pastReservations', 'latestReservations'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

    // Validation rules
    $rules = [
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'contact_number' => 'required|string|max:20',
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique('users')->ignore($user->id),
        ],
        'profile_picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Assuming profile picture is an image
    ];

    // Validate the request
    $request->validate($rules);

    // Update user data
    $user->first_name = $request->input('first_name');
    $user->address = $request->input('address');
    $user->contact_number = $request->input('contact_number');
    $user->email = $request->input('email');


    $imagePath = null;
        if ($request->hasFile('profile_picture')) {
            $profile_picture = $request->file('profile_picture');
            $imagePath = time() . '_' . $profile_picture->getClientOriginalName();
            $profile_picture->move(public_path('images/user'), $imagePath);
            $user->profile_picture = $imagePath;
        }

    // Save the changes
    $user->save();

        return redirect()->route('user.dashboard')->with('success', 'Profile updated successfully.');
    }
}
