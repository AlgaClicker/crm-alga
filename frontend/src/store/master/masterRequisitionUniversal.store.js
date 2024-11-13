import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'

import store from '@/store'

const mutations = {
    MASTER_REQUISITION_UNIVERSAL_CLEAR(state){
        state.masterRequisitionUniversalList = []
    },
    MASTER_REQUISITION_UNIVERSAL_ADD_ROW(state){

        var index = state.masterRequisitionUniversalList.length == 0 
            ? 1
            : state.masterRequisitionUniversalList[state.masterRequisitionUniversalList.length - 1].index + 1

        state.masterRequisitionUniversalList.push({
            index: index,
            directoryMaterialId: '',
            id: null,
            position: index,
            fullname: '',
            unit: '',
            quantity: 0,
            description: ''
        })
    },
    MASTER_REQUISITION_UNIVERSAL_SET_DRAFT(state, data){
        
        state.masterRequisitionUniversalDraft.id = data.id
        state.masterRequisitionUniversalDraft.end_at = data.end_at
        state.masterRequisitionUniversalDraft.number = data.number
        state.masterRequisitionUniversalDraft.description = data.description

        for(let index = 0; index < data.materials.length; index++){

            if(typeof data.materials[index].directory_material != 'undefined' ){
                Vue.set(state.masterRequisitionUniversalList, index, { 
                    ...data.materials[index]?.directory_material, 
                    id: data.materials[index]?.id,
                    material: data.materials[index]?.material,
                    fullname: data.materials[index]?.fullname,
                    quantity: data.materials[index]?.quantity,
                    description: data.materials[index]?.description,
                    directoryMaterialId: data.materials[index].directory_material?.id,
                    directoryMaterial: data.materials[index]?.directory_material,
                    unit: data.materials[index].directory_material.unit?.title, 
                    position: index + 1
                })
            } else {
                Vue.set(state.masterRequisitionUniversalList, index, {
                    id: data.materials[index]?.id,
                    material: data.materials[index]?.material,
                    description: data.materials[index]?.description,
                    fullname: data.materials[index].name,
                    quantity: data.materials[index].quantity, 
                    unit: data.materials[index].unit?.title, 
                    position: index + 1
                })
            }
        }

        state.masterRequisitionUniversalError == ''
        
        state.masterRequisitionUniversalOldList = []
        state.masterRequisitionUniversalDeleteList = []

        for( let i = 0; i < state.masterRequisitionUniversalList.length; i++){
            state.masterRequisitionUniversalOldList.push( Object.assign( {}, state.masterRequisitionUniversalList[i] ) )
        }

    },
    MASTER_REQUISITION_UNIVERSAL_UN_ATTACH_MATERIAL(state, material){

        let index = state.masterRequisitionUniversalList.indexOf(state.masterRequisitionUniversalList.filter( item => item.position == material.position)[0])

        Vue.set(state.masterRequisitionUniversalList, index, { 
                directoryMaterialId: '',
                fullname: '',
                unit: '',
                quantity: 0,
                position: material.position,
                description: '',
            } 
        ) 
    },
    MASTER_REQUISITION_UNIVERSAL_DELETE_ROW(state, position){
        
        // поиск по имени

        state.masterRequisitionUniversalDeleteList.push(state.masterRequisitionUniversalList.filter( item => item.position == position)[0])
        
        state.masterRequisitionUniversalList.splice(position - 1, 1)
        state.masterRequisitionUniversalOldList.splice(position - 1, 1)

        for(let i = 0; i < state.masterRequisitionUniversalList.length; i++){
            Vue.set(state.masterRequisitionUniversalList, i, {
                ...state.masterRequisitionUniversalList[i],
                position: i + 1
            })  
        }

        for(let i = 0; i < state.masterRequisitionUniversalOldList.length; i++){
            Vue.set(state.masterRequisitionUniversalOldList, i, {
                ...state.masterRequisitionUniversalOldList[i],
                position: i + 1
            })  
        }
    },
    MASTER_REQUISITION_UNIVERSAL_ADD_NEW_ROW(state, material){

        Vue.set(state.masterRequisitionUniversalList, material.position - 1, {
            ...material
        })

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Материал добавлен'
        })

    },
    MASTER_REQUISITION_UNIVERSAL_SET_MATERIAL(state, params){
        
        let index = state.masterRequisitionUniversalList.indexOf(state.masterRequisitionUniversalList.filter( item => item.position == params.position)[0])
        
        Vue.set(state.masterRequisitionUniversalList, index, { 
            ...params.material, 
            id: null,
            quantity: 0, 
            description: params.material.description,
            unit: params.material.unit.title, 
            directoryMaterialId: params.material.id,
            directoryMaterial: params.material,
            position: params.position 
        }) 

    },
    MASTER_REQUISITION_UNIVERSAL_SET_ERROR(state, error){

        state.masterRequisitionUniversalError = error
        
        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: error.message
        })  
    }
}

const actions = {
    masterRequisitionUniversalSetEmptyListActions({ commit }){
        commit('MASTER_REQUISITION_UNIVERSAL_CLEAR')
        for(var i = 0; i < 14; i++){
            commit('MASTER_REQUISITION_UNIVERSAL_ADD_ROW')
        }
    },
    async masterRequisitionUniversalEditActions({ commit }, editForm){

        let form = {
            id: editForm.id,
            end_at: editForm.end_at,
            description: editForm.description,
        }

        const data = await httpRequest('crm/master/requisition/other/new', 'post', form)

        if( data.code == 200 ){
            console.log(data.data)
        } else {
            console.log(data)
        }

        commit('MASTER_REQUISITION_UNIVERSAL_EDIT', form)
    },
    async masterRequisitionUniversalSetDraftActions( { commit }, id){

        const data = await httpRequest(`crm/master/requisition/other/${id}`, 'post', null)
        
        if(data.code == 200){
            commit('MASTER_REQUISITION_UNIVERSAL_SET_DRAFT', data.data)
        } else {
            commit('MASTER_REQUISITION_UNIVERSAL_SET_ERROR', data.data)
        }

    },
    async masterRequisitionUniversalSendActions({ commit }, params){

        const data = await httpRequest('crm/master/requisition/other/new', 'post', params.form)

        if(data.code == 200){

            if(params.fixed){
                const dataFixed = await httpRequest(`crm/master/requisition/${data.data.id}/fixed`, 'post', {})
                    
                if(dataFixed.code == 200){
                    Vue.notify({
                        group: 'success',
                        title: 'Успех',
                        type: 'success',
                        text: 'Создана новая заявка'
                    })    
                    store.dispatch('masterRequisitionSendActions', dataFixed.data)
                }else{
                    commit('MASTER_REQUISITION_UNIVERSAL_SET_ERROR', dataFixed.message)
                }
            }else {
                Vue.notify({
                    group: 'success',
                    title: 'Успех',
                    type: 'success',
                    text: 'Создан новый черновик'
                })  
                store.dispatch('masterRequisitionSendActions', data.data)
            }

            await store.dispatch('masterRequisitionSetListActions')

        }else{
            commit('MASTER_REQUISITION_UNIVERSAL_SET_ERROR', data)
        }

    },
    async masterRequisitionUniversalEditDraftActions({ commit, getters }, params){

        let editMaterialsList = []

        let deleteMaterials = getters.masterRequisitionUniversalListGetter.filter(item => item.id != null & Number(item.quantity) == 0).concat(getters.masterRequisitionUniversalDeleteListGetter).map( item => {
            const select = {}
            select.id = item.id
            select.unit = { code: String(store.getters.materialsUnitsListGetter.filter( element => element.title == item.unit)[0].code) }

            return select
        })

        let addMaterials = getters.masterRequisitionUniversalListGetter.filter(item => item?.id == null & Number(item.quantity) > 0 & item.fullname != '').map( item => {

            const select = {}

            if(typeof item.directoryMaterial != 'undefined' & item.directoryMaterial != ''){
                select.name = item.fullname
                select.material = {
                    id: item.directoryMaterialId
                }
            }
            
            select.name = item.fullname
            select.position = item.position

            select.unit = String(store.getters.materialsUnitsListGetter.filter( element => element.title == item.unit)[0].code )
            
            
            select.quantity = item.quantity
            select.description = item.description

            return select
        })

        for( let i = 0; i < getters.masterRequisitionUniversalOldListGetter.length; i++){

            let position = i + 1
            let item = getters.masterRequisitionUniversalListGetter.filter( element => element.position == position)[0]
            
            if(typeof item.id != 'undefined' & item?.id != null){

                // получить со справочниками

                if(typeof item.directoryMaterialId != 'undefined' && getters.masterRequisitionUniversalOldListGetter[i].quantity > 0 & item.quantity > 0){

                    editMaterialsList.push({
                        id: item.id,
                        quantity: item.quantity, // Кол-во
                    })

                } else {
                    if( getters.masterRequisitionUniversalOldListGetter[i].quantity > 0 & item.quantity > 0 & item.quantity != getters.masterRequisitionUniversalOldListGetter[i].quantity ){
                        editMaterialsList.push({
                            id: item.id,
                            quantity: item.quantity, // Кол-во
                            name: item.fullname,    
                            description: item.description, // Коментарий к материалу
                            unit: { code: String(store.getters.materialsUnitsListGetter.filter( element => element.title == item.unit)[0].code) }
                        })
                    } else if(getters.masterRequisitionUniversalOldListGetter[i].description != item.description | getters.masterRequisitionUniversalOldListGetter[i].fullname != item.fullname | getters.masterRequisitionUniversalOldListGetter[i].unit != item.unit){
                        editMaterialsList.push(
                            {
                                id: item.id,
                                quantity: item.quantity, // Кол-во
                                name: item.fullname,    
                                description: item.description, // Коментарий к материалу
                                unit: { code: String(store.getters.materialsUnitsListGetter.filter( element => element.title == item.unit)[0].code) }
                            }
                        )
                    }
                }
            }
        }

        for(let i = 0; i < deleteMaterials.length; i++){
            const data = await httpRequest(`crm/master/requisition/other/${params.requisitionId}/material/${deleteMaterials[i].id}`, 'delete', { id: deleteMaterials[i].id })

            if(data.code != 200){
                commit('MASTER_REQUISITION_UNIVERSAL_SET_ERROR', data.data)
            }
        }

        // Добавить материал

        for(let i = 0; i < addMaterials.length; i++){
            const data = await httpRequest(`crm/master/requisition/other/${params.requisitionId}/material/add`, 'post', addMaterials[i])

            if(data.code != 200){
                commit('MASTER_REQUISITION_FOR_SPECIFICATION_ERROR', data.data)
            } else {

                let newMaterial = {
                    id: data.data.id,
                    description: data.data.description,
                    fullname: data.data.name,
                    quantity: data.data.quantity, 
                    unit: String(data.data.unit?.title), 
                    position: addMaterials[i].position
                }

                commit('MASTER_REQUISITION_UNIVERSAL_ADD_NEW_ROW', newMaterial)
            }
        } 

        for(let i = 0; i < editMaterialsList.length; i++){
            const data = await httpRequest(`crm/master/requisition/other/${params.requisitionId}/material/${editMaterialsList[i].id}`, 'patch', editMaterialsList[i])

            if(data.code != '200'){
                commit('MASTER_REQUISITION_UNIVERSAL_SET_ERROR', data.data)
            }
        }   

        // Редактировать материал
        
        // редактировать

        const editForm = await httpRequest(`crm/master/requisition`, 'post', params.form)

        if(editForm.code != 200){
            commit('MASTER_REQUISITION_UNIVERSAL_SET_ERROR', editForm.data)
        }   

        if(!params.fixed){
            Vue.notify({
                group: 'success',
                title: 'Успех',
                type: 'success',
                text: 'Черновик изменен'
            })
        }

        if(params.fixed){
            const dataFixed = await httpRequest(`crm/master/requisition/${params.requisitionId}/fixed`, 'post', {})
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
    masterRequisitionUniversalDeleteRowActions( { commit }, position){
        commit('MASTER_REQUISITION_UNIVERSAL_DELETE_ROW', position)
    },
    masterRequisitionUniversalSetMaterialActions({ commit }, params){
        commit('MASTER_REQUISITION_UNIVERSAL_SET_MATERIAL', params)
    },
    masterRequisitionUniversalUnAttachMaterialActions({ commit }, material){
        commit('MASTER_REQUISITION_UNIVERSAL_UN_ATTACH_MATERIAL', material)
    },
    masterRequisitionUniversalAddRowActions({ commit }){
        commit('MASTER_REQUISITION_UNIVERSAL_ADD_ROW')
    },
}
    
const getters = {
    masterRequisitionUniversalListGetter: (state) => state.masterRequisitionUniversalList,
    masterRequisitionUniversalErrorGetter: (state) => state.masterRequisitionUniversalError,
    masterRequisitionUniversalDraftGetter: (state) => state.masterRequisitionUniversalDraft,
    masterRequisitionUniversalLoadingGetter: (state) => state.masterRequisitionUniversalLoading,
    masterRequisitionUniversalOldListGetter: (state) => state.masterRequisitionUniversalOldList,
    masterRequisitionUniversalDeleteListGetter: (state) => state.masterRequisitionUniversalDeleteList,
    masterRequisitionUniversalSpecificationMaterialGetter: (state) => state.masterRequisitionUniversalSpecificationMaterial,
}

const state = () => ({
    masterRequisitionUniversalList: [],
    masterRequisitionUniversalOldList: [],
    masterRequisitionUniversalDeleteList: [],
    masterRequisitionUniversalError: '',
    masterRequisitionUniversalLoading: false,
    masterRequisitionUniversalSpecificationMaterial: [],
    masterRequisitionUniversalDraft: {
        id: '',
        end_at: '',
        number: '',
        description: '',
        specification: ''
    }
})

export default {
    mutations,
    getters,
    actions,
    state
}