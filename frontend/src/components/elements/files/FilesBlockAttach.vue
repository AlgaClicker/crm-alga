<template>
    <div class="px-2 mt-2 mb-2">
        <file
            v-for="file in fileProps" :key="file.hash"
            :fileProps="file"
            class="mb-1"
            @deleteFileEmit="deleteFile(file.hash)"
        />
        <label v-show="!progressUpload & !errUpload" class="mt-2 attach-files__select"> 
            <svg width="20" height="18" viewBox="0 0 20 18">
                <path d="M9.74263 1.17157L2.31802 8.59619C0.56066 10.3535 0.56066 13.2028 2.31802 14.9601C4.07537 16.7175 6.92462 16.7175 8.68197 14.9601L17.5208 6.12134C18.6924 4.94974 18.6924 3.05024 17.5208 1.87869C16.3492 0.707104 14.4497 0.707104 13.2782 1.87869L4.43932 10.7175C3.85357 11.3033 3.85357 12.253 4.43932 12.8388C5.02512 13.4246 5.97488 13.4246 6.56068 12.8388L13.9853 5.41419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span> {{ titleProps }} </span>
            <input  type="file" ref="file" @change="fileUpload()"/>
        </label>
        <div class="progress-bar" v-show="progressUpload" id="output">
            <div class="progress-bar__header progress-bar-header">
                <base-icon iconProps="file" sizeProps="lg" />   
                <div class="progress-bar-header__title">
                    <div class="progress-bar-header__text" id="progress-bar-text"></div>
                    <div class="progress-bar-header__size" id="progress-bar-size"></div>
                </div>
            </div>
            <progress class="progress-bar__progress" id="progress-upload" max="100" value="0"> </progress>
        </div>
        <div v-show="errUpload" class="upload-error mt-2">
            <div class="close" @click="closeError"> 
                <div class="close__icon">
                    <base-icon iconProps="x" sizeProps="sm" />
                </div>
            </div>
            <div class="message">
                <base-icon iconProps="error" sizeProps="md" />
                Ошибка загрузки
            </div>
        </div>
    </div>
</template>

<script>
    import { instance } from '@/services/instances.service'
    import { size } from "@/utils/convert"
    import File from '@/components/elements/files/File'
    import BaseIcon from "@/components/elements/BaseIcon"

    export default {
        name: 'FilesBlockAttach',
        data(){
            return {
                progressUpload: false,
                errUpload: false
            }
        },
        props: {
            fileProps: { type: Array, default: () => [] },
            idMaterialProps: { type: String, default: ""},
            titleProps: { type: String, default: "Прикрепить счет" }
        },
        emits: ['deleteFileEmit', 'addFileEmit', 'setFlagEmit', 'resetFlagEmit'],
        components: {
            File,
            BaseIcon
        },
        methods: {
            fileUpload(){
                //let output = document.getElementById('output');
                this.progressUpload = true
                
                this.file = this.$refs.file?.files[0];
                let formData = new FormData();

                formData.append('upload', this.file);
                document.getElementById("progress-bar-text").textContent = this.file.name
                document.getElementById("progress-bar-size").textContent = size(this.file.size)
                
                var config = {
                    headers: {'Content-Type': 'multipart/form-data'},
                    onUploadProgress: function(progressEvent) {
                        var percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                        document.getElementById("progress-upload").value = percentCompleted;
                    }
                };
                
                this.$emit('setFlagEmit')
                
                instance
                .post(`/api/v1/services/file/upload/`, formData, config)
                .then( (res) => {
                    
                    this.newFile = {
                        name: res.data.data[0].name, 
                        hash: res.data.data[0].hash,  
                        size: res.data.data[0].size,
                        message: res.data.message,
                    }

                    this.$emit('addFileEmit', {
                        file: this.newFile,
                        id: this.idMaterialProps,
                    })
                    this.progressUpload = false
                })
                .catch( () => {

                    this.errUpload = true
                    this.progressUpload = false

                    //console.log(err)
                    /*err = {
                        message: 'большой файл'
                    }*/
                    
                }).finally( () => {
                    this.$emit('resetFlagEmit')
                })

            },
            deleteFile(hash){
                this.$emit('deleteFileEmit', {
                    hash: hash,
                    idMaterial: this.idMaterialProps,
                })
            },
            closeError(){
                this.errUpload = false
                this.progressUpload = false
            }
        },
    }

</script>
 
<style lang="scss">
    .upload-error{
        border: 1px solid #e7313d;
        border-radius: 5px;
        background-color: rgba(243, 94, 104, 0.2);
        padding: 0.2rem;
        padding-bottom: 0.8rem;
        padding-top: 0.2rem;
        max-width: 510px;
        min-width: 200px;
        .close{
            width: 100%;
            display: flex;
            &__icon{
                margin-left: auto;
                svg{
                    fill: #915d60;
                    cursor: pointer;
                    margin-left: auto;
                }
            }
        }
        .message{
            align-items: center;
            color: #e7313d;
            display: flex;
            margin-top: -7px;
            svg{
                fill: #e7313d;
                margin-left: 6px;
                margin-right: 6px;
                stroke: none;
            }
        }
    }
    .attach-files{
        svg{
            display: block !important;
            stroke: #C7C7C8;
            margin-right: 6px;
            stroke: #C7C7C8;
            margin-left: auto;
            margin-top: 3px;
            margin-bottom: 3px;
        }
    }
    .progress-bar{
        border-radius: 6px;
        border: 1px solid #F3F3F3;
        padding: 1rem;
        padding: 0.4rem;
        padding-left: 0.8rem;
        &__header{
            align-items: center;
            display: flex;
            .progress-bar-header{
                svg{
                    margin-left: 4px;
                }
                display: flex;
                &__title{
                    align-items: center;
                    display: flex;
                    width: 100%;
                }
                &__text{
                    margin-left: 0.5rem;
                    color: #777A80;
                    max-width: 90%;
                    margin-right: 1rem;
                    text-overflow: ellipsis; 
                    white-space: nowrap; 
                    overflow: hidden;
                }
                &__size{
                    color: #c4c4c4;
                    font-size: 0.7rem;
                    margin-top: -2px;
                    margin-left: auto;
                }   
            }
        }
        &__progress{
            height: 8px;
            margin: auto;
            width: 100%;
        }
        &__progress::-webkit-progress-bar {
            fill: #65acee !important;
            background-color: silver;
        }
    }
</style>