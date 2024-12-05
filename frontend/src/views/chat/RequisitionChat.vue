<template>
    <div class="c-requisition-chat">
        <header class="c-requisition-chat-header">
            <div class="c-requisition-chat-header__title">
                <base-avatar
                    :avatarProfileProps="autor"
                />
                <div class="user">
                    <div class="username">{{ autor?.username }}</div>
                    <small>{{ autor?.email }}</small>
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
                :deleteMessangeActionsNameProps="'requisitionChatDeleteMessageActions'"
            /> 
        </div>
        <div class="c-requisition-chat__footer">
            <div class="c-chat-textarea" :class="{'c-chat-textarea--is-files': files.length}">
                <div class="c-chat-upload">
                    <label>
                        <input name="c-chat-upload" id="c-chat-upload" type="file" ref="file" @change="fileUpload()"/>
                        <base-icon iconProps="link" sizeProps="md" />
                    </label>
                </div>
                <div class="wrap">
                    <textarea
                        ref="textarea"
                        v-model="message"
                        placeholder="введите сообщение"
                        onInput="this.parentNode.dataset.value = this.value"
                        @input="resizeTextarea($event)"
                        @keyup.enter="sendMessage"
                    >
                    </textarea>
                </div>
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
    import { mapActions, mapGetters } from 'vuex';
    import { instance } from '@/services/instances.service';

    import BaseAvatar from '@/components/elements/avatars/BaseAvatar'
    import ChatMessage from '@/components/chat/ChatMessage' 
    import BaseIcon from "@/components/elements/BaseIcon"
    import FilesChatAttached from '@/components/elements/files/FilesChatAttached'
    import FilesChatAttachedProgress from '@/components/elements/files/FilesChatAttachedProgress'

    export default {
        name: "RequisitionChat",
        data(){
            return {
                message: '',
                iconLink: '',
                files: [],
                fileUploaded: {},
                showProgress: false,
                autor: {}
            }
        },
        watch: {
            '$route.params.id': {
                async handler() {
                    let id = window.location.href.split('/').reverse()[1];
                    await this.requisitionChatSetList(id)
                    
                    if (this.profile.roles.service == "master"){
                        await this.masterRequisitionSet(id)
                        this.autor = this.masterRequisition.manager

                    } else if(this.profile.roles.service == "snabzenie"){
                        await this.supplyMyRequisitionSet(id)
                        this.autor = this.supplyMyRequisition.autor
                    }
                }
            },
            'requisitionChatMessageList': {
                handler() {
                    this.$refs.scrollwrapper.scrollTo(0, this.$refs.scrollwrapper.scrollHeight + 10000);
                }
            }
        },
        components: {
            BaseIcon,
            BaseAvatar,
            ChatMessage,
            FilesChatAttached,
            FilesChatAttachedProgress 
        },
        computed: {
            ...mapGetters({
                profile: "profileGetter",
                masterRequisition: 'masterRequisitionGetter',
                supplyMyRequisition: 'supplyMyRequisitionGetter',
                requisitionChatMessageList: 'requisitionChatMessageListGetter',
            })
        },
        methods: {
            ...mapActions({
                masterRequisitionSet: 'masterRequisitionSetActions',
                supplyMyRequisitionSet: 'supplyMyRequisitionSetActions',
                requisitionChatSetList: 'requisitionChatSetListActions',
                requisitionChatSendMessage: 'requisitionChatSendMessageActions',
                requisitionChatSetRequisitionId: 'requisitionChatSetRequisitionIdActions'
            }),
            fileUpload(){
                var output = document.getElementById('output')
                this.file = this.$refs.file?.files[0]
                let formData = new FormData()
                formData.append('upload', this.file)

                this.fileUploaded = this.file
                this.showProgress = true
                
                var config = {
                    headers: {'Content-Type': 'multipart/form-data'},
                    onUploadProgress: function(progressEvent) {
                        var percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                        document.getElementById("progress-upload").value = percentCompleted;
                    }
                };

                instance
                .post(`/api/v1/services/file/upload/`, formData, config)
                .then( (res) => {
                    var file = {
                        name: res.data.data[0].name, 
                        hash: res.data.data[0].hash,  
                        size: res.data.data[0].size,
                        message: res.data.message
                    }
                    this.files.push(file)
                  

                })
                .catch(function (err) {
                    output.className = 'container';
                    output.innerHTML = err.message;
                }).finally( () => {
                    this.showProgress = false
                })
            },
            resizeTextarea(){
                if(event.target.value.split(' ').reverse()[0].length > 153){
                    this.message =  this.message + " "
                }
            },
            sendMessage(){
                var form = {}
                let id = window.location.href.split('/').reverse()[1];

                if( (this.message == '' | this.message == "\n") & this.files.length != 0 ){
                    form = {
                        idRequisition: id,
                        message: 'Прикрепленный файл',
                        private: true,
                        files: this.files.map( item => item.hash)
                    }
                }

                else if(this.message != '') {
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
        },
        async mounted(){
            let id = window.location.href.split('/').reverse()[1];
            this.requisitionChatSetRequisitionId(id)
            await this.requisitionChatSetList(id)

            if(this.profile.roles.service == "master"){
                await this.masterRequisitionSet(id)
                this.autor = this.masterRequisition.manager
            } else if(this.profile.roles.service == "snabzenie"){
                await this.supplyMyRequisitionSet(id)
                this.autor = this.supplyMyRequisition.autor
            }
            this.$refs.scrollwrapper.scrollTo(0, this.$refs.scrollwrapper.scrollHeight + 10000);
        }
    }
</script>