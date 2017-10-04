<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeMethod;
class RecipesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Recipe::truncate();

        RecipeIngredient::truncate();

        RecipeMethod::truncate();

        $categories = ['cake','candy','cream','food'];
        foreach(range(1,10) as $i){
            $recipe = Recipe::create([
                'user_id'=>$i,
                'name'=>$faker->sentence,
                'description'=>$faker->paragraph(mt_rand(10,20)),
                'category'=>$categories[mt_rand(0,3)],
                'image'=>'test.jpg'

            ]);           
            
            foreach(range(1,mt_rand(3,12)) as $j){
                RecipeIngredient::create([
                    'recipe_id'=>$recipe->id,
                    'name'=> $faker->word,
                    'qty'=>mt_rand(1,12).' kg'
                ]);
            }

            foreach(range(1,mt_rand(3,12)) as $k){
                RecipeMethod::create([
                    'recipe_id'=>$recipe->id,
                    'description'=>$faker->sentence,
                ]);
            }
        }
    }
}
