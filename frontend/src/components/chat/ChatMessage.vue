<template> 
    <div class="chat-wrapper" :id="idComputed" >
        <div 
            :class="{ 'chat-message--my' : chatMessangeProps.autor?.id == profile?.id  }" 
            class="mb-2 chat-message"
        >
            <div class="chat-message__header">
                <div class="name">
                    <base-avatar
                        class="mr-1"
                        :avatarProfileProps="chatMessangeProps?.autor"
                    />
                    {{ chatMessangeProps.autor?.username }}
                </div>
            </div>
            <div class="chat-message__content">
                {{ chatMessangeProps.message }}
                <div class="files">
                    <hr v-show="chatMessangeProps.files.length > 0">
                    <files-chat-block
                        v-for="item in chatMessangeProps.files" :key="item.id"
                        :fileProps="item"
                    />
                </div>
            </div>
            <div class="chat-message__date">
                {{ chatMessangeProps.created_at | dateFilter }}
            </div>
        </div>
    </div>
</template> 
<script>
    import { mapGetters } from 'vuex'
    import store from '@/store'
    import VanillaContextMenu from 'vanilla-context-menu'

    import FilesChatBlock from '@/components/elements/files/FilesChatBlock'
    import BaseAvatar from '@/components/elements/avatars/BaseAvatar' 
    
    export default {
        name: 'ChatMessages',
        data(){
            return {
                selected: false,
                id: ""
            }
        },
        components: {
            BaseAvatar,
            FilesChatBlock
        },
        props: {
            chatMessangeProps: { type: Object, default: function () {} },
            deleteMessangeActionsNameProps: { type: String, default: '' },
        },
        methods: {
            async deleteMessage(){
                store.dispatch(this.deleteMessangeActionsNameProps, this.chatMessangeProps.id)
            }
        },
        computed: {
            ...mapGetters({
                profile: "profileGetter",
            }),
            idComputed(){
                return this.id
            }
        },
        mounted(){
            if(this.chatMessangeProps.autor.id == this.profile.id){
                this.id = `s${this.chatMessangeProps.id}`
                setTimeout(() => {
                    let item = document.getElementById(this.id)
            
                        new VanillaContextMenu({
                            scope: item,
                            menuItems: [
                                {
                                    label: 'Удалить',
                                    callback: () => {
                                        this.deleteMessage()
                                    },
                                },
                            ],
                            normalizePosition: true
                        })
                    
                },100)
            }

        }
    }
</script>

<style lang="scss">
    .chat-wrapper{
        display: flex;
    }

    .chat-message{
        margin-bottom: 1.6rem !important;
        display: inline-block;
        width: 50%;
        align-items: flex-start;
        cursor: pointer;
        &__header{
            .name{
                align-items: center;
                display: flex;
            }
        }
        &__content{
            background-color: #F3F4F8;
            border-radius: 7px;
            border-top-left-radius: 0;
            color: #7F7B9F;
            text-overflow: ellipsis; 
            overflow: hidden;
            word-break: break-all;
            margin-left: 2.9rem;
            padding: 0.8rem;
            font-size: 0.8rem;
        }
        &__date{
            float: right;
            color: #BABABC;
            margin-top: 4px;
            font-size: 0.8rem;
        }
    }

    .chat-message--my{
        margin-left: auto;
        .chat-message__content{
            background-color: #F3F8F3;
            color: #4E4E4E;
        }
    }
</style>