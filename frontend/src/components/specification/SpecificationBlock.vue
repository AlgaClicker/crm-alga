<template>
    <div class="specification-block">
        <div class="specification-block__text">
            {{ specificationProps.name }}
        </div>
        <div class="specification-block__object">
            ( {{ specificationProps.objectName }} )
        </div>
        <div class="specification-block__btn" @click="removeSpec()">
            <b-icon icon="x" scale="1"></b-icon>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'SpecificationBlock',
        props: {
            specificationProps: { type: Object, default: function () {} },
        },
        emits: ['removeSpecEmit'],
        methods: {
            removeSpec(){
                this.$emit('removeSpecEmit')
            }
        }
    }
</script>

<style lang="scss">
    .specification-block{
        display: flex;
        cursor: pointer;
        margin-top: 0.5rem;
        &__text{
            color: #35395C !important;
        }
        &__object{
            margin-left: 0.5rem;
            color: #3391FF;
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