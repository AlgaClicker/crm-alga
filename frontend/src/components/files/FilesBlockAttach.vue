<template>
    <div class="">
        <file
            v-for="file in fileProps" :key="file.hash"
            :fileProps="file"
            class="mb-1"
            @deleteFileEmit="deleteFile(file.hash)"
        />
        <label v-show="!showProgress" class="mt-1 attach-files__select"> 
            <svg width="20" height="18" viewBox="0 0 20 18">
                <path d="M9.74263 1.17157L2.31802 8.59619C0.56066 10.3535 0.56066 13.2028 2.31802 14.9601C4.07537 16.7175 6.92462 16.7175 8.68197 14.9601L17.5208 6.12134C18.6924 4.94974 18.6924 3.05024 17.5208 1.87869C16.3492 0.707104 14.4497 0.707104 13.2782 1.87869L4.43932 10.7175C3.85357 11.3033 3.85357 12.253 4.43932 12.8388C5.02512 13.4246 5.97488 13.4246 6.56068 12.8388L13.9853 5.41419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span> Прикрепить счет </span>
            <input  type="file" ref="file" @change="fileUpload()"/>
        </label>
        <div class="progress-bar" v-show="showProgress" id="output">
            <div class="progress-bar__header progress-bar-header">
                <base-icon iconProps="file" sizeProps="lg" />   
                <div class="progress-bar-header__title">
                    <small class="progress-bar-header__text" id="progress-bar-text"></small>
                    <small class="progress-bar-header__size" id="progress-bar-size"></small>
                </div>
            </div>
            <progress class="progress-bar__progress" id="progress-upload" max="100" value="0"> </progress>
        </div>
    </div>
</template>

<script>
    import { instance } from '@/services/instances.service';
    import File from '@/components/files/File';
    import BaseIcon from "@/components/elements/BaseIcon"

    export default {
        name: 'FilesBlockAttach',
        data(){
            return {
                showProgress: false,
            }
        },
        props: {
            fileProps: { type: Array, default: () => [] },
            idMaterialProps: { type: String, default: ""}
        },
        emits: ['deleteFileEmit', 'addFileEmit'],
        components: {
            File,
            BaseIcon
        },
        methods: {
            fileUpload(){
                //let output = document.getElementById('output');
                this.showProgress = true
                
                this.file = this.$refs.file?.files[0];
                let formData = new FormData();

                console.log(this.file.size)
                
                formData.append('upload', this.file);
                document.getElementById("progress-bar-text").textContent = this.file.name
                document.getElementById("progress-bar-size").textContent = this.file.size
                
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
                    console.log('then')
                    this.newFile = {
                        name: res.data.data[0].name, 
                        hash: res.data.data[0].hash,  
                        size: res.data.data[0].size,
                        message: res.data.message,
                    }
                    
                    console.log(this.file)

                    console.log('id material')
                    console.log(this.idMaterialProps)
                    
                    this.$emit('addFileEmit', {
                        file: this.newFile,
                        id: this.idMaterialProps,
                    })

                })
                .catch( function (err) {
                    console.log('pu puypu up')
                    console.log(err)
                    /*err = {
                        message: 'большой файл'
                    }*/
                    
                }).finally( () => {
                    this.showProgress = false
                })
            },
            deleteFile(hash){
                this.$emit('deleteFileEmit', {
                    hash: hash,
                    idMaterial: this.idMaterialProps,
                })
            }
        },
    }

</script>

<style lang="scss">
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
                &__title{
                    align-items: center;
                    display: flex;
                }
                &__text{
                    margin-left: 0.5rem;
                }
                &__size{
                    
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