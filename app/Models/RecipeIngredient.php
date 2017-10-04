<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Recipe;

class RecipeIngredient extends Model
{
    protected $fillable=[
        'name','qty'
    ];

    public $timestamps = false;

    public static function form(){
        return [
            'name'=>'',
            'qty'=>''
        ];
    }

    public function recipe(){
        return $this->belongsTo(Recipe::class);
    }
}
