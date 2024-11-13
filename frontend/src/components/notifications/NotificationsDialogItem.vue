<template> 
    <div class="c-notification-list">
        <base-avatar 
            class="mr-1 mt-4"
            :avatarProfileProps="notificationProps?.to_account"
        />
        <div class="c-notification-list__right">
            <div @click="readNotificcations(notificationProps.id)" class="icon">
                <base-icon iconProps="x" sizeProps="sm" />
            </div>
            <header class="notification-header">
                <div class="notification-header__title">
                    {{ notificationProps.title }}
                </div>
            </header>
            <div class="notification-body"> 
                <p>{{ notificationProps.message }}</p>
            </div>
            <div class="date">
                {{ notificationProps.created_at | dateFilter }}
            </div>
        </div>
    </div>
</template>

<script>
    import BaseAvatar from '@/components/elements/avatars/BaseAvatar'
    import BaseIcon from "@/components/elements/BaseIcon"
    import { mapActions } from 'vuex'

    export default {
        name: 'NotificationsDialogItem',
        data(){
            return{
                isRead: false,
            }
        },
        components: {
            BaseAvatar,
            BaseIcon
        },
        props: {
            notificationProps: { type: Object, default: () => {} }
        },
        methods: {
            ...mapActions({
                notificationRead: 'notificationReadActions',
            }),
            readNotificcations(id){
                this.notificationRead(id)
            }
        }
    }
</script>