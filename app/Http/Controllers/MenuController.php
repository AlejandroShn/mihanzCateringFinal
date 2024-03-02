<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuSelection;
use App\Models\Menu;
use App\Models\Reservation;
use App\Models\ReservationSelection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;


class MenuController extends Controller
{
    public function index()
    {
        
        $menuItems = MenuSelection::all();
        foreach ($menuItems as $menuItem) {
            $menuItem->menu_image = asset("images/menu/menu_selection/".rawurlencode($menuItem->menu_image));
        }
        return view('user.menus', compact('menuItems'));
    }

    public function specificmenuindex($category = null)
    {
        $categoryName = MenuSelection::where('menu_category', $category)->firstOrFail()->menu_category;
        $menus = Menu::whereHas('menuSelection', function ($query) use ($category) {
            $query->where('menu_category', $category);
        })->get();

        return view('user.menuContainer', compact('menus', 'categoryName'));
    }



    public function mostOrderedMenuPerCategory()
{
    $menuCategories = ['pork', 'beef', 'pasta', 'chicken', 'veggies', 'fish', 'seafood', 'dessert', 'drink'];

    $result = collect();

    foreach ($menuCategories as $category) {
        $categoryResults = DB::table('menus')
            ->join("reservations", "menus.id", "=", "reservations.{$category}_menu_id")
            ->join('menu_selections', 'menus.menu_selection_id', '=', 'menu_selections.id')
            ->select(
                'menus.id as menu_id',
                'menus.name as menu_name',
                'menus.description',
                'menus.price',
                'menus.serving',
                'menus.menu_selection_id',
                'menus.menus_image', 
                'menu_selections.menu_category as category_name',
                'menus.created_at',
                DB::raw('COUNT(menus.id) as total_orders'),
                DB::raw('MAX(reservations.created_at) as last_order_date'),
                DB::raw('RANK() OVER (ORDER BY COUNT(menus.id)) as ranking') // Use RANK() for ranking
            )
            ->groupBy('menus.id', 'menus.name', 'menus.description', 'menus.price', 'menus.serving','menus.menu_selection_id', 'menus.menus_image', 'menu_selections.menu_category', 'menus.created_at')
            ->orderByDesc('total_orders')
            ->get();

        if ($categoryResults->isNotEmpty()) {
            $maxRank = $categoryResults->max('ranking');

            $maxResults = $categoryResults->filter(function ($menuItem) use ($maxRank) {
                return $menuItem->ranking === $maxRank;
            });

            $maxResults = $maxResults->map(function ($menuItem) {
                $menuItem->last_order_date = date('Y-m-d H:i:s', strtotime($menuItem->last_order_date));
                $menuItem->formatted_price = '₱' . number_format($menuItem->price, 2);
                return $menuItem;
            });

            $result = $result->merge($maxResults);
        }
    }

    return view('mostOrderedMenu', ['result' => $result]);
}
    





    private function getMostOrderedMenuId($categoryIds)
    {
        $menuIdCounts = array_count_values(array_filter($categoryIds));

        if (empty($menuIdCounts)) {
            return null;
        }

        arsort($menuIdCounts);

        return key($menuIdCounts);
    }



    


}
