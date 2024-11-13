<<<<<<< HEAD
import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'  

const mutations = {
    DIRECTORY_CONTRACTS_SET_LIST(state, data){

        state.directoryContractsOptions = data.options
        state.directoryContractsError = ''

        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.directoryContractsList = data.data
        }
        else{
            state.directoryContractsList = []
        }
        
    },
    DIRECTORY_CONTRACTS_CREATE_CONTRACT(state, contract){

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Договор добавлен'
        })

        console.log(contract)

        state.directoryContractsError = ''
        state.directoryContractsList.push(contract)
    },
    DIRECTORY_CONTRACTS_EDIT(state, form){

        state.directoryContractsError = ''
        let index = state.directoryContractsList.indexOf(state.directoryContractsList.filter( item => item.id == form.id)[0])
        
        Vue.set(state.directoryContractsList, index, form)
        
        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Договор изменен'
        })

    },
    DIRECTORY_CONTRACTS_SET_OPTIONS(state, options){
        state.directoryContractsOptions = options
    },
    DIRECTORY_CONTRACTS_LOADING(state){
        state.directoryContractsLoading = !state.directoryContractsLoading
    },
    DIRECTORY_CONTRACTS_ERROR(state, data){

        state.directoryContractsError = data

        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: data.message
        })
    },
    DIRECTORY_CONTRACTS_DELETE(state, id){
        state.directoryContractsError = ''
        state.directoryContractsList = state.directoryContractsList.filter( item => item.id != id)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Договор удален'
        })
    },
    DIRECTORY_CONTRACTS_FIXATE(state, id){

        state.directoryContractsError = ''
        let index = state.directoryContractsList.indexOf(state.directoryContractsList.filter( item => item.id == id)[0])
        
        Vue.set(state.directoryContractsList, index, {
            ...state.directoryContractsList.filter(item => item.id == id)[0],
            fixed: true,
        })
        
        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Договор зафиксирован'
        })
    },
    DIRECTORY_CONTRACTS_REMOVE_FIXATION(state, id){

        state.directoryContractsError = ''

        let index = state.directoryContractsList.indexOf(state.directoryContractsList.filter( item => item.id == id)[0])
        
        Vue.set(state.directoryContractsList, index, {
            ...state.directoryContractsList.filter(item => item.id == id)[0],
            fixed: false,
        })
        
        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Фиксация снята'
        })
    }
}

const actions = {
    async directoryContractsSetListActions({ commit, getters }){
        commit('DIRECTORY_CONTRACTS_LOADING')
        const data = await httpRequest('crm/upravleine/contracts', 'post', { 
            options: getters.directoryContractsOptionsGetter 
        })
 
        commit('DIRECTORY_CONTRACTS_LOADING')

        if(data.status == 500){
            commit('DIRECTORY_CONTRACTS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_SET_LIST', data)
        }
        else{
            commit('DIRECTORY_CONTRACTS_ERROR', data.message)
        }
    },
    async directoryContractsWorkCreateActions({ commit }, form){
        
        const data = await httpRequest('crm/upravleine/contracts/work/add', 'post', form)

        if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_CREATE_CONTRACT', data.data)
        }else{
            commit('DIRECTORY_CONTRACTS_ERROR', data)
        }
    },
    async directoryContractsSupplyCreateActions({ commit }, form){
        
        const data = await httpRequest('crm/upravleine/contracts/supply/add', 'post', form)

        if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_CREATE_CONTRACT', data.data)
        }else{
            commit('DIRECTORY_CONTRACTS_ERROR', data)
        }
    },
    async directoryContractsEditActions({ commit }, form){

        const data = await httpRequest(`crm/upravleine/contract/${form.id}`, 'patch', form)

        if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_EDIT', data.data)
        }else{
            commit('DIRECTORY_CONTRACTS_ERROR', data)
        }

    },
    async directoryContractsDeleteActions({ commit }, id){
        const data = await httpRequest(`directory/contract/${id}`, 'delete', { id: id } )

        if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_DELETE', id)
        }
        else{
            commit('DIRECTORY_CONTRACTS_ERROR', data.data)
        }
    },
    async directoryContractsFixateActions({ commit }, id){

        const data = await httpRequest(`directory/contract/${id}/fix`, 'post', null )

        if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_FIXATE', id)
        }
        else{
            commit('DIRECTORY_CONTRACTS_ERROR', data.data)
        }
    },
    async directoryContractsRemoveFixationActions({ commit }, id){

        const data = await httpRequest(`directory/contract/${id}/unfix`, 'post', null )

        if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_REMOVE_FIXATION', id)
        }
        else{
            commit('DIRECTORY_CONTRACTS_ERROR', data.data)
        }
    },
    directoryContractsSetOptionsActions( { commit }, options ){
        commit('DIRECTORY_CONTRACTS_SET_OPTIONS', options)
    },
}

const getters = {
    directoryContractsListGetter: (state) => state.directoryContractsList,
    directoryContractsErrorGetter: (state) => state.directoryContractsError,
    directoryContractsLoadingGetter: (state) => state.directoryContractsLoading,
    directoryContractsOptionsGetter: (state) =>  state.directoryContractsOptions,
    directoryContractsPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.directoryContractsOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
}   

const state = () => ({
    directoryContractsList: [],
    directoryContractsError: '',
    directoryContractsLoading: false,
    directoryContractsOptions: {
        pagginate: {
            page: 1,
            pages: 1,
            limit: 10
        },
        orderBy: {
            created_at: "DESC",
        }
    },
})

export default {
    mutations,
    getters,
    actions,
    state,
}

=======
import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'  

const mutations = {
    DIRECTORY_CONTRACTS_SET_LIST(state, data){

        state.directoryContractsOptions = data.options
        state.directoryContractsError = ''

        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.directoryContractsList = data.data
        }
        else{
            state.directoryContractsList = []
        }
        
    },
    DIRECTORY_CONTRACTS_SET_FIXED_LIST(state, data){
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.directoryContractsFixedList = data.data
        }
        else{
            state.directoryContractsFixedList = []
        }
    },
    DIRECTORY_CONTRACTS_CREATE_CONTRACT(state, contract){

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Договор добавлен'
        })

        state.directoryContractsError = ''
        state.directoryContractsList.push(contract)
    },
    DIRECTORY_CONTRACTS_EDIT(state, form){

        state.directoryContractsError = ''
        let index = state.directoryContractsList.indexOf(state.directoryContractsList.filter( item => item.id == form.id)[0])
        
        Vue.set(state.directoryContractsList, index, form)
        
        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Договор изменен'
        })

    },
    DIRECTORY_CONTRACTS_SET_OPTIONS(state, options){
        state.directoryContractsOptions = options
    },
    DIRECTORY_CONTRACTS_LOADING(state){
        state.directoryContractsLoading = !state.directoryContractsLoading
    },
    DIRECTORY_CONTRACTS_ERROR(state, message){

        state.directoryContractsError = message
        
        if( message != '' & message != null & typeof message != 'undefined') {
            Vue.notify({
                group: 'error',
                title: 'Ошибка',
                type: 'error',
                text: message
            })
        }
    },
    DIRECTORY_CONTRACTS_DELETE(state, id){
        state.directoryContractsError = ''
        state.directoryContractsList = state.directoryContractsList.filter( item => item.id != id)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Договор удален'
        })
    },
    DIRECTORY_CONTRACTS_FIXATE(state, id){

        state.directoryContractsError = ''
        let index = state.directoryContractsList.indexOf(state.directoryContractsList.filter( item => item.id == id)[0])
        
        Vue.set(state.directoryContractsList, index, {
            ...state.directoryContractsList.filter(item => item.id == id)[0],
            fixed: true,
        })
        
        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Договор зафиксирован'
        })
    },
    DIRECTORY_CONTRACTS_REMOVE_FIXATION(state, id){

        state.directoryContractsError = ''

        let index = state.directoryContractsList.indexOf(state.directoryContractsList.filter( item => item.id == id)[0])
        
        Vue.set(state.directoryContractsList, index, {
            ...state.directoryContractsList.filter(item => item.id == id)[0],
            fixed: false,
        })
        
        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Фиксация снята'
        })
    }
}

const actions = {
    async directoryContractsSetListActions({ commit, getters }){
        commit('DIRECTORY_CONTRACTS_LOADING')
        const data = await httpRequest('directory/contracts', 'post', { 
            options: getters.directoryContractsOptionsGetter 
        })
 
        commit('DIRECTORY_CONTRACTS_LOADING')

        if(data.status == 500){
            commit('DIRECTORY_CONTRACTS_ERROR', 'Ошибка на сервере')
        }
        else if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_SET_LIST', data)
        }
        else{
            commit('DIRECTORY_CONTRACTS_ERROR', data.message)
        }
    },
    async directoryContractsWorkCreateActions({ commit }, form){
        
        const data = await httpRequest('directory/contracts/work/add', 'post', form)

        if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_CREATE_CONTRACT', data.data)
        }else{
            commit('DIRECTORY_CONTRACTS_ERROR', data)
        }
    },
    async directoryContractsSupplyCreateActions({ commit }, form){
        
        const data = await httpRequest('directory/contracts/supply/add', 'post', form)

        if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_CREATE_CONTRACT', data.data)
        }else{
            commit('DIRECTORY_CONTRACTS_ERROR', data)
        }
    },
    async directoryContractsEditActions({ commit }, form){
        
        const data = await httpRequest(`directory/contracts/${form.id}`, 'patch', form)

        if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_EDIT', data.data)
        }else{
            commit('DIRECTORY_CONTRACTS_ERROR', data)
        }
    },
    async directoryContractsDeleteActions({ commit }, params){

        const data = await httpRequest(`directory/contract/${params.id}`, 'delete', null)

        if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_DELETE', params.id)
        }
        else{
            commit('DIRECTORY_CONTRACTS_ERROR', data.data)
        }
    },
    async directoryContractsFixateActions({ commit }, id){

        const data = await httpRequest(`directory/contract/${id}/fix`, 'post', null )

        if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_FIXATE', id)
        }
        else{
            commit('DIRECTORY_CONTRACTS_ERROR', data.data)
        }
    },
    async directoryContractsRemoveFixationActions({ commit }, id){

        const data = await httpRequest(`directory/contract/${id}/unfix`, 'post', null )

        if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_REMOVE_FIXATION', id)
        }
        else{
            commit('DIRECTORY_CONTRACTS_ERROR', data.data)
        }
    },
    async directoryContractsSearchActions({ commit }, value){

        commit('DIRECTORY_CONTRACTS_LOADING')
        let data

        if(value != null){
            data = await httpRequest('directory/contracts', 'post', { 
                options: {
                    orderBy: {
                        createdAt: "DESC"
                    },
                    filter: {
                        number: value,
                    },
                    pagginate: {
                        page: 1,
                        limit: "100"
                    }
                },
            })
        } else {
            data = await httpRequest('directory/contracts', 'post', { 
                options: {
                    orderBy: {
                        createdAt: "DESC"
                    },
                    pagginate: {
                        page: 1,
                        limit: "100"
                    }
                },
            })
        }


        commit('DIRECTORY_CONTRACTS_LOADING')

        if(data.code == 200){
            commit('DIRECTORY_CONTRACTS_SET_LIST', data)
        }else {
            commit('DIRECTORY_CONTRACTS_ERROR', data.message)
        }
    },
    directoryContractsSetOptionsActions( { commit }, options ){
        commit('DIRECTORY_CONTRACTS_SET_OPTIONS', options)
    },
    async directoryContractsAddSpecificationActions( { commit }, params){
        commit('DIRECTORY_CONTRACTS_ERROR', '')
        const data = await httpRequest(`directory/contract/${params.contractId}/specification/${params.specificationId}/add`, 'post', null )

        if(data.code != 200){
            commit('DIRECTORY_CONTRACTS_ERROR', data.message)
        }
    },
    async directoryContractsRemoveSpecificationActions( { commit }, params){
        commit('DIRECTORY_CONTRACTS_ERROR', '')
        const data = await httpRequest(`directory/contract/${params.contractId}/specification/${params.specificationId}/remove`, 'post', null )

        if(data.code != 200){
            commit('DIRECTORY_CONTRACTS_ERROR', data.message)
        }

    }
}

const getters = {
    directoryContractsListGetter: (state) => state.directoryContractsList,
    directoryContractsSupplyListGetter: (state) => state.directoryContractsList.filter( item => item.type == 'supply'),
    directoryContractsErrorGetter: (state) => state.directoryContractsError,
    directoryContractsLoadingGetter: (state) => state.directoryContractsLoading,
    directoryContractsFixedListGetter: (state) => state.directoryContractsFixedList,
    directoryContractsOptionsGetter: (state) =>  state.directoryContractsOptions,
    directoryContractsPagesOptionsGetter: (state) => {
        let items = []
        for(var n = 1; n <= state.directoryContractsOptions.pagginate.pages; n++){
            items.push( { value: n, text: String(n) })
        }
        return items
    },
}   

const state = () => ({
    directoryContractsList: [],
    directoryContractsError: '',
    directoryContractsLoading: false,
    directoryContractsFixedList: [],
    directoryContractsOptions: {
        pagginate: {
            page: 1,
            pages: 1,
            limit: 10
        },
        orderBy: {
            created_at: "DESC",
        }
    },
})

export default {
    mutations,
    getters,
    actions,
    state,
}

>>>>>>> feature/f_requisitions_master
