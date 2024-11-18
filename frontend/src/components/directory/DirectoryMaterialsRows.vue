<template>
    <div class="c-tabel-material-body-row">
        <div class="c-tabel-material-body-row_name" :style="{ paddingLeft: levelProps * 34 + 'px !important'}">
            <input 
                class="с-checkbox" 
                type="checkbox"
                v-show="profleDirectoryEditAccess"
                :checked="material.selected"
                @change="directoryMaterialsChecked(material)"
            >
            <span> {{ order }}. </span> <p> {{ material.name }} </p>
        </div>
        <div class="c-tabel-material-body-row_col"> {{ material.code }} </div>
        <div class="c-tabel-material-body-row_col"> {{ material.vendor }} </div>
        <div class="c-tabel-material-body-row_col"> {{ material.articul }} </div>
        <div class="c-tabel-material-body-row_col"> {{ material.unit?.title }} </div>
        <div class="c-tabel-material-body-row_col">
            <div @click="showInfo" v-b-modal.directory-materials-modal-info class="c-label-info">
                <span v-html="iconInfo"></span>
                <div> Информация </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from "vuex";
    import { svgs } from "@/utils/svgList";

    export default {
        name: 'DirectoryMaterialsRows',
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
                iconInfo: ""
            }
        },
        emits: ['selectMatrialEmit'],
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
                        vendor: this.materialProps.vendor,
                        articul: this.materialProps.articul,
                        isGroup: this.materialProps.isGroup,
                        selected: this.materialProps.selected,
                        parent: this.materialProps.parent,
                        unit: this.materialProps.unit,
                    }
                }
            }
        },
        computed: {
            ...mapGetters({
                profleDirectoryEditAccess: 'profleDirectoryEditAccessGetter'
            }),
        },
        methods: {
            ...mapActions({ 
                directoryMaterialsChecked: 'directoryMaterialsCheckedActions',
                directoryMaterialsSelectForInfo: 'directoryMaterialsSelectForInfoActions'
            }),  
            showMenu(){
                this.isVisibleMenu = !this.isVisibleMenu
            },
            showInfo(){
                this.directoryMaterialsSelectForInfo(this.materialProps)
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
            this.iconInfo = svgs.info.md
            this.isUpdate = false
        },
    }
</script>

<style>
</style>
