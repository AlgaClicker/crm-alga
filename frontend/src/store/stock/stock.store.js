import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'
//import _ from 'lodash'
//import Vue from 'vue'

const mutations = {
    STOCK_SET_LIST(state, data){
        if(typeof data.options != 'undefined'){
            state.stockOptions = data.options
        } 
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.stockList = data.data
        }else{
            state.stockList = []
        }
    },
    STOCK_ADD_ACTIONS(state, data){

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Склад добавлен'
        })      

        state.stockList.push(data)
    },
    STOCK_EDIT_ACTIONS(state, form){

        let index = state.stockList.indexOf(state.stockList.filter( item => item.id == form.id)[0])
        
        Vue.set(state.stockList, index, form)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Склад изменен'
        })   
    },
    STOCK_DELETE_ACTIONS(state, data){

        state.stockList = state.stockList.filter( item => item.id != data.id)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Склад удален'
        })   
    },
    STOCK_SET_OPTIONS(state, options){
        state.stockOptions = options
    },
    STOCK_SET_ERROR(state, error){

        state.stockError = error

        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: error
        })      

    },
    STOCK_LOADING(state){
        state.stockLoading = !state.stockLoading
    }
}

const actions = {
    async stockSetListActions( { commit } ){

        commit('STOCK_LOADING')
        const data = await httpRequest('buisness/company/stock', 'post', { 
            options: getters.stockOptionsGetter
        })
        commit('STOCK_LOADING')
        
        if(data.status == 500){
            commit('STOCK_SET_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('STOCK_SET_LIST', data)
        }
        else{
            commit('STOCK_SET_ERROR', data.message)
        }
    },
    async stockAddActions( { commit }, form){
        const data = await httpRequest('crm/stock/add', 'post', form)
        if(data.code == 200){
            commit('STOCK_ADD_ACTIONS', data.data)
        }else {
            commit('STOCK_SET_ERROR', data.message)
        }
    },
    async stockDeleteActions({ commit },  form){
        commit('DIRECTORY_CONTRACTS_ERROR', '')
        const data = await httpRequest(`crm/stock`, 'delete', { id: form.id } )

        if(data.code == 200){
            commit('STOCK_DELETE_ACTIONS', data.data)
        }else {
            commit('STOCK_SET_ERROR', data.message)
        }
    },
    async stockEditActions({ commit }, form){
        const data = await httpRequest(`crm/stock`, 'patch', form )

        if(data.code == 200){
            commit('STOCK_EDIT_ACTIONS', form)
        }else {
            commit('STOCK_SET_ERROR', data.message)
        }
    }
}

const getters = {
    stockListGetter: (state) => state.stockList,
    stockErrorGetter: (state) => state.stockErrorGetter,
    stockLoadingGetter: (state) => state.stockLoading,
    stockOptionsGetter: (state) => state.stockOptions
}

const state = () => ({
    stockList: [],
    stockError: '',
    stockLoading: false,
    stockOptions: {
        pagginate: {
            page: 1,
            pages: 1,
            limit: 10
        },
        orderBy: {
            name: "ASC",
        }
    }
})

export default {
    mutations,
    getters,
    actions,
    state,
}
