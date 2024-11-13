import Vue from 'vue'

const mutations = {
    DEBUG_SET_NOTIFICATIONS(state, notification){

        Vue.notify({
            group: 'foo',
            title: 'Ошибка',
            type: 'error',
            text: notification
        })

        state.debugNotifications = notification
    }
}

const actions = {
    async debugSetNotificationsActions( { commit }, notification ){
        commit('DEBUG_SET_NOTIFICATIONS', notification)
    },
}
    
const getters = {
    debugNotificationsGetter: (state) => state.debugNotifications,
}

const state = () => ({
    debugNotifications: {}
})

export default {
    mutations,
    getters,
    actions,
    state,
}