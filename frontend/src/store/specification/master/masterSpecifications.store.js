import { httpRequest } from '@/services/httpRequest.service'
//import Vue from 'vue' 

const mutations = {
    MASTER_SPECIFICATION_SET(state, data){
        state.masterSpecificationError = ''
        state.masterSpecification = data
    },
    MASTER_SPECIFICATION_LOADING(state){
        state.masterSpecificationLoading = !state.masterSpecificationLoading
    },
    MASTER_SPECIFICATION_ERROR(state, error){
        state.masterSpecificationError = error
    },
    MASTER_SPECIFICATION_SET_LIST_IZM(state, data){
        state.masterSpecificationListIZM = data
    },
    MASTER_SPECIFICATION_LIST_SET(state, data){
        state.masterSpecificationError = ''
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.masterSpecificationList = data.data
        }else{
            state.masterSpecificationList = []
        }
    }   
}

const actions = {
    async masterSpecificationSetListActions( { commit } ){
        commit('MASTER_SPECIFICATION_LOADING')
        const data = await httpRequest('crm/master/specifications', 'post', {})
        commit('MASTER_SPECIFICATION_LOADING')

        if(data.code == 200){
            commit('MASTER_SPECIFICATION_LIST_SET', data)
        }
        else{
            commit('MASTER_SPECIFICATION_ERROR', data.data)
        }
    },
    async masterSpecificationGetByIdActions( { commit }, form ){
        
        commit('MASTER_SPECIFICATION_LOADING')
        const data = await httpRequest('crm/specification/', 'post', form)

        if(data.code == 200){
            commit('MASTER_SPECIFICATION_SET', data.data)
        } else if(data.status == 422){
            commit("MASTER_SPECIFICATION_ERROR", 'Заполните все поля')
        }
        else{
            commit("MASTER_SPECIFICATION_ERROR", data.data)
        }
        
        commit('MASTER_SPECIFICATION_LOADING')
    },
    async masterSpecificationSetIZMActions( { commit, getters }, id){
        const data = await httpRequest('crm/specification/change', 'post', { id: id })
        
        if(data.code == 200){
            if(data.data == null){
                commit('MASTER_SPECIFICATION_SET_LIST_IZM', 
                    [
                        {
                            value: getters.masterSpecificationGetter.id,
                            text: getters.masterSpecificationGetter.name,
                            idx: 0
                        }
                    ]
                )
            } else {
                commit('MASTER_SPECIFICATION_SET_LIST_IZM', data.data.map( item => { 
                    const select = {}
                    select.value = item.id
                    select.text = item.name
                    select.idx = item.idx
    
                    return select
                })) 
            }

        }
        else if(data.status == 500){
            commit("MASTER_SPECIFICATION_ERROR", 'Ошибка на сервере')
        }
        else{
            commit("MASTER_SPECIFICATION_ERROR", data.data)
        }
    }
}

const getters = {
    masterSpecificationListIZMGetter: ( state ) => state.masterSpecificationListIZM,
    masterSpecificationGetter: (state) => state.masterSpecification,
    masterSpecificationLoadingGetter:  (state) => state.masterSpecificationLoading,
    masterSpecificationListGetter: (state) => state.masterSpecificationList
}

const state = () => ({
    masterSpecification: {},
    masterSpecificationList: [],
    masterSpecificationAllList: [],
    masterSpecificationListIZM: null,
    masterSpecificationError: '',
    masterSpecificationLoading: false,
})

export default {
    mutations,
    getters,
    actions,
    state,
}

