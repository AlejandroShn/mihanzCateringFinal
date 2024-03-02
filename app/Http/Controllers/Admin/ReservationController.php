<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReservationApprovedMail;
use App\Mail\ReservationDeclinedMail;
use App\Models\CallStatus;
use App\Models\MenuSelection;
use App\Models\Reservation;
use App\Models\ReservationSelection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{

    public function showReservationStatus($status)
    {
        $categories = MenuSelection::all();
        $reservation_categories = Reservation::all();

        if ($status === 'all') {
            $allReservations = Reservation::all();
        } else if ($status === 'pending' or $status === 'approved') {
            $allReservations = ($status === 'pending') ? Reservation::where('reservation_status', 'pending')->get() : Reservation::where('reservation_status', 'approved')->get();
        } else if ($status === 'incoming') {
            $allReservations = Reservation::where('event_date', '>=', Carbon::today())->get();
        } else if ($status === 'history') {
            $allReservations = Reservation::where('event_date', '<', Carbon::today())->get();
        } else {
            return abort(404);
        }

        $events = [];
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
                'reservation_status' => $reservation->reservation_status,

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

        $menus = [];
        $menus['beef'] = MenuSelection::where('menu_category', 'beef')->first()->menus;
        $menus['pork'] = MenuSelection::where('menu_category', 'pork')->first()->menus;
        $menus['chicken'] = MenuSelection::where('menu_category', 'chicken')->first()->menus;
        $menus['fish'] = MenuSelection::where('menu_category', 'fish')->first()->menus;
        $menus['seafood'] = MenuSelection::where('menu_category', 'seafood')->first()->menus;
        $menus['pasta'] = MenuSelection::where('menu_category', 'pasta')->first()->menus;
        $menus['vegetable'] = MenuSelection::where('menu_category', 'vegetables')->first()->menus;
        $menus['dessert'] = MenuSelection::where('menu_category', 'desserts')->first()->menus;
        $menus['drink'] = MenuSelection::where('menu_category', 'drinks')->first()->menus;

        return view('admin.reservation.index', compact('categories', 'events', 'menus', 'reservation_categories', 'status'));
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);

        return view('admin.reservation.edit', compact('reservation'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $user = $reservation->user;
        $reservationSelection = ReservationSelection::where('reservation_id', $id)->first();

        $request->validate([
            'celebrant_name' => 'required|string',
            'celebrant_age' => 'required|numeric|min:0',
            'event_theme' => 'required|string',
            'celebrant_gender' => 'required|in:Male,Female',
            'event_date' => [
                'required',
                'date',
                Rule::unique('reservations')->ignore($request->route('reservation'), 'id')
                    ->where(function ($query) use ($request) {
                        return $query->where('event_date', '!=', $request->input('event_date'));
                    }),
            ],
            'event_time' => 'required',
            'venue_address' => 'required|string',
            'pork_menu' => 'nullable|exists:menus,id',
            'beef_menu' => 'nullable|exists:menus,id',
            'chicken_menu' => 'nullable|exists:menus,id',
            'fish_menu' => 'nullable|exists:menus,id',
            'seafood_menu' => 'required|exists:menus,id',
            'vegetable_menu' => 'required|exists:menus,id',
            'dessert_menu' => 'required|exists:menus,id',
            'drink_menu' => 'required|exists:menus,id',
            'pasta_menu' => 'required|exists:menus,id',
            'reservation_status' => 'required',
        ]);

        $reservation->update([
            'celebrant_name' => $request->input('celebrant_name'),
            'celebrant_age' => $request->input('celebrant_age'),
            'event_theme' => $request->input('event_theme'),
            'celebrant_gender' => $request->input('celebrant_gender'),
            'event_date' => $request->input('event_date'),
            'event_time' => $request->input('event_time'),
            'venue_address' => $request->input('venue_address'),
            'pork_menu_id' => $request->input('pork_menu'),
            'beef_menu_id' => $request->input('beef_menu'),
            'chicken_menu_id' => $request->input('chicken_menu'),
            'fish_menu_id' => $request->input('fish_menu'),
            'seafood_menu_id' => $request->input('seafood_menu'),
            'veggies_menu_id' => $request->input('vegetable_menu'),
            'dessert_menu_id' => $request->input('dessert_menu'),
            'drink_menu_id' => $request->input('drink_menu'),
            'pasta_menu_id' => $request->input('pasta_menu'),
            'reservation_status' => $request->input('reservation_status'),
        ]);

        // Check if 'reservation_status' was changed
        if ($reservation->wasChanged('reservation_status')) {
            // Send notification based on the new status
            $newStatus = $reservation->reservation_status;

            if ($newStatus === 'Approved') {
                // Send approval email
                Mail::to($user->email)->send(new ReservationApprovedMail($user, $reservation));
            } else if ($newStatus === 'Decline') {
                Mail::to($user->email)->send(new ReservationDeclinedMail($user, $reservation));
            }
        } else {
            // Log if the reservation status is not updated
            info('Reservation status not updated.');
        }

        if ($reservationSelection) {
            $choice = $reservationSelection->choice;

            if ($choice === 'customize') {
                $reservation->reservationCustomize()->update([
                    'pax' => $request->input('premade_pax'),
                    'price' => $request->input('premade_price'),
                ]);
            } elseif ($choice === 'premade') {
                $reservation->selections()->update([
                    'categoryName' => $request->input('categoryName'),
                ]);
            }

            
        } else {
            // Handle the case when no reservation selection is found
        }

        return redirect()->back()->with('success', 'Reservation details updated successfully!');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);

        // Delete the menu item
        $reservation->delete();

        return redirect()->back()->with('success', 'Reservation deleted successfully!');
    }

    public function indexCallStatus($id)
    {
        $status = 'all';
        $categories = MenuSelection::all();
        $reservation = Reservation::findOrFail($id);
        $callStatuses = $reservation->callStatus()->orderBy('call_status_date', 'desc')->get();

        return view('admin.reservation.call.index', compact('reservation', 'callStatuses', 'status', 'categories'));
    }

    public function addCallStatus($id)
    {
        $reservation = Reservation::findOrFail($id);

        return view('admin.reservations.add_call_status', compact('reservation'));
    }

    public function storeCallStatus(Request $request, $id)
    {
        $request->validate([
            'call_status_date' => 'required|date',
            'call_status_time' => 'required|date_format:H:i',
            'call_status' => 'required|in:Waiting,Contacted,Approved,Canceled',
            'call_remarks' => 'nullable|string',
        ]);

        // The $id parameter is already the reservation_id
        $reservation = Reservation::findOrFail($id);

        $call = new CallStatus([
            'call_status_date' => $request->input('call_status_date'),
            'call_status_time' => $request->input('call_status_time'),
            'call_status' => $request->input('call_status'),
            'call_remarks' => $request->input('call_remarks'),
        ]);

        $reservation->callStatus()->save($call);

        return redirect()->route('call-status.index', $id)->with('success', 'Call status added successfully.');
    }
}
