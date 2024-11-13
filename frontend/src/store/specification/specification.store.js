import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'

const mutations = {
    SPECIFICATION_ERROR(state, error){
        
        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: error.message
        })      
        state.specificationError = error
    },  
    SPECIFICATION_GET_LIST(state, data){
        state.specificationError = ''
        state.specificationList = data
    },
    SPECIFICATION_SET_LIST_IZM(state, data){
        state.specificationListIZM = data
    },
    SPECIFICATION_SET_LIST_CONTRACTS(state, data){
        
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.specificationListContracts = data.data
        }else{
            state.specificationListContracts = []
        }

    },
    SPECIFICATION_GET(state, data){
        state.errorSpecification = ''
        state.specification = data
    },
    SPECIFICATION_EDIT(state, data){
        state.specificationError = ''

        let index = state.specificationList.indexOf(state.specificationList.filter( item => item.id == data.id)[0])
        state.specification = data

        Vue.set(state.specificationList, index, data)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Спецификация обнавленна'
        })      
    },
    SPECIFICATION_SET_FIXED(state, flag){
        state.specification.fixed = flag
    },
    SPECIFICATION_SET_LIST(state, data){

        state.errorSpecification = ''
        state.specificationList.push(data)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Спецификация добавленна'
        })      
    },
    SPECIFICATION_SET_ALL_LIST(state, data){
        state.errorSpecification = ''
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.specificationAllList = data.data
        }else{
            state.specificationAllList = []
        }
    },
    SPECIFICATION_LOADING(state){
        state.specificationLoading = !state.specificationLoading
    },
}

const actions = {
    async specificationGetListActions( { commit }, id ) {

        //commit('SPECIFICATION_LOADING')
        const data = await httpRequest('crm/specification/', 'post', { objectId: id }) 
        //commit('SPECIFICATION_LOADING')
        
        if(data.status == 406){
            commit("SPECIFICATION_GET_LIST", [])
        }
        if(data.code == 200){
            commit("SPECIFICATION_GET_LIST", data.data || [])
        }
        else{
            commit("SPECIFICATION_ERROR", data.data)
        }
    },
    async specificationSetAllListActions( { commit } ){
        
        const data = await httpRequest('crm/specification/all', 'post', null) 
        
        if(data.code == 200){
            commit('SPECIFICATION_SET_ALL_LIST', data)
        }else{
            commit("SPECIFICATION_ERROR", data.data)
        }
         
    },
    async specificationSetListContractsActions( { commit }, id){

        const data = await httpRequest(`crm/specification/${id}/contract`, 'post', null) 
        
        if(data.code == 200){
            commit('SPECIFICATION_SET_LIST_CONTRACTS', data)
        }else{
            commit("SPECIFICATION_ERROR", data.data)
        }
        
    },
    async specificationEditActions( { commit }, form ){
        
        const data = await httpRequest('crm/specification/edit', 'patch', {
            ...form,
            files: form.files.map(item => item.hash)
        })
        
        if(data.code == 200){
            commit("SPECIFICATION_EDIT", data.data)
        }else{
            commit("SPECIFICATION_ERROR", data.data)
        }
    },
    async specificationAddActions( { commit }, form){
        const data = await httpRequest('crm/specification/add', 'post', form )
        if(data.code == 200){
            commit('SPECIFICATION_SET_LIST', data.data)
        }
        else if(data.status == 422){
            commit("SPECIFICATION_ERROR", 'Заполните все поля')
        }
        else{
            commit("SPECIFICATION_ERROR", data.data)
        }
    },
    async specificationGetByIdActions( { commit }, form){
        //commit('SPECIFICATION_LOADING')
        const data = await httpRequest('crm/specification/', 'post', form)
        //commit('SPECIFICATION_LOADING')
        if(data.code == 200){
            commit('SPECIFICATION_GET', data.data)
        } else if(data.status == 422){
            commit("SPECIFICATION_ERROR", 'Заполните все поля')
        }
        else{
            commit("SPECIFICATION_ERROR", data.data)
        }
    },
    async specificationAddIZMActions( { commit, getters }, params ){

        const data = await httpRequest('crm/specification/change/add', 'post', {
            id: getters.specificationGetter.id,
            name: params.form.name,
            description: params.form.description,
        } )

        if(data.code == 200){
            commit('SPECIFICATION_GET', data.data)

            Vue.notify({
                group: 'foo',
                title: 'Успех',
                type: 'success',
                text: 'Изменение созданно'
            })

        }
        else if(data.status == 422){
            commit("SPECIFICATION_ERROR", 'Заполните все поля')
        }
        else{
            commit("SPECIFICATION_ERROR", data.data)
        }
    },
    async specificationFixedActions( { commit, getters }){
        
        const data = await httpRequest('crm/specification/fixed', 'post', { id: getters.specificationGetter.id})

        if(data.code == 200){
            commit('SPECIFICATION_SET_FIXED', true)
        }
        else if(data.status == 422){
            commit("SPECIFICATION_ERROR", 'Заполните все поля')
        }
        else{
            commit("SPECIFICATION_ERROR", data.data)
        }
    },
    async specificationUnFixedActions( { commit, getters }){

        const data = await httpRequest('crm/specification/unfixed', 'post', { id: getters.specificationGetter.id})

        if(data.code == 200){
            commit('SPECIFICATION_SET_FIXED', false)
        }
        else if(data.status == 422){
            commit("SPECIFICATION_ERROR", 'Заполните все поля')
        }
        else{
            commit("SPECIFICATION_ERROR", data.data)
        }
    },
    async specificationSetListIZMActions( { commit, getters } ,id){
        
        const data = await httpRequest('crm/specification/change', 'post', { id: id })
      
        if(data.code == 200){
            if(data.data == null){
                commit('SPECIFICATION_SET_LIST_IZM', 
                    [
                        {
                            value: getters.specificationGetter.id,
                            text: getters.specificationGetter.name,
                            idx: 0
                        }
                    ]
                )
            } else {
                commit('SPECIFICATION_SET_LIST_IZM',  data.data.map( item => { 
                    const select = {}
                    select.value = item.id
                    select.text = item.name
                    select.idx = item.idx
    
                    return select
                })) 
            }

        }
        else if(data.status == 500){
            commit("SPECIFICATION_ERROR", 'Ошибка на сервере')
        }
        else{
            commit("SPECIFICATION_ERROR", data.data)
        }
 
    },
    async specificationDeleteActions( { commit }, id){

        const data = await httpRequest('crm/specification/', 'delete', { id: id })
        
        if(data.code == 200){
            Vue.notify({
                group: 'foo',
                title: 'Успех',
                type: 'success',
                text: 'Спецификация удалена'
            })
        }
        else if(data.code == 500){
            commit("SPECIFICATION_ERROR", 'Ошибка на сервере')
        }
        else{
            commit("SPECIFICATION_ERROR", data.data)
        }
    },
    async specificationAddContractActions( { commit }, params){

        commit('SPECIFICATION_ERROR', '')
        const data = await httpRequest(`directory/contract/${params.contractId}/specification/${params.specificationId}/add`, 'post', null )

        if(data.status != 200){
            commit('SPECIFICATION_ERROR', data.message)
        }

    },
    specificationRemoveContractActions( { commit }, params){

        console.log(commit)
        console.log(params)

    },
}

const getters = {
    specificationListGetter: (state) => state.specificationList,
    specificationAllListGetter: (state) => state.specificationAllList,
    specificationAllListNameOptionsGetter: (state) => state.specificationAllList.map( item => {
        const select = {}
        
        select.value = item.id
        select.text = item.name

        return select
    }),
    specificationListContractsGetter: (state) => state.specificationListContracts,
    specificationListIZMGetter: (state) => state.specificationListIZM,
    specificationGetter: (state) => state.specification,
    specificationIsFixedGetter: (state) => state.specification.fixed,
    specificationErrorGetter: (state) => state.specificationError,
    specificationLoadingGetter: (state) => state.specificationLoading,
}

const state = () => ({
    specification: {},
    specificationList: [],
    specificationAllList: [],
    specificationListIZM: null,
    specificationError: '',
    specificationLoading: false,
    specificationListContracts: []
})

export default {
    mutations,
    getters,
    actions,
    state,
}

