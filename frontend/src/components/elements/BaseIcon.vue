<template>
    <div>
        <span v-html="icon"></span>
    </div>
</template>

<script>
    import { svgs } from '@/utils/svgList'

    export default {
        name: 'BaseIcon',
        data(){
            return {
                icon: ""
            }
        },
        props: {
            iconProps: { type: String, default: "x" },
            sizeProps: { type: String, default: "md" }
        },
        mounted(){
            this.icon = svgs[this.iconProps][this.sizeProps]
        }
    }
</script>
