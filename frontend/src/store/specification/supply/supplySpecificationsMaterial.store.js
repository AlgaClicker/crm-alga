import { httpRequest } from '@/services/httpRequest.service'
/* import Vue from 'vue' */

const mutations = {
    SUPPLY_SPECIFICATION_MATERIAL_ADD_ROW(state){
        var rowKey = String(Math.random())

        var index = state.supplySpecificationMaterialList.length == 0 
            ? 1
            : state.supplySpecificationMaterialList[state.supplySpecificationMaterialList.length - 1].index + 1
                
        state.supplySpecificationMaterialList.push({
            id: '',
            index: index,
            saveIs: true,
            deleteIs: true,
            position: '',
            fullname: '',
            type: '',
            description: '',
            code: '',
            rowKey: rowKey,
            unit: '',
            quantity: 0,
            vendor: '',
            material: null,
            ordered: '',
            is_group: false
        })
    },
    SUPPLY_SPECIFICATION_MATERIAL_SET_LIST(state, params){

        let material = []
        state.supplySpecificationMaterialError = ''
        
        if(params.materials.length != 0) {

            for( var i = 0; i < params.materials.length; i++){
                if(params.materials[i].is_group == true){
                    material.push({
                        ...params.materials[i],
                        rowKey: String(Math.random()),
                        saveIs: true,
                    })
                }else{
                    material.push(
                        { ...params.materials[i], unit: params.materials[i].unit.title, saveIs: true, rowKey: String(Math.random()), ordered: params.materialsRemnant[i].remnant }
                    )
                }
            }
        }

        for(let i = 0; i < material.length; i++){
            let index = state.supplySpecificationMaterialList.indexOf(state.supplySpecificationMaterialList.filter( item => item.index == material[i].index)[0])

            if(index != -1){                    
                state.supplySpecificationMaterialList.splice(
                    index,
                    1,
                    {
                        ...material[i],
                        index: state.supplySpecificationMaterialList[index].index
                    }
                )
            }
        }   

        state.supplySpecificationMaterialList = state.supplySpecificationMaterialList.sort( (a, b) => Number(a.index) - Number(b.index) )
    },
    SUPPLY_SPECIFICATION_MATERIAL_LOADING(state){
        state.supplySpecificationMaterialLoading = !state.masterSpecificationMaterialLoading
    },
    SUPPLY_SPECIFICATION_MATERIAL_CLEAR(state){
        state.supplySpecificationMaterialList = []
    },
    SUPPLY_SPECIFICATION_MATERIAL_ERROR(state, error){
        state.supplySpecificationMaterialError = error
    }
}

const actions = {
    async supplySpecificationMaterialSetListActions({ commit }, id){
    
        commit('SUPPLY_SPECIFICATION_MATERIAL_CLEAR')
        commit('SUPPLY_SPECIFICATION_MATERIAL_LOADING')
        const data = await httpRequest('crm/specification/material/', 'post', { specificationId: id })
        commit('SUPPLY_SPECIFICATION_MATERIAL_LOADING')

        let params = {}
        params.materials = data.data
        const materialsRemnant = await httpRequest(`crm/specification/${id}/material/remnants`, 'post', null)

        params.materialsRemnant = materialsRemnant.data

        if(data.code == 200){
            if(data?.data === null){
                for(var i = 0; i < 40; i++){
                    commit('SUPPLY_SPECIFICATION_MATERIAL_ADD_ROW')
                }
            }else{
                for(i = 0; i < 40 + data.data.length; i++){
                    commit('SUPPLY_SPECIFICATION_MATERIAL_ADD_ROW')
                }

                commit('SUPPLY_SPECIFICATION_MATERIAL_SET_LIST', params)
            }
        }
        else{
            commit('SUPPLY_SPECIFICATION_MATERIAL_ERROR', data?.data)
        }
    }
}

const getters = {
    supplySpecificationMaterialListGetter: (state) => state.supplySpecificationMaterialList,
    supplySpecificationMaterialLoadingGetter: (state) => state.supplySpecificationMaterialLoading,
}

const state = () => ({
    supplySpecificationMaterialList: [],
    supplySpecificationMaterialError: '',
    supplySpecificationMaterialLoading: false
})

export default {
    mutations,
    getters,
    actions,
    state,
}

