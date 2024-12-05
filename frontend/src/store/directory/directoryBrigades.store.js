import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'

const mutations = {
    DIRECTORY_BRIGADES_SET_LIST(state, data){

        state.directoryBrigadesOptions = data.options
        state.directoryBrigadesList = data.data

        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.directoryBrigadesList = data.data.map( (item) => {
                return { ...item, selected: false }   
            })  
        }else{
            state.directoryBrigadesList = []
        }
    },
    DIRECTORY_BRIGADES_SET(state, data){
        state.directoryBrigade = data.data
    },
    DIRECTORY_BRIGADES_SET_OPTIONS(state, options){
        state.directoryBrigadesOptions = options
    },
    DIRECTORY_BRIGADES_ADD(state, data){    
        state.directoryBrigadesError = ''

        state.directoryBrigadesList.push(data.data)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Бригада добавлена'
        })
    },
    DIRECTORY_BRIGADES_ADD_EMPLOYEES(state, data){

        let index = state.directoryBrigadesList.indexOf(state.directoryBrigadesList.filter( item => item.id == data.brigade.id)[0])

        var workpeoples = state.directoryBrigadesList[index].workpeoples

        workpeoples.push(data)
 
        Vue.set(state.directoryBrigadesList, index, {
            ...state.directoryBrigadesList[index],
            workpeoples: workpeoples
        }) 

    },
    DIRECTORY_BRIGADES_DELETE_EMPLOYEES(state, form){
        state.directoryBrigadesList.filter( item => item.id == form.idBrigade )

    },
    DIRECTORY_BRIGADES_MOVE_EMPLOYEES(state, data){
        console.log(data)
        console.log(state)
    },
    DIRECTORY_BRIGADES_EDIT(state, brigade){
        state.directoryBrigadesError = ''
        let index = state.directoryBrigadesList.indexOf(state.directoryBrigadesList.filter( item => item.id == brigade.id)[0])
        
        Vue.set(state.directoryBrigadesList, index, brigade)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Бригада изменена'
        })

    },
    DIRECTORY_BRIGADES_LOADING(state){
        state.directoryBrigadesLoading = !state.directoryBrigadesLoading
    },
    DIRECTORY_BRIGADES_DELETE(state, id){
        state.directoryBrigadesList = state.directoryBrigadesList.filter(item => item.id != id)
        state.directoryBrigadesError = ''

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Бригада удалена'
        })

        state.directoryBrigadesSelectedList = []  
    },
    DIRECTORY_BRIGADES_WORKPEOPLE_SET(state, param){

        let index = state.directoryBrigadesList.indexOf(state.directoryBrigadesList.filter( item => item.id == param.idBrigade)[0])
        Vue.set(state.directoryBrigadesList, index, { 
            ...state.directoryBrigadesList[index],
            workpeoples: param.peoples
        })

    },
    DIRECTORY_BRIGADES_CLEAR_SELECTED_LIST(state){

        for( let i = 0; i < state.directoryBrigadesList.length; i++){
            Vue.set(state.directoryBrigadesList, i, { 
                ...state.directoryBrigadesList[i],
                selected: false,
            })
        }

        state.directoryBrigadesSelectedList = []
    },
    DIRECTORY_BRIGADES_FREE_EMPLOYEES_SET(state, data){

        if(data != [] && data != null && typeof data != 'undefined'){
            state.directoryBrigadesFreeEmployeesList = data
        }else{
            state.directoryBrigadesFreeEmployeesList = []
        }

    },
    DIRECTORY_BRIGADES_ERROR(state, error){
        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: error.message
        })
            
        state.directoryBrigadesError = error
    },
}

const actions = {  
    async directoryBrigadesSetListActions({ commit, getters }) {

        commit('DIRECTORY_BRIGADES_LOADING')
        const data = await httpRequest('crm/brigade/', 'post', { 
            options: getters.directoryBrigadesOptionsGetter 
        })

        if(data.code == 200){
            commit('DIRECTORY_BRIGADES_SET_LIST', data)
        }
        else{
            commit('DIRECTORY_BRIGADES_ERROR', data.data)
        }

        commit('DIRECTORY_BRIGADES_LOADING')
    },
    async directoryBrigadesSetBrigadeActions({ commit }, id){
        const data = await httpRequest('crm/brigade/', 'post', { id: id })
        if(data.code == 200){
            commit('DIRECTORY_BRIGADES_SET', data)
        }else {
            commit('DIRECTORY_BRIGADES_ERROR', data.data)
        }
    },
    async directoryBrigadesSetPeopleActions({ commit }, id){

        let data = await httpRequest(`crm/brigade/${id}/people`, 'post', null )
        
        if(data.code == 200){
            let param = {}
            param.idBrigade = id
            param.peoples = data.data
            commit('DIRECTORY_BRIGADES_WORKPEOPLE_SET', param)
        }
    },
    async directoryBrigadesAddActions({ commit }, form) {

        const data = await httpRequest('crm/brigade/add', 'post', form)

        if(data.code == 200){
            commit("DIRECTORY_BRIGADES_ADD", data)

        }else{
            commit("DIRECTORY_BRIGADES_ERROR", data)
        }

    },

    async directoryBrigadesEditActions( { commit }, form ){

        let workpeoples = form.workpeoples.map( item => {
            const select = {}
            select.id = item.id
            return select
        })

        form.workpeoples = workpeoples

        const data = await httpRequest('crm/brigade/', 'patch', form)

        if(data.code == 200){
            commit('DIRECTORY_BRIGADES_EDIT', data.data)
        }
        else{
            commit('DIRECTORY_BRIGADES_ERROR', data.data)
        }

    },
    async directoryBrigadesDeleteActions({ commit }, form){

        const data = await httpRequest('crm/brigade/', 'delete', { id: form.id } )

        if(data.code == 200){
            commit('DIRECTORY_BRIGADES_DELETE', form.id)
        }
        else{
            commit('DIRECTORY_BRIGADES_ERROR', data.data)
        }
    },
    async directoryBrigadesMoveEmployeesActions( { commit }, form ){

        const data = await httpRequest(`crm/brigade/${form.idBrigade}/people`, 'patch', form )

        if(data.code == 200){
            commit('DIRECTORY_BRIGADES_MOVE_EMPLOYEES', form.id)
        }
        else{
            commit('DIRECTORY_BRIGADES_ERROR', data.data)
        }

    },
    async directoryBrigadesSetFreeEmployeesActions( { commit } ){

        const data = await httpRequest(`crm/brigade/people/free`, 'post')
        if(data.code == 200){
            commit('DIRECTORY_BRIGADES_FREE_EMPLOYEES_SET', data.data)
        }
        else{
            commit('DIRECTORY_BRIGADES_ERROR', data.data)
        }

    },
    directoryBrigadesSetOptionsActions( { commit }, options ){
        commit('DIRECTORY_BRIGADES_SET_OPTIONS', options)
    },
}

const getters = {
    directoryBrigadesErrorGetter: (state) => state.directoryBrigadesError,
    directoryBrigadeGetter: (state) => state.directoryBrigade,
    directoryBrigadesListGetter: (state) => state.directoryBrigadesList,
    directoryBrigadesOptionsGetter: (state) => state.directoryBrigadesOptions,
    directoryBrigadesLoadingGetter: (state) => state.directoryBrigadesLoading,
    directoryBrigadesWorkpeopleLoadingGetter: (state) => state.directoryBrigadesWorkpeopleLoading,
    directoryBrigadesFreeEmployeesListGetter: (state) => state.directoryBrigadesFreeEmployeesList,
    directoryBrigadesPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.directoryBrigadesOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
}

const state = () => ({
    directoryBrigadesList: [],
    directoryBrigade: {},
    directoryBrigadesFreeEmployeesList: [],
    directoryBrigadesLoading: false,
    directoryBrigadesWorkpeopleLoading: false,
    directoryBrigadesOptions: {
        pagginate: {
            page: 1,
            limit: 10,
            pages: 1
        },
        orderBy: {
            created_at: 'ASC'
        }
    },
    directoryBrigadesError: '',
})

export default {
    mutations,
    getters,
    actions,
    state,
}
