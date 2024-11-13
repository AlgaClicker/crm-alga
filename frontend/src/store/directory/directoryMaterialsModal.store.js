import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue' 

const mutations = {
    DIRECTORY_MATERIALS_MODAL_SET_LIST(state, data){
        state.directoryMaterialsModalOptions = data.options
        state.directoryMaterialsModalList = data.data
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.directoryMaterialsModalList = data.data.map( (item) => {
                return { ...item, selected: false }   
            })  
        }else{
            state.directoryMaterialsModalList = []
        }
    },
    DIRECTORY_MATERIALS_MODAL_SET_GROUPE(state, data){
        var materialGroupe = []
        if( data.data != [] && data.data != null && typeof data.data != 'undefined' ){
            materialGroupe = data.data.map( (item) => {
                return { ...item, selected: false }   
            })
            state.directoryMaterialsModalGroupeList = state.directoryMaterialsModalGroupeList.filter(item => item.parent.id != data.data[0].parent.id)
            state.directoryMaterialsModalGroupeList.push(...materialGroupe)
        }

        state.directoryMaterialsModalError = ''
    },
    DIRECTORY_MATERIALS_MODAL_CHECKED(state, material){

        material.selected = !material.selected
        state.directoryMaterialsModalSelectedList = []

        if( material.selected == true ){
            state.directoryMaterialsModalSelectedList[0] = material
        } 

        for(var i = 0; i < state.directoryMaterialsModalList.length; i++ ){
            if(state.directoryMaterialsModalList[i].id != material.id){ 
                Vue.set(state.directoryMaterialsModalList, i, {
                    ...state.directoryMaterialsModalList[i],
                    selected: false
                } ) 
            }
        }
    },
    DIRECTORY_MATERIALS_MODAL_SET_OPTIONS(state, options){
        console.log(options)
    },
    DIRECTORY_MATERIALS_MODAL_SELECTED_LIST_CLEAR(state){
        state.directoryMaterialsModalSelectedList = []
    },
    DIRECTORY_MATERIALS_MODAL_LOADING(state){
        state.directoryMaterialsModalLoading = !state.directoryMaterialsModalLoading
    },
    DIRECTORY_MATERIALS_MODAL_ERROR(state, data){
        state.directoryMaterialsModalError = data.data
    },
    DIRECTORY_MATERIALS_MODAL_SET_SEARCH(state, data){

        state.directoryMaterialsModalError = ''
        state.directoryMaterialsModalSelectedList = []
        state.directoryMaterialsModalList = []

        if(data != null && typeof data != 'undefined' && data.length != 0){
            state.directoryMaterialsModalList = data.map( (item) => {
                return { ...item, selected: false }   
            })
        } 
    },
}

const actions = {
    async directoryMaterialsModalSetActions({ commit, getters }, parent) {
        
        let data = null

        if(typeof parent == 'undefined' | parent == null){
            parent = ''
        }

        if(parent == ''){
            commit('DIRECTORY_MATERIALS_LOADING')
            data = await httpRequest('directory/material/', 'post', {
                options:  getters.directoryMaterialsModalOptionsGetter 
            })
            commit('DIRECTORY_MATERIALS_LOADING')
        }

        if(parent != ''){
            data = await httpRequest('directory/material/', 'post', {
                parent: parent
            })
        }
        
        if(data.status == 500){
            commit('DIRECTORY_MATERIALS_MODAL_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            if(parent != ''){
                commit('DIRECTORY_MATERIALS_MODAL_SET_GROUPE', data)
            }
            else if(parent == ''){
                commit('DIRECTORY_MATERIALS_MODAL_SET_LIST', data)
            }else {
                commit('DIRECTORY_MATERIALS_MODAL_SET_LIST', data)
            }
        }
        else{
            commit('DIRECTORY_MATERIALS_MODAL_ERROR', data.data)
        }
    },
    async directoryMaterialsModalSearchActions({ commit }, val){
        
        commit('DIRECTORY_MATERIALS_MODAL_LOADING')
        
        const data = await httpRequest('directory/material/search', 'post', { name: val })
        commit('DIRECTORY_MATERIALS_MODAL_LOADING')

        if(data.status == 500){
            commit('DIRECTORY_MATERIALS_MODAL_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_MATERIALS_MODAL_SET_GROUPE', [])
            commit('DIRECTORY_MATERIALS_MODAL_SET_SEARCH', data.data)
        }
        else{
            commit('DIRECTORY_MATERIALS_MODAL_ERROR', data.data)
        }
    },
    directoryMaterialsModalSelectedListClearActions( { commit } ){
        commit('DIRECTORY_MATERIALS_MODAL_SELECTED_LIST_CLEAR')
    },
    async directoryMaterialsModalCheckedActions( { commit }, material ){
        commit('DIRECTORY_MATERIALS_MODAL_CHECKED', material)
    },   
    async directoryMaterialsModalSetOptionsActions( { commit }, options ){
        commit('DIRECTORY_MATERIALS_MODAL_SET_OPTIONS', options)
    },
}

const getters = {
    directoryMaterialsModalListGetter: (state) => state.directoryMaterialsModalList,
    directoryMaterialsModalGroupeListGetter: (state) => state.directoryMaterialsModalGroupeList,
    directoryMaterialsModalSelectedListGetter: (state) => state. directoryMaterialsModalSelectedList,
    directoryMaterialsModalOptionsGetter: (state) => state.directoryMaterialsModalOptions,
    directoryMaterialsModalLoadingGetter: (state) => state.directoryMaterialsModalLoading,
    directoryMaterialsModalErrorGetter: (state) => state.directoryMaterialsModalError,
    directoryMaterialsModalPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.directoryMaterialsModalOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    }
}

const state = {
    directoryMaterialsModalList: [],
    directoryMaterialsModalGroupeList: [],
    directoryMaterialsModalSelectedList: [],
    directoryMaterialsModalError: '',
    directoryMaterialsModalLoading: false,
    directoryMaterialsModalOptions: {
        pagginate: {
            page: 1,
            limit: 10,
            pages: 1
        },
        orderBy: {
            created_at: 'ASC'
        }
    },
}

export default {
    mutations,
    getters,
    actions,
    state,
}
