<template>
    <div class="c-base-switch">
        <button @click="switchToggle()" :class="{ 'c-base-switch_button--active': onProps}">
            {{ onTextProps }}
        </button>
        <button @click="switchToggle()" :class="{ 'c-base-switch_button--active': !onProps}">
            {{ offTextProps }}
        </button>
    </div>
 </template>

<script>

export default {
    name: "BaseSwitch",
    props: {
        onTextProps: { type: String, default: 'включено' },
        offTextProps: { type: String, default: 'выключено' },
        onProps: { type: Boolean, default: false}
    },
    emits: ['switchEmit'],
    methods: {
        switchToggle(){
            this.$emit('switchEmit', !this.onProps)
        }
    }
}

</script>

<style>
</style>