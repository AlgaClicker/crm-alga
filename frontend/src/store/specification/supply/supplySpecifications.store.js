import { httpRequest } from '@/services/httpRequest.service'
/* import Vue from 'vue'  */

const mutations = {
    SUPPLY_SPECIFICATION_SET(state, data){
        state.supplySpecificationError = ''
        state.supplySpecification = data
    },
    SUPPLY_SPECIFICATION_SET_LIST(state, data){
        state.supplySpecificationError = ''
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.supplySpecificationList = data.data
        }else{
            state.supplySpecificationList = []
        }
    },
    SUPPLY_SPECIFICATION_LOADING(state){
        state.supplySpecificationLoading = !state.supplySpecificationLoading
    },
    SUPPLY_SPECIFICATION_ERROR(state, error){
        state.supplySpecificationError = error
    },
    SUPPLY_SPECIFICATION_SET_LIST_IZM(state, data){
        state.supplySpecificationListIZM = data
    }
}

const actions = {
    async supplySpecificationSetListActions( { commit } ){
        commit('SUPPLY_SPECIFICATION_LOADING')
        const data = await httpRequest('crm/master/specifications', 'post', {})
        commit('SUPPLY_SPECIFICATION_LOADING')

        if(data.code == 200){
            commit('SUPPLY_SPECIFICATION_SET_LIST', data)
        }
        else{
            commit('SUPPLY_SPECIFICATION_ERROR', data.data)
        }
    },
    async supplySpecificationGetByIdActions( { commit }, form ){

        commit('SUPPLY_SPECIFICATION_LOADING')
        const data = await httpRequest('crm/specification/', 'post', form)

        if(data.code == 200){
            commit('SUPPLY_SPECIFICATION_SET', data.data)
        } else if(data.status == 422){
            commit("SUPPLY_SPECIFICATION_ERROR", 'Заполните все поля')
        }
        else{
            commit("SUPPLY_SPECIFICATION_ERROR", data.data)
        }
        
        commit('SUPPLY_SPECIFICATION_LOADING')
    },
    async supplySpecificationSetIZMActions( { commit, getters }, id){
        
        const data = await httpRequest('crm/specification/change', 'post', { id: id })

        if(data.code == 200){
            if(data.data == null){
                commit('SUPPLY_SPECIFICATION_SET_LIST_IZM', 
                    [
                        {
                            value: getters.supplySpecificationGetter.id,
                            text: getters.supplySpecificationGetter.name,
                            idx: 0
                        }
                    ]
                )
            } else {
                commit('SUPPLY_SPECIFICATION_SET_LIST_IZM', data.data.map( item => { 
                    const select = {}
                    select.value = item.id
                    select.text = item.name
                    select.idx = item.idx
    
                    return select
                })) 
            }

        }
    }
}

const getters = {
    supplySpecificationListIZMGetter: ( state ) => state.supplySpecificationListIZM,
    supplySpecificationGetter: (state) => state.supplySpecification,
    supplySpecificationListGetter: (state) => state.supplySpecificationList,
    supplySpecificationLoadingGetter: (state) => state.supplySpecificationLoading,
}

const state = () => ({
    supplySpecification: {},
    supplySpecificationList: [],
    supplySpecificationListIZM: null,
    supplySpecificationLoading: false,
    supplySpecificationError: ''
})

export default {
    mutations,
    getters,
    actions,
    state,
}