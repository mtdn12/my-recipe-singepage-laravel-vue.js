<template>
    <div class="container">
        <form action="" class="form">
            <h1 class="form__title">Register</h1>
            <div class="form__group">
                <label for="">User Name:</label>
                <input type="text" placeholder="user name" class="form__control" v-model="form.name">
                <small v-if="errors.name" class="error__control">{{errors.name[0]}}</small>
            </div>
            <div class="form__group">
                <label for="">Email:</label>
                <input type="text" placeholder="Email" class="form__control" v-model="form.email">
                <small v-if="errors.email" class="error__control">{{errors.email[0]}}</small>
            </div>
            <div class="form__group">
                <label for="">Password:</label>
                <input type="password" placeholder="password" class="form__control" v-model="form.password">
                <small v-if="errors.password" class="error__control">{{errors.password[0]}}</small>
            </div>
            <div class="form__group">
                <label for="">Confirm Password:</label>
                <input type="password" placeholder="confirm password" class="form__control" v-model="form.password_confirmation">
            </div>
            <div class="form__group">
                <button class="btn btn__primary" @click="register()" :disabled="isProcessing">Register</button>
            </div>
        </form>
    </div>
</template>

<script>
    import {post} from '../../helper/api'
    import Flash from '../../helper/flash'
    export default {
        data(){
            return {
                form:{
                    name:'',
                    email:'',
                    password:'',
                    password_confirmation:''
                },

                errors:{},

                isProcessing:false
            }
         },
        methods:{
            register(){
                this.isProcessing = true;
                post('/api/register',this.form)
                .then((res)=>{                 
                    if(res.data.register){
                        Flash.setSuccess('You have been successfully create an account!')
                        this.$router.push('/login')
                    }
                    this.isProcessing=false;
                })
                .catch((err)=>{                  
                    
                    if(err.response.status==422){
                        this.errors = err.response.data.errors
                    }
                    
                    this.isProcessing=false;
                })
            }
        }
    }
</script>
