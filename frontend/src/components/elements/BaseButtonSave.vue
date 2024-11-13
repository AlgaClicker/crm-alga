<template>
    <div>
        <button
            :class="{ 'c-button-save-sm': saveIsProps, 'c-button-save-sm--success': !saveIsProps }" 
            :disabled="saveIsProps"
            @click="$emit('click', $event)"
        >
            <div v-if="loadingProps">
                <svg class="spinner_aj0A" width="26" height="26" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
            </div>
            <template v-else>
                <span v-html="iconSave"></span>
                {{ textProps }}
            </template>
        </button>
    </div>
</template>
<script>
    import { svgs } from '@/utils/svgList'

    export default {
        name: 'BaseButtonSave',
        data(){
            return {
                iconSave: ''
            }
        },
        props: {
            textProps: { type: String, default: 'text' },
            loadingProps: { type: Boolean, default: false},
            saveIsProps: { type: Boolean, default: false}
        },
        mounted() {
            this.iconSave = svgs.save.sm
        }
    }
</script>   
