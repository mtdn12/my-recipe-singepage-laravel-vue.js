export default {
    state : {
        api_token:null,
        user_id:null
    },

    get(){
        this.state.api_token = localStorage.getItem('api_token')

        this.state.user_id = parseInt(localStorage.getItem('user_id'))
    },

    set(user_id,api_token){
        localStorage.setItem('user_id',user_id);

        localStorage.setItem('api_token',api_token);

        this.get();
    },

    remove(){
        localStorage.removeItem('user_id');

        localStorage.removeItem('api_token');

        this.get();
    }
}