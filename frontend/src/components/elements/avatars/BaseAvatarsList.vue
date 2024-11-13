<template>
    <div class="c-avatars">
        <div class="c-avatars_avatar" v-for="item in responsiblesProps" :key="item.id" >
            <span>{{ item.username.split('')[0].toUpperCase() }}</span>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'BaseAvatars',
        props: {
            responsiblesProps: { type: Array, default: function () {} },
        },
    }        
</script>