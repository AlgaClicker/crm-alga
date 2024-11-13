<template>
    <div class="file-chat-attached">
        <p> {{ fileProps.name }} ({{ fileProps.size | sizeFilter }}) </p>
        <b-icon icon="x" @click="deleteFile()"></b-icon>
    </div>
</template>

<script>
    export default {
        name: 'FilesChatAttached',
        props: {
            fileProps: { type: Object, default: () => {} } 
        },
        emits: ['deleteFileEmit'],
        methods: {
            deleteFile(){
                this.$emit('deleteFileEmit', this.fileProps)
            }
        }
    }
</script>

<style lang="scss">
    .file-chat-attached{
        background-color: #ebeaea;
        display: flex;
        margin-right: 1rem;
        margin-bottom: 0.3rem;
        padding: 0.2rem;
        padding-left: 0.6rem;
        padding-right: 0.1rem;
        p{
            margin: 0;
            padding: 0;
        }
        svg{
            margin-left: 0.4rem;
            cursor: pointer;
        }
    }
</style>