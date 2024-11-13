import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'  

const mutations = {
    SUPPLY_REQUISITION_MATERIAL_ADD_ROW(state){
        var rowKey = String(Math.random())
        var index = state.supplyRequisitionMaterialList.length == 0 
            ? 0
            : state.supplyRequisitionMaterialList[state.supplyRequisitionMaterialList.length - 1].index + 1
        
        state.supplyRequisitionMaterialList.push({
            id: '',
            selected: false,
            index: index,
            position: index + 1,
            fullname: '',
            rowKey: rowKey,
            description: '',
            unit: '',
            quantity: 0,
            ordered: 0,
            order: 0,
            material: '',
            attached_invoices: [],
            price: 0,
        })
    },
    SUPPLY_REQUISITION_MATERIAL_SET_LIST(state, data){
        let material = []
        state.supplyRequisitionMaterialError = ''
        
        if(data.length != 0) {

            for( var i = 0; i < data.length; i++){
                
                let directoryMaterialName
                
                if(typeof data[i].specification_material == 'undefined'){
                    directoryMaterialName = typeof data[i].directory_material == 'undefined' ? '': data[i].directory_material.name

                    if(typeof data[i].directory_material != 'undefined'){
                        material.push(
                            { 
                                ...data[i], 
                                selected: false, 
                                specification_id: '',
                                description: data[i].description,
                                ordered: 0, 
                                fullname: data[i].directory_material.name,
                                directory_material_name: directoryMaterialName,
                                order: data[i].quantity, 
                                unit: data[i].directory_material.unit.title, 
                                rowKey: String(Math.random()),
                                attached_invoices: [],
                                price: 0,
                            }
                        )
                    } else {
                        material.push(
                            { 
                                ...data[i], 
                                selected: false, 
                                specification_id: '',
                                ordered: 0, 
                                description: data[i].description,
                                fullname: data[i].name,
                                directory_material_name: '',
                                order: data[i].quantity, 
                                unit: data[i].unit.title, 
                                rowKey: String(Math.random()),
                                attached_invoices: [],
                                price: 0,
                            }
                        )
                    }
                }else{
                    directoryMaterialName = typeof data[i].directory_material == 'undefined' ? '': data[i].directory_material.name

                    material.push(
                        { 
                            ...data[i], 
                            selected: false, 
                            specification_id: data[i].specification_material.id,
                            ordered: 0, 
                            description: data[i].description,
                            fullname: data[i].specification_material?.fullname,
                            directory_material_name: directoryMaterialName,
                            order: data[i].quantity, 
                            unit: data[i].specification_material?.unit.title, 
                            rowKey: String(Math.random()),
                            attached_invoices: [],
                            price: 0,
                        }
                    )
                }

            }
        }
    
        state.supplyRequisitionMaterialList = material

        //state.supplyRequisitionMaterialList = state.supplyRequisitionMaterialList.sort( (a, b) => Number(a.position) - Number(b.position) )
    },
    SUPPLY_REQUISITION_MATERIAL_NEW_INVOICE_SET(state, materials){
        let i = 1

        state.supplyRequisitionMaterialNewInvoice = {
            delivery_at: '',
            partner: '',
            stock: '',
            materials: materials.map( (item) => {
                return {
                    id: item.id,
                    fullname: item.fullname,
                    position: i++, 
                    unit: item.unit,
                    order: item.order,
                    ordered: item.ordered,
                    quantity: 0,
                    price: 0,
                    files: [],
                    description: ''
                }
            })
        }
        
    },
    SUPPLY_REQUISITION_MATERIAL_SET_INVOICES(state, invoices){

        // ищу материалы
        // найти индекс материала
        // вставить счет в attached_invoices
        // вывести цену
        // вывести количестов

        if( typeof invoices != 'undefined' & invoices != null & invoices != []){

            for( let i = 0; i < invoices.length; i++){

                for(let n of invoices[i].materials){

                    let index = state.supplyRequisitionMaterialList.findIndex(item => item.id == n.requisition_material)

                    let invoicesList = []

                    invoicesList = state.supplyRequisitionMaterialList[index].attached_invoices
                    invoicesList.push(invoices[i])

                    Vue.set(state.supplyRequisitionMaterialList, index, { 
                        ...state.supplyRequisitionMaterialList[index],
                        attached_invoices: invoicesList,
                        ordered: state.supplyRequisitionMaterialList[index].ordered + n.quantity 
                    })
                    
                }
    
            }
    
        }
        
        state.supplyRequisitionMaterialQuantity.requiredQuantity = state.supplyRequisitionMaterialList.reduce( ( sum, item ) => sum + item.quantity, 0 )
        state.supplyRequisitionMaterialQuantity.orderedQuantity = state.supplyRequisitionMaterialList.reduce( ( sum, item ) => sum + item.ordered, 0 )
    },
    SUPPLY_REQUISITION_MATERIAL_SET(state){
        state.supplyRequisitionMaterialList = []
    },
    SUPPLY_REQUISITION_MATERIAL_SET_MATERIAL(state, params){

        let index = state.supplyRequisitionMaterialList.findIndex(item => item.rowKey == params.rowKey)

        state.supplyRequisitionMaterialList.splice(index, 1, {
            ...state.supplyRequisitionMaterialList[index],
            directory_material_name: params.material.name,
            directory_material: params.material,
            unit: params.material.unit.title,
            selected: false,
            material: params.material,
            saveIs: false
        })  

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Материал прекреплен'
        })    

    },
    SUPPLY_REQUISITION_MATERIAL_RESET_MATERIAL(state, params){

        let index = state.supplyRequisitionMaterialList.findIndex(item => item.id == params.idMaterial)

        state.supplyRequisitionMaterialList.splice(index, 1, {
            ...state.supplyRequisitionMaterialList[index],
            directory_material_name: null,
            directory_material: null,
        })  

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Материал удален'
        })  
    },
    SUPPLY_REQUISITION_MATERIAL_ADD_INVOICE(state, invoice){

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Счет прикреплен'
        })    
        
        state.supplyRequisitionMaterialList
        state.supplyRequisitionMaterialError = ''

        for(let i = 0; i < invoice.materials.length; i++){

            let index = state.supplyRequisitionMaterialList.findIndex( item => item.id == invoice.materials[i].requisition_material)

            Vue.set(state.supplyRequisitionMaterialList, index, { 
                ...state.supplyRequisitionMaterialList[index],
                attached_invoices: state.supplyRequisitionMaterialList[index].attached_invoices.concat( [ invoice ]),
                ordered: Number(state.supplyRequisitionMaterialList[index].ordered) + Number(invoice.materials[i].quantity),
                selected: false,
            })

            state.supplyRequisitionMaterialQuantity.orderedQuantity = state.supplyRequisitionMaterialQuantity.orderedQuantity + Number(invoice.materials[i].quantity)
        }
    },
    SUPPLY_REQUISITION_MATERIAL_CLEAR_SELECT(state){
        for( let i = 0; i < state.supplyRequisitionMaterialList.length; i++){
            Vue.set(state.supplyRequisitionMaterialList, i, { 
                ...state.supplyRequisitionMaterialList[i],
                selected: false,
            })
        }

        state.supplyRequisitionMaterialSelectedList = []
    },
    SUPPLY_REQUISITION_MATERIAL_ATTACH_FILE_FOR_INVOICES(state, params){

        let index = state.supplyRequisitionMaterialNewInvoice.materials.findIndex(item => item.id == params.id)
        let files = state.supplyRequisitionMaterialNewInvoice.materials[index].files.push(params.file)
        Vue.set(state.supplyRequisitionMaterialNewInvoice, index, { 
            ...state.supplyRequisitionMaterialNewInvoice[index],
            files: files,
        })
    },
    SUPPLY_REQUISITION_MATERIAL_DELETE_ATTACH_FILE(state, hash){

        let material = state.supplyRequisitionMaterialNewInvoice.materials.filter( item => item.files.filter( file => file.hash == hash)[0])[0]

        let index = state.supplyRequisitionMaterialNewInvoice.materials.findIndex(item => item.id == material.id)

        Vue.set(state.supplyRequisitionMaterialNewInvoice.materials, index, { 
            ...state.supplyRequisitionMaterialNewInvoice.materials[index],
            files: state.supplyRequisitionMaterialNewInvoice.materials[index].files.filter(item => item.hash != hash),
        })
    },
    SUPPLY_REQUISITION_DELETE_MATERIAL(state, data){

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Материал удален'
        })    

        state.supplyRequisitionMaterialList = state.supplyRequisitionMaterialList.filter( item => item.id != data.data.id)
    },
    SUPPLY_REQUISITION_MATERIAL_DELETE_INVOICE(state, id){

        Vue.notify({
            group: 'success',
            title: 'Успех',
            type: 'success',
            text: 'Счет удален'
        })    

        for( let i = 0; i < state.supplyRequisitionMaterialList.length; i++){

            if(typeof state.supplyRequisitionMaterialList[i].attached_invoices != 'undefined'){
                let materials = state.supplyRequisitionMaterialList[i].attached_invoices.filter( item => item.id == id)[0]?.materials

                if(typeof materials != 'undefined'){
                    for(let j = 0; j < materials.length; j++){
                        if(materials[j].requisition_material == state.supplyRequisitionMaterialList[i].id){
                            state.supplyRequisitionMaterialList[i].ordered = state.supplyRequisitionMaterialList[i].ordered - materials[j].quantity
                            state.supplyRequisitionMaterialQuantity.orderedQuantity = Number(state.supplyRequisitionMaterialQuantity.orderedQuantity) - state.supplyRequisitionMaterialList[i].ordered - materials[j].quantity
                        }
                    }
                }
            }

            state.supplyRequisitionMaterialList[i].attached_invoices = state.supplyRequisitionMaterialList[i].attached_invoices.filter( item => item.id != id)
        } 

    },
    SUPPLY_REQUISITION_MATERIAL_LOADING(state){
        state.supplyRequisitionMaterialLoading = !state.supplyRequisitionMaterialLoading
    },
    SUPPLY_REQUISITION_MATERIAL_SELECT(state, material){
        material.selected = !material.selected

        material.selected 
            ? state.supplyRequisitionMaterialSelectedList.push(material)
            : state.supplyRequisitionMaterialSelectedList = state.supplyRequisitionMaterialSelectedList.filter(item => item.id != material.id)
    },
    SUPPLY_REQUISITION_MATERIAL_ERROR(state, data){

        state.supplyRequisitionMaterialError = data.message

        Vue.notify({
            group: 'error',
            title: 'Ошибка',
            type: 'error',
            text: data.message
        })
    }
}

const actions = {
    supplyRequisitionMaterialSetActions({ commit }, materials){

        commit('SUPPLY_REQUISITION_MATERIAL_SET')
        for(var i = 0; i <=  materials.length; i++){
            commit('SUPPLY_REQUISITION_MATERIAL_ADD_ROW')
        }
        commit('SUPPLY_REQUISITION_MATERIAL_SET_LIST', materials)
        
    },
    async supplyRequisitionMaterialSetInvoicesActions( { commit }, idRequisition){

        const data = await httpRequest(`crm/snabzheniye/requisitions/${idRequisition}/work/invoices`,'post', {})

        if(data.code == 200){
            commit('SUPPLY_REQUISITION_MATERIAL_SET_INVOICES', data.data)
        }else {
            commit('SUPPLY_REQUISITION_MATERIAL_ERROR', data.data)
        }

    },
    async supplyRequisitionMaterialDeleteInvoiceActions({ commit }, params){

        const data = await httpRequest(`crm/snabzheniye/requisitions/${params.idRequisition}/work/invoice`, 'delete', {
            id: params.idInvoices
        })

        if(data.code == 200){
            commit('SUPPLY_REQUISITION_MATERIAL_DELETE_INVOICE', params.idInvoices)
        }else {
            commit('SUPPLY_REQUISITION_MATERIAL_ERROR', data.data)
        }
    },
    async supplyRequisitionMaterialAddInvoicesActions( { commit }, params ){
        
        const data = await httpRequest(`crm/snabzheniye/requisitions/${params.idRequisition}/work/invoice/add`, 'post', params.newInvoice)

        if(data.code == 200){
            commit('SUPPLY_REQUISITION_MATERIAL_ADD_INVOICE', data.data)

        }else {
            commit('SUPPLY_REQUISITION_MATERIAL_ERROR', data.data)
        }

    },
    async supplyRequisitionMaterialResetMaterialActions( { commit }, params){

        const data = await httpRequest(`crm/snabzheniye/requisition/${params.idRequisition}/material/${params.idMaterial}/unset/material`, 'post', { id: params.idDirectoryMaterial })

        if(data.code == 200){
            commit('SUPPLY_REQUISITION_MATERIAL_RESET_MATERIAL', params)
        }else{
            commit('SUPPLY_REQUISITION_MATERIAL_ERROR', data)
        }

    },
    async supplyRequisitionMaterialDeleteActions({ commit, getters }, idRequisition ){

        for(let i = 0; i < getters.supplyRequisitionMaterialSelectedListGetter.length; i++){
            const data = await httpRequest(`crm/master/requisition/other/${idRequisition}/material/${getters.supplyRequisitionMaterialSelectedListGetter[i].id}`, 'delete', {})

            if(data.code == 200){
                commit('SUPPLY_REQUISITION_DELETE_MATERIAL', data)
            }else { 
                commit('SUPPLY_REQUISITION_MATERIAL_ERROR', data)
            }
        }

    },
    async supplyRequisitionMaterialSetMaterialActions( { commit, getters }, params){

        const data = await httpRequest(`crm/snabzheniye/requisition/${getters.supplyMyRequisitionGetter.id}/material/${params.row.id}/set/material`, 'post', {id: params.material.id})

        if(data.code == 200){
            commit('SUPPLY_REQUISITION_MATERIAL_SET_MATERIAL', { 
                rowKey: params.row.rowKey,
                material: params.material, 
            })
        }else {
            commit('SUPPLY_REQUISITION_MATERIAL_ERROR', data.data)
        }
    },
    supplyRequisitionMaterialAttachFileForInvoicesActions({commit}, params){
        commit('SUPPLY_REQUISITION_MATERIAL_ATTACH_FILE_FOR_INVOICES', params)
    },
    supplyRequisitionMaterialDeleteAttachFileActions({commit}, hash){
        commit('SUPPLY_REQUISITION_MATERIAL_DELETE_ATTACH_FILE', hash)
    },
    supplyRequisitionMaterialSelectActions({ commit }, row){
        commit('SUPPLY_REQUISITION_MATERIAL_SELECT', row)
    },
    supplyRequisitionMaterialClearSelectActions({ commit }){
        commit('SUPPLY_REQUISITION_MATERIAL_CLEAR_SELECT')
    },
    supplyRequisitionMaterialNewInvoiceSetActions({ commit }, materials){
        commit('SUPPLY_REQUISITION_MATERIAL_NEW_INVOICE_SET', materials)
    },
    supplyRequisitionMaterialSetLoadingActions({commit}){
        commit('SUPPLY_REQUISITION_MATERIAL_LOADING')
    },
    supplyRequisitionMaterialSetErrorActions( { commit }, error){
        commit('SUPPLY_REQUISITION_MATERIAL_ERROR', error)
    }
}

const getters = {
    supplyRequisitionMaterialListGetter: (state) => state.supplyRequisitionMaterialList,
    supplyRequisitionMaterialErrorGetter: (state) => state.supplyRequisitionMaterialError,
    supplyRequisitionMaterialSelectedListGetter: (state) => state.supplyRequisitionMaterialSelectedList,
    supplyRequisitionMaterialNewInvoiceGetter: (state) => state.supplyRequisitionMaterialNewInvoice,
    supplyRequisitionMaterialLoadingGetter: (state) => state.supplyRequisitionMaterialLoading,
    supplyRequisitionMaterialQuantityGetters: (state) => state.supplyRequisitionMaterialQuantity,
    supplyRequisitionMaterialIsAllAttachedMaterialGetter: (state) => state.supplyRequisitionMaterialList.filter(item => item.selected).filter( item => typeof item.directory_material != 'undefined' & item.directory_material != null).length == state.supplyRequisitionMaterialList.filter(item => item.selected).length,
    supplyRequisitionMaterialIsAttachedInvoicesGetter: (state) => state.supplyRequisitionMaterialList.filter( item => item.attached_invoices.length > 0).length != 0
}

const state = () => ({
    supplyRequisitionMaterialQuantity: {
        requiredQuantity: 0,
        orderedQuantity: 0,
    },
    supplyRequisitionMaterialList: [],
    supplyRequisitionMaterialError: '',
    supplyRequisitionMaterialSelectedList: [],
    supplyRequisitionMaterialLoading: false,
    supplyRequisitionMaterialNewInvoice: {
        delivery_at: '',
        partner: '',
        stock: '',
        description: '',
        materials: []
    } 
})

export default {
    mutations,
    getters,
    actions,
    state,
}