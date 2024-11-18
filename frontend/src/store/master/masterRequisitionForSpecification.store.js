import { httpRequest } from "@/services/httpRequest.service"
import Vue from 'vue'
import store from '@/store'

const mutations = {
    MASTER_REQUISITION_FOR_SPECIFICATION_ADD_ROW(state){
        var rowKey = String(Math.random())
        var index = state.masterRequisitionForSpecificationList.length == 0 
            ? 1
            : state.masterRequisitionForSpecificationList[state.masterRequisitionForSpecificationList.length - 1].index + 1
        
        state.masterRequisitionForSpecificationList.push({
            id: '',
            checked: false,
            index: index,
            fullname: '',
            rowKey: rowKey,
            description: '',
            unit: '',
            quantity: 0,
            ordered: 0,
            order: 0,
            material: '',
        })
        
    },
    MASTER_REQUISITION_FOR_SPECIFICATION_DRAFT_DELETE_ROW(state, item){

        state.masterRequisitionForSpecificationDeleteList.push(item)

        let index = state.masterRequisitionForSpecificationList.filter( material => material.idReq == item.idReq)[0].index

        state.masterRequisitionForSpecificationDeleteList.push(item)

        Vue.set(state.masterRequisitionForSpecificationList, index - 1, {
            ...state.masterRequisitionForSpecificationList[index - 1],
            order: 0,
            checked: false
        })
    },
    MASTER_REQUISITION_FOR_SPECIFICATION_SET_LIST(state, data){
        let material = []
        state.masterRequisitionForSpecificationError = ''

        if(data.length != 0) {

            for( var i = 0; i < data.length; i++){
                if(data[i].is_group == true){
                    material.push({
                        ...data[i],
                        rowKey: String(Math.random()),
                    })
                }else{
                    material.push(
                        { ...data[i], checked: data[i].quantity == data[i].ordered ? true : false, order: 0, unit: data[i].unit.title, rowKey: String(Math.random()) }
                    )
                }
            }
        }
        
        state.masterRequisitionForSpecificationList = material
    },
    MASTER_REQUISITION_FOR_SPECIFICATION_DRAFT_SET_LIST(state, params){

        state.masterRequisitionForSpecificationError = ''

        let requisitionMaterial = params.requisitionMaterial

        let specificationMaterial = params.specificationMaterial
        
        if(specificationMaterial.length != 0) {
 
            for( var i = 0; i < specificationMaterial.length; i++){

                if(specificationMaterial[i].is_group == true){
                    Vue.set(
                        state.masterRequisitionForSpecificationList,
                        specificationMaterial[i].index - 1,
                        { ...specificationMaterial[i], checked: false, order: '',  quantity: '', ordered: '' }
                    ) 

                }else {
                    Vue.set(
                        state.masterRequisitionForSpecificationList,
                        specificationMaterial[i].index - 1,
                        { ...specificationMaterial[i], checked: false, order: 0, ordered: 0, unit: specificationMaterial[i].unit?.title, rowKey: String(Math.random()) }
                    )
                }

            }
        }

        for(let i = 0; i < requisitionMaterial.length; i++){

            let index = state.masterRequisitionForSpecificationList.filter( item => item.index == requisitionMaterial[i].specification_material.index)[0].index

            if(state.masterRequisitionForSpecificationList.filter(item => item.index == index)[0].is_group != true){
                Vue.set(
                    state.masterRequisitionForSpecificationList,
                    state.masterRequisitionForSpecificationList.indexOf(state.masterRequisitionForSpecificationList.filter(item => item.index == index)[0]),
                    {
                        ...requisitionMaterial[i].specification_material,
                        ordered: 0,
                        order: requisitionMaterial[i].quantity,
                        index: index,
                        idReq: requisitionMaterial[i].id,
                        unit: requisitionMaterial[i].specification_material.unit.title,
                        checked: true,
                        description: requisitionMaterial[i].description
                    }
                )
            }
        } 

        state.masterRequisitionForSpecificationListOld = []

        for( let i = 0; i< state.masterRequisitionForSpecificationList.length; i++){
            state.masterRequisitionForSpecificationListOld.push( Object.assign({}, state.masterRequisitionForSpecificationList[i]) )
        }
    },
    MASTER_REQUISITION_FOR_SPECIFICATION_CHECKED(state, row){
         
        let index = state.masterRequisitionForSpecificationList.indexOf(state.masterRequisitionForSpecificationList.filter( item => item.index == row.index)[0])
        let order = 0
        let checked = false

        state.masterRequisitionForSpecificationDeleteList.filter( item => item.index != row.index )

        if (row.order == ''){
            order = ''
        } else if(Number(row.order) <= 0 | Number.isNaN(Number(row.order))) {
            order = 0
            checked = false
        } else {
            order = row.order
            checked = true
        }
        
        Vue.set(state.masterRequisitionForSpecificationList, index, { 
            ...row,
            order,
            checked: checked
        })
        
    },
    MASTER_REQUISITION_FOR_SPECIFICATION_ERROR(state, error){
        state.masterRequisitionForSpecificationError = error
    },
    MASTER_REQUISITION_FOR_SPECIFICATION_LOADING(state){
        state.masterRequisitionForSpecificationLoading = !state.masterRequisitionForSpecificationLoading
    },
    MASTER_REQUISITION_FOR_SPECIFICATION_CLEAR(state){
        state.masterRequisitionForSpecificationList = []
    }
}

const actions = {
    async masterRequisitionForSpecificationSetListActions( { commit }, id){
        // получ остатки материала в спеке

        if( id != '' & typeof id != 'undefined'){
            commit('MASTER_REQUISITION_FOR_SPECIFICATION_CLEAR')
            commit('MASTER_REQUISITION_FOR_SPECIFICATION_LOADING')
            const data = await httpRequest('crm/specification/material/', 'post', { specificationId: id })
    
            if(data.code == 200){

                for(let i = 0; i < data.length; i++){
                    commit('MASTER_REQUISITION_FOR_SPECIFICATION_ADD_ROW')
                }

                for(let i = 0; i < data.data.length; i++){
                    data.data[i].ordered = 0
                }

                const materialsRemnant = await httpRequest(`crm/specification/${id}/material/remnants`, 'post', null)

                if(materialsRemnant.code == 200 ){
                    for( let i = 0; i < materialsRemnant.data.length; i++ ){
                        let remnant = materialsRemnant.data.filter( item => item.id == data.data[i].id)[0].remnant
                        if( remnant == null | typeof remnant == 'undefined'){
                            data.data[i].ordered = 0
                        }else{
                            data.data[i].ordered = remnant
                        }
                    }
                } else {
                    commit('MASTER_REQUISITION_FOR_SPECIFICATION_ERROR', materialsRemnant?.data)
                }

                commit('MASTER_REQUISITION_FOR_SPECIFICATION_SET_LIST', data.data)
                
            }
            else{
                commit('MASTER_REQUISITION_FOR_SPECIFICATION_ERROR', data?.data)
            }

            commit('MASTER_REQUISITION_FOR_SPECIFICATION_LOADING')
        } else {
            commit('MASTER_REQUISITION_FOR_SPECIFICATION_CLEAR')
        }

    },
    async masterRequisitionForSpecificationRemnants(){
        
    },
    async masterRequisitionForSpecificationDraftSetListActions({ commit }, params){

        commit('MASTER_REQUISITION_FOR_SPECIFICATION_CLEAR')
        commit('MASTER_REQUISITION_FOR_SPECIFICATION_LOADING')

        const data = await httpRequest('crm/specification/material/', 'post', { specificationId: params.specificationId })
    
        const materialsRemnant = await httpRequest(`crm/specification/${params.specificationId}/material/remnants`, 'post', null)

        if(materialsRemnant.code == 200 ){

            for( let i = 0; i < materialsRemnant.data.length; i++ ){

                let remnant = materialsRemnant.data.filter( item => item.id == data.data[i].id)[0].remnant

                if( remnant == null | typeof remnant == 'undefined'){
                    remnant = 0
                }else{
                    data.data[i].ordered = remnant
                }
            }

        } else {
            commit('MASTER_REQUISITION_FOR_SPECIFICATION_ERROR', materialsRemnant?.data)
        }

        let commitParams = {
            specificationMaterial: data.data,
            requisitionMaterial: params.materials
        }
    
        if(data.code == 200){
            for(let i = 0; i < data.data.length; i++){
                commit('MASTER_REQUISITION_FOR_SPECIFICATION_ADD_ROW')
            }
            commit('MASTER_REQUISITION_FOR_SPECIFICATION_DRAFT_SET_LIST', commitParams)
        }
        else{
            commit('MASTER_REQUISITION_FOR_SPECIFICATION_ERROR', data?.data)
        }

        commit('MASTER_REQUISITION_FOR_SPECIFICATION_LOADING')
        
    },
    async masterRequisitionForSpecificationDraftEditActions({ commit, getters }, form){
        
        let addMaterialsList = []
        let deleteMaterialsList = []
        let editMaterialsList = []

        for( let i = 0; i < getters.masterRequisitionForSpecificationListOldGetter.length; i++){

            let index = i + 1
            let item = getters.masterRequisitionForSpecificationListGetter.filter( element => element.index == index)[0]

            if( getters.masterRequisitionForSpecificationListOldGetter[i].order != 0 & item.order == 0 ){
                deleteMaterialsList.push(item)
            } 

            if( getters.masterRequisitionForSpecificationListOldGetter[i].order == 0 & item.order > 0 ){
                addMaterialsList.push(item)
            }

            if( getters.masterRequisitionForSpecificationListOldGetter[i].order > 0 & item.order > 0 & item.order != getters.masterRequisitionForSpecificationListOldGetter[i].order ){
                editMaterialsList.push(item)
            } else if(item.description != getters.masterRequisitionForSpecificationListOldGetter[i].description){
                editMaterialsList.push(item)
            }
        }

        deleteMaterialsList = deleteMaterialsList.map( item => {
            const select = {}
            select.id = item.idReq

            return select
        })

        addMaterialsList = addMaterialsList.map( item => {
            const select = {}
            select.material = {
                id: item.id
            }
            
            select.quantity = item.order
            select.description = item.description

            return select
        })

        editMaterialsList = editMaterialsList.map( item => {
            const select = {}

            select.idReq = item.idReq
            select.quantity = item.order
            select.description = item.description

            return select
        })

        // удалить материал

        for(let i = 0; i < deleteMaterialsList.length; i++){
            const data = await httpRequest(`crm/master/requisition/${form.id}/material/${deleteMaterialsList[i].id}`, 'delete', { })

            if(data.code != 200){
                commit('MASTER_REQUISITION_FOR_SPECIFICATION_ERROR', data.data)
            }
        }

        // добавить материал

        for(let i = 0; i < addMaterialsList.length; i++){
            const data = await httpRequest(`crm/master/requisition/${form.id}/material/add`, 'post', addMaterialsList[i])

            if(data.code != 200){
                commit('MASTER_REQUISITION_FOR_SPECIFICATION_ERROR', data.data)
            }
        } 

        // редактировать черновик

        for(let i = 0; i < editMaterialsList.length; i++){
            const data = await httpRequest(`crm/master/requisition/${form.id}/material/${editMaterialsList[i].idReq}`, 'patch', editMaterialsList[i])

            if(data.code != 200){
                commit('MASTER_REQUISITION_FOR_SPECIFICATION_ERROR', data.data)
            }
        } 

        // отрдактиролвать форму

        const editForm = await httpRequest(`crm/master/requisition`, 'patch', {
            id: form.id,
            description: form.description,
            end_at: form.end_at
        })

        if(editForm.code != 200){
            commit('MASTER_REQUISITION_FOR_SPECIFICATION_ERROR', editForm ) 
        }

        if(!form.fixed){
            Vue.notify({
                group: 'success',
                title: 'Успех',
                type: 'success',
                text: 'Черновик изменен'
            })
        }

        if(form.fixed){
            const dataFixed = await httpRequest(`crm/master/requisition/${form.id}/fixed`, 'post', form)
            if(dataFixed.code == 200){
                Vue.notify({
                    group: 'success',
                    title: 'Успех',
                    type: 'success',
                    text: 'Заявка отправленна'
                })
                store.dispatch('masterRequisitionFixedDraftActions', dataFixed.data)
                store.dispatch('masterRequisitionSendActions', dataFixed.data)
            }else{
                commit('MASTER_REQUISITION_FOR_SPECIFICATION_ERROR', dataFixed.data)
            }
        }

    },
    async masterRequisitionForSpecificationCreateActions({ commit, getters }, form){

        if(getters.masterRequisitionForSpecificationListGetter.filter(item => item.order > 0).length > 0){
            let materials = getters.masterRequisitionForSpecificationListGetter.filter(item => item.order > 0).map( item => { 
                const material = {}
                
                material.material_specification_id = item.id
                material.quantity = item.order
                material.description = item.description
    
                return material
            })
    
            let requisition = {
                end_at: form.end_at,
                materials: materials,
                description: form.description
            }

            const newRequisition = await httpRequest(`crm/master/specification/${form.idSpecifiactions}/requisition/material`, 'post', requisition)
            
            if(newRequisition.status == 500){
                commit('MASTER_REQUISITION_FOR_SPECIFICATION_ERROR', 'Ошибка на сервере')
            }
            else if(newRequisition.code == 200){
                
                if( form.isFixed ){
                    const data = await httpRequest(`crm/master/requisition/${newRequisition.data.id}/fixed`, 'post', {})
                    
                    if(data.code == 200){
                        store.dispatch('masterRequisitionSendActions', data.data)

                        Vue.notify({
                            group: 'success',
                            title: 'Успех',
                            type: 'success',
                            text: 'Создана новая заявка'
                        })    
                    }else{
                        Vue.notify({
                            group: 'error',
                            title: 'Ошибка',
                            type: 'error',
                            text: data.data
                        })    
                    }
                } else {
                    store.dispatch('masterRequisitionSendActions', newRequisition.data)

                    Vue.notify({
                        group: 'success',
                        title: 'Успех',
                        type: 'success',
                        text: 'Создан черновик'
                    })    
                }
            } 
            else{
                commit('MASTER_REQUISITION_FOR_SPECIFICATION_ERROR', newRequisition.data)
            }

            store.dispatch('masterRequisitionSetListActions')
        }

    },
    async masterRequisitionForSpecificationCheckedActions( { commit }, row ){
        commit('MASTER_REQUISITION_FOR_SPECIFICATION_CHECKED', row)
    },
    masterRequisitionForSpecificationDeleteRowActions( { commit }, item){
        commit('MASTER_REQUISITION_FOR_SPECIFICATION_DRAFT_DELETE_ROW', item)
    }
}
    
const getters = {
    masterRequisitionForSpecificationListGetter: (state) => state.masterRequisitionForSpecificationList,
    masterRequisitionForSpecificationDeleteListGetter: (state) => state.masterRequisitionForSpecificationDeleteList,
    masterRequisitionForSpecificationErrorGetter: (state) => state.masterRequisitionForSpecificationError,
    masterRequisitionForSpecificationLoadingGetter: (state) => state.masterRequisitionForSpecificationLoading,
    masterRequisitionForSpecificationListOldGetter: (state) => state.masterRequisitionForSpecificationListOld,
    masterRequisitionForSpecificationValidationsGetter: (state) => state.masterRequisitionForSpecificationList.filter( item => item.checked == true).length > 0
}

const state = () => ({
    masterRequisitionForSpecificationList: [],
    masterRequisitionForSpecificationListOld: [],
    masterRequisitionForSpecificationLoading: false,
    masterRequisitionForSpecificationError: '',
    masterRequisitionForSpecificationDeleteList: []
})

export default {
    mutations,
    getters,
    actions,
    state
}