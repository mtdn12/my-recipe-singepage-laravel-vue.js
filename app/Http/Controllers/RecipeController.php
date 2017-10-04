<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Recipe;

use App\Models\RecipeIngredient;

use App\Models\RecipeMethod;

use File;

class RecipeController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api')
            ->except('index','show');
    }

    public function index(){
        $recipes = Recipe::orderBy('created_at','desc')
                    ->get();
        
        return response()
                ->json([
                    'recipes'=>$recipes
                ]);
    }

    public function show($id){
        $recipe = Recipe::with(['user','ingredients','methods'])
                    ->findOrFail($id);

        return response()
                ->json([
                    'recipe'=>$recipe,                    
                ]);
    }

    public function destroy($id){
        $recipe = Recipe::where('id',$id)->first();

        $recipe->ingredients()->delete();

        $recipe->methods()->delete();
        
        File::delete(base_path('public/images/'.$recipe->image));

        $recipe->delete();

        return response()
            ->json([
                'deleted'=>true
            ]);
    }

    public function edit($id, Request $request){
        $form = $request->user()->recipes()
        ->with([
            'ingredients'=>function($query) {
                $query->get(['id','name','qty']);
            }])
            ->with([            
            'methods'=>function($query){
                $query->get(['id','description']);
            }
        ])->findOrFail($id);      

    return response()
            ->json([
                'form'=>$form
            ]);
    }

    public function getFileName($file){
        return str_random(32).'.'.$file->extension();
    }
    
    public function update(Request $request,$id){
        $this->validate($request,[
            'name'=>'required|max:255',
            'category'=>'required',            
            'description'=>'required|max:3000',         
            'ingredients'=>'required|array|min:1',
            'ingredients.*.id'=>'integer|exists:recipe_ingredients',
            'ingredients.*.name' =>'required|max:255',
            'ingredients.*.qty'=>'required|max:255',
            'methods'=>'required|array|min:1',
            'methods.*.id'=>'integer|exists:recipe_methods',
            'methods.*.description'=>'required|max:3000'
        ]);

        $recipe = $request->user()->recipes()->findOrFail($id);
               
        $ingredients=[];

        $ingredientsUpdated=[];

        foreach($request->ingredients as $ingredient){
            if(isset($ingredient['id'])){
                RecipeIngredient::where('recipe_id',$recipe->id)
                                    ->where('id',$ingredient['id'])
                                    ->update($ingredient);
                $ingredientsUpdated[] = $ingredient['id'];
            } else {
               $ingredients[] = new RecipeIngredient($ingredient); 
            }
        }

        $methods=[];
        
        $methodsUpdated=[];

        foreach($request->methods as $method){
            if(isset($method['id'])){
                RecipeMethod::where('recipe_id',$recipe->id)
                                    ->where('id',$method['id'])
                                    ->update($method);
                $methodsUpdated[] = $method['id'];
            } else {
                $methods[] = new RecipeMethod($method); 
            }
        }        
        $recipe->name = $request->name;

        $recipe->description = $request->description;

        $recipe->category = $request->category;        

        $recipe->save();

        RecipeIngredient::whereNotIn('id',$ingredientsUpdated)
                            ->where('recipe_id',$recipe->id)
                            ->delete();

        RecipeMethod::whereNotIn('id',$methodsUpdated)
        ->where('recipe_id',$recipe->id)
        ->delete();

        if(count($ingredients)){
            $recipe->ingredients()->saveMany($ingredients);
        }

        if(count($methods)){
            $recipe->methods()->saveMany($methods);
        }
        return response()
                ->json([
                    'updated'=>true,
                    'id'=>$recipe->id,
                ]);
    }

    public function create(){
        $form = Recipe::form();

        return response()
            ->json([
                'form'=>$form
            ]);
    }

    public function storeRecipe(Request $request){
        $this->validate($request,[
            'name'=>'required|max:255',
            'description'=>'required|max:3000',
            'category'=>'required',          
            'ingredients'=>'required|array|min:1',
            'ingredients.*.name' =>'required|max:255',
            'ingredients.*.qty'=>'required|max:255',
            'methods'=>'required|array|min:1',
            'methods.*.description'=>'required|max:3000'
        ]);

        $ingredients =[];

        foreach($request->ingredients as $ingredient){
            $ingredients[] = new RecipeIngredient($ingredient);
        }

        $methods =[];
        
        foreach($request->methods as $method){
            $methods[] = new RecipeMethod($method);            
        }
        $recipe = new Recipe($request->all());        

        $request->user()->recipes()->save($recipe);

        $recipe->methods()->saveMany($methods);

        $recipe->ingredients()->saveMany($ingredients);

        return response()
            ->json([
                'saved'=>true,
                'id'=>$recipe->id,
                'message'=>'You have successfully created recipe'
            ]);
    }

    public function imageUpload($id,Request $request){
        $this->validate($request,[
            'image'=>'image'
        ]);
        
        $recipe = Recipe::where('id',$id)->first();

        if(!$request->hasFile('image') && !$request->file('image')->isValid()){
            return abort(404,'Image not upload');
        } 

        if($request->hasFile('image') && $request->file('image')->isValid()){
            $filename = $this->getFileName($request->image);

            $request->image->move(base_path('public/images'),$filename);

            File::delete(base_path('public/images/'.$recipe->image));

            $recipe->image = $filename;
        }

        $recipe->save();

        return response()
                ->json([
                    'image_upload'=>true
                ]);

    }

}

