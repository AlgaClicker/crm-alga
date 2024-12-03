<template>
    <div class="files-chat-block" @click="downloadFile(fileProps.hash, fileProps.name)">
        <div>
            <base-icon iconProps="file" sizeProps="md" />
        </div>
        <div class="title">
            <div class="name"> {{ fileProps.name }} </div>
            <div class="size"> {{ fileProps.size | sizeFilter }} </div>
        </div>
    </div>
</template>

<script>
    import downloadFile from '@/mixins/files.mixins'
    import BaseIcon from "@/components/elements/BaseIcon"

    export default {
        name: 'FilesChatBlock',
        mixins: [downloadFile],
        components: {
            BaseIcon
        },
        props: {
            fileProps: { type: Object, default: () => {} } 
        }
    }
</script>

<style lang="scss">
    .files-chat-block{
        border-radius: 5px;
        padding: 0.3rem;
        margin: 0;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
        background-color: none;
        display: flex;
        align-items: center;
        svg{
            stroke: #3391FF;
            margin-right: 6px;
        }
        .title{
            .name{
                color: #000000;
                margin-bottom: 0;
            }
            .size{
                font-size: 0.7rem;
                margin-top: -4px;
                color: #c4c4c4;
            }
        }
    }
</style>