import Vue from 'vue'
import { httpRequest } from "@/services/httpRequest.service"

const mutations = {
    KADRY_SET_LOADING(state){
        state.kadryLoading = !state.kadryLoading
    },
    KADRY_PAYMENTS_BRIGADE_SET_LIST(state, data){
        state.kadryError = ''
        state.kadryPaymentsBrigadeOptions = data.options
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.kadryPaymentsBrigadeList = data.data
        }else{
            state.kadryPaymentsBrigadeList = []
        }
    },
    KADRY_PAYMENTS_BRIGADE_ADD(state, data){

        state.kadryPaymentsBrigadeList.push(data)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Выплата произведена'
        })
    },
    KADRY_SET_ERROR(state, data){
        state.kadryError = data.message[0]

        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: data.message[0]
        })

    },
    KADRY_PAYMENTS_SET_OPTIONS(state, options){
        state.kadryPaymentsBrigadeOptions = options
    },
    KADRY_PAYMENTS_SET_FILTER(state, filter){
        state.kadryPaymentsBrigadeOptions.filter.amount = filter.amount
        state.kadryPaymentsBrigadeOptions.filter.type = filter.type
    },
    KADRY_SET_FILTER_DATE(state, filter){
        state.kadryPaymentsBrigadeOptions.filter.date = filter
    },
}

const actions = {
    async kadryPaymentsBrigadeSetActions({ commit, getters }){
        commit('KADRY_SET_LOADING')
        const data = await httpRequest('crm/payments/brigade/', 'post', {
            options: getters.kadryPaymentsBrigadeOptionsGetter
        })
        commit('KADRY_SET_LOADING')

        if(data.code == 200){
            commit('KADRY_PAYMENTS_BRIGADE_SET_LIST', data)
        }
        else{
            commit('KADRY_SET_ERROR', data)
        }
    },
    async kadryPaymentsBrigadeNewActions( { commit }, form){

        const data = await httpRequest('crm/payments/brigade/add', 'post', form )

        if(data.code == 200){
            commit('KADRY_PAYMENTS_BRIGADE_ADD', data.data)
        }
        else{
            commit('KADRY_SET_ERROR', data)
        }
    },
    kadryPaymentsBrigadeSetOptionsActions( { commit }, options ){
        commit('KADRY_PAYMENTS_SET_OPTIONS', options)
    }, 
    kadryPaymentsBrigadeSetDateFilterActions({ commit }, filter){
        commit('KADRY_SET_FILTER_DATE', filter)
    },
    kadryPaymentsBrigadeSetFilterActions({ commit }, filter){
        commit('KADRY_PAYMENTS_SET_FILTER', filter)
    }
}
    
const getters = {
    kadryPaymentsBrigadeListGetter: (state) => state.kadryPaymentsBrigadeList,
    kadryLoadingGetter: (state) => state.kadryLoading,
    kadryPaymentsBrigadeOptionsGetter: (state) => state.kadryPaymentsBrigadeOptions, 
    kadryPaymentsPageOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.kadryPaymentsBrigadeOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
    kadryErrorGetter: (state) => state.kadryError
}

const state = () => ({
    kadryPaymentsBrigadeList: [],
    kadryLoading: false,
    kadryPaymentsBrigadeOptions: {
        orderBy: {
            created_at: "ASC"
        },
        pagginate: {
            pages: 1,
            page: 1,
            limit: 10
        },
        filter: {
            date: {
                start: '',
                end: ''
            },
            amount: {
                start: '',
                end: ''
            },
            type: '' 
        }
    },
    kadryError: ''
})

export default {
    mutations,
    getters,
    actions,
    state,
}