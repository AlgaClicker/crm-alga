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
    COMPANYCONFIRMED(state, company){

        state.companyConfirmed = company
        console.log("COMPANYCONFIRMED",state.companyConfirmed)
        state.loginLoading = true;
    },
    REGISTERSTATUS(state,status) {
        state.registrationStatus = status
    },
    REGISTERERROR(state,error) {
        state.registrationError = error
    },

}

const actions = {
    async defaultLoginParam( { commit } ) {
        commit('LOGIN_LOADING', false)
        commit("REGISTERERROR",null)
    },
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
    async companyRegistration({ commit }, data) {

        try {
            const dataRes = await httpRequestAuth('auth/registration/', 'post', data)

            if(dataRes.code == 200){
                //console.log(data,key)
                commit("REGISTERSTATUS",true)
            } else {
                commit("REGISTERSTATUS",false)
                commit("REGISTERERROR",dataRes.data.message)
            }
        } catch (e) {
            console.log("companyRegistration ERROR:",e)
            commit("REGISTERSTATUS",false)
            commit("REGISTERERROR",e.message)
        }

    },
    async companyConfirmed({ commit }, key) {
        commit("LOGIN_LOADING",true)
       // console.log("companyConfirmed",commit,key)
        const data = await httpRequestAuth('auth/registration/confirm?k='+key, 'get', )

        if(data.code == 200){
            //console.log(data,key)
            commit('COMPANYCONFIRMED',data.data)
        }
    },

}

const getters = {
    loginErrorGetter: (state) => state.loginError,
    profileErrorGetter: (state) => state.profileError,
    accountGetter: (state) => state.account,
    loginLoadingGetter: (state) => state.loginLoading,
    rolesGetter: (state) => state.roles,
    profileGetter: (state) => state.profile,
    registrationStatusGetter: (state)  => state.registrationStatus,
    registrationErrorGetter: (state)  => state.registrationError,
    companyGetter: (state)  => state.company,
    companyConfirmedGetter: (state)  => state.companyConfirmed,
    profileOptionsGetter: (state) => state.profile.options,
    isAuthorisation: () => localStorage.getItem('token') || "",
    profleDirectoryEditAccessGetter: (state) => state.profile.roles.service == 'upravlenie' || state.profile.roles.service == 'snabzenie',
}

const state = () => ({
    loginError:  null,
    loginStatus: null,
    loginLoading: false,
    registrationStatus: false,
    registrationError: "",
    account: {},
    profileError: '',
    accountOptions: {},
    roles: '',
    profile: {},
    company: {},
    companyConfirmed: {},
})

export default {
    mutations,
    getters,
    actions,
    state,
}
