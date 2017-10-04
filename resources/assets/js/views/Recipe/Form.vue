<template>
    <div class="recipe__show">
        <div class="recipe__header">
            <h3>{{action}} Recipe</h3>

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
                    <image-upload v-model="form.image"></image-upload>
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
                </div>
            </div>
        </div>
        <div class="recipe__row">
            <div class="recipe__ingredients">
                <div class="recipe__box">
                    <h3 class="recipe__sub_title">Ingredients</h3>
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
                <h3 class="recipe__sub_title">Methods:</h3>
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
    </div>
</template>

<script>  

    import Flash from'../../helper/flash'

    import {get,post} from '../../helper/api'

    import ImageUpload from '../../components/imageUpload.vue'   
    
    export default {
        components:{
            'image-upload':ImageUpload
        },
        data(){
            return {
                form:{
                   ingredients:[],
                   methods:[]
                },

                error:{},

                isProcessing:false,

                initializeURL:`/api/recipes/create`,

                storeURL:`/api/recipes`,

                action:'Create'
            }

        },

        created(){
            if(this.$route.meta.mode==='edit'){
                this.initializeURL = `/api/recipes/${this.$route.params.id}/edit`

                this.storeURL =`/api/recipes/${this.$route.params.id}?_method=PUT`

                this.action='Update'
            }

            get(this.initializeURL)
                .then((res)=>{
                    console.log(res);
                    Vue.set(this.$data,'form',res.data.form)
                })
        },

        methods:{
            save(){
                this.isProcessing = true;
                const form = toMulipartedForm(this.form,this.$route.meta.mode)
                post(this.storeURL,form)
                    .then((res)=>{
                        if(res.data.saved){
                            Flash.setSuccess(res.data.message)
                            this.$router.push(`/recipes/${res.data.id}`)
                        }

                    })
                    .catch((e)=>{
                        if(e.response.status===422){
                            this.error = e.response.data
                        }

                        this.isProcessing=false
                    })
            },

            addMethod(){
                this.form.methods.push({
                    description:''
                })
            },

            addIngredient(){
                this.form.ingredients.push({
                    name:'',
                    qty:''
                })
            },

            remove(type,index){
                if(this.form[type].length >1){
                    this.form[type].splice(index,1)
                }
            }
        }
    }
</script>

