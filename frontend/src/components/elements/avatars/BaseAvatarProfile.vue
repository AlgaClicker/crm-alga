<template>
    <div class="c-avatar-profile">
        <div class="c-avatar-profile__avatar" >
            <span v-show='socketsActiveUsers.filter( item => item.account.id  == avatarProfileProps.id ).length != 0' class="c-avatar-profile_active"> </span>
            <label>
                {{ avatarProfileProps?.username.split('')[0] }}{{ avatarProfileProps?.username.split('')[1] }}
            </label>
        </div>
        <div class="c-avatar-profile__title">
            <div class="username">{{ avatarProfileProps?.username }}</div>
            <small>{{ avatarProfileProps?.roles.name }}</small>
        </div>
    </div>
</template>
<script>
    import { mapGetters } from 'vuex'
    
    export default {
        name: 'BaseAvatarProfile',
        props: {
            avatarProfileProps: { type: Object, default: () => {} }
        },
        computed: {
            ...mapGetters({
                socketsActiveUsers: 'socketsActiveUsersGetter'
            })
        }
    }
</script>