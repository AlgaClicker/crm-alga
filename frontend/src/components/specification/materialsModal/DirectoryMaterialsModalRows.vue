<template>
    <div class="c-tabel-material-body-row">
        <div class="c-tabel-material-body-row_name" :style="{ paddingLeft: levelProps * 24 + 'px !important'}">
            <input 
                class="с-checkbox" 
                type="checkbox"
                :checked="material.selected"
                @change="directoryMaterialsModalChecked(material)"
            >
            <span> {{ order }}.</span> {{ material.name }}
        </div>
        <div class="c-tabel-material-body-row_col"> {{ material.code }} </div>
        <div class="c-tabel-material-body-row_col"> {{ material.vendor }} </div>
        <div class="c-tabel-material-body-row_col"> {{ material.articul }} </div>
        <div class="c-tabel-material-body-row_col"> {{ material.unit?.title }} </div>
    </div>
</template>

<script>
    import { mapActions } from "vuex";

    export default {
        name: 'DirectoryMaterialsModalRows',
        data(){
            return {
                material: {
                    name: this.materialProps.name,
                    description: this.materialProps.description,
                    code: this.materialProps.code,
                    vendor: this.materialProps.vendor,
                    articul: this.materialProps.articul,
                    isGroup: this.materialProps.isGroup,
                    selected: this.materialProps.selected,
                    unit: this.materialProps.unit?.title,
                },
                isVisibleMenu: false,
                isUpdate: false,
            }
        },
        props: {
            materialProps: { type: Object, default: function () {} },
            unitsProps: { type: Array, default: function () {} },
            order: { type: String, default: '' },
            levelProps: { type: Number, default: 0 },
        },
        watch: {
            materialProps: {
                immediate: true, 
                handler(){
                    this.material = {
                        id: this.materialProps.id,
                        name: this.materialProps.name,
                        description: this.materialProps.description,
                        code: this.materialProps.code,
                        selected: this.materialProps.selected,
                        vendor: this.materialProps.vendor,
                        articul: this.materialProps.articul,
                        isGroup: this.materialProps.isGroup,
                        parent: this.materialProps.parent,
                        unit: this.materialProps.unit,
                    }
                }
            }
        },
        methods: {
            ...mapActions({ 
                directoryMaterialsModalChecked: 'directoryMaterialsModalCheckedActions'
            }),  
            showMenu(){
                this.isVisibleMenu = !this.isVisibleMenu
            },
            showModalEditMaterial(){
                if(typeof this.materialProps.parent === 'undefined'){
                    this.materialsSetCurrentGroupeUid('')
                }else{
                    this.materialsSetCurrentGroupeUid(this.materialProps.parent.id)
                }
                this.materialsSetEdit(this.materialProps)
            },
        },
        mounted(){
            this.isVisibleMenu = false
            this.isUpdate = false
        },
    }
</script>

<style>
</style>