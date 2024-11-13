<template>
    <div class="c-specification_material-column">
       <!--  <input :value="materialProps"> -->
       {{ materialProps }}
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    
    export default {
        name: 'SpecificationMaterialColumn',
        data(){
            return {
                value: ''
            }
        },
        props: {
            materialProps: { type: String, default: '' },
        },
        computed: {
            ...mapGetters({
                specificationMaterialList: 'specificationMaterialListGetter',
            }),
        },
        methods: {
            ...mapActions({ 
                materialsSearch: 'materialsSearchActions',
            }),
        }
    }

</script>