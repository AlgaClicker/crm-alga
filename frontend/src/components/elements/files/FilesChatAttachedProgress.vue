<template>
    <div class="chat-file-progress-bar" id="chat-file-progress-bar">
        <div class="chat-file-progress-bar__header">
            <b-icon
                icon="file-earmark"
            >
            </b-icon> 
            <div class="chat-file-progress-bar__title">
                <small id="chat-file-progress-bar-text"></small>
            </div>
        </div>
        <progress 
            class="chat-file-progress-bar__progress" 
            id="progress-upload" 
            max="100" 
            value="0"
        ></progress>
    </div>
</template>

<script>
    export default {
        name: 'FilesChatAttachedProgress',
    }
</script>

<style lang="scss">
    .chat-file-progress-bar{
        max-width: 300px;
        min-width: 200px;
        &__header{
            display: flex;
        }
        &__title{
            display: flex;
            .text{
                align-items: center;
                text-overflow: ellipsis; 
                max-width: 33%;
                overflow: hidden;
                word-break: break-all;  
            }
            .size{
                align-items: center;
            }
        }
        &__progress{
            height: 8px;
            margin: auto;
            width: 98%;
        }
        &__progress::-webkit-progress-bar {
            fill: #0D4CD6;
            background-color: silver;
        }
    }
</style>