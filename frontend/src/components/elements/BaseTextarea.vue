<template>
    <textarea :value="inputVal"  @input="resizeTextarea($event)" rows="1" class="c-textarea"></textarea>
</template>

<script>
    export default {
        name: 'BaseTextarea',
        props: {
            valueProps: { type: String, default: '' },
        },
        computed: {
            inputVal: {
                get() {
                    return this.valueProps;
                },
                set(val) {
                    this.$emit('input', val);
                }
            }
        },
        methods: {
            resizeTextarea(event){  
                this.$emit('input', event.target.value);
                setTimeout(function() {
                    event.target.style.cssText = 'height:auto; padding:0';
                    event.target.style.cssText = 'height:' + event.target.scrollHeight + 'px';
                }, 1);
                
            }
        }
    }
</script>

<style lang="scss">
    .c-textarea{
        overflow: hidden;
        border: none !important;
        width: 100%;
        height: 100%;
        resize: none;
        -webkit-box-sizing: border-box; /* Сафари и Хром будут рады */
        -moz-box-sizing: border-box;    /* Firefox тоже не упустит */
    }

    .c-textarea:focus{
        outline: none !important;
    }
</style>