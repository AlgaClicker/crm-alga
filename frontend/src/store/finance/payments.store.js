import { httpRequest } from '@/services/httpRequest.service'

import Vue from 'vue'

const mutations = {
    PAYMENTS_SET_LIST(state, data){
        state.paymentsError = ''
       
        if( typeof data.options.filter == 'undefined'){
            state.paymentsOptions.filter = {
                date: null,
                amount: null,
                type: null
            }
        }

        if(typeof data.options != 'undefined'){
            state.paymentsOptions.pagginate = data.options.pagginate
            state.paymentsOptions.orderBy = data.options.orderBy
        } 

        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.paymentsList = data.data.map( (item) => {
                if(typeof item.specification == 'undefined'){
                    return { 
                        ...item, 
                        specification: '', 
                        selected: false 
                    }
                }else{
                    return { ...item, selected: false }  
                } 
            })
        }
        else{
            state.paymentsList = []
        } 
    },
    PAYMENTS_SET_PAYMENT(state, data){
        if(data != [] && data != null && typeof data != 'undefined'){
            state.payment = data
        }else{
            state.payment = {}
        }   

    },
    PAYMENTS_ADD(state, data){

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Платеж исполнен'
        })

        state.paymentsList.push(data.data)
    },
    PAYMENTS_SET_LOADING(state){
        state.paymentsLoading = !state.paymentsLoading
    },
    PAYMENTS_SET_ERROR(state, data){
        state.paymentsError = data.message[0]

        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: data.message[0]
        })

    },
    PAYMENTS_SET_OPTIONS(state, options){
        state.paymentsOptions = options
    },
    PAYMENTS_SET_FILTER(state, filter){
        let created_at = state.paymentsOptions.filter.created_at
        state.paymentsOptions.filter = filter
        state.paymentsOptions.filter.created_at = created_at
    },
    PAYMENTS_EDIT(state, data){
        let index = state.paymentsList.indexOf(state.paymentsList.filter( item => item.id == data.id)[0])

        Vue.set(state.paymentsList, index, data)

        state.paymentsError = '' 
    },
    PAYMENTS_SET_FILTER_DATE(state, filter){
        state.paymentsOptions.filter.date = filter
    }
}

const actions = {
    async paymentsSetActions( { commit, getters } ) {

        let options = getters.paymentsOptionsGetter

        let filter = {}
        let count = 0

        for (let key in options.filter) {
            if(options.filter[key] != null){
                count += 1
                filter[key] = options.filter[key]
            }
        }

        if(count != 0){
            options.filter = filter
        } else  {
            delete options.filter 
        }

        commit('PAYMENTS_SET_LOADING')
        const data = await httpRequest('crm/payments/invoice/', 'post', { 
            options: options
        })
        commit('PAYMENTS_SET_LOADING')

        if(data.status == 500){
            commit('PAYMENTS_SET_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('PAYMENTS_SET_LIST', data)
        }
        else{
            commit('PAYMENTS_SET_ERROR', data.data)
        }
    },
    async paymentsSetPaymentActions({ commit }, id ){
        commit('PAYMENTS_SET_LOADING')
        const data = await httpRequest('crm/payments/invoice/', 'post', { 
            id: id
        })
        commit('PAYMENTS_SET_LOADING')

        if(data.status == 500){
            commit('PAYMENTS_SET_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('PAYMENTS_SET_PAYMENT', data.data)
        }
        else{
            commit('PAYMENTS_SET_ERROR', data.data)
        }
    },
    async paymentsNewActions( { commit }, form ){
        const data = await httpRequest('crm/payments/invoice/add', 'post', form)

        if(data.status == 500){
            commit('PAYMENTS_SET_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('PAYMENTS_ADD', data)
        }
        else{
            commit('PAYMENTS_SET_ERROR', data.data)
        }

    },
    async paymentsEditActions( { commit }, form){
        const data = await httpRequest('crm/payments/invoice/', 'patch', form)

        if(data.status == 500){
            commit('PAYMENTS_EDIT_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('PAYMENTS_EDIT', data.data)
        }
        else{
            commit('PAYMENTS_EDIT_ERROR', data.data)
        }
    },
    paymentsSetOptionsActions( { commit }, options ){
        commit('PAYMENTS_SET_OPTIONS', options)
    }, 
    paymentsSetFilterActions( { commit }, filter ){
        commit('PAYMENTS_SET_FILTER', filter)
    },
    paymentsSetDateFilterActions( { commit}, filter ){
        commit('PAYMENTS_SET_FILTER_DATE', filter)
    }
}

const getters = {
    paymentsListGetter: (state) => state.paymentsList,
    paymentGetter: (state) => state.payment,
    paymentsOptionsGetter: (state) => state.paymentsOptions,
    paymentsFilterGetter: (state) => state.paymentsOptions.filter,
    paymentsLoadingGetter: (state) => state.paymentsLoading,
    paymentsErrorGetter: (state) => state.paymentsError,
    paymentsPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.paymentsOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
}

const state = () => ({
    paymentsList: [],
    payment: {},
    paymentsOptions: {
        orderBy: {
            date: "DESC"
        },
        pagginate: {
            page: 1,
            limit: 10,
            pages: 1
        },
        filter: {
            date: null,
            amount: null,
            type: null
        }
    },
    paymentsLoading: false,
    paymentsError: ''
})

export default {
    mutations,
    getters,
    actions,
    state,
}