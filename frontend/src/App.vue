<template>
    <div id="app" class="app">
        <component :is="layout">
            <router-view />
        </component>
        <base-delete-modal />
        <notifications 
            group="success"  
            position="bottom right"
            classes="vue-notification-success"
        >
            <template slot="body" slot-scope="props">
                <div class="vue-notification-success">
                    <div class="icon" v-html="iconSuccess"></div>
                    <div class="vue-notification-success__wrapper">
                        <div class="vue-notification-success__title">
                            <div class="title">{{ props.item.title }}</div>
                            <span class="close" @click="props.close" v-html="iconX"></span>
                        </div>
                        <div class="vue-notification-success__content">
                            {{ props.item.text }}
                        </div>
                    </div>
                </div>
            </template>
        </notifications>
        <!-- -->
        <notifications 
            group="error"  
            position="bottom right"
            classes="vue-notification-error"
        >
            <template slot="body" slot-scope="props">
                <div class="vue-notification-error">
                    <div class="icon" v-html="iconError"></div>
                    <div class="vue-notification-error__wrapper">
                        <div class="vue-notification-error__title">
                            <div class="title">{{ props.item.title }}</div>
                            <span class="close" @click="props.close" v-html="iconX"></span>
                        </div>
                        <div class="vue-notification-error__content">
                            {{ props.item.text }}
                        </div>
                    </div>
                </div>
            </template>
        </notifications>
        <!-- Chat -->
        <notifications 
            group="chat"  
            position="bottom right"
            classes="vue-notification-chat"
        >
            <template slot="body" slot-scope="props">
                <div class="vue-notification-chat">
                    <div class="icon" v-html="iconMessage"></div>
                    <div class="vue-notification-chat__wrapper">
                        <div class="vue-notification-chat__title">
                            <div class="title">{{ props.item.text.autor.username }}</div>
                            <div class="date">{{ props.item.text.created_at | dateFilter }}</div>
                            <span class="close" @click="props.close" v-html="iconX"></span>
                        </div>
                        <div class="vue-notification-chat__content">
                            {{ props.item.text.message }}
                        </div>
                    </div>
                </div>
            </template>
        </notifications>

        <notifications 
            group="chat-req"  
            position="bottom right"
            classes="vue-notification-chat"
        >
            <template slot="body" slot-scope="props">
                <div class="vue-notification-chat">
                    <div class="icon" v-html="iconMessage"></div>
                    <div class="vue-notification-chat__wrapper">
                        <router-link 
                            :to="props.item.text.href"
                        >
                            <div class="vue-notification-chat__title">
                                <div class="title">{{ props.item.text.messageBody.autor.username }}</div>
                                <div class="date">{{ props.item.text.messageBody.created_at | dateFilter }}</div>
                                <span class="close" @click="props.close" v-html="iconX"></span>
                            </div>
                            <div class="vue-notification-chat__content">
                                {{  props.item.text.messageBody.message }}
                            </div>
                        </router-link>
                    </div>
                </div>
            </template>
        </notifications>

    </div>
</template>

<script>

import { mapActions } from 'vuex';

import { servicesSocketInit, socketServiceJoinCompany, socketServicejoinNotification, chatAccountInit } from '@/services/sockets.service'
import BaseDeleteModal from '@/components/elements/BaseDeleteModal'
import { svgs } from '@/utils/svgList'

export default {
    name: "App",
    data(){
        return {
            iconMessage: '',
            iconSuccess: '',
            iconError: '',
            iconX: ''
        }
    },
    components: {
        AdminLayout: () => import("@/layouts/AdminLayout"),
        EmptyLayout: () => import("@/layouts/EmptyLayout"),
        CRMLayout: () => import("@/layouts/CRMLayout"),
        BaseDeleteModal,
    },
    computed: { 
        layout(){ 
            return this.$route.meta.layout || "EmptyLayout";
        },
    },
    methods: {
        ...mapActions({
            heightSpecificationsTableSet: 'heightSpecificationsTableSetActions',
            heightMasterSpecificationsTableSet: 'heightMasterSpecificationsTableSetActions'
        }),
        resize(){
            this.heightSpecificationsTableSet(document.documentElement?.clientHeight)
            this.heightMasterSpecificationsTableSet(document.documentElement?.clientHeight)
        }
    },
    mounted(){
        this.iconMessage = svgs.message.md
        this.iconSuccess = svgs.success.md
        this.iconX = svgs.x.sm  
        this.iconError = svgs.error.md
         
        servicesSocketInit()
        socketServiceJoinCompany( JSON.parse(localStorage.getItem('company'))?.id )
        socketServicejoinNotification(JSON.parse(localStorage.getItem('account'))?.id )
        chatAccountInit(JSON.parse(localStorage.getItem('account'))?.id)
    },
    created() {
        window.addEventListener("resize", this.resize);
    },
}

</script>

<style lang="scss">
    @import 'assets/scss/style';
</style>

