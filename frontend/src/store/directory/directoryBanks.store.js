import { httpRequest } from '@/services/httpRequest.service'

const mutations = {
    DIRECTORY_BANKS_SET_LIST(state, data){
        if(typeof data.options != 'undefined'){
            state.directoryBanksOptions = data.options
        }

        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.directoryBanksList = data.data
        }else{
            state.directoryBanksList = []
        }
    },
    DIRECTORY_BANKS_ERROR(state, error){
        state.directoryBanksError = error
    },
    DIRECTORY_BANKS_LOADING(state){
        state.directoryBanksLoading = !state.directoryBanksLoading
    },
    DIRECTORY_BANKS_SET_OPTIONS(state, option){
        state.directoryBanksOptions = option
    },
    DIRECTORY_BANKS_SEARCH_LOADING(state){
        state.directoryBanksSearchLoading = !state.directoryBanksSearchLoading
    }
}

const actions = {  
    async directoryBanksGetActions( { commit, getters } ) {

        commit('DIRECTORY_BANKS_LOADING')
        const data = await httpRequest('directory/banki/', 'post', { 
            options: getters.directoryBanksOptionsGetter 
        })
        commit('DIRECTORY_BANKS_LOADING')

        if(data.status == 500){
            commit('DIRECTORY_BANKS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_BANKS_SET_LIST', data)
        }
        else{
            commit('DIRECTORY_BANKS_ERROR', data.data)
        }
    },
    async directoryBanksSearchActions({ commit, getters }, val){

        commit('DIRECTORY_BANKS_SEARCH_LOADING')
        const data = await httpRequest('directory/banki/search', 'post', { 
            name: val, 
            options: getters.directoryBanksOptionsGetter 
        })
        commit('DIRECTORY_BANKS_SEARCH_LOADING')
    
        if(data.status == 500){
            commit('DIRECTORY_BANKS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_BANKS_SET_LIST', data)
        }
        else{
            commit('DIRECTORY_BANKS_ERROR', data.data)
        }
    },
    directoryBanksSetOptionsActions( { commit }, option ){
        commit('DIRECTORY_BANKS_SET_OPTIONS', option)
    },
}

const getters = {
    directoryBanksErrorGetter: (state) => state.directoryBanks,
    directoryBanksListGetter: (state) => state.directoryBanksList,
    directoryBanksLoadingGetter: (state) => state.directoryBanksLoading,
    directoryBanksSearchLoadingGetter: (state) => state.directoryBanksSearchLoading,
    directoryBanksOptionsGetter: (state) => state.directoryBanksOptions,
    directoryBanksPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.directoryBanksOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
}

const state = () => ({
    directoryBanksList: [],
    directoryBanksError: '',
    directoryBanksLoading: false,
    directoryBanksSearchLoading: false,
    directoryBanksOptions: {
        pagginate: {
            page: 1,
            limit: 10,
            pages: 1
        },
        orderBy: {
            name: "ASC",
        }
    },
})

export default {
    mutations,
    getters,
    actions,
    state,
}