<template>
    <div class="author">
        <div v-if="typeof authorProps.workpeople != 'undefined' & typeof authorProps.workpeople?.surname != 'undefined' ">
            {{ authorProps.workpeople?.surname }} 
            {{ authorProps.workpeople?.name }} 
            {{ authorProps.workpeople?.patromic }} 
            ( {{ authorProps.workpeople.profession?.name }} )
        </div>
        <div v-else>
            {{ authorProps?.username }}
        </div> 
    </div>
</template>

<script>
    export default {
        name: 'AuthorTitle',
        props: {
            authorProps: { type: Object, default: () => {} },
        },
    }
</script>

<style lang="scss">
    .author{
              
    }
</style>