<template>
    <div class="container">
        <div class="navbar">
            <div class="navbar__brand">
                <router-link to="/" >
                <span @click="category()">My-Recipe</span> 
                </router-link>
            </div>            
            <ul class="navbar__list">
                <li class="navbar__item">
                    <router-link to="/register" v-if="!check">Register</router-link>
                </li>
                <li class="navbar__item">
                    <router-link to="/login" v-if="!check">Login</router-link>
                </li>
                <li class="navbar__item">
                    <a to="/logout" v-if="check"  @click.stop="logout">logout</a>
                </li>
                <li class="navbar__item">
                    <router-link to="/create" v-if="check">Create</router-link>
                </li>
            </ul>            
        </div>
        
        <div class="flash flash__success" v-if="flash.success">{{flash.success}}</div>
        <div class="flash flash__error" v-if="flash.error">{{flash.error}}</div>
        <router-view></router-view>
    </div>
</template>
    
<script>
    import Flash from './helper/flash'
    import Auth from './store/Auth'
    import {post} from './helper/api'
    import {bus} from './app'
    export default {
        data(){
            return {
                flash : Flash.state,

                auth : Auth.state,
            }
        },
        computed:{
            check(){
                if(this.auth.api_token && this.auth.user_id){
                    return true
                }
                return false                
            }
        },
        created(){
            Auth.get();            
        },
        methods:{
            logout(){  
                post('/api/logout')
                .then((res)=>{                   
                    
                    Auth.remove()

                    Flash.setSuccess('You have been successfully logged out!')

                    this.router.push('/login')
                })
            },
            category(){
                bus.$emit('category','')
            }
           
        },
        
        
    }
</script>

