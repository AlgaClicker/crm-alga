const mutations = {
    SET_HEIGHT_SPECIFICATIONS(state, height){
        state.heightSpecificationsTable = height
    },
    SET_MASTER_HEIGHT_SPECIFICATIONS(state, height){
        state.heightMasterSpecificationsTable = height
    }
}

const actions = {
    heightSpecificationsTableSetActions({ commit }, clientHeight){

        let height = 0
        let layoutHeaderHeight = document.getElementsByClassName('c-header')[0]?.clientHeight

        if(document.getElementsByClassName('c-specification__header').length > 0 & document.getElementsByClassName('l-specification').length > 0){
            
            let specHeader = document.getElementsByClassName('c-specification__header')[0]?.clientHeight
            let mainHeader = document.getElementsByClassName('c-specification-wrapper__header')[0]?.clientHeight
            let breadcrumbHeight = document.getElementsByClassName('breadcrumb')[0]?.clientHeight
            
            let container = document.getElementsByClassName('l-specification')
            let containerPaddingTop =  window.getComputedStyle(container[0], null).paddingTop;
            let paddingTop  = Number(containerPaddingTop.split('').reverse().slice(2, containerPaddingTop.split('').length).reverse().join(''))
        
            let breadcrumb = document.getElementsByClassName('breadcrumb')
            let breadcrumbPadding =  window.getComputedStyle(breadcrumb[0], null).marginTop;
            let marginBreadcrumb  = Number(breadcrumbPadding.split('').reverse().slice(2, breadcrumbPadding.split('').length).reverse().join(''))

            height = clientHeight + 50 - ( marginBreadcrumb * 2 + 30 + breadcrumbHeight  + paddingTop + layoutHeaderHeight + mainHeader + specHeader + breadcrumbHeight)
        }

        commit('SET_HEIGHT_SPECIFICATIONS', height)
    },
    heightMasterSpecificationsTableSetActions({ commit }, clientHeight){

        let height = 0
        let layoutHeaderHeight = document.getElementsByClassName('c-header')[0]?.clientHeight

        if(document.getElementsByClassName('l-specification-master').length > 0  & document.getElementsByClassName('l-specification').length == 0){
            let specMaster = window.getComputedStyle(document.getElementsByClassName('l-specification-master')[0]).paddingTop

            let specHeader = document.getElementsByClassName('l-specification-master__header')[0]?.clientHeight
            let breadcrumbHeight = document.getElementsByClassName('breadcrumb')[0]?.clientHeight
            
            let breadcrumb = document.getElementsByClassName('breadcrumb')
        
            let breadcrumbPadding = window.getComputedStyle(breadcrumb[0], null).marginTop;
        
            let marginBreadcrumb = Number(breadcrumbPadding.split('').reverse().slice(2, breadcrumbPadding.split('').length).reverse().join(''))
            
            specMaster = Number(specMaster.split('').reverse().slice(2, specMaster.split('').length).reverse().join(''))
        
            height = clientHeight - ( marginBreadcrumb * 2 + specMaster + breadcrumbHeight + layoutHeaderHeight + specHeader)
        }

        commit('SET_MASTER_HEIGHT_SPECIFICATIONS', height)
    }
}

const getters = {
    heightSpecificationsTableGetter: (state) => state.heightSpecificationsTable,
    heightMasterSpecificationsTableGetter: (state) => state.heightMasterSpecificationsTable,
}

const state = () => ({
    heightSpecificationsTable: 0,
    heightMasterSpecificationsTable: 0
})

export default {
    mutations,
    getters,
    actions,
    state,
}