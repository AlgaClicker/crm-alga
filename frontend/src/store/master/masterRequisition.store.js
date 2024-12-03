import { httpRequest } from "@/services/httpRequest.service"

import Vue from 'vue'

const mutations = {
    MASTER_REQUISITION_SET_LIST(state, data){

        state.masterRequisitionError = null
        
        if( typeof data.options.filter == 'undefined'){
            state.masterRequisitionOptions.filter = {
                manager: null,
                master: null,
                status: null,
                created_at: null,
                specification: null
            }
        }
        
        if(typeof data.options != 'undefined'){
            state.masterRequisitionOptions.pagginate = data.options.pagginate
            state.masterRequisitionOptions.orderBy = data.options.orderBy,
            state.masterRequisitionOptions.filter = {
                manager: null,
                master: null,
                status: null,
                created_at: null,
                specification: null
            }
        } 
        
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.masterRequisitionList = data.data
        }else{
            state.masterRequisitionList = []
        }
    },
    MASTER_REQUISITION_SET(state, data){
        state.masterRequisition = data
    },
    MASTER_REQUISITION_SET_MATERIAL(state, materials){

        let index = 1
        
        state.masterRequisitionMaterialList = materials.map( item => {
            const select = {}

            select.index = index 
            select.quantity = item.quantity 
            select.ordered = 0
            select.delivery = {}
            select.name = ''
            select.unit = null

            if(typeof item.requisition_material.specification_material != 'undefined'){
                select.name = item.requisition_material.specification_material.fullname 
                select.unit = item.requisition_material.specification_material.unit
            } else if (typeof item.requisition_material.directory_material != 'undefined'){
                select.name = item.requisition_material.directory_material.name 
                select.unit = item.requisition_material.directory_material.unit
            } else {
                select.name = item.requisition_material.name 
                select.unit = item.requisition_material.unit
            }

            if(typeof item.deliverys != 'undefined'){
                select.delivery = item.deliverys
            }

            if( typeof item.remnant != 'undefined'){
                select.ordered = item.remnant
            }

            index += 1

            return select
        })
    },
    MASTER_REQUISITION_DELETE(state, id){

        state.masterRequisitionList = state.masterRequisitionList.filter(item => item.id != id)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Черновик удален'
        })

        /*MASTER_REQUISITION_DELETE(state, id){
            
            state.directoryBrigadesList = state.directoryBrigadesList.filter(item => item.id != id)
            a5e6ed7ae65b31b85253c45c7b4f7407e6e2884f
            state.directoryBrigadesError = ''

            Vue.notify({
                group: 'success',
                title: 'Успех',
                type: 'success',
                text: 'Черновик удален'
            })

            state.directoryBrigadesSelectedList = [] 
        },*/
    },
    MASTER_REQUISITION_LOADING(state){
        state.masterRequisitionLoading = !state.masterRequisitionLoading
    },
    MASTER_REQUISITION_SET_OPTIONS(state, options){
        state.masterRequisitionOptions.pagginate = options.pagginate
        state.masterRequisitionOptions.orderBy = options.orderBy
    },
    MASTER_REQUISITION_SET_FILTER(state, filter){

        let created_at = state.masterRequisitionOptions.filter?.created_at
        state.masterRequisitionOptions.filter = filter
        state.masterRequisitionOptions.filter.created_at = created_at
    },
    MASTER_REQUISITION_SET_FILTER_DATE(state, date){

        state.masterRequisitionOptions.filter.created_at = date
    },
    MASTER_REQUISITIONS_FIXED_DRAFT(state, draft){
        state.masterRequisitionList = state.masterRequisitionList.filter( item => item.id != draft.id)
    },
    MASTER_REQUISITION_SEND(state, req){
        state.masterRequisitionList.push(req)
    },
    MASTER_REQUISITION_ERROR(state, error){
        state.masterRequisitionError = error
    },
}
 
const actions = {
    async masterRequisitionDeliveryConfirmed( { commit, getters }){
        console.log("masterRequisitionDeliveryConfirmed",commit,getters)

    },
    async masterRequisitionSetListActions( { commit, getters }){

        commit('MASTER_REQUISITION_LOADING')

        let options = getters.masterRequisitionOptionsGetter

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

        const data = await httpRequest('crm/master/requisition', 'post', {
            options: options
        } )
        commit('MASTER_REQUISITION_LOADING')

        if(data.code == 200){
            commit('MASTER_REQUISITION_SET_LIST', data)
        }
        else{
            commit('MASTER_REQUISITION_ERROR', data.data)
        }
    },
    masterRequisitionSendActions({ commit }, req){
        commit('MASTER_REQUISITION_SEND', req)
    },
    masterRequisitionFixedDraftActions({ commit }, draft){
        commit('MASTER_REQUISITIONS_FIXED_DRAFT', draft)
    },
    async masterRequisitionSetActions({ commit }, id){
        commit('MASTER_REQUISITION_LOADING')
        const data = await httpRequest(`crm/master/requisition/${id}`, 'post', {} )
        commit('MASTER_REQUISITION_LOADING')

        if(data.code == 200){
            commit('MASTER_REQUISITION_SET', data.data)
        }
        else{
            commit('MASTER_REQUISITION_ERROR', data.data)
        }
    },
    async masterRequisitionGetDeliveryOne({ commit}, requisition_id,delivery_id){
        console.log(delivery_id,commit,requisition_id)
        const data = await httpRequest(`crm/master/requisition/${requisition_id}/delivery/${delivery_id}`, 'post', {} )

        console.log(delivery_id,commit,requisition_id,data)
    },
    async masterRequisitionGetDelivery({ commit}, requisition_id){

        commit('MASTER_REQUISITION_LOADING')

        const data = await httpRequest(`crm/master/requisition/${requisition_id}/delivery/`, 'post', {} )

        commit('MASTER_REQUISITION_LOADING')

        if(data.code == 200){
            commit('MASTER_REQUISITION_SET_MATERIAL', data.data)
        } else {
            commit('MASTER_REQUISITION_ERROR', data.data)
        }
    },

    async masterRequisitionSetMaterialActions({ commit, getters }){

        commit('MASTER_REQUISITION_LOADING')

        const data = await httpRequest(`crm/master/requisition/${getters.masterRequisitionGetter.id}/delivery`, 'post', {} )

        commit('MASTER_REQUISITION_LOADING')

        if(data.code == 200){
            commit('MASTER_REQUISITION_SET_MATERIAL', data.data)
        } else {
            commit('MASTER_REQUISITION_ERROR', data.data)
        }
    },
    async masterRequisitionDeleteActions({ commit }, id){
        
        const data = await httpRequest(`crm/master/requisition`, 'delete', { id: id } )

        if(data.code == 200){
            commit('MASTER_REQUISITION_DELETE', id)
        }
        else{
            commit('MASTER_REQUISITION_ERROR', data.data)
        }

    },
    async masterRequisitionUnFixedActions({ commit}, id ){

        const data = await httpRequest(`crm/master/requisition/${id}/unfixed`, 'post', {} )

        if(data.code == 200){
            Vue.notify({
                group: 'success',
                title: 'Успех',
                type: 'success',
                text: 'Заявка отменена'
            })
        }
        else{
            commit('MASTER_REQUISITION_ERROR', data.data)
        }

    },
    masterRequisitionSetOptionsActions( { commit }, options ){
        commit('MASTER_REQUISITION_SET_OPTIONS', options)
    },
    masterRequisitionSetFilterActions( { commit }, filter ){
        commit('MASTER_REQUISITION_SET_FILTER', filter)
    },
    masterRequisitionSetDateFilterActions( { commit }, filter ){
        commit('MASTER_REQUISITION_SET_FILTER_DATE', filter)
    }
}
    
const getters = {
    masterRequisitionGetter: (state) => state.masterRequisition,
    masterRequisitionListGetter: (state) => state.masterRequisitionList,
    masterRequisitionErrorGetter: (state) => state.masterRequisitionError,
    masterRequisitionMaterialListGetter: (state) => state.masterRequisitionMaterialList,
    masterRequisitionOptionsGetter: (state) => state.masterRequisitionOptions,
    masterRequisitionLoadingGetter: (state) => state.masterRequisitionLoading,
    masterRequisitionFilterGetter: (state) => state.masterRequisitionOptions.filter,
    masterRequisitionPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.masterRequisitionOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },

}

const state = () => ({
    masterRequisition: {},
    masterRequisitionList: [],
    masterRequisitionMaterialList: [],
    masterRequisitionError: '',
    masterRequisitionLoading: false,
    masterRequisitionOptions: {
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
        }
    },
})

export default {
    mutations,
    getters,
    actions,
    state,
}
