import { httpRequest } from '@/services/httpRequest.service'
import Vue from 'vue'

const mutations = {
    CHAT_SET_DIALOG_LIST(state, data){
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.chatDialogList = data.data
        }else{
            state.chatDialogList = []
        }
    },
    CHAT_NEW_DIALOG(state, data){
        state.chatDialogList.push(data)
    },
    CHAT_SET_MESSANGES_LIST(state, data){
        if(data.data != [] && data.data != null && typeof data.data != 'undefined'){
            state.chatMessangesList = data.data.reverse()
        }else{
            state.chatMessangesList = []
        }
    },
    CHAT_SET_ERROR(state, data){
        state.chatError = data
    },
    CHAT_SEND_MESSAGE(state, data){
        state.chatMessangesList.push(data)
        state.chatFlagToggle = !state.chatFlagToggle
    },
    CHAT_DELETE_MESSAGE(state, id){
        state.chatMessangesList = state.chatMessangesList.filter( item => item.id != id)
    },
    CHAT_PAGGINATE(){
        
    },
    CHAT_LOADING(state){
        state.chatLoading = !state.chatLoading
    },
    CHAT_RECEIVE(state, message){

        state.chatMessangesList.push(message.messageBody)
        
    },
    CHAT_SET_ROOM_ACCOUNT(state, account){
        state.chatRoomAccount = account
    }
}

const actions = {
    async chatSetDialogListActions({ commit }){
        
        const data = await httpRequest("chat/list", 'post', {})

        if(data.code == 200){
            commit("CHAT_SET_DIALOG_LIST", data)
        }else{
            commit("CHAT_SET_ERROR", data.error)
        }
    },
    async chatSetMessangesListActions({ commit, getters }, id){
        
        if( typeof id != 'undefined' & id != '' & id != null ){

            commit('CHAT_LOADING')
            const data = await httpRequest(`chat/${id}/`, 'post', { options: getters.chatOptionsGetter })
            commit('CHAT_LOADING')

            if(data.code == 200){
                commit("CHAT_SET_MESSANGES_LIST", data)
            }else{
                commit("CHAT_SET_ERROR", data.error)
            }
        } 
    
    },
    async chatSendMessangesActions({ commit }, form){

        const data = await httpRequest("chat/send", 'post', {
            message: form.message,
            private: form.private,
            account_to: form.account_to,
            files: form.files
        })

        if(data.code == 200){
            commit("CHAT_SEND_MESSAGE", data.data)
        }else{
            commit("CHAT_SET_ERROR", data.error)
        }
    },
    async chatDeleteMessageActions({ commit }, id){

        const data = await httpRequest("chat/", 'delete', { id: id })

        if(data.code == 200){
            commit("CHAT_DELETE_MESSAGE", id)
        }else{
            commit("CHAT_SET_ERROR", data.error)
        }
        
    },
    chatReceiveMessageActions({ commit, getters }, message){
        Vue.notify({
            group: 'chat',
            title:  message.messageBody.autor.username,
            type: 'success',
            text:  message.messageBody
        })  

        if(message.autor.id == getters.chatRoomAccountGetter.id){
            commit("CHAT_RECEIVE", message)
        }

    },
    chatNewDialogActions({ commit }, account){
        var newDialog = {
            id: account.id,
            username: account.username,
            email: account.email,
            active: true,
            roles: account.roles
        }

        commit("CHAT_NEW_DIALOG", newDialog)
    },
    chatSetRoomAccountActions({ commit }, account){
        commit('CHAT_SET_ROOM_ACCOUNT', account)
    }
}

const getters = {
    chatLoadingGetter: (state) => state.chatLoading,
    chatRoomAccountGetter: (state) => state.chatRoomAccount,
    chatDialogListGetter: (state) => state.chatDialogList,
    chatMessangesListGetter: (state) => state.chatMessangesList,
    chatOptionsGetter: (state) => state.chatOptions,
    chatFlagToggleGetter: (state) => state.chatFlagToggle,
    chatErrorGetter: (state) => state.chatError
}

const state = () => ({
    chatMessangesList: [],
    chatRoomAccount: {},
    chatLoading: false,
    chatDialogList: [],
    chatError: '',
    chatFlagToggle: false,
    chatOptions: {
        pagginate: {
            page: 1,
            limit: 10,
            pages: 1
        },
        orderBy: { 
            name: "ASC",
            created_at: "ASC",
        }
    },
})

export default {
    mutations,
    getters,
    actions,
    state,
}
