import Vue from 'vue'
import Vuex from 'vuex'

import auth from "@/store/auth.store"
import sockets from '@/store/sockets/sockets.store'
import accountOptions from '@/store/accountOptions.store'
import chat from '@/store/chat/chat.store'
import requisitionChat from '@/store/chat/requisitionChat.store'
import debug from '@/store/debug.store'
import notifications from '@/store/notifications.strore'

import objects from "@/store/crm/object.store"
import accountsCompany from "@/store/crm/accounts.store"
import tabel from "@/store/crm/tabel.store"

import specification from "@/store/specification/specification.store"
import specificationMaterial from "@/store/specification/specificationMaterial.store"
import specificationResource from "@/store/specification/specificationResource.store"
import specificationTypeWorks from "@/store/specification/specificationTypeWorks.store"

import specificationsMaster from "@/store/specification/master/masterSpecifications.store"
import specificationsMasterMaterial from "@/store/specification/master/masterSpecificationsMaterial.store"

import specificationsSupply from '@/store/specification/supply/supplySpecifications.store'
import specificationsSupplyMaterial from '@/store/specification/supply/supplySpecificationsMaterial.store'

import directoryMaterials from "@/store/directory/directoryMaterials.store"
import directoryBanks from "@/store/directory/directoryBanks.store"
import directoryPartners from '@/store/directory/directoryPartners.store'
import directoryEmployees from '@/store/directory/directoryEmployees.store'
import directoryPosts from '@/store/directory/directoryPosts.store'
import directoryBrigades from '@/store/directory/directoryBrigades.store'
import directoryMaterialModal from '@/store/directory/directoryMaterialsModal.store'
import directoryContracts from '@/store/directory/directoryContracts.store'

import master from '@/store/master/master.store'
import masterRequisition from '@/store/master/masterRequisition.store'
import masterRequisitionUniversal from '@/store/master/masterRequisitionUniversal.store'
import masterRequisitionForSpecification from '@/store/master/masterRequisitionForSpecification.store'
import masterRequisitionsMaterialInfo from '@/store/master/masterRequisitionsMaterialInfo.store'
import masterRequisitionDelivery from '@/store/master/masterRequisitionDelivery.store'

import supplyFreeRequisition from '@/store/supply/supplyFreeRequisition.store'
import supplyMyRequisition from '@/store/supply/supplyMyRequisition.store'
import supplyRequisitionsMaterial from '@/store/supply/supplyRequisitionsMaterial.store'
import supplyRequisioinsInvoices from '@/store/supply/supplyRequisioinsInvoices.store'

import upravlenieRequisitions from '@/store/upravlenie/upravlenieRequisitions.store'

import kadry from '@/store/kadry/kadry.store'

import payments from '@/store/finance/payments.store'

import stock from '@/store/stock/stock.store'

import screen from '@/store/screen.store'

Vue.use(Vuex);

export default new Vuex.Store({
    mutations: {
        UPDATE_STATUS(state, data){
            state.loadingStatus = data;
        },
        NETWORK_STATUS_SET(state){
            state.networkAvailable = !state.networkAvailable
        },
        SET_DELETE_MODAL_PARAMS(state, params){
            state.deleteModalParams = params
        },
        SET_CURRENT_EDIT_UID(state, id){
            state.curentEditUid = id
        }
    },
    actions: {
        networkAvailableActions( { commit } ){
            commit("NETWORK_STATUS_SET")
        },
        setParamsDeleteModalActions( { commit }, params){
            commit('SET_DELETE_MODAL_PARAMS', params)
        },
        setCurrentEditUidActions( { commit }, id){
            commit('SET_CURRENT_EDIT_UID', id)
        },
    },
    getters: {
        getLoadingStatus: (state) => state.loadingStatus,
        deleteModalParamsGetter: (state) => state.deleteModalParams,
        editCurrentUidGetter: (state) => state.curentEditUid,
        networkStatusGetter: (state) => state.networkAvailable
    },
    state: () => ({
        networkAvailable: true,
        curentEditUid: '',
        deleteModalParams: {
            title: '',
            actionsName: '',
            data: {}
        }
    }),
    modules: {
        auth,
        objects,
        master,
        masterRequisition,
        masterRequisitionDelivery,
        masterRequisitionUniversal,
        masterRequisitionsMaterialInfo,
        masterRequisitionForSpecification,
        requisitionChat,
        accountsCompany,
        directoryMaterials,
        directoryMaterialModal,
        directoryBanks,
        directoryPartners,
        directoryEmployees,
        directoryBrigades,
        directoryContracts,
        directoryPosts,
        specification,
        specificationMaterial,
        specificationResource,
        specificationTypeWorks,
        specificationsMaster,
        specificationsMasterMaterial,
        specificationsSupply,
        specificationsSupplyMaterial,
        upravlenieRequisitions,
        kadry,
        supplyMyRequisition,
        supplyFreeRequisition,
        supplyRequisioinsInvoices,
        supplyRequisitionsMaterial,
        notifications,
        sockets,
        payments,
        chat,
        debug,
        tabel,
        accountOptions,
        stock,
        screen
    }
})
