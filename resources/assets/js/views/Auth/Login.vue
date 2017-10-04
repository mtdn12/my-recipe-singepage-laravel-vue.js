<template>
    <div class="container">
        <form action="" class="form"> 
            <h1 class="form__title">Log In</h1>          
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
                <button class="btn btn__primary" :disabled="isProcess" @click="login">Login</button>
            </div>
        </form>
    </div>
</template>

<script>
    import {post} from '../../helper/api'
    import Flash from '../../helper/flash'
    import Auth from '../../store/Auth'
    export default {
        data(){
            return{
                form:{
                    'name':'',
                    'password':''
                },
                errors:{},

                isProcess:false
            }
        },
        methods:{
            login(){
                isProcess:true;
                
                post('/api/login',this.form)
                    .then((res)=>{
                        if(res.data.authenticated) {
                            Auth.set(res.data.user_id,res.data.api_token);

                            Flash.setSuccess('You have been successfully logged in')

                            this.$router.push('/');
                        }
                    })
                    .catch((err)=>{
                        this.errors = err.response.data.errors
                       
                        if(err.response.data.email){
                            Flash.setError(err.response.data.email[0])
                        }

                        isProcess:false
                    })
            },

        }
    }
</script>
