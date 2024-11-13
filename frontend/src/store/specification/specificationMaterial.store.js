import { httpRequest } from '@/services/httpRequest.service'
//import _ from 'lodash'
import Vue from 'vue'

const mutations = {
    SPECIFICATION_MATERIAL_ERROR(state, error){     

        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: error
        })
        
        state.specificationMaterialError = error
    },  
    SPECIFICATION_MATERIAL_CLEAR(state){
        state.specificationMaterialList = []
        state.specificationMaterialSaveIs = true
    },  
    SPECIFICATION_MATERIAL_ADD_ROW(state){

        var rowKey = String(Math.random())
        var index = state.specificationMaterialList.length == 0 
            ? 1
            : state.specificationMaterialList[state.specificationMaterialList.length - 1].index + 1
        
        state.specificationMaterialList.push({
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
            is_group: false
        })
    },
    SPECIFICATION_MATERIAL_SET_LIST(state, data){

        let material = []
        state.specificationMaterialError = ''

        if(data.length != 0) {

            for( var i = 0; i < data.length; i++){
                if(data[i].is_group == true){
                    material.push({
                        ...data[i],
                        rowKey: String(Math.random()),
                        saveIs: true,
                    })
                }else{
                    material.push(
                        { ...data[i], unit: data[i].unit.title, saveIs: true, rowKey: String(Math.random()) }
                    )
                }
            }
        }

        for(let i = 0; i < material.length; i++){
            state.specificationMaterialList.splice(
                state.specificationMaterialList.indexOf(state.specificationMaterialList.filter( item => item.index == material[i].index)[0]),
                1,
                material[i]
            )
        }   

        state.specificationMaterialList = state.specificationMaterialList.sort( (a, b) => Number(a.index) - Number(b.index) )
    },
    SPECIFICATION_MATERIAL_GET_HISTORY_LIST(state, data){
        state.specificationMaterialError = ''
        state.specificationMaterialHistoryList = data
    },
    SPECIFICATION_MATERIAL_ADD_GROUP(state, group){

        let index = state.specificationMaterialList.indexOf(state.specificationMaterialList.filter( item => item.index == group.index)[0])

        Vue.set(state.specificationMaterialList, index, group)
        state.specificationMaterialSaveIs = false

    },
    SPECIFICATION_MATERIAL_EDIT(state, material){

        let index = state.specificationMaterialList.indexOf(state.specificationMaterialList.filter( item => item.rowKey == material.rowKey)[0])

        Vue.set(state.specificationMaterialList, index, { 
            ...material,
            saveIs: false
        })

        state.specificationMaterialSaveIs = false
    },
    SPECIFICATION_MATERIAL_COPY(state, params){

        let indexs
        let endIndex 
        let startIndex
        
        startIndex = state.specificationMaterialList.filter(item => item.rowKey == params.rangeKeys.startRowKey)[0].index
        endIndex = state.specificationMaterialList.filter(item => item.rowKey == params.rangeKeys.endRowKey)[0].index
        
        // поиск подстроки

        indexs = Array.from({ length: endIndex + 1 - startIndex }, ( _, i ) => i += startIndex);

        for( var i = 0; i < indexs.length; i++){
            
            /*
            for(let j = 0; j < params.unitList.length; j++){
            
                if(typeof params.data[i].unit != 'undefined'){
                    if(params.unitList[j].title.includes(params.data[i].unit.replace(/[^А-Яа-яЁё]/g,''))){
                        unit = params.unitList[j].title
                    } else {
                        unit = ''
                    }
                }else {
                    unit = ''
                }
            }
            */

            if(state.specificationMaterialList[indexs[i] - 1].id != ''){  
                
                Vue.set(
                    state.specificationMaterialList,
                    indexs[i] - 1,
                    {
                        id: state.specificationMaterialList[indexs[i] - 1].id,
                        index: state.specificationMaterialList[indexs[i] - 1].index,
                        rowKey: state.specificationMaterialList[indexs[i] - 1].rowKey,
                        position: typeof params.data[i].position == 'undefined' ? state.specificationMaterialList[indexs[i] - 1].position : params.data[i].position,
                        fullname: typeof params.data[i].fullname == 'undefined' ? state.specificationMaterialList[indexs[i] - 1].fullname: params.data[i].fullname,
                        type: typeof params.data[i].type == 'undefined' ? state.specificationMaterialList[indexs[i] - 1].type: params.data[i].type,
                        description: typeof params.data[i].description == 'undefined' ? state.specificationMaterialList[indexs[i] - 1].description: params.data[i].description,
                        code: typeof params.data[i].code == 'undefined' ? state.specificationMaterialList[indexs[i] - 1].code: params.data[i].code,
                        quantity: typeof params.data[i].quantity  == 'undefined' ? state.specificationMaterialList[indexs[i] - 1].quantity : params.data[i].quantity,
                        vendor: typeof params.data[i].vendor == 'undefined' ? state.specificationMaterialList[indexs[i] - 1].vendor : params.data[i].vendor,
                        unit: '',
                        saveIs: false
                    }
                )
                    
            }else{
                Vue.set(
                    state.specificationMaterialList,
                    indexs[i] - 1,
                    {
                        ...state.specificationMaterialList[indexs[i] - 1],
                        saveIs: false,
                    }
                )
            }
        }

        state.specificationMaterialSaveIs = false
    },
    SPECIFICATION_MATERIAL_AUTOFILE(state, params){

        var indexs = []

        for(let i = 0; i < params.targetSelectionData.length; i++) {
            indexs.push( state.specificationMaterialList.indexOf(state.specificationMaterialList.filter( item => item.rowKey == params.targetSelectionData[i].rowKey)[0]))
        }

        /*
        for (let i = 0; i < indexs.length; i++) {
            console.log(state.specificationMaterialList[indexs[i]])
            state.specificationMaterialList.splice(indexs[i], 1, {
                ...params.sourceSelectionData[(i + indexs.length) % params.sourceSelectionData.length],
                ...state.specificationMaterialList[indexs[i]],
                saveIs: false
            })
        } */

        // по row key получ всю строку источника

        for (let i = 0; i < indexs.length; i++) {
            state.specificationMaterialList.splice(indexs[i], 1, {
                ...state.specificationMaterialList[indexs[i]],
                ...params.sourceSelectionData[(i + indexs.length) % params.sourceSelectionData.length],
                is_group: state.specificationMaterialList.filter( item => item.rowKey == params.sourceSelectionData[(i + indexs.length) % params.sourceSelectionData.length].rowKey)[0].is_group,
                rowKey: state.specificationMaterialList[indexs[i]].rowKey,
                index: state.specificationMaterialList[indexs[i]].index,
                saveIs: false
            })
        } 

        state.specificationMaterialSaveIs = false
    },
    SPECIFICATION_MATERIAL_ADD_ABOVE(state, params){
        
        if(params.rowIndex == 0){
            let list = state.specificationMaterialList.slice(0, state.specificationMaterialList.length + 1).map( item => item = {
                ...item,
                index: item.index + 1,
                saveIs: false
            })
            
            state.specificationMaterialList = [{
                id: '',
                index: 0,
                saveIs: true,
                deleteIs: true,
                position: "",
                fullname: '',
                type: '',
                description: '',
                code: '',
                rowKey: String(Math.random()),
                unit: '',
                quantity: 0,
                vendor: '',
                material: '',
            }].concat(list)
        }else{
            
            let beforeList = state.specificationMaterialList.slice(0, params.rowIndex + 1).map( item => item = {
                ...item,
                saveIs: false
            })
            
            beforeList[beforeList.length - 1] = {
                id: '',
                index: Number(beforeList[beforeList.length - 1].index),
                saveIs: false,
                deleteIs: true,
                position: '',
                fullname: '',
                type: '',
                description: '',
                code: '',
                rowKey: String(Math.random()),
                unit: '',
                quantity: 0,
                vendor: '',
                material: null,
            }
    
            let afterList = state.specificationMaterialList.slice(params.rowIndex , state.specificationMaterialList.length).map( item => item = {
                ...item,
                index: item.index + 1,
                saveIs: false
            })

            state.specificationMaterialList = beforeList.concat(afterList)
        }

        state.specificationMaterialSaveIs = false
    },
    SPECIFICATION_MATERIAL_ADD_BELOW(state, params){

        // raw 
        let beforeList = state.specificationMaterialList.slice(0, params.rowIndex + 1)
        
        let afterList = state.specificationMaterialList.slice(params.rowIndex + 1, state.specificationMaterialList.length).map( item => item = {
            ...item,
            saveIs: false
        })
        
        beforeList.push({
            id: '',
            saveIs: true,
            deleteIs: true,
            position: '',
            fullname: '',
            type: '',
            description: '',
            code: '',
            rowKey: String(Math.random()),
            unit: '',
            quantity: 0,
            vendor: '',
            material: null,
            is_group: false
        })

        state.specificationMaterialList = beforeList.concat(afterList)
        state.specificationMaterialSaveIs = false
    },
    SPECIFICATION_MATERIAL_CLEAR_CELL(state, selectionRangeIndexes){
        let indexs = Array.from({ length: selectionRangeIndexes.endRowIndex
            + 1 - selectionRangeIndexes.startRowIndex }, (_, i ) => i += selectionRangeIndexes.startRowIndex
        );
        
        for(var i = 0; i < indexs.length; i++){      
            Vue.set(state.specificationMaterialList, indexs[i], { 
                ...state.specificationMaterialList[indexs[i]],
                saveIs: false
            })
        }
        
        state.specificationMaterialSaveIs = false
    },
    SPECIFICATION_MATERIALS_SET_MATERIAL(state, params){
        
        let index = 0

        index = state.specificationMaterialList.findIndex(item => item.rowKey == params.rowKey)
        
        if(state.specificationMaterialList[index].fullname == '' & state.specificationMaterialList[index].code == ''){
            state.specificationMaterialList.splice(index, 1, {
                ...state.specificationMaterialList[index],
                fullname: state.specificationMaterialList[index].fullname == '' ? params.material.name : state.specificationMaterialList[index].fullname,
                type: state.specificationMaterialList[index].type == '' ? params.material.type : state.specificationMaterialList[index].type,
                code: state.specificationMaterialList[index].code == '' ? params.material.code : state.specificationMaterialList[index].code,
                description: state.specificationMaterialList[index].description == '' ? params.material.description : state.specificationMaterialList[index].description,
                vendor: state.specificationMaterialList[index].vendor == '' ? params.material.vendor :  state.specificationMaterialList[index].vendor,
                unit: params.code.title,
                material: params.material,
                saveIs: false
            })  
        } else {
            state.specificationMaterialList.splice(index, 1, {
                ...state.specificationMaterialList[index],
                fullname: params.material.name,
                type: params.material.articul,
                code: params.material.code,
                description: params.material.description,
                vendor: params.material.vendor,
                unit: params.code.title,
                material: params.material,
                saveIs: false
            })      
        }
        
        state.specificationMaterialSaveIs = false
    },
    SPECIFICATION_MATERIAL_LOADING(state){
        state.specificationMaterialLoading = !state.specificationMaterialLoading
    },
    SPECIFICATION_MATERIAL_EDIT_LOADING(state){
        state.specificationMaterialEditLoading = !state.specificationMaterialEditLoading
    },
    SPECIFICATION_MATERIAL_ADD_SEARCH(state, data){
        state.specificationMaterialDirectoryList = []
        state.specificationMaterialDirectoryList = data
    },
    SPECIFICATION_MATERIAL_SAVE(state){
        state.specificationMaterialSaveIs = true 
        
        let savedList = state.specificationMaterialList.filter( item => item.saveIs != true)
        
        for(let i = 0; i < savedList.length; i++){
            
            let index = state.specificationMaterialList.indexOf(savedList[i])

            Vue.set(state.specificationMaterialList, index , { 
                ...savedList[i],
                saveIs: true
            })
        }

        state.specificationMaterialDeleteList = []
    },
    SPECIFICATION_MATERIAL_DELETE_FOR_POSITION(state, position){
        
        var index = state.specificationMaterialList.findIndex(item => item.index == position)
        state.specificationMaterialDeleteList.push(state.specificationMaterialList[index])
        
        state.specificationMaterialList.splice(index, 1, {
            ...state.specificationMaterialList[index],
            id: '',
            material: null,
            saveIs: true,
            deleteIs: true,
            position: '',
            fullname: '',
            type: '',
            description: '',
            code: '',
            unit: ''
        })  

        state.specificationMaterialSaveIs = false
    },
    SPECIFICATION_MATERIAL_DELETE_ROWS(state, materials){   

        state.specificationMaterialDeleteList.push( ...materials.filter(item => item.id != '') )
        state.specificationMaterialSaveIs = false 

        // Нужно доб saveIs у удаленных

        /*
        let afterList
        let beforeList

        state.specificationMaterialDeleteList.push( ...materials.filter(item => item.id != '') )

        let deleteIndexs = materials.map(deleteItem => deleteItem.index)

        console.log('delete indexes')
        console.log(deleteIndexs)

        state.specificationMaterialList = state.specificationMaterialList.filter( item => !deleteIndexs.find( index => index == item.index ) )
        
        console.log('state.specificationMaterialList')
        console.log(state.specificationMaterialList)

        if(Number(materials[0].index) == 1){

            beforeList = state.specificationMaterialList
                .slice(Number(materials[materials.length - 1].index), state.specificationMaterialList.length)
                .map( item => item = {
                    ...item,
                    index:  Number(item.index) - materials.length,
                    saveIs: false
                })

            state.specificationMaterialList = beforeList

            console.log(beforeList)

        }else{
            afterList = state.specificationMaterialList.slice(0, Number(materials[0].index))

            let beforeList = state.specificationMaterialList
                .filter( item => item.index > afterList[afterList.length - 1].index )
                .map( item => item = {
                    ...item,
                    index: Number(item.index) - materials.length,
                    saveIs: false
                })

            state.specificationMaterialList = afterList.concat(beforeList)
        }
        
        state.specificationMaterialSaveIs = false */ 
    },
    SPECIFICATION_MATERIAL_ADD(state, row){

        if(row.material.is_group){

            let index = state.specificationMaterialList.indexOf(state.specificationMaterialList.filter(item => item.rowKey == row.rowKey)[0])
            Vue.set(state.specificationMaterialList, index, {
                ...state.specificationMaterialList[index],
                saveIs: true,
                deleteIs: true,
                position: '',
                fullname: '',
                type: '',
                description: '',
                code: '',
                unit: null,
                quantity: null,
                vendor: '',
                material: null,
                is_group: true
            })

        } else {
            let index = state.specificationMaterialList.indexOf(state.specificationMaterialList.filter(item => item.rowKey == row.rowKey )[0])
            Vue.set(state.specificationMaterialList, index, {
                ...state.specificationMaterialList[index],
                saveIs: true,
                deleteIs: true,
                position: '',
                fullname: '',
                type: '',
                description: '',
                code: '',
                unit: '',
                quantity: 0,
                vendor: '',
                material: null,
                is_group: false
            })
            
        }
        
        if(row.material.is_group){
            Vue.set(state.specificationMaterialList, row.material.index - 1, { 
                ...row.material,
                rowKey: state.specificationMaterialList[row.material.index - 1].rowKey,
                saveIs: true
            })
        } else {
            Vue.set(state.specificationMaterialList, row.material.index - 1, { 
                ...row.material,
                unit: row.material.unit.title,
                rowKey: state.specificationMaterialList[row.material.index - 1].rowKey,
                saveIs: true
            })
        }
        
    }
}

const actions = {
    specificationMaterialAddRowActions( { commit } ){
        commit('SPECIFICATION_MATERIAL_ADD_ROW')
    },
    async specificationMaterialAddGroupActions( { commit, getters }, params ){
        
        let group = {
            id: '',
            rowKey: params.rowKey,
            position: '',
            fullname: 'Новая группа',
            is_group: true,
            index: getters.specificationMaterialListGetter.filter( item => item.rowKey == params.rowKey)[0].index,
            saveIs: false,
            deleteIs: true,
        }
        
        commit('SPECIFICATION_MATERIAL_ADD_GROUP', group)
    },
    async specificationMaterialGetListActions( { commit }, id ){
        
        commit('SPECIFICATION_MATERIAL_CLEAR')
        commit('SPECIFICATION_MATERIAL_LOADING')
        const data = await httpRequest('crm/specification/material/', 'post', { specificationId: id })
        commit('SPECIFICATION_MATERIAL_LOADING')

        if(data.code == 200){
            if(data?.data === null){
                for(var i = 0; i < 200; i++){
                    commit('SPECIFICATION_MATERIAL_ADD_ROW')
                }
            }else{
                for(i = 0; i < 200 + data.data.length; i++){
                    commit('SPECIFICATION_MATERIAL_ADD_ROW')
                }

                commit('SPECIFICATION_MATERIAL_SET_LIST', data.data)
            }
        }
        else{
            commit('SPECIFICATION_MATERIAL_ERROR', data?.data)
        }
    },
    async specificationMaterialAddRowAboveActions( { commit }, params ){
        commit('SPECIFICATION_MATERIAL_ADD_ABOVE', params)
    },
    async specificationMaterialAddRowBelowActions({ commit }, params){
        commit('SPECIFICATION_MATERIAL_ADD_BELOW', params)
    },
    async specificationMaterialGetHistoryListActions( { commit }, id ){

        console.log(commit)
        console.log(id)

    },
    async specificationMaterialClearCellActions( { commit }, selectionRangeIndexes ){
        commit('SPECIFICATION_MATERIAL_CLEAR_CELL', selectionRangeIndexes)
    },
    async specificationMaterialEditActions( { commit }, form){
        commit('SPECIFICATION_MATERIAL_EDIT', form)
    },
    async specificationMaterialCopyRowActions({ commit, getters }, { data, specificationId, selectionRangeKeys }){

        let param = {
            data: data,
            rangeKeys: selectionRangeKeys,
            specificationId: specificationId,
            unitList: getters.materialsUnitsListGetter
        }
        
        for(let i = 0; i <= param.data.length; i++){
            commit('SPECIFICATION_MATERIAL_ADD_ROW')
        }
        
        commit('SPECIFICATION_MATERIAL_COPY', param)
    },
    async specificationMaterialResetMaterialActions( { commit }, row ){
        commit('SPECIFICATION_MATERIAL_DELETE_FOR_POSITION', row.index)
    },
    async specificationMaterialSetMaterialActions( { commit, getters }, params){

    /*  var form = getters.specificationMaterialListGetter.filter( item => item.id == params.specificationMaterialId)[0]*/
        var code = getters.materialsUnitsListGetter.filter( item => item.title == params.material.unit.title )[0]  

        commit('SPECIFICATION_MATERIALS_SET_MATERIAL', { 
            rowKey: params.row.rowKey,
            specificationMaterialId: params.specificationMaterialId, 
            material: params.material, code: code 
        })
    },
    async specificationMaterialAoutofileActions( { commit }, { sourceSelectionData, targetSelectionData }){

        var params = {
            sourceSelectionData: sourceSelectionData,
            targetSelectionData: targetSelectionData
        }
        
        commit('SPECIFICATION_MATERIAL_AUTOFILE', params)
    },
    async specificationMaterialAddActions( { commit }){
        commit('SPECIFICATION_MATERIAL_ADD_ROW')
    },
    async specificationMaterialDeleteRowActions( { commit, getters }, params ){

        let materials
        materials = getters.specificationMaterialListGetter.filter( item => item.rowKey == params.key)
        commit('SPECIFICATION_MATERIAL_DELETE_ROWS', materials)

    },
    async specificationMaterialDeleteRowsActions( {commit, getters }, params){

        let materials
        materials = getters.specificationMaterialListGetter.slice(params.start, params.end + 1)
        
        commit('SPECIFICATION_MATERIAL_DELETE_ROWS', materials)
    },
    async specificationMaterialSaveActions( { commit, getters }, params){
        
        // есть флаг insert
        // удалить
        // отправить новое

        // Удаление
        
        if(getters.specificationMaterialDeleteListGetter.length > 0){

            commit('SPECIFICATION_MATERIAL_EDIT_LOADING')
            
            for(let i = 0; i < getters.specificationMaterialDeleteListGetter.length; i++){
                let data = await httpRequest('crm/specification/material/', 'delete', { 
                    specificationId: params,
                    specificationMaterialId: getters.specificationMaterialDeleteListGetter[i].id
                })

                if (data.code != 200){
                    if(typeof data.message == 'undefined'){
                        commit('SPECIFICATION_MATERIAL_ERROR', 'Ошибка удаления')
                    }else{
                        commit('SPECIFICATION_MATERIAL_ERROR', data.message)
                    }
                } 
            }

            commit('SPECIFICATION_MATERIAL_EDIT_LOADING')
        }

        // Создать материал

        commit('SPECIFICATION_MATERIAL_EDIT_LOADING')

        for(let i = 0; i < getters.specificationMaterialListGetter.length; i++){

            // Создание группы

            if(getters.specificationMaterialListGetter[i].id == '' && getters.specificationMaterialListGetter[i].is_group == true && getters.specificationMaterialListGetter[i].fullname != ''){
                let data = await httpRequest('crm/specification/material/add', 'post', {
                    position: getters.specificationMaterialListGetter[i].position,
                    fullname: getters.specificationMaterialListGetter[i].fullname,
                    specificationId: params,
                    isGroup: true
                })
                
                if(data.code == 500){
                    commit('SPECIFICATION_MATERIAL_ERROR', 'Ошибка на сервере')
                }
                else if(data.code == 200){

                    let row = {
                        rowKey: getters.specificationMaterialListGetter[i].rowKey,
                        material: data.data
                    }

                    commit('SPECIFICATION_MATERIAL_ADD', row)
                } else {
                    commit('SPECIFICATION_MATERIAL_ERROR', data.data.message)
                }
            }

            if(getters.specificationMaterialListGetter[i].id == '' && getters.specificationMaterialListGetter[i].is_group == false && getters.specificationMaterialListGetter[i].fullname != '' && getters.specificationMaterialListGetter[i].unit != ''){

                let data = await httpRequest('crm/specification/material/add', 'post', {
                    unit_code: getters.materialsUnitsListGetter.filter( item => item.title == getters.specificationMaterialListGetter[i].unit || item.title == getters.specificationMaterialListGetter[i].unit + ';'  )[0]?.code || '006',
                    ...getters.specificationMaterialListGetter[i],
                    specificationId: params,
                    material: typeof getters.specificationMaterialListGetter[i].material === 'undefined' || getters.specificationMaterialListGetter[i].material == null ?
                    '' : getters.specificationMaterialListGetter[i].material.id,
                    quantity : String(getters.specificationMaterialListGetter[i].quantity).includes(',') ? String(getters.specificationMaterialListGetter[i].quantity).replace(',','.') : getters.specificationMaterialListGetter[i].quantity
                })
                
                if(data.code == 500){
                    commit('SPECIFICATION_MATERIAL_ERROR', 'Ошибка на сервере')
                }
                else if(data.code == 200){

                    let row = {
                        rowKey: getters.specificationMaterialListGetter[i].rowKey,
                        material: data.data
                    }

                    commit('SPECIFICATION_MATERIAL_ADD', row)
                } else {
                    commit('SPECIFICATION_MATERIAL_ERROR', data.data.message)
                }
            }

        }

        // Редактировать

        let materialListEdit = getters.specificationMaterialListGetter.filter(item => item.id != '' && item.saveIs === false && item.is_group !== true)

        for(let i = 0; i < materialListEdit.length; i++){
            let data = await httpRequest('crm/specification/material/', 'patch', {
                position: materialListEdit[i].position,
                fullname: materialListEdit[i].fullname,
                type: materialListEdit[i].type,
                description: materialListEdit[i].description,
                quantity: materialListEdit[i].quantity,
                code: materialListEdit[i].code,
                vendor: materialListEdit[i].vendor,
                specificationId: params,
                specificationMaterialId: materialListEdit[i].id, 
                unit_code: getters.materialsUnitsListGetter.filter( item => item.title == materialListEdit[i].unit )[0]?.code || '006',
                material: typeof materialListEdit[i].material === 'undefined' ? '' : materialListEdit[i].material.id
            })

            if(data.code != 200){
                commit('SPECIFICATION_MATERIAL_ERROR', data.data.message)
            }
        }

        let groupListEdit = getters.specificationMaterialListGetter.filter(item => item.id != '' && item.saveIs == false && item.is_group == true)

        for(let i = 0; i < groupListEdit.length; i++){
            let data = await httpRequest('crm/specification/material/', 'patch', {
                ...groupListEdit[i],
                specificationMaterialId: groupListEdit[i].id, 
                specificationId: params,
                is_group: true
            })
            if(data.code != 200){
                commit('SPECIFICATION_MATERIAL_ERROR', data.data.message)
            }
        }

        commit('SPECIFICATION_MATERIAL_EDIT_LOADING')

        commit('SPECIFICATION_MATERIAL_SAVE')

    },

}

const getters = {
    specificationMaterialListGetter: (state) => state.specificationMaterialList,
    specificationMaterialByKeyGetter: (state) => (key) => state.specificationMaterialList.filter( item => item.rowKey == key),
    specificationMaterialHistoryListGetter: (state) => state.specificationMaterialHistoryList,
    specificationMaterialErrorGetter: (state) => state.specificationMaterialError,
    specificationMaterialDirectoryListGetter: (state) => state.specificationMaterialDirectoryList,
    specificationMaterialLoadingGetter: (state) => state.specificationMaterialLoading,
    specificationMaterialEditLoadingGetter: (state) => state.specificationMaterialEditLoading,
    specificationMaterialDeleteListGetter: (state) => state.specificationMaterialDeleteList,
    specificationMaterialSaveIsGetter: (state) => state.specificationMaterialSaveIs,
}

const state = () => ({
    specificationMaterialInsert: false,
    specificationMaterialList: [],
    specificationMaterialHistoryList: [],
    specificationMaterialDirectoryList: [],
    specificationMaterialError: '',
    specificationMaterialLoading: false,
    specificationMaterialEditLoading: false,
    specificationMaterialDeleteList: [],
    specificationMaterialSaveIs: true,
    specificationMaterialIZMList: [],
    currentIZM: {}
})

export default {
    mutations,
    getters,
    actions,
    state,
}
