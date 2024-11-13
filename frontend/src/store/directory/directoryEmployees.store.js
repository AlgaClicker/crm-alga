import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'

const mutations = {
    DIRECTORY_EMPLOYEES_SET_LIST(state, data){

        state.directoryEmployeesOptions = data.options
        state.directoryEmployeesList = data.data
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.directoryEmployeesList = data.data.map( (item) => {
                return { ...item, selected: false }   
            })  
        }else{
            state.directoryEmployeesList = []
        }
    },
    DIRECTORY_EMPLOYEES_SET_OPTIONS(state, options){
        state.directoryEmployeesOptions = options
    },
    DIRECTORY_EMPLOYEES_EDIT(state, data){
        
        state.directoryEmployeesError = ''
        let index = state.directoryEmployeesList.indexOf(state.directoryEmployeesList.filter( item => item.id == data.id)[0])
        
        Vue.set(state.directoryEmployeesList, index, data)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Сотрудник изменен'
        })

    },
    DIRECTORY_EMPLOYEES_ADD(state, data){

        state.directoryEmployeesError = ''
        state.directoryEmployeesList.push(data.data)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Сотрудник добавлен'
        })
    },
    DIRECTORY_EMPLOYEES_DELETE(state, id){

        state.directoryEmployeesList = state.directoryEmployeesList.filter(item => item.id != id)
        state.directoryEmployeesError = ''

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Сотрудник удален'
        })

    },
    DIRECTORY_EMPLOYEES_LOADING(state){
        state.directoryEmployeesLoading = !state.directoryEmployeesLoading
    },
    DIRECTORY_EMPLOYEES_EDIT_LOADING(state){
        state.directoryEmployeesEditLoading = !state.directoryEmployeesEditLoading
    },
    DIRECTORY_EMPLOYEES_ERROR(state, data){

        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: data.message
        })

        state.directoryEmployeesError = data
    },
}

const actions = {
    async directoryEmployeesSetListActions( { commit, getters } ) {
        
        commit('DIRECTORY_EMPLOYEES_LOADING')
        const data = await httpRequest('directory/people', 'post', { 
            options: getters.directoryEmployeesOptionsGetter 
        })
 
        commit('DIRECTORY_EMPLOYEES_LOADING')

        if(data.status == 500){
            commit('DIRECTORY_EMPLOYEES_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_EMPLOYEES_SET_LIST', data)
        }
        else{
            commit('DIRECTORY_EMPLOYEES_ERROR', data)
        }
    },
    async directoryEmployeesAddActions( { commit }, form ){
        
        commit('DIRECTORY_EMPLOYEES_EDIT_LOADING')
        const data = await httpRequest('directory/people/add', 'post', form)
        commit('DIRECTORY_EMPLOYEES_EDIT_LOADING')

        if(data.status == 500){
            commit('DIRECTORY_EMPLOYEES_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_EMPLOYEES_ADD', data)
        }
        else{
            commit('DIRECTORY_EMPLOYEES_ERROR', data.data)
        }
    },
    async directoryEmployeesEditActions( { commit }, form ){

        const data = await httpRequest('directory/people', 'patch', {
            ...form,
        })
        
        if(data.status == 500){
            commit('DIRECTORY_EMPLOYEES_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_EMPLOYEES_EDIT', data.data)
        }
        else{
            commit('DIRECTORY_EMPLOYEES_ERROR', data.data)
        }
    },
    async directoryEmployeesDeleteActions({ commit }, form){

        const data = await httpRequest('directory/people', 'delete', { id: form.id } )

        if(data.status == 500){
            commit('DIRECTORY_EMPLOYEES_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_EMPLOYEES_DELETE', form.id)
        }
        else{
            commit('DIRECTORY_EMPLOYEES_ERROR', data.data)
        }
    },
    directoryEmployeesCheckedActions( { commit }, employees ){
        commit('DIRECTORY_EMPLOYEES_CHECKED', employees )
    },
    directoryEmployeesSetOptionsActions( { commit }, option ){
        commit('DIRECTORY_EMPLOYEES_SET_OPTIONS', option)
    },
}
    
const getters = { 
    directoryEmployeesListGetter: (state) => state.directoryEmployeesList,
    directoryEmployeesOptionsGetter: (state) => state.directoryEmployeesOptions,
    directoryEmployeesErrorGetter: (state) => state.directoryEmployeesError,
    directoryEmployeesLoadingGetter: (state) => state.directoryEmployeesLoading,
    directoryEmployeesEditLoadingGetter: (state) => state.directoryEmployeesEditLoading,
    directoryEmployeesPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.directoryEmployeesOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
}

const state = () => ({
    directoryEmployeesList: [],
    directoryEmployeesError: '',
    directoryEmployeesLoading: false,
    directoryEmployeesEditLoading: false,
    directoryEmployeesOptions: {
        pagginate: {
            page: 1,
            pages: 1,
            limit: 10
        },
        orderBy: {
            surname: "ASC",
        }
    },
})

export default {
    mutations,
    getters,
    actions,
    state,
}