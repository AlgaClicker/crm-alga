import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'

const mutations = {
    OBJECTS_ERROR(state, error){
        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text:  error.message[0]
        })
        state.objectsError = error
    },
    OBJECTS_SET_LIST(state, data){

        state.objectError = ''
        
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.objectsList  = data.data  
            state.objectOptions = data.options
        }else{
            state.objectsList = []
        }
    },
    OBJECTS_SET_SPECIFICATION_LIST(state, data){
        state.objectError = ''
        state.objectSpecificationList = data
    },
    OBJECT_GET(state, data){
        state.objectError = ''
        state.object = data
    },
    OBJECTS_ADD(state, data){
        state.objectError = ''
        state.objectsList.push(data)
        state.object = data

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Объект добавлен'
        })
    },
    OBJECTS_EDIT(state, data){
        state.objectError = ''
        
        state.objectsList.splice(
            state.objectsList.indexOf(state.objectsList.filter( item => item.id == data.id)[0]),
            1,
            data
        )
        
        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Объект изменен'
        })
    },  
    OBJECTS_DELETE(state, id){
        state.objectError = ''
        state.objectsList = state.objectsList.filter(item => item.id != id)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Объект удален'
        })
    },
    OBJECTS_SET_OPTIONS(state, data){
        state.objectOptions = data
    },
    UPLOAD_ERROR(state, data){
        state.uploadError = data
    },
    REMOVE_FILES(state){
        state.objectError = ''
        state.newFilesList = []
    },
    OBJECTS_LOADING(state){
        state.objectsLoading = !state.objectsLoading
    },
    OBJECTS_LIST_LOADING(state){
        state.objectsListLoading = !state.objectsListLoading
    },
    OBJECTS_ADD_SPECIFICATIONS(state, specification){
        state.objectError = ''
        state.objectSpecificationList.push(specification)
    }
}

const actions = {
    async objectsSetListActions( { commit, getters } ) {

        commit('OBJECTS_LIST_LOADING')
        const data = await httpRequest('crm/objects', 'post', {
            options: getters.objectOptionsGetter
        })
        commit('OBJECTS_LIST_LOADING')

        if(data.status == 500){
            commit('OBJECTS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit("OBJECTS_SET_LIST", data)
        }
        else{
            commit("OBJECTS_ERROR", data.data)
        }
    },
    async objectSpecificationSetListActions({ commit }, id){

        commit('OBJECTS_LIST_LOADING')
        const data = await httpRequest('crm/specification/', 'post', { objectId: id } )
        commit('OBJECTS_LIST_LOADING')

        if(data.status == 500){
            commit('OBJECTS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            if(data.data === null || typeof data.data == 'undefined'){
                commit("OBJECTS_SET_SPECIFICATION_LIST", [])
            }else{
                commit("OBJECTS_SET_SPECIFICATION_LIST", data.data)
            }
        }
        else{
            commit("OBJECTS_ERROR", data.data)
        }
    },
    async objectsAddActions( { commit }, form ) {
        const data = await httpRequest('crm/objects/add', 'post', form)

        if(data.status == 500){
            commit('OBJECTS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit("OBJECTS_ADD", data.data || [])
        }
        else{
            commit("OBJECTS_ERROR", data.data)
        }
    },
    async objectEditActions( { commit }, form ){

        const data = await httpRequest('crm/objects', 'patch', form)
        
        if(data.status == 500){
            commit('OBJECTS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit("OBJECTS_EDIT", data.data)
        }
        else{
            commit("OBJECTS_ERROR", data.data)
        }
    },
    async objectSetActions( { commit }, id ){
        commit('OBJECTS_LOADING')
        const data = await httpRequest('crm/objects/', 'post', { id: id} )
        commit('OBJECTS_LOADING')
        if(data.status == 500){
            commit('OBJECTS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit("OBJECT_GET", data.data)
        }
        else{
            commit("OBJECTS_ERROR", data.data)
        }

    },
    async objectDeleteActions({ commit }, id){

        const data = await httpRequest('crm/objects/', 'delete', { objectId: id } )

        if(data.status == 500){
            commit('OBJECTS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
           commit('OBJECTS_DELETE', id)
        }
        else{
            commit('OBJECTS_ERROR', data.data)
        }
    },
    removeFilesActions( {commit} ){
        commit("REMOVE_FILES")
    },
    objectSetOptionsActions( { commit }, options ){
        commit('OBJECTS_SET_OPTIONS', options)
    },
}

const getters = {
    objectsListGetter: (state) => state.objectsList,
    objectSpecificationListGetter: (state) => state.objectSpecificationList,
    objectGetter: (state) => state.object,
    objectByIdGetter: (state) => (id) => state.objectsList.filter(item => item.id == id)[0],
    objectsLoadingGetter: (state) => state.objectsLoading,
    objectsListLoadingGetter: (state) => state.objectsListLoading,
    objectsErrorGetter: (state) => state.objectsError,
    newFilesListGetter: (state) => state.newFilesList,
    objectOptionsGetter: (state) => state.objectOptions,
    objectPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.objectOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
}

const state = () => ({
    object: {},
    objectsList: [],
    objectSpecificationList: [],
    objectsError: '',
    objectsLoading: false,
    objectsListLoading: false,
    objectOptions: {
        pagginate: {
            page: 1,
            limit: 10,
            pages: 1
        },
        orderBy: {
            name: "ASC",
        }
    },
})

export default {
    mutations,
    getters,
    actions,
    state,
}
