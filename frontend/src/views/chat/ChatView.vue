<template>
    <div class="c-chat-warapper">
        <div class="c-chat">
            <div class="c-chat-list">
                <header class="c-chat-list__header">
                    <h5>Чат</h5>
                    <multiselect
                        v-model="value" 
                        placeholder="Поиск пользователя" 
                        @input=addDialogAccount()
                        :options="accountCompanyOptionsFree"
                        label="username"
                        track-by="username"
                        :show-labels="false"
                    >
                        <template slot="option" slot-scope="props">
                            <div class="option__desc">
                                {{ props.option.username }}
                            </div>
                        </template>
                    </multiselect>
                </header>
                <div class="c-chat-list__wrapper">
                    <ChatDialog v-for="item in chatDialogList" :key="item.id"
                        :dialogProps="item"
                        :selectedDialogProps="selectedRoom"
                        @selectRoomEmit="selectRoom"
                    />
                </div>
            </div>
            <ChatRoom
                :roomProps="selectedRoom"
            />
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from "vuex"
    import { heightDialogsList, heightMessagesList } from '@/utils/heightChatContainers'
    
    import { svgs } from '@/utils/svgList'
    import ChatRoom from "@/components/chat/ChatRoom"
    import ChatDialog from '@/components/chat/ChatDialog'

    export default {
        name: 'ChatView',
        data(){
            return {
                iconSearch: '',
                selectedRoom: null,
                value: null,
            }
        },
        components: {
            ChatRoom,
            ChatDialog,
         /*    ChatMessage */
        },
        computed: {
            ...mapGetters({
                profile: "profileGetter",
                chatDialogList: 'chatDialogListGetter',
                accountsOptions: 'accountsOptionsGetter',
                accountsCompanyList: 'accountsCompanyListGetter'
            }),
            accountCompanyOptionsFree(){
                
                var list = this.accountsCompanyList
                    .filter( element => this.chatDialogList.map(item => item.id)
                    .indexOf(element.id) == -1 )
                
                return list
            }
        },
        methods: {
            ...mapActions({
                chatSetDialogList: 'chatSetDialogListActions',
                accountOptionsSet: 'accountOptionsSetActions',
                accountsCompanySet: 'accountsCompanySetActions',
                chatNewDialog: 'chatNewDialogActions',
                accountOptionsEditChat: 'accountOptionsEditChatActions' 
            }),
            async sendMessage(){
                this.chatSendMessage(this.newMessage)
                this.newMessage = null
            },
            addDialogAccount(){
                this.chatNewDialog(this.value)
                this.selectRoom(this.chatDialogList.filter( item => item.id == this.value.id)[0])
            },
            selectRoom(room){   
                this.accountOptionsEditChat(room.id)
                this.selectedRoom = room
            }
        },
        async mounted(){
            this.heightDialogsList = heightDialogsList()
            this.heightMessages = heightMessagesList()
            this.iconSearch = svgs.search.md

            await this.accountsCompanySet()

            /* await this.chatSet(this.profile) */
            await this.chatSetDialogList()

            if(this.chatDialogList.length > 0){
                this.selectedRoom = this.chatDialogList[0]
            }

        }
    }
</script>