<template>
   <div class="c-tabel-material-body-groupe">
        <div class="c-tabel-material-body-groupe-row">
            <div class="c-tabel-material-body-groupe-row_name" :style="{ paddingLeft: levelProps * 20 + 'px !important'}">
                <b-icon 
                    v-if="isVisible"
                    @click="toggleVisibleGroup"
                    class="caret-right"
                    icon="caret-down-fill"
                    font-scale="0.7" 
                ></b-icon>
                <b-icon 
                    v-else
                    class="caret-right"
                    icon="caret-right-fill"
                    font-scale="0.7"
                    @click="toggleVisibleGroup"
                ></b-icon>
                <input 
                    class="с-checkbox" 
                    type="checkbox"
                    :checked="groupeProps.selected"
                    v-show="profleDirectoryEditAccess"
                    @change="directoryMaterialsChecked(groupeProps)"
                >
                <span> {{ order }}. </span> <p> {{ groupeProps.name }} </p>
            </div>
        </div>
        <div class="c-nested-table-body" v-show="isVisible" style="border: 0;" v-for="(item, key) in materialsGroupe" :key="key">
            <DirectoryMaterialsGroupeRows 
                v-if="item.isGroup"
                :groupeProps="item" 
                :unitsProps="unitsProps"
                :order="order + '.' + String(Number(key + 1))" 
                :levelProps="levelProps + 1"
            />
            <DirectoryMaterialsRows 
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

    import DirectoryMaterialsGroupeRows from '@/components/directory/DirectoryMaterialsGroupeRows'
    import DirectoryMaterialsRows from '@/components/directory/DirectoryMaterialsRows'
    
    export default {
        name: 'DirectoryMaterialsGroupeRows',
        components: {
            DirectoryMaterialsGroupeRows,
            DirectoryMaterialsRows,
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
                materialsGroupeList: 'materialsGroupeListGetter',
                materialsSelectedList: 'materialsSelectedListGetter',
                materialsError: 'materialsErrorGetter',
                directoryMaterialsIsModalSpec: 'directoryMaterialsIsModalSpecGetter',
                profleDirectoryEditAccess: 'profleDirectoryEditAccessGetter'
            }),
            materialsGroupe(){
                if(this.materialsGroupeList != []){
                    return this.materialsGroupeList.filter(item => item.parent.id == this.groupeProps?.id)
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
                materialsGet: 'materialsGetActions',
                materialsSetEdit: "materialsSetEditActions",
                directoryMaterialsChecked: 'directoryMaterialsCheckedActions',
            }),  
            async toggleVisibleGroup(){
                
                if(this.isVisible == false){
                    await this.materialsGet(this.groupeProps.id)

                    this.isVisible = !this.isVisible
                }else{
                    this.isVisible = !this.isVisible
                }
            },
            async toggleAdd(){
                if(this.isVisible == false){
                    this.isVisible = true
                    const params = {
                        parent: this.groupeProps.id,
                        options: this.options
                    }
                    await this.materialsGet(params)
                }
            },
            showMenu(){
                this.isVisibleMenu = !this.isVisibleMenu
            },
        },

    }
</script>

<style lang="scss">
    .caret-right{
        margin-right: 0.82rem;
        cursor: pointer;
        fill: #B2B2B2;
    }
</style>