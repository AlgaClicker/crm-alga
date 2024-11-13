import { httpRequest } from "@/services/httpRequest.service"
import Vue from 'vue'

const mutations = {
    DIRECTORY_POSTS_SET_LIST(state, data){
        state.directoryPostsError = ''
        state.directoryPostsOptions = data.options

        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.directoryPostsList = data.data.map( (item) => {
                return { ...item, selected: false }   
            })
        }
        else{
            state.directoryPostsList = []
        }
    },
    DIRECTORY_POSTS_SET_OPTIONS(state, options){
        state.directoryPostsOptions = options
    },
    DIRECTORY_POSTS_DELETE(state, id){
        state.directoryPostsList = state.directoryPostsList.filter(item => item.id != id)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Должность удалена'
        })
 
    },
    DIRECTORY_POSTS_ADD(state, data){
        state.directoryPostsError = ''
        state.directoryPostsList.push(data.data)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Должность добавлена'
        })
    },
    DIRECTORY_POSTS_EDIT(state, data){
        state.directoryPostsError = ''
        state.directoryPostsList[state.directoryPostsList.findIndex(item => item.id == data.id)] = data

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Должность изменена'
        }) 
    },
    DIRECTORY_POSTS_LOADING(state){
        state.directoryPostsLoading = !state.directoryPostsLoading
    },
    DIRECTORY_POSTS_EDIT_LOADING(state){
        state.directoryPostsEditLoading = !state.directoryPostsEditLoading
    },
    DIRECTORY_POSTS_ERROR(state, data){
        state.directoryPostsOptions = data
    },
}

const actions = {
    async directoryPostsGetListActions( { commit,getters } ){

        commit('DIRECTORY_POSTS_LOADING')
        const data = await httpRequest('directory/profession/', 'post', {
            options: getters.directoryPostsOptionsGetter 
        })
        commit('DIRECTORY_POSTS_LOADING')

        if(data.status == 500){
            commit('DIRECTORY_POSTS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_POSTS_SET_LIST', data)
        }
        else{
            commit('DIRECTORY_POSTS_ERROR', data.data)
        }
    },
    async directoryPostsSetAllActions({ commit }){
        const data = await httpRequest('directory/profession/', 'post',  { 
            options: {
                pagginate: {
                    "page": 1,
                    "limit": 100
                }
            }
        })
        if(data.status == 500){
            commit('DIRECTORY_POSTS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_POSTS_SET_LIST', data)
        }
        else{
            commit('DIRECTORY_POSTS_ERROR', data.data)
        }
    },
    async directoryPostsAddActions({ commit }, form){

        commit('DIRECTORY_POSTS_EDIT_LOADING')
        const data = await httpRequest('directory/profession/add', 'post', form)
        commit('DIRECTORY_POSTS_EDIT_LOADING')

        if(data.status == 500){
            commit('DIRECTORY_POSTS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_POSTS_ADD', data)
        }
        else{
            commit('DIRECTORY_POSTS_ERROR', data.data)
        }
    },
    async directoryPostsEditActions( { commit }, form ){
        commit('DIRECTORY_POSTS_EDIT_LOADING')
        const data = await httpRequest('directory/profession', 'patch', form )
        commit('DIRECTORY_POSTS_EDIT_LOADING')
        
        if(data.status == 500){
            commit('DIRECTORY_POSTS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_POSTS_EDIT', data.data)
        }
        else{
            commit('DIRECTORY_POSTS_ERROR', data.data)
        }
    },
    async directoryPostsDeleteActions({ commit }, form){

        const data = await httpRequest('directory/profession', 'delete', { id: form.id} )

        if(data.status == 500){
            commit('DIRECTORY_POSTS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_POSTS_DELETE', form.id)
        }
        else{
            commit('DIRECTORY_POSTS_ERROR', data.data)
        }
    },
    directoryPostsSetOptionsActions( { commit }, options ){
        commit('DIRECTORY_POSTS_SET_OPTIONS', options)
    },
}
    
const getters = {
    directoryPostsErrorGetter: (state) => state.materialsError,
    directoryPostsListGetter: (state) => state.directoryPostsList,
    directoryPostsOptionsGetter: (state) => state.directoryPostsOptions,
    directoryPostsLoadingGetter: (state) => state.directoryPostsLoading,
    directoryPostsEditLoadingGetter: (state) => state.directoryPostsEditLoading,
    directoryPostsNameOptionsGetter: (state) => state.directoryPostsList.map( item => { 
        const select = {}
        select.value = item.id
        select.text = item.name
        return select
    }),
    directoryPostsPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.directoryPostsOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
}

const state = () => ({
    directoryPostsList: [],
    directoryPostsError: '',
    directoryPostsLoading: false,
    directoryPostsEditLoading: false,
    directoryPostsOptions: {
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