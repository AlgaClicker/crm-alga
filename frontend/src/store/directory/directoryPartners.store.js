import { httpRequest } from "@/services/httpRequest.service"
import Vue from 'vue'

const mutations = {
    DIRECTORY_PARTNERS_SET_LIST(state, data){
        
        if(typeof data.options == 'undefined'){
            state.directoryPartnersOptions = {
                pagginate: {
                    page: 1,
                    limit: 10,
                    pages: 1
                }
            }
            state.directoryPartnersError = ''
        } else {
            state.directoryPartnersOptions = data.options
            state.directoryPartnersError = ''
        }

        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.directoryPartnersList = data.data
        }
        else{
            state.directoryPartnersList = []
        }
    },
    DIRECTORY_PARTNERS_SET_FORM(state, data){
        state.directoryPartnersError = ''
        state.directoryPartnersForm = data
    },
    DIRECTORY_PARTNERS_DELETE_BANK_ACCOUNT(state, idAccount){

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Банковский счет удален'
        })

        state.directoryPartner.bank_accounts = state.directoryPartner.bank_accounts.filter( item => item.id != idAccount)
    },
    DIRECTORY_PARTNERS_ADD(state, data){
        state.directoryPartnersError = ''
        state.directoryPartnersList.push(data.data)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Контрагент добавлен'
        })
    },
    DIRECTORY_PARTNERS_ADD_BANK_ACCOUNT(state, newAccount){

        state.directoryPartner.bank_accounts.push(newAccount.bank_account)

    },
    DIRECTORY_PARTNERS_EDIT(state, data){
        state.directoryPartnersError = ''

        var index = state.directoryPartnersList.indexOf(state.directoryPartnersList.filter( item => item.id == data.data.id)[0])

        Vue.set(state.directoryPartnersList, index, { 
            ...data.data,
        })
        
        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Контрагент изменен'
        })
    },
    DIRECTORY_PARTNERS_DELETE(state, id){
        state.directoryPartnersError = ''
        state.directoryPartnersList = state.directoryPartnersList.filter(item => item.id != id)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Партнер удален'
        })
    },
    DIRECTORY_PARTNERS_SET_OPTIONS(state, options){
        state.directoryPartnersOptions = options
    },
    DIRECTORY_PARTNERS_ERROR(state, data){
        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: state.directoryPartnersError = data.message[0]
        })

        state.directoryPartnersError = data
    },
    DIRECTORY_PARTNERS_LOADING(state){
        state.directoryPartnersLoading = !state.directoryPartnersLoading
    },
    DIRECTORY_PARTNERS_EDIT_LOADING(state){
        state.directoryPartnersEditLoading = !state.directoryPartnersEditLoading
    },
    DIRECTORY_PARTNER_SET(state, partner){
        state.directoryPartner = partner
    }
}

const actions = {
    async directoryPartnersSetListActions( { commit, getters } ){
        let options = getters.directoryPartnersOptionsGetter
        commit('DIRECTORY_PARTNERS_LOADING')
        const data = await httpRequest('directory/partner/', 'post', {
            options: options
        })
        commit('DIRECTORY_PARTNERS_LOADING')

        if(data.status == 500){
            commit('DIRECTORY_PARTNERS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_PARTNERS_SET_LIST', data)
        }
        else{
            commit('DIRECTORY_PARTNERS_ERROR', data.data)
        }
    },
    async directoryPartnersAddActions( { commit }, form ){

        commit('DIRECTORY_PARTNERS_EDIT_LOADING')
        const data = await httpRequest('directory/partner/add/', 'post', form)
        commit('DIRECTORY_PARTNERS_EDIT_LOADING')

        if(data.status == 500){
            commit('DIRECTORY_PARTNERS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_PARTNERS_ADD', data)
        }
        else{
            commit('DIRECTORY_PARTNERS_ERROR', data.data)
        }
    },
    async directoryPartnersAddByInnActions( { commit }, inn){

        commit('DIRECTORY_PARTNERS_EDIT_LOADING')
        const data = await httpRequest('directory/partner/loadinn/', 'post', { inn: inn })
        commit('DIRECTORY_PARTNERS_EDIT_LOADING')

        if(data.status == 500){
            commit('DIRECTORY_PARTNERS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_PARTNERS_SET_FORM', data.data)
        }
        else{
            commit('DIRECTORY_PARTNERS_ERROR', data.data)
        }
    },
    async directoryPartnersAddBankAccountActions({ commit }, form){

        let data = await httpRequest(`directory/partner/${form.idPartner}/bank/account/add`, 'post',
            { 
                idBank: form.idBank,
                account: form.account
            } 
        )

        var newAccount = {}
        
        if(data.status == 500){
            commit('DIRECTORY_PARTNERS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){

            Vue.notify({
                group: 'success',
                title: 'Успех',
                type: 'success',
                text: "Добавлен новый счет"
            })

            newAccount.idPartner = form.idPartner
            newAccount.bank_account = data.data

            commit('DIRECTORY_PARTNERS_ADD_BANK_ACCOUNT', newAccount)
        }
        else{
            commit('DIRECTORY_PARTNERS_ERROR', data.data)
        }

    },
    async directoryPartnersDeleteActions({ commit }, form){

        if(form.isGroup){
            let data = await httpRequest('directory/partner/', 'delete', { id: form.id } )
            if(data.status == 500){
                commit('DIRECTORY_PARTNERS_ERROR', 'Ошибка на сервере')
            }
            else if(data.code == 200){
                commit('DIRECTORY_PARTNERS_DELETE', form.id)
            }else{
                commit('DIRECTORY_PARTNERS_ERROR', data.data)
            }
        }else{
            let data = await httpRequest('directory/partner/', 'delete', { id: form.id } )
            if(data.status == 500){
                commit('DIRECTORY_PARTNERS_ERROR', 'Ошибка на сервере')
            }
            else if(data.code == 200){
                commit('DIRECTORY_PARTNERS_DELETE', form.id)
            }else{
                commit('DIRECTORY_PARTNERS_ERROR', data.data)
            }
        }
    },
    async directoryPartnersDeleteBankAccountActions({ commit }, form){
        const data = await httpRequest(`directory/partner/${form.idPartner}/bank/account`, 'delete', {
            id: form.idAccount
        })
        
        if(data.code == 500){
            commit('DIRECTORY_PARTNERS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_PARTNERS_DELETE_BANK_ACCOUNT', form.idAccount)
        }
        else{
            commit('DIRECTORY_PARTNERS_ERROR', data)
        }
    }, 
    directoryPartnersSetOptionsActions( { commit }, option ){
        commit('DIRECTORY_PARTNERS_SET_OPTIONS', option)
    },
    directoryPartnersCheckedActions( { commit }, partner ){
        commit('DIRECTORY_PARTNERS_CHECKED', partner)
    },
    async directoryPartnerSetActions( { commit }, id ){

        commit('DIRECTORY_PARTNERS_LOADING')

        const data = await httpRequest('directory/partner/', 'post', {
            id: id
        })

        commit('DIRECTORY_PARTNERS_LOADING')

        if(data.status == 500){
            commit('DIRECTORY_PARTNERS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_PARTNER_SET', data.data)
        }
        else{
            commit('DIRECTORY_PARTNERS_ERROR', data.data)
        }
    },
    async directoryPartnerSearchActions( { commit }, value ){

        const data = await httpRequest('directory/partner/search', 'post', {
            name: value
        })

        if(data.status == 500){
            commit('DIRECTORY_PARTNERS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_PARTNERS_SET_LIST', data)
        }
        else{
            commit('DIRECTORY_PARTNERS_ERROR', data.data)
        }
    }
}

const getters = {
    directoryPartnersListGetter: (state) => state.directoryPartnersList,
    directoryPartnerGetter: (state) => state.directoryPartner,
    directoryPartnersOptionsGetter: (state) => state.directoryPartnersOptions,
    directoryPartnersGroupeListGetter: (state) => state.directoryPartnersGroupeList,
    directoryPartnersErrorGetter: (state) => state.directoryPartnersError,
    directoryPartnersLoadingGetter: (state) => state.directoryPartnersLoading,
    directoryPartnersFormGetter: (state) => state.directoryPartnersForm,
    directoryPartnersPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.directoryPartnersOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
}

const state = () => ({
    directoryPartner: {},
    directoryPartnersList: [],
    directoryPartnersError: '',
    directoryPartnersLoading: false,
    directoryPartnersEditLoading: false,
    directoryPartnersForm: {
        name: '',
        fullname: '',
        address: '',
        ogrn: '',
        okpo: '',
        oktmo: '',
        inn: '',
        kpp: '',
        bank_accounts: [],
        type: '',
        articul: '',
        parent: '',
        is_group: false,
    },
    directoryPartnersOptions: {

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
