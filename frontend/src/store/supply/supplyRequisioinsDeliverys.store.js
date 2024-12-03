import { httpRequest } from '@/services/httpRequest.service'

import Vue from 'vue'

const mutations = {

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
        } else {
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
    supplyRequisitionsDeliverysListGetter: (state) => state.supplyRequisitionsDeliverysList
}

const state = () => ({
    supplyRequisitionsDeliverysList: [],
    supplyRequisitionsDeliverysListLoading: false
})

export default {
    mutations,
    getters,
    actions,
    state,
}