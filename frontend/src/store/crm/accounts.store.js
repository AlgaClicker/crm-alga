import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'

const mutations = {
    ACCOUNTS_COMPANY_ERROR(state, data){

        state.accountsCompanyError = data
        
        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: data.data.message
        })
    },
    ACCOUNTS_COMPANY_SET_ERROR(state){
        state.accountsCompanyList = []
    },
    ACCOUNTS_COMPANY_SET_LIST(state, data){
        state.accountsCompanyError = ''
        state.accountsCompanyList = data.data
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.accountsCompanyList = data.data  
            state.accountsCompanyOptions = data.options
        }else{
            state.accountsCompanyList = []
        }
    },
    ACCOUNTS_COMPANY_SET_ROLES_LIST(state, data){
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.accountsCompanyRolesList = data.data
        }else{
            state.accountsCompanyRolesList = []
        }
    },
    ACCOUNTS_COMPANY_EDIT(state, data){
        state.accountCompanyError = ''
        let index = state.accountsCompanyList.indexOf(state.accountsCompanyList.filter( item => item.id == data.id)[0])

        Vue.set(state.accountsCompanyList, index, data)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Аккаунт изменен'
        })
    },
    ACCOUNTS_COMPANY_ADD(state, data){
        state.accountsCompanyError = ''
        state.accountsCompanyList.push(data.data)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Аккаунт добавлен'
        })
    },
    DIRECTORY_ACCOUNTS_LOADING(state){
        state.accountsCompanyLoading = !state.accountsCompanyLoading
    },
    ACCOUNTS_COMPANY_REMOVE_EMPLOYEE(state, id){
        var index = state.accountsCompanyList.indexOf(state.accountsCompanyList.filter( item => item.id == id)[0])
        var account = state.accountsCompanyList[index]
        delete account.workpeople

        Vue.set(state.accountCompanyList, index, {
            ...account,
        } )
    },
    ACCOUNTS_COMPANY_SET_OPTIONS(state, options){
        state.accountsCompanyOptions = options
    },
    ACCOUNT_COMPANY_SET_FILTER(state, filter){
        state.accountsCompanyOptions.filter = filter
        state.accountsCompanyOptions.pagginate = {
            page: 1,
            limit: 100
        }
    }
}

const actions = {
    async accountsCompanySetActions( { commit, getters } ){

        commit('DIRECTORY_ACCOUNTS_LOADING')

        let options = getters.accountsCompanyOptionsGetter
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

        const data = await httpRequest('crm/account/', 'post', {
            options: options
        })
        commit('DIRECTORY_ACCOUNTS_LOADING')    
        
        if(data.code == 200){
            commit("ACCOUNTS_COMPANY_SET_LIST", data)
        }
        else{
            commit("ACCOUNTS_COMPANY_ERROR", data)
            commit("ACCOUNTS_COMPANY_SET_ERROR")
        }
    },
    async accountsCompanySetRolesActions( { commit } ){
        const data = await httpRequest('crm/account/roles', 'post')
        if(data.code == 200){
            commit("ACCOUNTS_COMPANY_SET_ROLES_LIST", data)
        }else{
            commit("ACCOUNTS_COMPANY_ERROR", data)
        }
    },
    async accountsCompanyAddActions( { commit }, form ){
        const data = await httpRequest('crm/account/add', 'post', form)
        if(data.code == 200){
            commit("ACCOUNTS_COMPANY_ADD", data)
        }else{
            commit("ACCOUNTS_COMPANY_ERROR", data)
        }
    },
    async accountsCompanyEditActions( { commit }, account ){
        const data = await httpRequest('crm/account', 'patch', account )
        
        if(data.code == 200){
            commit("ACCOUNTS_COMPANY_EDIT", data.data)
        }else{
            commit("ACCOUNTS_COMPANY_ERROR", data)
        } 
    },
    accountsCompanyRemoveEmployeesActions( { commit }, id){
        commit('ACCOUNTS_COMPANY_REMOVE_EMPLOYEE', id)
    },
    accountsCompanySetOptionsActions( { commit }, options){
        commit('ACCOUNTS_COMPANY_SET_OPTIONS', options)
    },   
    accountsCompanySetFilterActions( { commit }, filter ){
        commit('ACCOUNT_COMPANY_SET_FILTER', filter)
    },
}

const getters = {
    accountsCompanyListGetter: (state) => state.accountsCompanyList,
    accountsCompanyOptionsGetter: (state) => state.accountsCompanyOptions,
    accountsCompanyErrorGetter: (state) => state.accountsCompanyError,
    accountsCompanyLoadingGetter: (state) => state.accountsCompanyLoading,
    accountsCompanyListByRoleGetter: (state) => (roles) => state.accountsCompanyList.filter( item => item.roles.service == roles ),
    accountsCompanyListByRoleOptionsGetter: (state) => (roles) => state.accountsCompanyList
        .filter( item => item.roles.service == roles )
        .map(item => {
            const select = {}
                
            select.value = item.id

            if( typeof item.workpeople == 'undefined'){
                select.text = item.username
            } else {
                select.text = item.workpeople.surname + ' ' + item.workpeople.name
            }

            return select
        }),
    accountsCompanyOptionsFreeGetter: (state) => state.accountsCompanyList
        .filter( item => typeof item.workpeople == 'undefined')
        .map(item => {
            const select = {}
            
            select.value = item
            select.username = item.username

            return select
        }),
    accountsCompanyRolesListGetter: (state) => state.accountsCompanyRolesList.map( item => {
        const select = {}
        
        select.value = item.service
        select.text = item.name

        return select
    }),
    accountsCompanySelectOptionsGetter: (state) => state.accountsCompanyList.map( item => { 
        const select = {}
        
        select.value = item.id
        select.username = item.username

        return select
    }),
    accountsCompanyPageOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.accountsCompanyOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
}

const state = () => ({
    accountsCompanyList: [],
    accountsCompanyRolesList: [],
    accountsCompanyError: '',
    accountsCompanyLoading: false,
    accountsCompanyOptions: {
        pagginate: {
            page: 1,
            pages: 1,
            limit: 10
        },
        filter: {
            username: null,
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
