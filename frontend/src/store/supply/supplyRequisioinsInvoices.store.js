import { httpRequest } from '@/services/httpRequest.service'
//import router from '@/router/index'

import Vue from 'vue'

const mutations = {
    SUPPLY_REQUISITION_INVOICES_SET_LIST(state, data){
        state.supplyRequisitionInvoicesError = null

        if(typeof data.options != 'undefined'){
            state.supplyRequisitionInvoicesOptions.pagginate = data.options.pagginate
            state.supplyRequisitionInvoicesOptions.orderBy = data.options.orderBy
        } 
        
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.supplyRequisitionInvoicesList = data.data
        }else{
            state.supplyRequisitionInvoicesList = []
        }
    },
    SUPPLY_REQUISITION_INVOICES_SET_OPTIONS(state, options){
        state.supplyRequisitionInvoicesOptions = options
    },
    SUPPLY_REQUISITION_INVOICES_SET_LOADING(state){
        state.supplyRequisitionInvoicesLoading = !state.supplyRequisitionInvoicesLoading
    },
    SUPPLY_REQUISITION_INVOICES_SET_ERROR(state, error){

        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: error
        })

        state.supplyRequisitionInvoicesError = error
    }
}

const actions = {   
    async supplyRequisioinsInvoicesSetListActions({ commit, getters }){

        let options = getters.supplyRequisitionInvoicesOptionsGetter

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
        
        commit('SUPPLY_REQUISITION_INVOICES_SET_LOADING')
        const data = await httpRequest("crm/snabzheniye/invoices", 'post', {
            options: options
        })
        commit('SUPPLY_REQUISITION_INVOICES_SET_LOADING')
        
        if(data.code == 200){
            commit("SUPPLY_REQUISITION_INVOICES_SET_LIST", data)
        }else{
            commit("SUPPLY_REQUISITION_INVOICES_SET_ERROR", data.error)
        }
    },
    supplyRequisioinsInvoicesSetOptionsActions( { commit }, options ){
        commit('SUPPLY_REQUISITION_INVOICES_SET_OPTIONS', options)
    },
}

const getters = {
    supplyRequisitionInvoicesListGetter: (state) => state.supplyRequisitionInvoicesList,
    supplyRequisitionInvoicesErrorGetter: (state) => state.supplyRequisitionInvoicesError,
    supplyRequisitionInvoicesOptionsGetter: (state) => state.supplyRequisitionInvoicesOptions,
    supplyRequisitionInvoicesLoadingGetter: (state) => state.supplyRequisitionInvoicesLoading,
    supplyRequisitionInvoicesPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.supplyRequisitionInvoicesOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    }, 
}

const state = () => ({
    supplyRequisitionInvoicesList: [],
    supplyRequisitionInvoicesError: '',
    supplyRequisitionInvoicesOptions: {
        filter: {
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
    },
    supplyRequisitionInvoicesLoading: false
})

export default {
    mutations,
    getters,
    actions,
    state,
}