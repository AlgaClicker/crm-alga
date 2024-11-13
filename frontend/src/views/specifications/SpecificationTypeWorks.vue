<template>
    <div class="s">
        <p> Работы </p>
    </div>
</template>

<script>
    export default {
        name: 'SpecificationTypeWorks',
        data() {
            return {

            }
        },
        props: {    
            specificationIZMList: { type: Array, default: () => [] },
            selectIZMId: { type: String, default: () => "" }
        },
    }
</script>

<style>
</style>