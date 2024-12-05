<template>
    <div class="c-requisition-chat">
        <header class="c-requisition-chat-header">
            <p>12</p>
            {{ requisitionProps }}
            111111111111111
            <div class="c-requisition-chat-header__title">
                <base-avatar
                    :avatarProfileProps="requisitionProps.manager"
                />
                <div class="user">
                    <div class="username">{{ requisitionProps.manager?.username }}</div>
                    <small>{{ requisitionProps.manager?.email }}</small>
                </div>
            </div>
        </header>
        <div v-if="false" class="room-messages-loading-wrapper">
            <div class="c-spinner_wrapper" style="height: 100%;">
                <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
            </div>
        </div>
        <div v-else ref="scrollwrapper" class="c-requisition-chat__messages">
            <chat-message 
                v-for="item in requisitionChatMessageList" :key="item.id"
                :chatMessangeProps="item"  
            /> 
        </div>
        <div class="c-requisition-chat__footer">
            <div class="c-chat-textarea" :class="{'c-chat-textarea--is-files': files.length}">
                <div class="c-chat-upload">
                    <label>
                        <input 
                            name="c-chat-upload" 
                            id="c-chat-upload" 
                            type="file" 
                            ref="file" 
                            @change="fileUpload()"
                        />
                        <base-icon iconProps="link" sizeProps="md" />
                    </label>
                </div>
                <textarea
                    ref="textarea"
                    v-model="message"
                    placeholder="введите сообщение"
                    @keyup.enter="sendMessage"
                    @keyup="resize"
                >
                </textarea>
                <div v-show="files.length > 0" class="c-chat-textarea__files">
                    <files-chat-attached 
                        v-for="item in files" :key="item.id"
                        :fileProps="item"
                        @deleteFileEmit="deleteFile"
                    />
                    <files-chat-attached-progress
                        v-show="showProgress"
                        :filesProps="fileUploaded"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import BaseAvatar from '@/components/elements/avatars/BaseAvatar'
    import ChatMessage from '@/components/chat/ChatMessage' 
    import BaseIcon from "@/components/elements/BaseIcon"
    import FilesChatAttached from '@/components/elements/files/FilesChatAttached'
    import FilesChatAttachedProgress from '@/components/elements/files/FilesChatAttachedProgress' 

    export default {
        name: "RequisitionInfoChat",
        data(){
            return {
                message: '',
                iconLink: '',
                files: [],
                fileUploaded: {},
                showProgress: false
            }
        },
        watch: {
            'requisitionProps': {
                async handler() {
                    await this.requisitionChatSetList(this.requisitionProps.id)
                }
            }
        },
        props: {
            requisitionProps: { type: Object, default: () => {} },
        },
        components: {
            BaseAvatar,
            ChatMessage,
            BaseIcon,
            FilesChatAttached,
            FilesChatAttachedProgress 
        },
        computed: {
            ...mapGetters({
                requisitionChatMessageList: 'requisitionChatMessageListGetter',
                supplyMyRequisition: 'supplyMyRequisitionGetter'
            })
        },
        methods: {
            ...mapActions({
                requisitionChatSetList: 'requisitionChatSetListActions',
                requisitionChatSendMessage: 'requisitionChatSendMessageActions'
            }),
            fileUpload(){
                
            },
            sendMessage(){
                var form = {}
                let id = this.requisitionProps.id

                if( this.message == '' & this.message == "\n" & this.files.length == 0 ){
                    form = {
                        idRequisition: id,
                        message: 'Прикрепленный файл',
                        private: true,
                        files: this.files.map( item => item.hash)
                    }
                }
                else {
                    form = {
                        idRequisition: id,
                        message: this.message,
                        private: true,
                        files: this.files.map( item => item.hash)
                    }
                }

                this.message = ''
                this.files = []

                this.requisitionChatSendMessage(form)
            },
            resize(){
                console.log('keyup')

                console.log(this.$refs.textarea.value)
                console.log(this.$refs.textarea.value)

                console.log(this.$refs.textarea.value.match(/\n/g))
            },
        },
    }
</script>