import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'  

const mutations = {
    SUPPLY_MY_REQUISITION_SET_LIST(state, data){
        state.supplyMyRequisitionError = null

        if( typeof data.options.filter == 'undefined'){
            state.supplyMyRequisitionOptions.filter = {
                master: null,
                status: null,
                created_at: null,
                specification: null
            }
        }
        
        if(typeof data.options != 'undefined'){
            state.supplyMyRequisitionOptions.pagginate = data.options.pagginate
            state.supplyMyRequisitionOptions.orderBy = data.options.orderBy
        } 
        
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.supplyMyRequisitionList = data.data
        }else{
            state.supplyMyRequisitionList = []
        }
    },
    SUPPLY_MY_REQUISITION_SET(state, data){
        if(data != [] && data != null && typeof data != 'undefined'){
            state.supplyMyRequisition = data
        }else{
            state.supplyMyRequisition = {}
        }
    },
    SUPPLY_MY_REQUISITION_SET_LOADING(state){
        state.supplyMyRequisitionLoading = !state.supplyMyRequisitionLoading
    },
    SUPPLY_MY_REQUISITION_SET_ERROR(state, data){

        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: data
        })
        
        state.supplyMyRequisitionError = data
    },
    SUPPLY_MY_REQUISITION_SET_OPTIONS(state, options){
        state.supplyMyRequisitionOptions = options
    },
    SUPPLY_MY_REQUISITION_SET_FILTER(state, filter){
        let created_at = state.supplyMyRequisitionOptions.filter?.created_at
        state.supplyMyRequisitionOptions.filter = filter
        state.supplyMyRequisitionOptions.filter.created_at = created_at
    },
    SUPPLY_MY_REQUISITION_SET_FILTER_DATE(state, filter){
        state.supplyMyRequisitionOptions.filter.created_at = filter
    }
}

const actions = {   
    async supplyMyRequisitionSetListActions({ commit, getters }){

        let options = getters.supplyMyRequisitionOptionsGetter

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

        commit('SUPPLY_MY_REQUISITION_SET_LOADING')
        const data = await httpRequest("crm/snabzheniye/requisitions/my", 'post', {
            options: options
        })
        commit('SUPPLY_MY_REQUISITION_SET_LOADING')
        
        if(data.code == 200){
            commit("SUPPLY_MY_REQUISITION_SET_LIST", data)
        }else{
            commit("SUPPLY_MY_REQUISITION_SET_ERROR", data.error)
        }
    },
    async supplyMyRequisitionSetActions( { commit }, id){

        const data = await httpRequest(`crm/snabzheniye/requisitions/${id}`, 'post', {})
        
        if(data.code == 200){
            commit("SUPPLY_MY_REQUISITION_SET", data.data)
        }else{
            commit("SUPPLY_MY_REQUISITION_SET_ERROR", data.error)
        }
    },
    async supplyMyRequisitionCancelActions({ commit }, params){
        
        const data = await httpRequest(`crm/snabzheniye/requisitions/${params.requisitionId}/cancel`, 'post', { 
            description: params.description
        })
        
        if(data.code != 200){
            commit("SUPPLY_MY_REQUISITION_SET_ERROR", data.data.message)
        } else {
            Vue.notify({
                group: 'success',
                title: 'Успех',
                type: 'success',
                text: 'Заявка отменена'
            })    
    
        }
    },
    supplyMyRequisitionSetOptionsActions( { commit }, options ){
        commit('SUPPLY_MY_REQUISITION_SET_OPTIONS', options)
    },
    supplyMyRequisitionSetFilterActions( { commit }, filter ){
        commit('SUPPLY_MY_REQUISITION_SET_FILTER', filter)
    },
    supplyMyRequisitionSetDateFilterActions( { commit}, filter ){
        commit('SUPPLY_MY_REQUISITION_SET_FILTER_DATE', filter)
    }
}

const getters = {
    supplyMyRequisitionGetter: (state) => state.supplyMyRequisition,
    supplyMyRequisitionListGetter: (state) => state.supplyMyRequisitionList,
    supplyMyRequisitionErrorGetter: (state) => state.supplyMyRequisitionError, 
    supplyMyRequisitionLoadingGetter: (state) => state.supplyMyRequisitionLoading,
    supplyMyRequisitionOptionsGetter: (state) => state.supplyMyRequisitionOptions, 
    supplyMyRequisitionFilterGetter: (state) => state.supplyMyRequisitionOptions.filter,
    supplyMyRequisitionPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.supplyMyRequisitionOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
    
}

const state = () => ({
    supplyMyRequisition: {},
    supplyMyRequisitionList: [],
    supplyMyRequisitionError: null,
    supplyMyRequisitionLoading: false,
    supplyMyRequisitionOptions: {
        filter: {
            master: null,
            status: null,
            created_at: null,
            specification: null
        },
        orderBy: {
            createdAt: "DESC"
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