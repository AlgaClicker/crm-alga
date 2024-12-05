import { httpRequest } from "@/services/httpRequest.service"
import Vue from 'vue'
import store from '@/store'

const mutations = {
    REQUISITION_CHAT_SET_MESSAGE_LIST(state, messages){

        state.requisitionChatError = ''

        if(messages != [] && messages != null && typeof messages != 'undefined'){
            state.requisitionChatMessageList =  messages
        }else{
            state.requisitionChatMessageList = []
        }
    },
    REQUISITION_CHAT_SEND_MESSAGE(state, message){
        state.requisitionChatMessageList.push(message)
    },
    REQUISITION_CHAT_RECEIVE_MESSAGE(state, message){
        
        if(message.requisition.id == state.requisitionChatRequisitionId){
            state.requisitionChatMessageList.push(message.messageBody)
        }
        
        message.href = store.getters.profileGetter.roles.service == 'master' ? 
            `/crm/master/requisition/info/${message.requisition.id}/chat` : `/crm/supply/requisition/my/info/${message.requisition.id}/chat` 

        Vue.notify({
            group: 'chat-req',
            title:  message.messageBody.autor.username,
            type: 'success',
            text:  message
        })  
    },
    REQUISITION_CHAT_SET_ERROR(state, data){
        state.requisitionChatError = data
    },
    REQUISITION_CHAT_SET_LOADING(state){
        state.requisitionChatLoading = !state.requisitionChatLoading
    },
    REQUISITION_CHAT_SET_REQUISITION_ID(state, id){
        state.requisitionChatRequisitionId = id
    },
    REQUISITION_CHA_DELETE_MESSAGE(state, id){
        state.requisitionChatMessageList = state.requisitionChatMessageList.filter( item => item.id != id)
    }
}

const actions = {
    async requisitionChatSetListActions( { commit }, id ){
        
        const data = await httpRequest(`crm/requisition/${id}/chat`, 'post', {} )
        
        if(data.code == 200){
            commit('REQUISITION_CHAT_SET_MESSAGE_LIST', data.data)
        } else{
            commit('REQUISITION_CHAT_SET_ERROR', data.data)
        }
    },
    async requisitionChatSendMessageActions( { commit }, form ){

        const data = await httpRequest(`crm/requisition/${form.idRequisition}/chat/send`, 'post', {
            message: form.message,
        })

        if(data.code == 200){
            commit('REQUISITION_CHAT_SEND_MESSAGE', data.data)
        }
        else{
            commit('REQUISITION_CHAT_SET_ERROR', data.data)
        }
    },
    async requisitionChatDeleteMessageActions({ commit }, id){
       
        const data = await httpRequest("chat/", 'delete', { id: id })

        if(data.code == 200){
            commit("REQUISITION_CHA_DELETE_MESSAGE", id)
        }else{
            commit("REQUISITION_CHAT_SET_ERROR", data.error)
        }

    },
    requisitionChatReceiveMessageActions({ commit }, message){
        commit('REQUISITION_CHAT_RECEIVE_MESSAGE', message)
    },
    requisitionChatSetRequisitionIdActions({ commit }, id){
        commit('REQUISITION_CHAT_SET_REQUISITION_ID', id)
    }
}
    
const getters = {
    requisitionChatMessageListGetter: (state) => state.requisitionChatMessageList,
    requisitionChatRequisitionIdGetter: (state) => state.requisitionChatRequisitionId,
}

const state = () => ({
    requisitionChatMessageList: [],
    requisitionChatRequisitionId: '',
    requisitionChatLoading: false,
    requisitionChatError: null,
    requisitionOptions: {
        orderBy: {
            name: "ASC",
            created_at: "ASC",
        },
        pagginate: {
            pages: 1,
            page: 1,
            limit: 10
        }
    },
})

export default {
    mutations,
    getters,
    actions,
    state,
}
