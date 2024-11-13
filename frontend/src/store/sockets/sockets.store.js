import Vue from 'vue'

const mutations = {
    SOCKETS_ACTIVE_SET_USERS(state, users){
        state.socketsError = ''
        state.socketsActiveUsers = users
    },
    SOCKETS_ACTIVE_USERS_ADD(state, user){
        state.socketsError = ''
        state.socketsActiveUsers.push(user)
    },
    SOCKETS_ACTIVE_USERS_DELETE(state, user){
        state.socketsError = ''
        state.socketsActiveUsers = state.socketsActiveUsers.filter( item => item.account.id != user.account.id)
    },
    SOCKETS_SET_ERROR(state, error){

        Vue.notify({
            group: 'foo',
            title: 'Ошибка',
            type: 'error',
            text: error
        })   

        state.socketsError = error
    }
}

const actions = {
    socketsActiveUsersSetActions( { commit }, users){
        commit('SOCKETS_ACTIVE_SET_USERS', users)
    },
    socketsActiveUsersAddActions( { commit }, user){
        commit('SOCKETS_ACTIVE_USERS_ADD', user)
    },
    socketsActiveUsersDeleteActions( { commit }, user){
        commit('SOCKETS_ACTIVE_USERS_DELETE', user)
    },
    socketsActiveSetErrorActions({ commit }, user){
        commit('SOCKETS_SET_ERROR', user)
    }
}
    
const getters = {
    socketsActiveUsersGetter: (state) => state.socketsActiveUsers
}

const state = () => ({
    socketsActiveUsers: [],
    socketsError: {}
})

export default {
    mutations,
    getters,
    actions,
    state,
}