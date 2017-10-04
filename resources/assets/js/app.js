import Vue from 'vue'

import App from'./App.vue'

import router from'./router/routes'


export const bus = new Vue();

const app = new Vue({
    el:'#root',
    template:`<app></app>`,
    components:{App},
    router,
  
})

