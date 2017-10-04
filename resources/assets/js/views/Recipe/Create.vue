<template>
    <div class="recipe__show">
        <div class="recipe__header">
            <h3>Edit Recipe</h3>
            <div>
                <button class="btn btn__primary" @click="save" :disabled="isProcessing">
                    Save
                </button>
                <button class="btn" @click="$router.back()" :disabled="isProcessing">
                    Cancel
                </button>
            </div>
        </div>
        <div class="recipe__row">
            <div class="recipe__image">
                <div class="recipe__box">
                    <image-upload v-model="image"></image-upload>
                    <small class="error__control" v-if="error.image">{{error.image[0]}}</small>
                </div>
            </div>
            <div class="recipe__details">
                <div class="recipe__details_inner">                    
                    <div class="form__group">
                        <label>Name</label>
                        <input type="text" class="form__control" v-model="form.name">
                        <small class="error__control" v-if="error.name">
                            {{error.name[0]}}
                        </small>
                    </div>
                    <div class="form__group">
                        <label>Description</label>
                        <textarea type="text" class="form__control" v-model="form.description"></textarea>
                        <small class="error__control" v-if="error.description">
                            {{error.description[0]}}
                        </small>
                    </div>
                    <div class="form__group">
                        <label>Category:</label>
                        <select v-model="form.category">
                            <option value="cake">Cake</option>
                            <option value="cream">Cream</option>
                            <option value="candy">Candy</option>
                            <option value="food">Food</option>
                        </select>
                    </div>                    
                </div>                
            </div>
        </div>
        <div class="recipe__row">
             <div class="recipe__ingredients">
                <div class="recipe__box">
                    <h3 class="recipe__sub_title">
                        Ingredients
                    </h3>
                    <div v-for="(ingredient,index) in form.ingredients" class="recipe__form">
                        <input type="text" class="form__control" v-model="ingredient.name"
                            :class="[error[`ingredients.${index}.name`] ? 'error__bg' : '']">

                        <input type="text" class="form__control form_qty" v-model="ingredient.qty"
                            :class="[error[`ingredients.${index}.qty`] ? 'error__bg' : '']">

                        <button class="btn btn__danger" @click="remove('ingredients',index)">
                            &times;
                        </button>
                    </div>
                    <button class="btn" @click="addIngredient">Add Ingredient</button>
                </div>
            </div>
            <div class="recipe__methods">
                <div class="recipe__methods_inner">
                    <h3 class="recipe__sub_title">Methods</h3>
                    <div v-for="(method,index) in form.methods" class="recipe__form">
                        <textarea type="text" class="form__control" v-model="method.description"
                            :class="[error[`methods.${index}.description`] ? 'error__bg' : '']">
                        </textarea>
                        <button class="btn btn__danger" @click="remove('methods',index)">
                            &times;
                        </button>
                    </div>
                    <button class="btn" @click="addMethod">Add Method</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {get,post,put} from '../../helper/api'

    import Flash from'../../helper/flash'

    import ImageUpload from '../../components/imageUpload.vue'
    export default {
       
        components:{
            'image-upload':ImageUpload
       
        },
        data(){
            return{
                form:{
                    'name':'',
                    'category':'',
                    'image':'',
                    'description':'',
                    'ingredients':[ 
                    ]                     
                    ,
                    'methods':[
                    ]
                },
                image:'',

                isProcessing:false,

                error:{},

                recipe_id:null,
            }
        },
        created(){
            get(`/api/recipes/create`)
                .then((res)=>{
                    this.form = res.data.form;                    
                })
        },
        methods:{
            save(){
                isProcessing:true;                
                post(`/api/reicpes/storerecipe`,this.form)
                    .then((res)=>{
                        this.recipe_id = res.data.id
                        let form = new FormData();

                        form.append('image',this.image);

                        console.log(form);
                        post(`/api/recipes/${this.recipe_id}/image`,form)
                        .then((res)=>{
                            if(res.data.image_upload){
                                Flash.setSuccess('You have been successfully create Recipe');
                                this.$router.push(`/recipes/${this.recipe_id}`)
                            }
                        }) 
                    })
                    .catch((e)=>{
                        if(e.response.status===422){
                            this.error = e.response.data.errors
                            
                        }

                        this.isProcessing=false
                    })

                let form = new FormData();

                form.append('image',this.form.image);

                post(`/api/recipes/${this.recipe_id}/image`,form)
                    .then((res)=>{
                        if(res.data.image_upload){
                            Flash.setSuccess('You have been successfully create Recipe');

                            this.$router.push(`/recipes/${this.recipe_id}`)
                        }
                    })
                    .catch((e)=>{                                               
                    })
            },
            remove(type,index){
                if(this.form[type].length>1){
                    this.form[type].splice(index,1)
                }
            },
            addIngredient(){
                this.form.ingredients.push({
                    'name':'',
                    'qty':''
                })               
            },
            addMethod(){
                this.form.methods.push({
                    'description':''
                })
            }
        },
    }
</script>

