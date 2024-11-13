import { httpRequest } from '@/services/httpRequest.service'
import router from '@/router/index'

import Vue from 'vue'

const mutations = {
    SUPPLY_FREE_REQUISITION_SET_LIST(state, data){
        
        state.supplyFreeRequisitionError = null

        if(typeof data.options.filter == 'undefined'){
            state.supplyFreeRequisitionOptions.filter = {
                master: null,
                status: null,
                created_at: null,
                specification: null
            }
        }

        if(typeof data.options != 'undefined'){
            state.supplyFreeRequisitionOptions.pagginate = data.options.pagginate
            state.supplyFreeRequisitionOptions.orderBy = data.options.orderBy
        } 

        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.supplyFreeRequisitionList = data.data
        }else{
            state.supplyFreeRequisitionList = []
        }
    },
    SUPPLY_FREE_REQUISITION_ERROR(state, error){
        state.supplyFreeRequisitionError = error
    },
    SUPPLY_FREE_REQUISITION_SET_LOADING(state){
        state.supplyFreeRequisitionLoading = !state.supplyFreeRequisitionLoading
    },
    SUPPLY_FREE_REQUISITION_PICK_UP(state, id){
        
        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Заявка взята в работу'
        })    

        state.supplyFreeRequisitionList = state.supplyFreeRequisitionList.filter( item => item.id != id )
        
        router.push(`/crm/supply/requisition/my/info/${id}/detail`)
    },  
    SUPPLY_FREE_REQUISITION_SET_OPTIONS(state, options){
        state.supplyFreeRequisitionOptions = options
    },
    SUPPLY_FREE_REQUISITION_SET_FILTER_DATE(state, date){
        state.supplyFreeRequisitionOptions.filter.created_at = date
    },
    SUPPLY_FREE_REQUISITION_SET_FILTER(state, filter){
        let created_at = state.supplyFreeRequisitionOptions.filter?.created_at
        state.supplyFreeRequisitionOptions.filter = filter
        state.supplyFreeRequisitionOptions.filter.created_at = created_at
    },
}

const actions = {   
    async supplyFreeRequisitionSetListActions({ commit, getters }){

        commit('SUPPLY_FREE_REQUISITION_SET_LOADING')

        let options = getters.supplyFreeRequisitionOptionsGetter

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

        const data = await httpRequest("crm/snabzheniye/requisitions/list", 'post', {
            options: options
        })
        commit('SUPPLY_FREE_REQUISITION_SET_LOADING')
        
        if(data.code == 200){
            commit("SUPPLY_FREE_REQUISITION_SET_LIST", data)
        }else{
            commit("SUPPLY_FREE_REQUISITION_ERROR", data.error)
        }
    },
    async supplyFreeRequisitionPickUpActions({ commit }, id){

        let data = await httpRequest(`crm/snabzheniye/requisitions/${id}/get`, 'post', { id: id } )

        if(data.code == 200){
            commit('SUPPLY_FREE_REQUISITION_PICK_UP', id)
        }else{
            commit("SUPPLY_FREE_REQUISITION_ERROR", data.error)
        }

    },
    supplyFreeRequisitionSetOptionsActions( { commit }, options ){
        commit('SUPPLY_FREE_REQUISITION_SET_OPTIONS', options)
    },
    supplyFreeRequisitionSetFilterActions( { commit }, filter ){
        commit('SUPPLY_FREE_REQUISITION_SET_FILTER', filter)
    },
    supplyFreeRequisitionSetDateFilterActions( { commit}, filter ){
        commit('SUPPLY_FREE_REQUISITION_SET_FILTER_DATE', filter)
    }
}

const getters = {
    supplyFreeRequisitionListGetter: (state) => state.supplyFreeRequisitionList,
    supplyFreeRequisitionErrorGetter: (state) => state.supplyFreeRequisitionError, 
    supplyFreeRequisitionLoadingGetter: (state) => state.supplyFreeRequisitionLoading,
    supplyFreeRequisitionOptionsGetter: (state) => state.supplyFreeRequisitionOptions, 
    supplyFreeRequisitionPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.supplyFreeRequisitionOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
}

const state = () => ({
    supplyFreeRequisitionList: [],
    supplyFreeRequisitionError: null,
    supplyFreeRequisitionLoading: false,
    supplyFreeRequisitionOptions: {
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