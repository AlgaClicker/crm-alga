import Echo from 'laravel-echo'

import store from '@/store'

export const servicesSocketInit = () => {
    
    let authHostSll = process.env.VUE_APP_WS_SSL == "true" ? "wss" : "ws"
    
    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: authHostSll + '://' + process.env.VUE_APP_WS_HOST + ':' + process.env.VUE_APP_WS_PORT,
        wsHost: process.env.VUE_APP_WS_SSL,
        disableStats: false,
        forceTLS: process.env.VUE_APP_WS_SSL,
        token: localStorage.getItem('token'),
        transports: ['websocket'],
        auth:
        {
            headers:
            {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        }
    });

    /*window.Echo.connector.socket.on("connect", function () {
        console.log("socket_client:CONNECTED");
    });
    
    window.Echo.connector.socket.on("reconnecting", function () {
        console.log("socket_client:CONNECTING");
    });
    
    window.Echo.connector.socket.on("disconnect", function () {
        console.log("socket_client:DISCONNECTED");
    }); */

}


/* 
    window.Echo.channel('news').listen('.addNews', (e) => {
        console.log(e);   
    });
*/

export const socketServiceJoinCompany = ( id ) => {

    window.Echo.join('online.' + id)

    .here( (accounts) => {
        store.dispatch('socketsActiveUsersSetActions', accounts)
    }).joining( (account) => {
        store.dispatch('socketsActiveUsersAddActions', account)
    }).leaving( (account) => {
        store.dispatch('socketsActiveUsersDeleteActions', account)
    }).listen('user.online', ( ) => {

    }).error( (error) => {
        store.dispatch('socketsActiveSetErrorActions', error)
    })  
}

export const chatInit = async ( companyId ) => {

    if(typeof window.Echo !== 'undefined') {
        await window.Echo.private('chat.'+ companyId)
            .listen(".newMessage", message => {
                store.dispatch('chatIncomingMessage', message.messageBody)
            });
    }

}

export const chatAccountInit = async ( accountId ) => {
    
    if(typeof window.Echo !== 'undefined') {
        await window.Echo.private('chat.'+ accountId)
            .listen(".newMessage", message => {
                store.dispatch('chatReceiveMessageActions', message)
            });
    }

    if(typeof window.Echo !== 'undefined') {
        await window.Echo.private('chat.'+ accountId)
            .listen(".newMessageReq", message => {
                store.dispatch('requisitionChatReceiveMessageActions', message)
            });
    }

}

// accountId
export const socketServicejoinNotification = async ( accountId ) => {

    window.Echo.join('notifications.' + accountId)
    /*.here((users) => {
        console.log("joinNotification: here:", users);
    })
    .error((error) => {
        console.log("joinNotification: error:", error);
    })*/
    
    .listen('.newNotification', ( notification ) => {
        if(notification.notification.title != 'Чат'){
            store.dispatch('notificationNewActions', notification.notification)
        }
    })

    // Private channel
    window.Echo.private('newNotification').listen('.presence-notifications.' + accountId, (e) => {
        store.dispatch('debugSetNotificationsActions', e.notification)
    })
}

export const chatLieave = async () => {
    
}

export const leaveChannel = () => {
    window.Echo.leaveAllChannels()
}

/* window.Echo.join('core.user.'+ '0189620c-3c21-72dc-ba41-cfcf51e2e6ca')
.here((users) => {
    console.log('users')
    console.log(users)
})
.joining((user) => {
    console.log('join')
    console.log(user)
    store.dispatch('socketsActiveUsersAddActions', user)
})
.leaving((user) => {
    console.log('leave')
    console.log(user)
    store.dispatch('socketsActiveUsersDeleteActions', user)
});

*/
 // уральских танкистов 8

// 