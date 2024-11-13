import { httpRequest } from "@/services/httpRequest.service"
import Vue from 'vue'

const mutations = {
    DIRECTORY_MATERIALS_SET_LIST(state, data){
        
        state.materialsError = ''
        state.directoryMaterialsList = []
        state.directoryMaterialsOptions = data.options
        
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.directoryMaterialsList = data.data.map( (item) => {
                return { ...item, selected: false }   
            })
        }
        else{
            state.directoryEmployeesList = []
        }
    },
    DIRECTORY_MATERIALS_SET_SEARCH(state, data){
            
        state.materialsError = ''
        state.directoryMaterialsList = []
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.directoryMaterialsList = data.data.map( (item) => {
                return { ...item, selected: false }   
            })
        }
    },
    DIRECTORY_MATERIALS_SET_GROUPE(state, data){

        var materialGroupe = []
        
        if( data.data != [] && data.data != null && typeof data.data != 'undefined' ){
            materialGroupe = data.data.map( (item) => {
                return { ...item, selected: false }   
            })
            state.materialsGroupeList = state.materialsGroupeList.filter(item => item.parent.id != data.data[0].parent.id)
            state.materialsGroupeList.push(...materialGroupe)
        }

        state.materialsError = ''
    },
    DIRECTORY_MATERIALS_ADD(state, data){
        state.materialsError = ''
        state.directoryMaterialsList.push(data)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Материал добавлен'
        })

    },
    DIRECTORY_MATERIALS_ADD_GROUPE(state, data){

        state.materialsError = ''
        state.materialsGroupeList.unshift(data)
        state.materialsCurrentGroupeUid = ''

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Материал добавлен'
        })
    },
    DIRECTORY_MATERIALS_DELETE(state, id){
        state.materialsError = ''
        state.directoryMaterialsList = state.directoryMaterialsList.filter(item => item.id != id)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Материал удален'
        })

        state.directoryMaterialsSelectedList = []  
    },
    DIRECTORY_MATERIALS_DELETE_GROUPE(state, id){
        state.materialsError = ''
        state.materialsGroupeList = state.materialsGroupeList.filter(item => item.id != id)
        state.materialsCurrentGroupeUid = ''

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Группа удалена'
        })

    },
    DIRECTORY_MATERIALS_EDIT(state, form){

        state.materialsError = ''

        let index = state.directoryMaterialsList.indexOf(state.directoryMaterialsList.filter( item => item.id == form.id)[0])

        if(form.isGroupe == true){
            Vue.set(state.directoryMaterialsList, index, form )
        }
        else{
            var unit = state.materialsUnitsList.filter( item => item.code == form.unit_code)[0]
            Vue.set(state.directoryMaterialsList, index, {
                ...form,
                selected: false,
                unit
            } )
        }

        state.directoryMaterialsSelectedList = []
        
        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: form.isGroup ? 'Группа изменена' :  'Материал изменен'
        })

    },
    DIRECTORY_MATERIALS_EDIT_GROUPE(state, form){ 

        state.materialsError = ''
        
        var index = state.materialsGroupeList.indexOf(state.materialsGroupeList.filter( item => item.id == form.id)[0])
        var unit = state.materialsUnitsList.filter( item => item.code == form.unit_code)[0]

        if(form.isGroupe == true){
            Vue.set(state.materialsGroupeList, index, form )
        }
        else{
            Vue.set(state.materialsGroupeList, index, {
                ...state.materialsGroupeList.filter(item => item.id == form.id)[0],
                name: form.name,
                code: form.code,
                articul: form.articul,
                description: form.description,
                vendor: form.vendor,
                unit: unit
            } )
        }

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: form.isGroup ? 'Группа изменена' :  'Материал изменен'
        })

        state.directoryMaterialsSelectedList = [] 
      
    },
    DIRECTORY_MATERIALS_SET_UNITS(state, data){

        if( data.data != [] && data.data != null && typeof data.data != 'undefined' ){
            state.materialsUnitsList = data.data
        }else{
            state.materialsUnitsList = []
        }
    },
    DIRECTORY_MATERIALS_SET_OPTIONS(state, options){
        state.directoryMaterialsOptions = options
    },
    DIRECTORY_MATERIAL_SET_ORDER_BY(state, orderBy){
        state.materialsError = ''
        state.directoryMaterialsOptions.orderBy = orderBy
    },
    DIRECTORY_MATERIALS_ERROR(state, error){
        
        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: error
        })
            
        state.materialsError = error
    },
    DIRECTORY_MATERIALS_SET_EDIT(state, material){
        state.materialsEdit = material
    },
    DIRECTORY_MATERIALS_SET_CURRENT_GROUPE_UID(state, id){
        state.materialsCurrentGroupeUid = id
    },
    DIRECTORY_MATERIALS_RESET_CURRENT_UID(state){
        state.materialsCurrentGroupeUid = ''
    },
    DIRECTORY_MATERIALS_CHECKED(state, material){
        material.selected = !material.selected
        material.selected 
            ? state.directoryMaterialsSelectedList.push(material)
            : state.directoryMaterialsSelectedList = state.directoryMaterialsSelectedList.filter(item => item.id != material.id)
    },
    DIRECTORY_MATERIALS_CLEAR_SELECTED_LIST(state){

        for( let i = 0; i < state.directoryMaterialsList.length; i++){
            Vue.set(state.directoryMaterialsList, i, { 
                ...state.directoryMaterialsList[i],
                selected: false,
            })
        }

        state.directoryMaterialsSelectedList = []
    },
    DIRECTORY_MATERIALS_LOADING(state){
        state.directoryMaterialsLoading = !state.directoryMaterialsLoading
    },
    DIRECTORY_MATERIALS_SEARCH_LOADING(state){
        state.directoryMaterialsSearchLoading = !state.directoryMaterialsSearchLoading
    },
    SELECT_MATERIAL(state, material){
        state.directoryMaterialsSelected = material
    }
}

const actions = {
    async getUnitsActions({ commit }){
        
        const data = await httpRequest('directory/material/units', 'post')

        if(data.status == 500){
            commit('DIRECTORY_MATERIALS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_MATERIALS_SET_UNITS', data)
        }
        else{
            commit('DIRECTORY_MATERIALS_ERROR', data.data)
        }
    },  
    async materialsGetActions({ commit, getters }, parent) {

        let data = null

        if(typeof parent == 'undefined' | parent == null){
            parent = ''
        }

        if(parent == ''){
            commit('DIRECTORY_MATERIALS_LOADING')
            data = await httpRequest('directory/material/', 'post', {
                options:  getters.directoryMaterialsOptionsGetter 
            })
            commit('DIRECTORY_MATERIALS_LOADING')
        }

        if(parent != ''){
            data = await httpRequest('directory/material/', 'post', {
                parent: parent
            })
        }

        if(data.status == 500){
            commit('DIRECTORY_MATERIALS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            if(parent != ''){
                commit('DIRECTORY_MATERIALS_SET_GROUPE', data)
            }else {
                commit('DIRECTORY_MATERIALS_SET_LIST', data)
            }
        }
        else{
            commit('DIRECTORY_MATERIALS_ERROR', data.data)
        }
    },
    async materialsAddActions({ commit }, form){

        const data = await httpRequest('directory/material/add', 'post', form)
        
        if(data.status == 500){
            commit('DIRECTORY_MATERIALS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            if(data.data.parent === undefined){
                commit('DIRECTORY_MATERIALS_ADD', data.data)
            }else{
                commit('DIRECTORY_MATERIALS_ADD_GROUPE', data.data)
            }
        }
        else{
            commit('DIRECTORY_MATERIALS_ERROR', data.data)
        }
    },
    async directoryMaterialsDeleteActions({ commit }, form){

        const data = await httpRequest('directory/material', 'delete', { id: form.id } )
        
        if(data.status == 500){
            if(typeof data.data.message != 'undefined'){
                commit('DIRECTORY_MATERIALS_ERROR', data.data.message)
            }else {
                commit('DIRECTORY_MATERIALS_ERROR', 'Ошибка на сервере')
            }
        }
        else if(data.code == 200){
            commit('DIRECTORY_MATERIALS_CLEAR_SELECTED_LIST')
            if(form?.parent === undefined){
                commit('DIRECTORY_MATERIALS_DELETE', form.id)
            }else{
                commit('DIRECTORY_MATERIALS_DELETE_GROUPE', form.id)
            }
        }
        else{
            commit('DIRECTORY_MATERIALS_ERROR', data.data)
        }
    },
    async materialsEditActions({ commit }, form){

        const data = await httpRequest('directory/material', 'patch', form)

        if(data.status == 500){
            commit('DIRECTORY_MATERIALS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            if(typeof form.parent == 'undefined'){
                commit('DIRECTORY_MATERIALS_EDIT', form)
            }else{
                commit('DIRECTORY_MATERIALS_EDIT_GROUPE', form)
            }
        }else{
            commit('DIRECTORY_MATERIALS_ERROR', data.data)
        }
    },
    async directoryMaterialsSearchActions( { commit }, val){

        commit('DIRECTORY_MATERIALS_SEARCH_LOADING')
      
        const data = await httpRequest('directory/material/search', 'post', { name: val })
        commit('DIRECTORY_MATERIALS_SEARCH_LOADING')

        if(data.status == 500){
            commit('DIRECTORY_MATERIALS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_MATERIALS_SET_GROUPE', [])
            commit('DIRECTORY_MATERIALS_SET_SEARCH', data)
        }
        else{
            commit('DIRECTORY_MATERIALS_ERROR', data.data)
        }
    },
    directoryMaterialSortActions( { commit }, orderBy){
        commit('DIRECTORY_MATERIAL_SET_ORDER_BY', orderBy)
    },
    directoryMaterialsCheckedActions( { commit }, material ){
        commit('DIRECTORY_MATERIALS_CHECKED', material)
    },
    directoryMaterialsClearSelectedListActions( { commit } ){
        commit('DIRECTORY_MATERIALS_CLEAR_SELECTED_LIST')
    },
    directoryMaterialsSetOptionsActions( { commit }, options ){
        commit('DIRECTORY_MATERIALS_SET_OPTIONS', options)
    },
    directoryMaterialsSetEditActions( { commit }, material){
        commit('DIRECTORY_MATERIALS_SET_EDIT', material)
    },
    directoryMaterialsSetCurrentGroupeUidActions( { commit }, id){
        commit('DIRECTORY_MATERIALS_SET_CURRENT_GROUPE_UID', id)
    },
    materialsResetCurrentGroupeUidActions( { commit } ){
        commit('DIRECTORY_MATERIALS_RESET_CURRENT_UID')
    },
    directorySetUrlActions( { commit }, url ){
        commit('DIRECTORY_SET_URL', url)
    },
    directoryMaterialsSelectForInfoActions({ commit }, material){
        commit('SELECT_MATERIAL', material)
    }
}
    
const getters = {
    materialsErrorGetter: (state) => state.materialsError,
    directoryMaterialsListGetter: (state) => state.directoryMaterialsList,
    materialsGroupeListGetter: (state) => state.materialsGroupeList,
    directoryMaterialsOptionsGetter: (state) => state.directoryMaterialsOptions,
    materialsCurrentGroupeUidGetter: (state) => state.materialsCurrentGroupeUid,
    directoryMaterialsSelectedListGetter: (state) => state.directoryMaterialsSelectedList,
    directoryMaterialSelectGetter: (state) => state.directoryMaterialSelect,
    materialsEditGetter: (state) => state.materialsEdit,
    materialsUnitsListGetter: (state) => state.materialsUnitsList,
    directoryMaterialsSearchLoadingGetter: (state) => state.directoryMaterialsSearchLoading,
    directoryMaterialsLoadingGetter: (state) =>  state.directoryMaterialsLoading,
    directoryMaterialsSelectedGetter: (state) => state.directoryMaterialsSelected,
    materialsUnitsOptionsGetter: (state) => state.materialsUnitsList.map( item => { 
        const select = {}
        select.value = item.code
        select.text = item.name
        return select
    }),
    directoryMaterialsPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.directoryMaterialsOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
    materialsListNameGetter: (state) => {
        if( state.directoryMaterialsList == null ) {
            return [ { name: 'Краска черная' }, { name: 'Краска синия' } ]
        }else {
            return state.directoryMaterialsList.map( item => item.name)
        }
    }
}

const state = () => ({
    directoryMaterialsList: [],
    directoryMaterialsSelectedList: [],
    materialsUnitsList: [],
    materialsGroupeList: [],
    directoryMaterialSelect: {},
    directoryMaterialsLoading: false,
    directoryMaterialsSearchLoading: false,
    directoryMaterialsSelected: {},
    directoryMaterialsOptions: {
        pagginate: {
            page: 1,
            limit: 10,
            pages: 1
        },
        orderBy: {
            created_at: 'ASC'
        }
    },
    materialsCurrentGroupeUid: '',
    materialsEdit: '',
    materialsError: '',
    directoryURL: 'materials'
})

export default {
    mutations,
    getters,
    actions,
    state,
}
