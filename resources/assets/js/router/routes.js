import Vue from'vue'

import VueRouter from 'vue-router'

import Register from '../views/Auth/Register.vue'

import Login from '../views/Auth/Login.vue'

import Index from '../views/Recipe/index.vue'

import RecipeShow from '../views/Recipe/Show.vue'

import RecipeForm from '../views/Recipe/Form.vue'

import RecipeEdit from '../views/Recipe/Edit.vue'

import RecipeCreate from '../views/Recipe/Create.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    routes:[
        {path:'/register', component:Register},
        
        {path:'/login', component:Login},

        {path:'/', component:Index},

        {path:'/recipes/:id', component:RecipeShow},

        {path:'/recipes/:id/edit', component:RecipeEdit,},

        {path:'/create', component:RecipeCreate,},
    ]
})

export default router