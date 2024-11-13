<template>
    <div class="c-chat-room">
        <template v-if="roomProps">
            <header class="c-chat-room__header">
                <div class="c-chat-room__title">
                    <base-avatar
                        :avatarProfileProps="roomProps"
                    />
                    <div class="user">
                        <div class="username">{{ roomProps?.username }}</div>
                        <small>{{ roomProps.roles?.name }}</small>
                    </div>
                </div>
            </header>
            <div v-if="chatLoading"  class="room-messages-loading-wrapper">
                <div class="c-spinner_wrapper" style="height: 100%;">
                    <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                </div>
            </div>
            <div v-else ref="scrollwrapper" class="c-chat-room__messages">
                <chat-message 
                    v-for="item in chatMessangesList" :key="item.id"
                    :chatMessangeProps="item"  
                /> 
            </div>
            <div class="c-chat-room__footer">
                <div class="c-chat-textarea" :class="{'c-chat-textarea--is-files': files.length}">
                    <div class="c-chat-upload">
                        <label>
                            <input name="c-chat-upload" id="c-chat-upload" type="file" ref="file" @change="fileUpload()"/>
                            <span v-html="iconLink"></span>
                        </label>
                    </div>
                    <div class="wrap">
                        <textarea
                            ref="textarea"
                            v-model="message"
                            rows="1"
                            onInput="this.parentNode.dataset.value = this.value"
                            @input="resizeTextarea($event)"
                            placeholder="введите сообщение"
                            @keydown.enter.prevent="sendMessage"
                        >
                        </textarea>
                    </div>
                    <div  class="c-chat-textarea__files">
                        <files-chat-attached 
                            v-for="item in files" :key="item.id"
                            :fileProps="item"
                            @deleteFileEmit="deleteFile"
                        />
                        <files-chat-attached-progress
                            v-show="showProgress"
                        />
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="c-chat-room__empty">
                Нет сообщений
            </div>
        </template>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import { svgs } from '@/utils/svgList'
    import { instance } from '@/services/instances.service';

    import ChatMessage from '@/components/chat/ChatMessage' 
    import BaseAvatar from '@/components/elements/avatars/BaseAvatar'
    import FilesChatAttached from '@/components/elements/files/FilesChatAttached'
    import FilesChatAttachedProgress from '@/components/elements/files/FilesChatAttachedProgress'

    export default {
        name: 'ChatRoom',
        data(){
            return {
                message: '',
                iconLink: '',
                files: [],
                fileUploaded: {},
                showProgress: false
            }
        },
        components: {
            ChatMessage,
            BaseAvatar,
            FilesChatAttached,
            FilesChatAttachedProgress
        },
        watch: {
            'roomProps': {
                async handler() {
                    if(typeof this.roomProps?.id == 'undefined'){
                        alert('undefined')
                        this.chatSetRoomAccount(this.roomProps)
                        await this.chatSetMessangesList('')
                    }else{
                        this.chatSetRoomAccount(this.roomProps)
                        await this.chatSetMessangesList(this.roomProps?.id)
                        this.$refs.textarea.focus();
                    }
                }
            },
            'chatFlagToggle': {
                handler(){
                    this.$refs.scrollwrapper.scrollTop = this.$refs.scrollwrapper.scrollHeight - this.$refs.scrollwrapper.clientHeight + 8
                }
            }
        },
        props: {
            roomProps: { type: Object, default: function () {} },
        },
        computed: {
            ...mapGetters({
                chatLoading: 'chatLoadingGetter',
                chatMessangesList: 'chatMessangesListGetter',
                chatFlagToggle: 'chatFlagToggleGetter',
            })
        },
        methods: {
            ...mapActions({ 
                chatSetMessangesList: 'chatSetMessangesListActions',
                chatSendMessanges: 'chatSendMessangesActions',
                chatSetRoomAccount: 'chatSetRoomAccountActions'
            }),
            sendMessage(){
                var form = {}

                if( (this.message == '' | this.message == "\n") & this.files.length != 0 ){
                    form = {
                        message: 'Прикрепленный файл',
                        private: true,
                        account_to: this.roomProps?.id,
                        files: this.files.map( item => item.hash)
                    }
                }
                else if(this.message != ''){
                    form = {
                        message: this.message,
                        private: true,
                        account_to: this.roomProps?.id,
                        files: this.files.map( item => item.hash)
                    }
                }
                
                this.message = ''
                this.files = []
                this.chatSendMessanges(form)
            },
            deleteFile(file){
                this.files = this.files.filter( item => item.hash != file.hash)
            },
            resizeTextarea(event){
                if(event.target.value.split(' ').reverse()[0].length > 153){
                    this.message =  this.message + " "
                }
            },
            fileUpload(){
                //var output = document.getElementById('output')
                this.file = this.$refs.file?.files[0]
                let formData = new FormData()
                formData.append('upload', this.file)
                this.showProgress = true
                document.getElementById("chat-file-progress-bar-text").textContent = this.file.name;

                this.fileUploaded = this.file
                
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
                    this.$emit( 'addFileEmit',  this.$event, file)
                })
                .catch(function (err) {
                    console.log(err)
                    //output.className = 'container';
                    //output.innerHTML = err.message;
                }).finally( () => {
                    this.showProgress = false
                })
            }
        },
        async mounted(){
            //this.$refs.textarea.focus();
            this.iconLink = svgs.link.md
            this.chatSetRoomAccount(this.roomProps)
            await this.chatSetMessangesList(this.roomProps?.id)
        }
    }
</script>