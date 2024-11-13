<template>
    <div class="upload-modal">
        <div class="upload-modal__select">
            <label for="input-modal-upload">  
                Выберите файл  
            </label>
            <input name="input-modal-upload" id="input-modal-upload" type="file" ref="file" @change="fileUpload()"/>
            <hr v-show="files.length != 0" />
        </div>
        <div v-show="showProgress" id="output">
            <div class="progress-bar">
                <div class="progress-bar__header">
                    <base-icon iconProps="file" sizeProps="lg" /> 
                    <div class="progress-bar__title">
                        <small id="file-upload-text"></small>
                        <small id="file-upload-size"></small>
                    </div>
                </div>
                <progress class="progress-bar__progress" id="progress-upload" max="100" value="0"> </progress>
            </div>
        </div>
        <div class="upload-modal__error mt-2" v-show="errUpload">
            <base-icon iconProps="error" sizeProps="md" />
            Ошибка загрузки
        </div>
        <div v-show="files.length != 0" class="upload-modal__files-list">
            <div class="file" v-for="(item, key) in files" :key="key">
                <div>
                    <base-icon iconProps="file" sizeProps="lg" />
                </div>
                <div class="file__text">
                    {{ item.name }}
                </div>
                <div class="file__size">
                    {{ item.size | sizeFilter}}
                </div>
                <div class="file__delete" @click="removeFile(item.hash)">
                    <base-icon iconProps="trash" sizeProps="md" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { instance } from '@/services/instances.service';
    import BaseIcon from "@/components/elements/BaseIcon"
    
    export default {
        name: 'FileUploadModal',
        data(){
            return {
                files: [],
                errUpload: "",
                showProgress: false,
            }
        },
        components: {
            BaseIcon
        },
        emits: ['addFileEmit', 'removeFileEmit'],
        methods: {
            async fileUpload(){ 

                this.showProgress = true
                this.errUpload = null

                this.file = this.$refs.file?.files[0];
                let formData = new FormData();
                formData.append('upload', this.file);
                document.getElementById("file-upload-text").textContent = this.file.name;

                var config = {
                    headers: {'Content-Type': 'multipart/form-data'},
                    onUploadProgress: function(progressEvent) {
                        var percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                        document.getElementById("progress-upload").value = percentCompleted;
                    }
                };

                instance
                .post(`/api/v1/services/file/upload/`, formData, config)
                .then( (res) => {
                    let file = {
                        name: res.data.data[0].name,         
                        hash: res.data.data[0].hash,          
                        size: res.data.data[0].size,         
                        message: res.data.message      
                    }
                    this.files.push(file)
                    this.$emit( 'addFileEmit', this.$event, file)
                })
                .catch(() => {
                    this.showProgress = false
                    this.errUpload = 'err'
                }).finally( () => {
                    this.showProgress = false
                })
            },
            removeFile(hash){
                this.files = this.files.filter(item => item.hash != hash)
                this.$emit('removeFileEmit', hash)
            },
            closeError(){
                this.errUpload = null
            }
        },
    }
</script>

<style lang="scss">
    .upload-modal{
        background-color: #F8FAFB;
        padding: 1rem;
        &__select{
            background-color: #F8FAFB;
            label{
                background-color: #FEFEFF;
                border: 2px solid #EEF1F3;
                border-radius: 6px;
                padding: 0.2rem;
                font-size: 0.8rem;
                cursor: pointer;
                color: #343243;
                text-decoration: none;
            }
            input{
                opacity: 0;
                position: absolute;
                z-index: -1;
            }
            hr{
                background-color: #387ee7 !important;
                color: #adadad !important;
            }
        }
        .progress-bar{
            border-radius: 10px;
            padding: 0.4rem;
            padding-bottom: 0.4rem;
            margin-top: 1rem;
            display: flex;
            margin-right: 1rem;
            &__header{
                display: flex;
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
            &__title{
                width: 100%;
                padding-top: 0.1rem;
                #file-upload-text{
                    font-size: 0.9rem;
                    font-weight: 600;
                    text-overflow: ellipsis; 
                    white-space: nowrap; 
                    overflow: hidden; 
                    width: 80%;
                    color: #293B4C;
                    margin-right: auto;
                }
                #file-upload-size{
                    color: silver;
                    white-space: nowrap;
                    font-size: 0.9rem;
                    float: right;
                }
            }
            &__progress{
                height: 8px;
                margin: auto;
                width: 100%;
            }
            &__progress::-webkit-progress-bar {
                fill: #0D4CD6;
                background-color: silver;
            }
        }
 
        &__files-list{
            .file{
                display: flex;
                align-items: center;
                border: none;
                padding: 0.4rem;
                svg{
                    stroke: #C7C7C8;
                    margin-right: 1rem;
                }
                &__text{
                    color: #293B4C;
                    max-width: 50%;
                    margin-right: 1rem;
                    text-overflow: ellipsis; 
                    white-space: nowrap; 
                    overflow: hidden;
                }
                &__size{
                    background-color: #E0E7EB;
                    border-radius: 6px;
                    padding: 0.1rem;
                    padding-left: 0.4rem;
                    padding-right: 0.4rem;
                    font-size: 0.9rem;
                }
                &__delete{
                    margin-left: auto;
                    svg{
                        fill: #B3BAC5;
                        cursor: pointer;
                    }
                    svg:hover{
                        transition: 1s all;
                        fill: #979ca4;
                    }
                }
            }
            .file:hover{
                border: none;
            }
        }

        .upload-modal__error{
            display: flex;
            align-items: center;
            color: #e7313d;
            svg{
                fill:#e7313d;
                margin-right: 6px;
            }
        }
    }
</style>