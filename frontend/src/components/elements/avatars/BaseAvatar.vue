<template>
    <div>
        <div class="c-avatar" v-tooltip.bottom-start="avatarProfileProps?.username">
            <span v-show='socketsActiveUsers.filter( item => item?.account.id  == avatarProfileProps?.id ).length != 0' class="c-avatar_active"> </span>
            <span v-if="typeof avatarProfileProps.username != 'undefined'" class="c-avatar_title">
                {{ avatarProfileProps.username.split('')[0].toUpperCase() }}
            </span>
        </div>
    </div> 
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        name: 'BaseAvatar',
        props: {
            avatarProfileProps: { type: Object, default: () => { } },
            sizeProps: { type: String, default: 'sm' }
        },
        computed: {
            ...mapGetters({
                socketsActiveUsers: 'socketsActiveUsersGetter'
            })
        }
    }
</script>