import { httpRequest } from '@/services/httpRequest.service'
import { httpRequestAuth } from '@/services/httpRequestAuth.service'
import { servicesSocketInit, socketServiceJoinCompany, leaveChannel } from '@/services/sockets.service'

import Vue from 'vue'

const mutations = {
    AUTH_ERROR(state, error){
        localStorage.removeItem('token')
        state.loginError = null
        state.profileError = null
        state.loginError = error.data.message
    },
    PROFILE_ERROR(state, error){
        localStorage.removeItem('token')
        state.loginError = null
        state.profileError = error.data.message
    },  
    PROFILE_SUCCESS(state, data){
        state.loginError = null
        state.profileError = null
        state.profile = data.data
        state.roles = data.data.roles.service
    },
    PROFILE_OPTIONS(state, data){
        state.profileError = null
        state.profile.options = data
    },
    LOGIN_SUCCESS(state, data){

        localStorage.removeItem('token')
        localStorage.removeItem('company')
        localStorage.setItem('token', data.data.token)
        localStorage.setItem('company', JSON.stringify(data.data.company))
        localStorage.setItem('account', JSON.stringify(data.data.account))
        
        state.loginError = null
        state.profileError = null
        state.profile = data.data.account
        state.company = data.data.company
        state.account = data.data.account

        state.roles = data.data.account.roles.service
        
        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: `Вход под ${state.profile.username}`
        })
    },
    LOGIN_LOADING(state, load){
        state.loginLoading = load
    },
    LOGOUT(state){   
        
        localStorage.removeItem('token')
        localStorage.removeItem('company')
        localStorage.removeItem('account')
        
        leaveChannel()
        
        state.loginError = null
        state.profileError = null
        state.roles = ''
    },
}

const actions = {
    async loginActions( { commit }, form ) {

        commit('LOGIN_LOADING', true)
        const data = await httpRequestAuth('auth/login/', 'post', form)
        commit('LOGIN_LOADING', false)
        
        if(typeof data == 'undefined'){
            let error = ''
            error.data.message = 'ошибка'
            commit("AUTH_ERROR", data)
        }
        if(data?.code == 200){
            commit("LOGIN_SUCCESS", data)
            servicesSocketInit()
            socketServiceJoinCompany(data.data.company.id)
        }
        else{
            commit("AUTH_ERROR", data)
        }
    },
    async getProfileActions( { commit } ){

        const data = await httpRequestAuth('auth/me/', 'post', null)

        if(typeof data == 'undefined' || typeof data.code == 'undefined' ){
            commit('LOGOUT')
        }
        else if(data.code == 200){
            commit("PROFILE_SUCCESS", data)
        }
        else{
            commit("PROFILE_ERROR", data)
        }
    },
    async setOptionsActions( { commit }, options) {

        const data = await httpRequest('crm/account/option/', 'patch', options)

        if(data.code == 200){
            commit('PROFILE_OPTIONS', data.data)
        }else{
            commit('PROFILE_ERROR', data.data)
        }
    },
    async logoutActions( { commit } ) {
        commit('LOGOUT')
    },    
}

const getters = {
    loginErrorGetter: (state) => state.loginError,
    profileErrorGetter: (state) => state.profileError,
    accountGetter: (state) => state.account,
    loginLoadingGetter: (state) => state.loginLoading,
    rolesGetter: (state) => state.roles,
    profileGetter: (state) => state.profile,
    companyGetter: (state) => state.company,
    profileOptionsGetter: (state) => state.profile.options,
    isAuthorisation: () => localStorage.getItem('token') || "",
    profleDirectoryEditAccessGetter: (state) => state.profile.roles.service == 'upravlenie' || state.profile.roles.service == 'snabzenie',
}

const state = () => ({
    loginError:  null,
    loginStatus: null,
    loginLoading: false,
    account: {},
    profileError: '',
    accountOptions: {},
    roles: '',
    profile: {},
    company: {}
})

export default {
    mutations,
    getters,
    actions,
    state,
}
