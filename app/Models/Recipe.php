<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\User;

use App\Models\RecipeIngredient;

use App\Models\RecipeMethod;

class Recipe extends Model
{
    protected $fillable =[
        'name','description','category','image'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function ingredients(){
        return $this->hasMany(RecipeIngredient::class);
    }

    public function methods(){
        return $this->hasMany(RecipeMethod::class);
    }

    public static function form (){
    
        return [
            'name'=>'',
            'description'=>'',
            'category'=>'',
            'image'=>'',
            'ingredients'=>[
                RecipeIngredient::form()
            ],
            'methods'=>[
                RecipeMethod::form()
            ]
        ];
    }

}
