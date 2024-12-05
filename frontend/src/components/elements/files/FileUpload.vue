<template>
    <div class="upload">
        <label v-show="!showProgress & !errUpload" for="upload-file" class="mt-3 upload__file"> 
            <base-icon iconProps="link" sizeProps="md" />
            <span> Прикрепить файл </span>
            <input 
                name="input-upload" 
                id="upload-file" 
                type="file" 
                ref="file" 
                @change="fileUpload($event)"
            />
        </label>
        <div class="upload__error mt-2" v-show="errUpload">
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
        <div v-show="showProgress" class="progress-bar" id="output">
            <div class="progress-bar__header progress-bar-header">
                <base-icon iconProps="file" sizeProps="lg" />   
                <div class="progress-bar-header__title">
                    <div class="progress-bar-header__text" id="progress-bar-text"></div>
                    <div class="progress-bar-header__size" id="progress-bar-size"></div>
                </div>
            </div>
            <progress class="progress-bar__progress" id="progress-upload" max="100" value="0"> </progress>
        </div>
    </div>
</template>

<script>
    import { size } from '@/utils/convert'
    import { instance } from '@/services/instances.service'
    import BaseIcon from "@/components/elements/BaseIcon"
    
    export default {
        name: 'FileUpload',
        data(){
            return {
                showProgress: false,
                errUpload: "",
                file: {}
            }
        },
        components: {
            BaseIcon
        },
        emits: ['addFileEmit'],
        methods: {
            fileUpload(e){

                this.showProgress = true
                
                this.file = e.target.files[0];

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

                instance
                .post(`/api/v1/services/file/upload/`, formData, config)
                .then( (res) => {
                    let file = {
                        name: res.data.data[0].name, 
                        hash: res.data.data[0].hash,  
                        size: res.data.data[0].size,
                        message: res.data.message
                    }

                    this.$emit( 'addFileEmit',  this.$event, file)
                    this.errUpload = ''
                    this.showProgress = false
                })
                .catch(() => {
                    this.showProgress = false
                    this.errUpload = 'err'
                })
            },
            fileUploadDrop(e){
                var filesUpload = []

                e.preventDefault();

                filesUpload = Array.from(e.dataTransfer.files);

                let formData = new FormData();
                formData.append('upload', filesUpload[0]);
                
                document.getElementById("progress-bar-text").textContent = this.file.name
                document.getElementById("progress-bar-size").textContent = size(this.file.size)
                
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

                    this.$emit( 'addFileEmit',  this.$event, file)
                    this.errUpload = ''
                    this.showProgress = false
                })
                .catch(() => {
                    this.showProgress = false
                    this.errUpload = 'err'
                })
            },
            closeError(){
                this.errUpload = null
            }
        },
        mounted(){
            const dropZone = document.querySelector(".upload")
            
            //let output = ''
            let hoverClassName = 'hover';
            if(dropZone){         
                dropZone.addEventListener("dragenter", function(e) {
                    console.log('dragenter')
                    e.preventDefault();
                    dropZone.classList.add(hoverClassName);
                });
                
                dropZone.addEventListener("dragover", function(e) {
                    console.log('dragover')
                    e.preventDefault();
                    dropZone.classList.add(hoverClassName);
                });

                dropZone.addEventListener("dragleave", function(e) {
                    console.log('dragleave')
                    e.preventDefault();
                    dropZone.classList.remove(hoverClassName);
                });
                
                dropZone.addEventListener("drop", (e) => {
                    e.preventDefault()
                    //this.fileUpload(e)
                    
                    this.fileUploadDrop(e)
                });
            }
        }
    }
</script>

<style lang="scss">
    .upload{
        &__file{
            cursor: pointer;
            display: flex;
            align-items: center;
            input{
                display: none;
                background: unset;
                border-color: inherit;
                border-radius: 0;
                border-width: 0;
                font-size: unset;
                line-height: inherit;
                padding: 0;
                cursor: pointer;
            }
            label{
                cursor: pointer;
                text-align: center;
                margin-top: 0.2rem;
                font-size: 1rem;
            }
            svg{
                cursor: pointer;
                margin-right: 0.3rem;
                margin-left: 0.3rem;
                stroke: #3391FF;
                fill:  #ffffff;
            }
            svg:hover{
                transition: 0.6s all;
                stroke: #2981e6;
            }
            span{
                margin-top: 0.3rem;
                font-size: 0.9rem;
                color: #3391FF;
            }
        }
        &__error{
            border: 1px solid #e7313d;
            border-radius: 5px;
            background-color: rgba(243, 94, 104, 0.2);
            padding: 1rem;
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
                }
            }
        }
        .progress-bar{
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #F3F3F3;
            padding: 1rem;
            padding: 0.4rem;
            padding-left: 0.8rem;
            max-width: 510px;
            min-width: 200px;
            &__header{
                align-items: center;
                display: flex;
                svg{
                    margin-left: 4px;
                    display: block !important;
                    stroke: #C7C7C8;
                    margin-right: 6px;
                    stroke: #C7C7C8;
                    margin-left: auto;
                    margin-top: 3px;
                    margin-bottom: 3px;
                }
                .progress-bar-header{
                    svg{
                        margin-left: 4px;
                    }
                    &__title{
                        align-items: center;
                        display: flex;
                        width: 100%;
                    }
                    &__text{
                        font-size: 0.9rem;
                        color: #777A80;
                        max-width: 90%;
                        margin-right: 1rem;
                        text-overflow: ellipsis; 
                        white-space: nowrap; 
                        overflow: hidden;
                    }
                    &__size{
                        color: #c4c4c4;
                        font-size: 0.8rem;
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
    }

    // как переать евент в драгон дроп

</style>