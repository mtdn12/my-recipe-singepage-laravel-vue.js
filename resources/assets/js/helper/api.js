import axios from 'axios'

import Auth from '../store/Auth'


export function post(url,data){
    return axios({
        method:'POST',
        url:url,
        data: data,
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`
        }      
    });
}

export function get(url){
    return axios({
        method:'GET',
        url:url,       
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`
        }      
    });
}

export function del(url){
    return axios({
        method:'DELETE',
        url:url,                      
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`
        }
    })
}

export function put(url,data){
    return axios({
        method:'PUT',
        url:url,
        data: data,
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`,             
        }      
    });
}