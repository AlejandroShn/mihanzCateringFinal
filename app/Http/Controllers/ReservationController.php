<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuSelection;
use App\Models\ThemeSelection;
use App\Models\Reservation;
use App\Models\ReservationCustomize;
use App\Models\ServicePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    public function showForm($packageId)
    {
        // Fetch menu selections to populate dropdowns
        $menuSelections = [
            'pork' => Menu::where('menu_selection_id', 1)->get(),
            'chicken' => Menu::where('menu_selection_id', 3)->get(),
            'beef' => Menu::where('menu_selection_id', 2)->get(),
            'fish' => Menu::where('menu_selection_id', 4)->get(),
            'seafood' => Menu::where('menu_selection_id', 5)->get(),
            'pasta' => Menu::where('menu_selection_id', 6)->get(),
            'vegetable' => Menu::where('menu_selection_id', 7)->get(),
            'dessert' => Menu::where('menu_selection_id', 8)->get(),
            'drink' => Menu::where('menu_selection_id', 9)->get(),

        ];

        $specificPackage = ServicePackage::findOrFail($packageId);
        $themeSelections = ThemeSelection::all();

        return view('user.reservations.premade', compact('menuSelections', 'specificPackage', 'themeSelections'));
    }

    public function submitForm(Request $request)
    {

        $request->validate([

            'celebrant_name' => 'required|string',
            'celebrant_age' => 'required|numeric|min:0',
            'event_theme' => 'required|string',
            'celebrant_gender' => 'required|in:Male,Female',
            'event_date' => [
                'required',
                'date',
                'after_or_equal:' . now()->addDays(7)->toDateString(),
                Rule::unique('reservations')->where(function ($query) use ($request) {
                    return $query->where('event_date', $request->input('event_date'));
                })],
            'event_time' => 'required|date_format:H:i',
            'venue_address' => 'required|string',
            'agree_terms' => 'required|boolean',
            'pork_menu' => 'exists:menus,id',
            'beef_menu' => 'exists:menus,id',
            'chicken_menu' => 'required|exists:menus,id',
            'fish_menu' => 'required|exists:menus,id',
            'seafood_menu' => 'required|exists:menus,id',
            'vegetable_menu' => 'required|exists:menus,id',
            'dessert_menu' => 'required|exists:menus,id',
            'drink_menu' => 'required|exists:menus,id',
            'pasta_menu' => 'required|exists:menus,id',
            'agree_terms' => 'required|accepted',

        ], [
            'agree_terms.accepted' => 'Please check the terms and conditions.',
            'agree_terms.required' => 'Please check the terms and conditions.',
        ]);

        $packageId = $request->input('selected_package');

        $reservation = Auth::user()->reservations()->create([
            'celebrant_name' => $request->input('celebrant_name'),
            'celebrant_age' => $request->input('celebrant_age'),
            'event_theme' => $request->input('event_theme'),
            'celebrant_gender' => $request->input('celebrant_gender'),
            'event_date' => $request->input('event_date'),
            'event_time' => $request->input('event_time'),
            'venue_address' => $request->input('venue_address'),
            'agree_terms' => $request->input('agree_terms'),
            'pork_menu_id' => $request->input('pork_menu'),
            'beef_menu_id' => $request->input('beef_menu'),
            'chicken_menu_id' => $request->input('chicken_menu'),
            'fish_menu_id' => $request->input('fish_menu'),
            'seafood_menu_id' => $request->input('seafood_menu'),
            'veggies_menu_id' => $request->input('vegetable_menu'),
            'dessert_menu_id' => $request->input('dessert_menu'),
            'drink_menu_id' => $request->input('drink_menu'),
            'pasta_menu_id' => $request->input('pasta_menu'),

        ]);

        $reservation->selections()->create([
            'categoryName' => session('categoryName'),
            'choice' => 'premade',
        ]);

        $reservation->premades()->create([
            'package_id' => $packageId,
        ]);

        session()->forget(['categoryName']);

        // Redirect to the summary page
        return redirect()->route('user.reservations.p_summary', $reservation->id);
    }

    public function showSummary($reservationId)
    {
        $reservations = Reservation::with('premades.servicePackage', 'user', 'reservationCustomize', 'reservationSelection')->findOrFail($reservationId);

        return view('user.reservations.p_summary', compact('reservations'));
    }

    public function custom()
    {
        return view('user.reservations.custom_package');
    }

    public function showCustomize()
    {

        return view('user.reservations.customize-form');
    }

    public function showCustomizeStore(Request $request)
    {
        $request->validate([
            'budget' => ['required', 'numeric', 'min:18000', 'max:130000'],
            'pax' => ['required', 'numeric', 'min:50', 'max:350'],

        ]);
    
        session()->put('budget', $request->input('budget'));
        session()->put('pax', $request->input('pax'));

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

        return view('user.reservations.form', compact('menus'));
    }

    public function showCustomizeForm(Request $request)
    {
        $menus = $this->getMenus();
        $pax = session('pax'); // Providing a default value of 0 if 'pax' is not set
    $budget = session('budget'); // Providing a default value of 0 if 'budget' is not set

    $themeSelections = ThemeSelection::all();
    
        return view('user.reservations.form', compact('menus', 'pax', 'budget', 'themeSelections'));
    }

    public function submitCustomizeForm(Request $request)
    {
        $request->validate([

            'celebrant_name' => 'required|string',
            'celebrant_age' => 'required|numeric|min:0',
            'event_theme' => 'required|string',
            'celebrant_gender' => 'required|in:Male,Female',
            'event_date' => [
                'required',
                'date',
                'after_or_equal:' . now()->addDays(7)->toDateString(),
                Rule::unique('reservations')->where(function ($query) use ($request) {
                    return $query->where('event_date', $request->input('event_date'));
                })],
            'event_time' => 'required|date_format:H:i',
            'venue_address' => 'required|string',
            'agree_terms' => 'required|boolean',
            'pork_menu' => 'exists:menus,id',
            'beef_menu' => 'exists:menus,id',
            'chicken_menu' => 'exists:menus,id',
            'fish_menu' => 'exists:menus,id',
            'seafood_menu' => 'required|exists:menus,id',
            'vegetable_menu' => 'required|exists:menus,id',
            'dessert_menu' => 'required|exists:menus,id',
            'drink_menu' => 'required|exists:menus,id',
            'pasta_menu' => 'required|exists:menus,id',
            'agree_terms' => 'required|accepted',

        ], [
            'agree_terms.accepted' => 'Please check the terms and conditions.',
            'agree_terms.required' => 'Please check the terms and conditions.',
        ]);

        $reservation = Auth::user()->reservations()->create([
            'celebrant_name' => $request->input('celebrant_name'),
            'celebrant_age' => $request->input('celebrant_age'),
            'event_theme' => $request->input('event_theme'),
            'celebrant_gender' => $request->input('celebrant_gender'),
            'event_date' => $request->input('event_date'),
            'event_time' => $request->input('event_time'),
            'venue_address' => $request->input('venue_address'),
            'agree_terms' => $request->input('agree_terms'),
            'pork_menu_id' => $request->input('pork_menu'),
            'beef_menu_id' => $request->input('beef_menu'),
            'chicken_menu_id' => $request->input('chicken_menu'),
            'fish_menu_id' => $request->input('fish_menu'),
            'seafood_menu_id' => $request->input('seafood_menu'),
            'veggies_menu_id' => $request->input('vegetable_menu'),
            'dessert_menu_id' => $request->input('dessert_menu'),
            'drink_menu_id' => $request->input('drink_menu'),
            'pasta_menu_id' => $request->input('pasta_menu'),

        ]);

        $reservation->selections()->create([
            'categoryName' => session('categoryName'),
            'choice' => 'customize',
        ]);

        $reservationCustomize = new ReservationCustomize([
            'pax' => session('pax'),
            'price' => session('budget'),
        ]);

        $reservation->reservationCustomize()->save($reservationCustomize);

        session()->forget(['budget', 'pax', 'categoryName']);

        // Redirect to the summary page
        return redirect()->route('user.reservations.p_summary', $reservation->id);
    }

    // public function showSummaryCustomize($reservationId)
    // {
    //     $reservations = Reservation::with('user', 'reservationCustomize', 'reservationSelection')->findOrFail($reservationId);

    //     return view('user.reservations.custom_summary', compact('reservations'));
    // }

    private function getMenus()
    {
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

        return $menus;
    }

    public function checkDateAvailability(Request $request)
{
    $selectedDate = $request->input('event_date');
    $existingReservation = Reservation::where('event_date', $selectedDate)->exists();

    return response()->json(['available' => !$existingReservation]);
}

}
