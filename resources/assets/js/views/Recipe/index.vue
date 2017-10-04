<template>
    <div class="container">
        <div class="jumbotron">
            <div class="jumbotron__content">
                <h1>Welcome To My-Recipe!!</h1>
                <p>Create your recipe and share with everybody!</p>                
            </div>            
        </div>
        <div class="recipe__container">        
            <div class="recipe__categories">
                <div class="recipe__search">
                    <p>Find your recipe: </p>
                    <input type="text" v-model="search" placeholder="search recipe" @keyup="search_recipe()">
                </div>        
                <div class="recipe__links">
                    <a class="recipe__links_link" v-bind:class="{active:isActive}" @click="category('cake')">
                        <span>Cake</span>
                    </a>
                    <a class="recipe__links_link" :class="{active:isActive}" @click="category('candy')">Candy</a>
                    <a class="recipe__links_link" @click="category('cream')">Cream</a>
                    <a class="recipe__links_link" @click="category('food')">Food</a>
                </div>
            </div>
            <div class="recipe__list">
                <div class="recipe__item" v-for="recipe in recipes_filter" >
                    <router-link :to="`/recipes/${recipe.id}`" class="recipe__inner">
                        <img :src="`images/${recipe.image}`" alt="" >
                        <p class="recipe__name">{{recipe.name|snippset}}</p>
                    </router-link>
                </div>              
            </div>            
        </div>        
    </div>
</template>

<script>
    import {get} from '../../helper/api'
    import {bus} from '../../app'
    export default {
        data(){
            return {
                recipes:[],
                recipes_current:[],
                search:'',
                recipes_filter:[],                
                isActive:false,
                pages:1

            }   
        },

        created(){
            get('/api/recipes')
            .then((res)=>{         
                this.recipes = res.data.recipes

                this.recipes_current = this.recipes

                this.recipes_filter = this.recipes_current
                         
            })

            bus.$on('category',(data)=>{
                this.category(data)
            })
        },
        computed:{
            filteredRecipe(){                
                return this.recipes_filter.filter((recipe)=>{
                    return recipe.name.match(this.search)
                });                
            }
        },
        filters:{
            snippset(value){
                return value.slice(0,30) +'...';
            }
        },
        methods:{
            search_recipe(){
                this.recipes_filter = this.recipes_current.filter((recipe)=>{
                    return recipe.name.match(this.search)
                })

            },

            category(value){

                this.recipes_current = this.recipes.filter((recipe)=>{
                    return recipe.category.match(value)
                })

                this.recipes_filter = this.recipes_current

                this.isActive = true;
                
            }
        }

    }
</script>

