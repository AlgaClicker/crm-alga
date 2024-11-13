import Vue from 'vue'
import { httpRequest } from "@/services/httpRequest.service"
import store from '@/store'

const mutations = {
    MASTER_SET_LOADING(state){
        state.masterLoading = !state.masterLoading
    },
    MASTER_BRIGADES_SET(state, data){
        state.masterBrigade = data.data
    },
    MASTER_BRIGADES_SET_LIST(state, data){
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.masterBrigadesList = data.data
        }else{
            state.masterBrigadesList = []
        }
    },
    MASTER_BRIGADES_INCOME_SET(state, data){
        state.masterBrigadeIncome = data.data
    },
    MASTER_BRIGADES_EDIT(state, brigade){

        state.masterError = ''
        let index = state.masterBrigadesList.indexOf(state.masterBrigadesList.filter( item => item.id == brigade.id)[0])
        state.masterBrigade = brigade
         
        Vue.set(state.masterBrigadesList, index, brigade)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Бригада изменена'
        })
    },
    MASTER_BRIGADES_WORKPEOPLES_SET(state, data){
        state.masterBrigadeWorkpeopleList = data.data
    },
    MASTER_BRIGADES_SPECIFICATION_ADD(state, data){
        state.masterError = ''
        state.masterBrigadeSpecificationsList.push(data)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Спецификация назначена'
        })
    },
    MASTER_BRIGADES_SPECIFICATION_DELETE(state, data){
        state.masterError = ''
        state.masterBrigadeSpecificationsList = state.masterBrigadeSpecificationsList.filter( item => item.id != data.id)

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Спецификация удалена'
        })
    },
    MASTER_BRIGADES_SPECIFICATION_SET(state, data){
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.masterBrigadeSpecificationsList= data.data
        }else{
            state.masterBrigadeSpecificationsList = []
        } data
    },
    MASTER_SET_ERROR(state, data){
        state.masterError = data.message[0]

        Vue.notify({
            group: 'foo',
            title: 'Ошибка',
            type: 'error',
            text: data.message[0]
        })

    },
}

const actions = {
    async masterBrigadesSetListActions( { commit }, param ){
        var form = param
        
        if(store.getters.profileGetter.roles.service != 'master'){
            form = {}
        }
        
        commit('MASTER_SET_LOADING')
        const data = await httpRequest('crm/brigade/', 'post', form )
        commit('MASTER_SET_LOADING')

        if(data.code == 200){
            commit('MASTER_BRIGADES_SET_LIST', data)
        }
        else{
            commit('MASTER_SET_ERROR', data.data)
        }
    },
    async masterBrigadeSetActions( { commit }, id ){
        
        commit('MASTER_SET_LOADING')
        let data = await httpRequest('crm/brigade/', 'post', { id: id } )

        if(data.code == 200){
            commit('MASTER_BRIGADES_SET', data)
        }
        else{
            commit('MASTER_SET_ERROR', data.data)
        }

        data = await httpRequest('crm/payments/brigade/', 'post', { id: id } )

        if(data.code == 200){
            commit('MASTER_BRIGADES_INCOME_SET', data)
        }
        else{
            commit('MASTER_SET_ERROR', data.data)
        }

        data = await httpRequest(`crm/brigade/${id}/people`, 'post', {} )

        if(data.code == 200){
            commit('MASTER_BRIGADES_WORKPEOPLES_SET', data)
        }
        else{
            commit('MASTER_SET_ERROR', data.data)
        }

        commit('MASTER_SET_LOADING')
    },
    async masterBrigadeSetSpecificationsActions( { commit, getters} ){

        const data = await httpRequest(`crm/brigade/${getters.masterBrigadeGetter.id}/specifications`, 'post', {})

        if(data.code == 200){
            commit('MASTER_BRIGADES_SPECIFICATION_SET', data)
        }
        else{
            commit('MASTER_SET_ERROR', data.data)
        }

    },
    async masterBrigadeAddSpecificationsActions( { commit, getters }, form){

        const data = await httpRequest(`crm/brigade/${getters.masterBrigadeGetter.id}/specification/set`, 'post', { 
            specification_id: form.specificationId,
            date_end: form.dateEnd
        })

        if(data.code == 200){
            commit('MASTER_BRIGADES_SPECIFICATION_ADD', data.data)
        }
        else{
            commit('MASTER_SET_ERROR', data.data)
        }

    },
    async masterBrigadeDeletepecificationsActions( { commit, getters }, idSpecifications){
        const data = await httpRequest(`crm/brigade/${getters.masterBrigadeGetter.id}/specifications`, 'delete', { id: idSpecifications} )

        if(data.code == 200){
            commit('MASTER_BRIGADES_SPECIFICATION_DELETE', data.data)
        }
        else{
            commit('MASTER_SET_ERROR', data.data)
        }
    },
    async masterBrigadeEditActions( { commit }, formEdit ){
        
        let form = { ...formEdit }
        
        let workpeoples = form.workpeoples.map( item => {
            const select = {}
            select.id = item.id
            return select
        })

        form.workpeoples = workpeoples

        const data = await httpRequest('crm/brigade', 'patch', form)

        if(data.code == 200){
            commit('MASTER_BRIGADES_EDIT', data.data)
        }
        else{
            commit('MASTER_SET_ERROR', data.data)
        }
    }
}
    
const getters = {
    masterBrigadesListGetter: (state) => state.masterBrigadesList,
    masterBrigadeIncomeGetter: (state) => state.masterBrigadeIncome,
    masterBrigadeWorkpeopleListGetter: (state) => state.masterBrigadeWorkpeopleList,
    masterBrigadeSpecificationsListGetter: (state) => state.masterBrigadeSpecificationsList,
    masterBrigadeGetter: (state) => state.masterBrigade,
    masterErrorGetter: (state) => state.masterError,
    masterLoadingGetter: (state) => state.masterLoading,
    masterBrigadesOptionsGetter: (state) => state.masterBrigadesOptions,
}

const state = () => ({
    masterBrigadesList: [],
    masterBrigade: {},
    masterBrigadeWorkpeopleList: [],
    masterBrigadeSpecificationsList: [],
    masterLoading: false,
    masterBrigadeIncome: [],
    masterBrigadesOptions: {
        orderBy: {
            createdAt:"DESC"
        },
        pagginate: {
            pages: 1,
            page: 1,
            limit: 25
        }
    },
    masterBrigadesLoading: false,
    masterError: '',
})

export default {
    mutations,
    getters,
    actions,
    state,
}