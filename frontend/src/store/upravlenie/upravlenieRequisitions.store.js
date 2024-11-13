import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'  

const mutations = {
    UPRAVLENIE_REQUISITIONS_SET_LIST(state, data){
        state.upravlenieRequisitionsError = null

        if( typeof data.options.filter == 'undefined'){
            state.upravlenieRequisitionsOptions.filter = {
                manager: null,
                master: null,
                status: null,
                created_at: null,
                specification: null
            }
        }

        if(typeof data.options != 'undefined'){
            state.upravlenieRequisitionsOptions.pagginate = data.options.pagginate
            state.upravlenieRequisitionsOptions.orderBy = data.options.orderBy
        } 
        
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.upravlenieRequisitionsList = data.data
        }else{
            state.upravlenieRequisitionsList = []
        }
        
    },
    UPRAVLENIE_REQUISITIONS_SET_OPTIONS(state, options){
        state.upravlenieRequisitionsOptions.pagginate = options.pagginate
    },
    UPRAVLENIE_REQUISITIONS_SET_MANAGE(state, data){

        let index = state.upravlenieRequisitionsList.indexOf(state.upravlenieRequisitionsList.filter( item => item.id == data.data.id)[0])

        Vue.set(
            state.upravlenieRequisitionsList,
            index,
            data.data
        ),
        
        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Менаджер назначен'
        })
    },
    UPRAVLENIE_REQUISITIONS_SET_FILTER_DATE(state, date){
        state.upravlenieRequisitionsOptions.filter.created_at = date
    },
    UPRAVLENIE_REQUISITIONS_SET_FILTER(state, filter){
        let created_at = state.upravlenieRequisitionsOptions.filter.created_at
        state.upravlenieRequisitionsOptions.filter = filter
        state.upravlenieRequisitionsOptions.filter.created_at = created_at
    },
    UPRAVLENIE_REQUISITIONS_SET_LOADING(state){
        state.upravlenieRequisitionsListLoading = !state.upravlenieRequisitionsListLoading
    },
    UPRAVLENIE_REQUISITIONS_SET_ERROR(state, data){

        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: data
        })

        state.upravlenieRequisitionsError = data
    }
}

const actions = {
    async upravlenieRequisitionsSetListActions({ commit, getters }){

        let options = getters.upravlenieRequisitionsOptionsGetter

        let filter = {}
        let count = 0

        for (let key in options.filter) {
            if(options.filter[key] != null){
                count += 1
                filter[key] = options.filter[key]
            }
        }

        if(count != 0){
            options.filter = filter
        } else  {
            delete options.filter 
        }

        commit('UPRAVLENIE_REQUISITIONS_SET_LOADING')
        const data = await httpRequest("crm/upravleine/requisitions/", 'post', {
            options: options
        })
        commit('UPRAVLENIE_REQUISITIONS_SET_LOADING')

        if(data.code == 200){
            commit("UPRAVLENIE_REQUISITIONS_SET_LIST", data)
        }else{
            commit("UPRAVLENIE_REQUISITIONS_SET_ERROR", data.error)
        }

    },
    async upravlenieRequisitionsSetManageActions( { commit }, form){
        const data = await httpRequest(`crm/snabzheniye/requisitions/${form.idRequisition}/manage/set`, 'post', { account: form.idMenager }) 

        if( data.code == 200 ){
            commit('UPRAVLENIE_REQUISITIONS_SET_MANAGE', data)
        }else{
            commit("UPRAVLENIE_REQUISITIONS_SET_ERROR", data.error)
        }
    },
    upravlenieRequisitionsSetOptionsActions( { commit }, options ){
        commit('UPRAVLENIE_REQUISITIONS_SET_OPTIONS', options)
    },
    upravlenieRequisitionsSetFilterActions( { commit }, filter ){
        commit('UPRAVLENIE_REQUISITIONS_SET_FILTER', filter)
    },
    upravlenieRequisitionsSetDateFilterActions( { commit }, filter ){
        commit('UPRAVLENIE_REQUISITIONS_SET_FILTER_DATE', filter)
    }
}

const getters = {
    upravlenieRequisitionsListGetter: (state) => state.upravlenieRequisitionsList,
    upravlenieRequisitionsErrorGetter: (state) => state.upravlenieRequisitionsError,
    upravlenieRequisitionsListLoadingGetter: (state) => state.upravlenieRequisitionsListLoading,
    upravlenieRequisitionsOptionsGetter: (state) => state.upravlenieRequisitionsOptions,
    upravlenieRequisitionsPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.upravlenieRequisitionsOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
}

const state = () => ({
    upravlenieRequisitionsList: [],
    upravlenieRequisitionsError: '',
    upravlenieRequisitionsListLoading: false,
    upravlenieRequisitionsOptions: {
        filter: {
            manager: null,
            master: null,
            status: null,
            created_at: null,
            specification: null
        },
        orderBy: {
            created_at: "DESC"
        },
        pagginate: {
            pages: 1,
            page: 1,
            limit: 10
        },
    }
})

export default {
    mutations,
    getters,
    actions,
    state,
}

