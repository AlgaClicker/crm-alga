<template>
    <div class="file">
        <div>
            <base-icon iconProps="file" sizeProps="lg" />
        </div>
        <div @click="view(fileProps.hash, fileProps.name)" class="title">
            <div class="name"> {{ fileProps.name }} </div>
            <div class="size"> {{ fileProps.size | sizeFilter }} </div>
        </div>
        <div class="left-container">
            <b-icon 
                v-show="deleteProps" 
                icon="trash" 
                @click="deleteFile()"
            ></b-icon>
            <b-icon 
                v-show="deleteProps" 
                icon="download" 
                @click="downloadFile(fileProps.hash, fileProps.name)"
            ></b-icon>
        </div>
    </div>
</template>

<script>
    import { fileDownload } from '@/services/files.service.js';
    import downloadFile from '@/mixins/files.mixins'
    import BaseIcon from "@/components/elements/BaseIcon"
    import Vue from 'vue'

    export default {
        name: 'File',
        mixins: [downloadFile],
        components: {
            BaseIcon
        },
        props: {
            fileProps: { type: Object, default: () => {} },
            deleteProps: { type: Boolean, default: () => false }
        },
        emits: ['deleteFileEmit'],
        methods: {
            deleteFile(){
                this.$emit('deleteFileEmit', this.fileProps)
            },
            view(hash, name){
                console.log(hash)
                console.log(name)

                fileDownload(hash).then(res => {
                    if (!res.ok) {
                        throw new Error('Ошибка сети');
                    }
                    return res.blob();
                }).then(blob => {
                    const pdfUrl = URL.createObjectURL(blob);
                    window.open(pdfUrl, '_blank');
                })
                .catch(error => {
                    console.log(error)
                    Vue.notify({
                        group: 'error',
                        title: 'Ошибка',
                        type: 'error',
                        text: 'Ошибка при загрузке'
                    })
                });
            }
        }
    }
</script>

<style lang="scss">
    .file{
        align-items: center;
        border-radius: 6px;
        border: 1px solid #F3F3F3;
        cursor: pointer;
        display: flex;
        padding: 0.4rem;
        padding-left: 0.8rem;
        width: 100%;
        max-width: 510px;
        min-width: 200px;
        svg{
            display: block !important;
            stroke: #C7C7C8;
            margin-right: 6px;
            stroke: #C7C7C8;
            margin-left: auto;
            margin-top: 3px;
            margin-bottom: 3px;
        }
        .title{
            margin-left: 0.4rem;
            width: 80%;
            .name{
                font-size: 0.9rem;
                color: #777A80;
                max-width: 90%;
                margin-right: 1rem;
                text-overflow: ellipsis; 
                white-space: nowrap; 
                overflow: hidden;
            }
            .size{
                font-size: 0.8rem;
                margin-top: -2px;
                color: #c4c4c4;
            }
        }
        .left-container{
            margin-left: auto;
        }
    }
    .file:hover{
        transition: 0.6s all;
        border-color: #e6e8ec;
        svg:hover{
            transition: 0.5s all;
            stroke: #3391FF;
        }
    }

    @media (max-width: 1000px){
        .title{
            max-width: 70%;
        }
        .name{
            max-width: 90% !important;
        }
    }
</style>