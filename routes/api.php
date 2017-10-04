<?php

use Illuminate\Http\Request;

Route::post('/register','AuthController@register');

Route::post('/login','AuthController@login');

Route::post('/logout','AuthController@logout');

Route::resource('/recipes','RecipeController');

Route::post('/recipes/{recipe}/image','RecipeController@imageUpload');

Route::post('/reicpes/storerecipe','RecipeController@storeRecipe');