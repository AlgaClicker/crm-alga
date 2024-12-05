const mutations = {
    MASTER_REQUISITION_MATERIAL_INFO_SET_LIST(state, materials){
        state.masterRequisitionsMaterialInfoList = []

        if( typeof materials[0]?.specification_material != 'undefined'){
            for(let i = 0; i < materials.length; i++){
                state.masterRequisitionsMaterialInfoList.push({
                    id: materials[i].id,
                    unit: materials[i].specification_material.unit,
                    name: materials[i].specification_material.fullname,
                    quantity: materials[i].specification_material.quantity,
                    description: materials[i]?.description,
                    index: i + 1
                })
            }
        } else {
            for(let i = 0; i < materials.length; i++){
                state.masterRequisitionsMaterialInfoList.push({
                    ...materials[i],
                    unit: materials[i].unit,
                    index: i + 1
                })
            }
        }
    }
}

const actions = {
    masterRequisitionsMaterialInfoSetListActions( { commit }, materials ){
        commit('MASTER_REQUISITION_MATERIAL_INFO_SET_LIST', materials)
    }
}
    
const getters = {
    masterRequisitionsMaterialInfoListGetter: (state) => state.masterRequisitionsMaterialInfoList
}

const state = () => ({
    masterRequisitionsMaterialInfoList: []
})

export default {
    mutations,
    getters,
    actions,
    state
}