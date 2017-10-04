<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Recipe;

class RecipeMethod extends Model
{
    protected $fillable =[
        'description'
    ];

    public $timestamps = false;

    public static function form(){
        return[
            'description'=>''
        ];
    }

    public function recipe(){
        return $this->belongsTo(Recipe::class);
    }
}
