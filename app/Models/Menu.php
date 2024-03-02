<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'menu_selection_id'
    ];

    public function menuSelection()
    {
        return $this->belongsTo(MenuSelection::class);
    }

    public function reservations()
{
    return $this->hasMany(Reservation::class, 'pork_menu_id')
        ->orWhere('beef_menu_id', $this->id)
        ->orWhere('pasta_menu_id', $this->id)
        ->orWhere('chicken_menu_id', $this->id)
        ->orWhere('veggies_menu_id', $this->id)
        ->orWhere('fish_menu_id', $this->id)
        ->orWhere('seafood_menu_id', $this->id)
        ->orWhere('dessert_menu_id', $this->id)
        ->orWhere('drink_menu_id', $this->id);
}
    

    public function getCategoryNameAttribute()
    {
        return $this->menuSelection->menu_category;
    }

    public function typeOfDish()
    {
        return $this->belongsTo(MenuSelection::class, 'menu_selection_id');
    }

    public function getMenuNameAttribute()
    {
        return $this->name;
    }

}
