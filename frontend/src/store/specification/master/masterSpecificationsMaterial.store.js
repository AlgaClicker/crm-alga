/* import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue' */

import { httpRequest } from '@/services/httpRequest.service'

const mutations = {
    MASTER_SPECIFICATION_MATERIAL_ADD_ROW(state){
        var rowKey = String(Math.random())
        var index = state.masterSpecificationMaterialList.length == 0 
            ? 1
            : state.masterSpecificationMaterialList[state.masterSpecificationMaterialList.length - 1].index + 1
        
        state.masterSpecificationMaterialList.push({
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
    MASTER_SPECIFICATION_MATERIAL_SET_LIST(state, params){
        let material = []
        state.masterSpecificationMaterialError = ''

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
            let index = state.masterSpecificationMaterialList.indexOf(state.masterSpecificationMaterialList.filter( item => item.index == material[i].index)[0])

            if(index != -1){                    
                state.masterSpecificationMaterialList.splice(
                    index,
                    1,
                    {
                        ...material[i],
                        index: state.masterSpecificationMaterialList[index].index
                    }
                )
            }
        }   

        state.masterSpecificationMaterialList = state.masterSpecificationMaterialList.sort( (a, b) => Number(a.index) - Number(b.index) )
    },
    MASTER_SPECIFICATION_MATERIAL_LOADING(state){
        state.masterSpecificationMaterialLoading = !state.masterSpecificationMaterialLoading
    },
    MASTER_SPECIFICATION_MATERIAL_CLEAR(state){
        state.masterSpecificationMaterialList = []
    },
    MASTER_SPECIFICATION_MATERIAL_ERROR(state, error){
        state.masterSpecificationError = error
    }
}

const actions = {
    async masterSpecificationMaterialSetListActions({ commit }, id){
    
        commit('MASTER_SPECIFICATION_MATERIAL_CLEAR')
        commit('MASTER_SPECIFICATION_MATERIAL_LOADING')
        const data = await httpRequest('crm/specification/material/', 'post', { specificationId: id })
        commit('MASTER_SPECIFICATION_MATERIAL_LOADING')

        let params = {}
        params.materials = data.data
        const materialsRemnant = await httpRequest(`crm/specification/${id}/material/remnants`, 'post', null)

        params.materialsRemnant = materialsRemnant.data

        if(data.code == 200){
            if(data?.data === null){
                for(var i = 0; i < 40; i++){
                    commit('MASTER_SPECIFICATION_MATERIAL_ADD_ROW')
                }
            }else{
                for(i = 0; i < 40 + data.data.length; i++){
                    commit('MASTER_SPECIFICATION_MATERIAL_ADD_ROW')
                }

                commit('MASTER_SPECIFICATION_MATERIAL_SET_LIST', params)
            }
        }
        else{
            commit('MASTER_SPECIFICATION_MATERIAL_ERROR', data?.data)
        }
    }
    
}

const getters = {
    masterSpecificationMaterialListGetter: (state) => state.masterSpecificationMaterialList,
    masterSpecificationMaterialLoadingGetter: (state) => state.masterSpecificationMaterialLoading,
}

const state = () => ({
    masterSpecificationMaterialList: [],
    masterSpecificationMaterialError: '',
    masterSpecificationMaterialLoading: false
})

export default {
    mutations,
    getters,
    actions,
    state,
}

