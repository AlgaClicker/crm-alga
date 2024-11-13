<template>
    <div class="c-switch-fixed">
        <div @click="switchToggle()" class="c-switch-fixed_wrapper">
            <button :class="{ 'c-button-off': !onProps}">
                <span v-html="iconUnlock"></span>
            </button>
            <button :class="{ 'c-button-on': onProps}">
                <span v-html="iconLoock"></span>
            </button>
        </div>
        <span v-if="onProps">
            Снять фиксацию
        </span>
        <span v-else>
            Зафиксировать
        </span>
    </div>
</template>

<script>
    import { svgs } from '@/utils/svgList'

    export default {
        name: "BaseSwitchFixed",
        data(){
            return {
                iconLoock: '',
                iconUnlock: ''
            }
        },
        props: {
            onTextProps: { type: String, default: 'включено' },
            offTextProps: { type: String, default: 'вычключено' },
            onProps: { type: Boolean, default: false}
        },
        emits: ['switchEmit'],
        methods: {
            switchToggle(){
                this.$emit('switchEmit', !this.onProps)
            }
        },
        mounted() {
            this.iconLoock = svgs.loock.md
            this.iconUnlock = svgs.unlock.md
        }
    }
</script>

<style>
</style>