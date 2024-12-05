import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'

const mutations = {
    NOTIFICATIONS_ERROR(data){
        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: data.message
        })
    },
    NOTIFICATIONS_SET_LIST(state, data){

        state.notificationOptions = data.options
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.notificationNewList = data.data.filter( item => item.readed == false)
        }else{
            state.notificationNewList = []
        }
        
    },
    NOTIFICATIONS_NEW(state, notification){
        
        Vue.notify({
            group: 'success',
            title: notification.from_account.username,
            type: 'success',
            text: notification.message
        })
   
        state.notificationNewList.push(notification)
    },
    NOTIFICATIONS_READ(state, id){
        state.notificationNewList = state.notificationNewList.filter( item => item.id != id)
    },
    NOTIFICATIONS_LOADING(state){
        state.notificationLoading = !state.notificationLoading
    },
    NOTIFICATIONS_PAGGINATE(state, data){
        /* if(state.notificationOptions.pagginate.pages > state.notificationOptions.pagginate.page){
            state.notificationOptions.pagginate.page += 1
        } */
        state.notificationNewList.unshift(...data)
    }
}

const actions = {
    async notificationSetActions( { commit, getters }){
        
        commit('NOTIFICATIONS_LOADING')
        const data = await httpRequest('notification/', 'post', { 
            options: getters.notificationOptions
        }) 
        commit('NOTIFICATIONS_LOADING') 
   
        if(data.status == 406){
            commit("NOTIFICATIONS_SET_LIST", data)
        }
        if(data.code == 200){
            commit("NOTIFICATIONS_SET_LIST", data)
        }
        else{
            commit("NOTIFICATIONS_ERROR", data.data)
        }
    },
    notificationNewActions( { commit }, notification ){
        commit('NOTIFICATIONS_NEW', notification)
    },
    async notificationReadActions( { commit }, id){
        commit('NOTIFICATIONS_READ', id)

        const data = await httpRequest(`notification/${id}/read`, 'post', {}) 

        if(data.code == 200){
            commit("NOTIFICATIONS_READ", id)
        }
        else{
            commit("NOTIFICATIONS_ERROR", data.data)
        }
    },
    async notificationPagginateActions( { commit, getters } ){

        if(getters.notificationOptionsGetter.pagginate.page < getters.notificationOptionsGetter.pagginate.pages){
            
            let options = getters.notificationOptionsGetter
            options.pagginate.page += 1

            const data = await httpRequest('notification/', 'post', { 
                options: options
            }) 

            commit('NOTIFICATIONS_PAGGINATE', data.data)
        }
    },
}
    
const getters = {
    notificationListGetter: (state) => state.notificationList,
    notificationListNewGetter: (state) => state.notificationNewList,
    notificationOptionsGetter: (state) => state.notificationOptions,
}

const state = () => ({
   notificationList: [],
   notificationNewList: [],
   notificationLoading: false,
   notificationOptions: {
        pagginate: {
            page: 1,
            limit: 10,
            pages: 1
        },
        orderBy: []
    },
})

export default {
    mutations,
    getters,
    actions,
    state,
}