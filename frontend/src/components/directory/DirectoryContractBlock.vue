<template>
    <div class="contract-block">
        <div @click="showContract()" class="contract-block__text">
            {{ ContractProps.number_sys }} ( {{ ContractProps.number }} ) 
        </div>  
        <div class="contract-block__btn" @click="removeContract()">
            <b-icon icon="x" scale="1"></b-icon>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'DirectoryContractBlock',
        props: {
            ContractProps: { type: Object, default: function () {} },
        },
        emits: ['removeContractEmit', 'showContractEmit'],
        methods: {
            removeContract(){
                this.$emit('removeContractEmit')
            },
            showContract(){
                this.$emit('showContractEmit')
            }
        }
    }
</script>

<style lang="scss">
    .contract-block{
        display: flex;
        cursor: pointer;
        margin-top: 0.5rem;
        &__text{
            color: #35395C !important;
        }
        &__btn{
            margin-right: 1rem;
            margin-top: -4px;
        }
        &__btn:hover{
            transition: 0.3s all;
            color: #3391FF;
        }
    }
</style>