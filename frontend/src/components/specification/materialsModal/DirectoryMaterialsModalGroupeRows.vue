<template>
   <div class="c-tabel-material-body-groupe">
        <div class="c-tabel-material-body-groupe-row">
            <div class="c-tabel-material-body-groupe-row_name" :style="{ paddingLeft: levelProps * 20 + 'px !important'}">
                <b-icon 
                    v-if="isVisible"
                    @click="toggleVisibleGroup"
                    class="c-caret-right"
                    icon="caret-down-fill"
                    font-scale="0.7" 
                ></b-icon>
                <b-icon 
                    v-else
                    class="c-caret-right"
                    icon="caret-right-fill"
                    font-scale="0.7"
                    @click="toggleVisibleGroup"
                ></b-icon>

                <span> {{ order }}.</span> {{ groupeProps.name }}
            </div>
        </div>
        <div 
            class="c-nested-table-body" 
            v-show="isVisible" 
            v-for="(item, key) in materialsGroupeComputed" :key="key"
        >
            <DirectoryMaterialsModalGroupeRows 
                v-if="item.isGroup"
                :groupeProps="item" 
                :unitsProps="unitsProps"
                :order="order + '.' + String(Number(key + 1))" 
                :levelProps="levelProps + 1"
            />
            <DirectoryMaterialsModalRows
                v-else
                :materialProps="item" 
                :order="order + '.' + String(Number(key + 1))"
                :levelProps="levelProps + 1"
                :unitsProps="unitsProps"
                v-on:deleteElement="removeItem(item)"
            />
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from "vuex";

    import DirectoryMaterialsModalGroupeRows from '@/components/specification/materialsModal/DirectoryMaterialsModalGroupeRows'
    import DirectoryMaterialsModalRows from '@/components/specification/materialsModal/DirectoryMaterialsModalRows'
    
    export default {
        name: 'DirectoryMaterialsModalGroupeRows',
        components: {
            DirectoryMaterialsModalGroupeRows,
            DirectoryMaterialsModalRows,
        },
        data(){
            return {
                isVisible: false,
                options: {
                    pagginate: {
                        page: 1,
                        limit: 50,
                        pages: 8
                    },
                    orderBy: {
                        name: "ASC"
                    }
                },
                groupe: {
                    id: '',
                    parent: '',
                    name: '',
                    description: '',
                    isGroup: false,
                },
                materials: [],
                optionsUnits: [],
                isVisibleMenu: false,
                isUpdate: false,
            }
        },
        props: {
            groupeProps: { type: Object, default: function () {} },
            unitsProps: { type: Array, default: function () {} },
            order: { type: String, default: "" },
            levelProps: { type: Number, default: 0 },
        },
        emits: ['deleteElement'],
        computed: {
            ...mapGetters({
                directoryMaterialsModalGroupeList: 'directoryMaterialsModalGroupeListGetter',
                directoryMaterialsModalSelectedList: 'directoryMaterialsModalSelectedListGetter',
                materialsError: 'materialsErrorGetter',
                directoryMaterialsIsModalSpec: 'directoryMaterialsIsModalSpecGetter',
            }),
            materialsGroupeComputed(){
                if(this.directoryMaterialsModalGroupeList != []){
                    return this.directoryMaterialsModalGroupeList.filter(item => item.parent.id == this.groupeProps?.id)
                }
                else{
                    return []
                }
            }
        },
        methods: {
            ...mapActions({ 
                setCurrentGroupeUid: "setCurrentGroupeUidActions",
                setEditMaterial: "setEditMaterialActions",
                directoryMaterialsModalSet: 'directoryMaterialsModalSetActions',
                materialsSetEdit: "materialsSetEditActions",
                directoryMaterialsModalChecked: 'directoryMaterialsModalCheckedActions',
            }),  
            async toggleVisibleGroup(){
                
                if(this.isVisible == false){
                    await this.directoryMaterialsModalSet(this.groupeProps.id)

                    this.isVisible = !this.isVisible
                }else{
                    this.isVisible = !this.isVisible
                }
            },
            showMenu(){
                this.isVisibleMenu = !this.isVisibleMenu
            },
        },

    }
</script>

<style>
</style>