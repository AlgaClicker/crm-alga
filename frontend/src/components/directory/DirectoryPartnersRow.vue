<template>
    <div class="c-nested-table-body-row">
        <div class="c-nested-table-body-row_name-partner" :style="{ paddingLeft: levelProps * 34 + 'px !important'}">
            <input 
                class="с-checkbox" 
                type="checkbox"
                v-show="profleDirectoryEditAccess"
                :checked="partnersProps.selected"
                @change="directoryPartnersChecked(partnersProps)"
            >
            <span> {{ order }}. </span> <p> {{ partnersProps.name }} </p>
        </div>
        <div class="c-nested-table-body-row_col c-nested-table-body-row_fullname-partner"> {{ partnersProps.fullname }} </div>
        <div class="c-nested-table-body-row_col c-nested-table-body-row_address-partner"> {{ partnersProps.address }} </div>
        <div class="c-nested-table-body-row_col"> {{ partnersProps.created_at | dateFilter }} </div>
    </div>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    
    export default {
        name: 'DirectoryPartnersRow',
        data(){
            return {
                
            }
        },
        props: {
            partnersProps: { type: Object, default: function () {} },
            order: { type: String, default: '' },
            levelProps: { type: Number, default: 0 },
        },
        computed: {
            ...mapGetters({
                profleDirectoryEditAccess: 'profleDirectoryEditAccessGetter'
            }),
        },
        methods: {      
            ...mapActions({
                directoryPartnersChecked: 'directoryPartnersCheckedActions',
            }),      
        }
    }
</script>