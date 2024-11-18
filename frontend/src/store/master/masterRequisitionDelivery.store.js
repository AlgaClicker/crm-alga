import { httpRequest } from "@/services/httpRequest.service"

import Vue from 'vue'

const mutations = {
    MASTER_REQUISITION_DELIVERY_SET_LIST(state, data){
        state.masterRequisitionDeliveryError = ''
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.masterRequisitionDeliveryList = data.data
        }else{
            state.masterRequisitionDeliveryList = []
        }
    },
    MASTER_REQUISITION_DELIVERY_SET_MATERIAL_LIST(state, data){
        
        if(data != [] && data != null && typeof data != 'undefined'){

            state.masterRequisitionDeliveryMaterialList = data
            state.masterRequisitionDeliveryMaterialList = state.masterRequisitionDeliveryMaterialList.map( item => {
                const material = {}
                
                material.requisition_material_id = item.requisition_material_id
                material.requisition_material_quantity = item.requisition_material_quantity
                material.delivery_quantity = item.delivery_quantity
                material.checked = true

                if( material.confirmed_quantity > 0 & material.confirmed_quantity != item.delivery_quantity ){
                    material.confirmed_quantity = item.confirmed_quantity
                    material.checked = false
                } else if(material.confirmed_quantity  == item.delivery_quantity){
                    material.checked = true
                } else {
                    material.confirmed_quantity = item.delivery_quantity
                }

                material.remnant_quantity = item.remnant_quantity
                
                if(typeof item.requisition_material.specification_material != 'undefined'){
                    material.requisition_material = {
                        id:  item.requisition_material.id,
                        material: item.requisition_material.material,
                        name: item.requisition_material.specification_material.fullname,
                        unit: item.requisition_material.specification_material.unit,
                        description: '',
                        files: []
                    }
                } else {
                    material.requisition_material = {
                        id:  item.requisition_material.id,
                        material: item.requisition_material.material,
                        name: item.requisition_material.name,
                        unit: item.requisition_material.unit,
                        description: '',
                        files: []
                    }
                }

                return material
            })
        }else {
            state.masterRequisitionDeliveryMaterialList = []
        }
    },
    MASTER_REQUISITION_DELIVERY_LOADING(state){
        state.masterRequisitionDeliveryLoading = !state.masterRequisitionDeliveryLoading
    },
    MASTER_REQUISITION_DELIVERY_ERROR(state, error){

        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: error
        })
        
        state.masterRequisitionDeliveryError = error
    },
    MASTER_REQUISITION_DELIVERY_CONFIRMED(state){

        state.masterRequisitionDeliveryError = ''

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Поставка подтверженна'
        })
    },
    MASTER_REQUISITION_DELIVERY_ATTACH_FILE(state, params){

        let index = state.masterRequisitionDeliveryMaterialList.findIndex(item => item.requisition_material_id == params.id)
        
        state.masterRequisitionDeliveryMaterialList[index].requisition_material.files.push(params.file)
    },
    MASTER_REQUISITION_DELIVERY_DELETE_ATTACH_FILE(state, hash){

        let material = state.masterRequisitionDeliveryMaterialList
            .filter( item => item.requisition_material.files
            .filter( file => file.hash == hash)[0])[0]
        
        let index = state.masterRequisitionDeliveryMaterialList
            .findIndex(item => item.requisition_material_id == material.requisition_material_id)
        
        let requisition_material = state.masterRequisitionDeliveryMaterialList[index].requisition_material

        requisition_material.files = state.masterRequisitionDeliveryMaterialList[index].requisition_material.files
            .filter(item => item.hash != hash)

        Vue.set(state.masterRequisitionDeliveryMaterialList, index, {  
            ...state.masterRequisitionDeliveryMaterialList[index],
            requisition_material: requisition_material
        })
    },
    MASTER_REQUISITION_DELIVERY_CHECKED(state, params){

        let checked = false

        let index = state.masterRequisitionDeliveryMaterialList.findIndex(item => item.requisition_material_id == params.requisition_material_id )

        params.confirmed_quantity > 0 ? checked = true : checked = false

        Vue.set(state.masterRequisitionDeliveryMaterialList, index, { 
            ...state.masterRequisitionDeliveryMaterialList[index],
            confirmed_quantity: params.confirmed_quantity, 
            checked: checked
        }) 
    }
}

const actions = {
    async masterRequisitionDeliverySetListActions({ commit, getters }){

        commit('MASTER_REQUISITION_DELIVERY_LOADING')

        const data = await httpRequest(`crm/master/requisition/${getters.masterRequisitionGetter.id}/deliverys`, 'post', {} )

        commit('MASTER_REQUISITION_DELIVERY_LOADING')

        if(data.code == 200){
            commit('MASTER_REQUISITION_DELIVERY_SET_LIST', data)
        } else {
            commit('MASTER_REQUISITION_DELIVERY_ERROR', data.data)
        }
    },
    masterRequisitionDeliverySetMaterialListActions({ commit }, data){
        commit('MASTER_REQUISITION_DELIVERY_SET_MATERIAL_LIST', data)
    },
    async masterRequisitionDeliveryConfirmedActions({ commit, getters }, deliveryId){

        console.log("masterRequisitionGetter")
        console.log(commit)
        console.log(getters.masterRequisitionGetter.id)
        console.log(deliveryId)
        console.log(getters.masterRequisitionDeliveryMaterialListGetter)

        let materials = getters.masterRequisitionDeliveryMaterialListGetter.map( item => {
            const material = {}

            material.confirmed_at = new Date().toLocaleString().split(',')[0].split('.').reverse().join('-')
            material.requisition_material = item.requisition_material.id
            material.quantity = item.confirmed_quantity
            material.description = item.description
            material.files = item.requisition_material.files.map( item => item.hash)

            return material
        })      

        const data = await httpRequest(`crm/master/requisition/${getters.masterRequisitionGetter.id}/delivery/${deliveryId}/confirmed`, 'post', { 
            materials: materials 
        })

        if(data.code == 200){
            commit('MASTER_REQUISITION_DELIVERY_CONFIRMED', deliveryId)
        } else {
            commit('MASTER_REQUISITION_DELIVERY_ERROR', data.data)
        }

    },
    masterRequisitionDeliveryAttachFileActions({ commit }, params){
        commit('MASTER_REQUISITION_DELIVERY_ATTACH_FILE', params)
    },
    masterRequisitionDeliveryDeleteAttachFileActions({ commit }, hash){
        commit('MASTER_REQUISITION_DELIVERY_DELETE_ATTACH_FILE', hash)
    },
    masterRequisitionDeliveryCheckedActions({ commit }, params){
        commit('MASTER_REQUISITION_DELIVERY_CHECKED', params)
    }
}    

const getters = {
    masterRequisitionDeliveryListGetter: (state) => state.masterRequisitionDeliveryList,
    masterRequisitionDeliveryMaterialListGetter: (state) => state.masterRequisitionDeliveryMaterialList,
    masterRequisitionDeliveryLoadingGetter: (state) => state.masterRequisitionDeliveryLoading,
    masterRequisitionDeliveryErrorGetter: (state) => state.masterRequisitionDeliveryError,
    masterRequisitionDeliveryPartiallyConfirmedGetter: (state) => state.masterRequisitionDeliveryMaterialList
        .filter( item => item.checked == false).length == 0
}

const state = () => ({
    masterRequisitionDeliveryList: [],
    masterRequisitionDeliveryMaterialList: [],
    masterRequisitionDeliveryLoading: false,
    masterRequisitionDeliveryError: ''
})

export default {
    mutations,
    getters,
    actions,
    state,
}
