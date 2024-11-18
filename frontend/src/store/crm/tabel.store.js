import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue' 

const mutations = {
    TABEL_SET_MARK_LIST(state, data){

        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.tabelMarkList = data.data
        }else{
            state.tabelMarkList = []
        }
        state.tabelError = ""
    },
    TABEL_SET_LIST(state, tabel){
        state.tabelDaysList = tabel
    },
    TABEL_ADD_MARK(state, data){
        let index = state.tabelDaysList
            .indexOf(state.tabelDaysList
            .filter( item => item.date == data.date & item.currentMonth == true)[0])
     
        Vue.set(state.tabelDaysList, index, { 
            ...state.tabelDaysList[index],
            time_amount: data.timeAmount,
            specification_id: data.specification_id
        }) 

        state.tabelError = ""
    },
    TABEL_DELETE_MARK(state, data){
        let index = state.tabelDaysList
            .indexOf(state.tabelDaysList
            .filter( item => item.date == data.date & item.currentMonth == true)[0])

        Vue.set(state.tabelDaysList, index, { 
            ...state.tabelDaysList[index],
            time_amount: ''
        }) 
        this.state.tabelError = '' 
    },
    TABEL_EDIT_MARK(state, data){

        let index = state.tabelDaysList
            .indexOf(state.tabelDaysList
            .filter( item => item.date == data & item.currentMonth == true)[0])
        
        Vue.set(state.tabelDaysList, index, {
            ...state.tabelDaysList[index],
            time_amount: data.time_amount,
            specification_id: data.specification_id
        })
        this.state.tabelError = '' 
    },
    TABEL_SET_ERROR(state, error){
        state.tabelError = error
    },
    TABEL_SET_LOADING(state){
        state.tabelLoading = !state.tabelLoading
    },
    TABEL_SET_EDIT_CELL(state, cell){
        state.tabelEditCell = cell
    }
}

const actions = {
    async tabelSetMarkListActions({ commit }, form){

        commit('TABEL_SET_LOADING')
        const data = await httpRequest('crm/brigade/tabel', 'post', form)
        commit('TABEL_SET_LOADING')

        if(data.code == 200){
            commit("TABEL_SET_MARK_LIST", data)
        }else{
            commit("TABEL_SET_ERROR", data.error)
        }
    },
    tabelSetListActions({ commit }, tabel){
        commit('TABEL_SET_LIST', tabel)
    },
    async tabelAddMarkActions({ commit }, form){
        const data = await httpRequest('crm/brigade/tabel/add', 'post', form)
        if(data.code == 200){

            form.specification_id = data.data.specification_id
            
            commit("TABEL_ADD_MARK", form)
        }else{
            commit("TABEL_SET_ERROR", data.error)
        }
    },
    async tabelEditMarkActions({ commit }, form){
        const data = await httpRequest('crm/brigade/tabel', 'patch', form)
        if(data.code == 200){
            commit("TABEL_EDIT_MARK", data.data)
        }else{
            commit("TABEL_SET_ERROR", data.error)
        }
    },
    async tabelDeleteMarkActions({ commit }, form){
        const data = await httpRequest('crm/brigade/tabel', 'delete', form)
        
        if(data.code == 200){
            commit("TABEL_DELETE_MARK", form)
        }else{
            commit("TABEL_SET_ERROR", data.error)
        }
    },
    tabelSetEditCellActions({ commit }, cell){
        commit('TABEL_SET_EDIT_CELL', cell)
    }
}

const getters = {
    tabelDaysListGetter: (state) => state.tabelDaysList,
    tabelMarkListGetter: (state) => state.tabelMarkList,
    tabelLoadingGetter: (state) => state.tabelLoading,
    tabelErrorGetter: (state) => state.tabelError,
    tabelEditCellGetter: (state) => state.tabelEditCell,
    totalTimeWorkGetter: (state) => {
        if(state.tabelDaysList.length == 0){
            return 0
        } else {
            return  state.tabelDaysList
            .map(item => Number(item.time_amount))
            .filter( item => item >= 0 || item <= 20)
            .reduce( (sum, num) => sum + num)
        }
    },
    totalDayWorkGetter: (state) => {
        if(state.tabelDaysList.length == 0){
            return 0
        } else {
            return state.tabelDaysList
            .filter(item => Number(item.timeAmount) > 0).length
        }

    }
}

const state = () => ({
    tabelDaysList: [],
    tabelMarkList: [],
    tabelLoading: false,
    tabelEditCell: {},
    tabelError: ''
})

export default {
    mutations,
    getters,
    actions,
    state,
}
