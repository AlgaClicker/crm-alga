import { httpRequest } from '@/services/httpRequest.service'

const mutations = {
    ACCOUNT_OPTIONS_SET(state, data){
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.accountsOptions = data.data
        }else{
            state.accountsOptions = []
        }
    },
    ACCOUNT_OPTIONS_ERROR(state, data){
        state.accountsOptionsError = data
    }
}

const actions = {
    async accountOptionsSetActions({ commit }){
        const data = await httpRequest("buisness/accounts/option", 'post', {})

        if(data.code == 200){
            commit("ACCOUNT_OPTIONS_SET", data)
        }else{
            commit("ACCOUNT_OPTIONS_ERROR", data.error)
        }
    },
    async accountOptionsEditChatActions({ commit }, id){

        const data = await httpRequest("buisness/accounts/option", 'patch', 
            {  room_id: id }
        )

        commit("ACCOUNT_OPTIONS_SET", data)
    }
}

const getters = {
    accountsOptionsGetter: (state) => state.accountsOptions
}

const state = () => ({
    accountsOptions: {},
    accountsOptionsError: ''
})

export default {
    mutations,
    getters,
    actions,
    state,
}
