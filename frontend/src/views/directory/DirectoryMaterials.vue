<template>
    <div class='l-default'>
        <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
        <main>
            <div class='c-panel'>
                <input-search 
                    :searchActionsNameProps="'directoryMaterialsSearchActions'"
                    :setListActionsNameProps="'materialsGetActions'"
                /> 
                <button v-if="directoryMaterialsSelectedList.length == 0 | directoryMaterialsSelectedList.length > 1" v-show="profleDirectoryEditAccess" v-b-modal.directory-materials-modal-add @click="setGroupeUid"  class="c-button-add">
                    <b-icon icon="plus-lg" scale="1"></b-icon>
                    <span>
                        Создать 
                    </span>
                </button>
                <button 
                    v-else 
                    v-show="profleDirectoryEditAccess"
                    @click="setGroupeUid" 
                    v-b-modal.directory-materials-modal-add 
                    class="c-button-add"
                >
                    <b-icon icon="plus-lg" scale="1"></b-icon>
                    <span>
                        Добавить в группу
                    </span>
                </button>
            </div>
            <div class='c-tabel-material'>
                <table>
                    <thead class='c-tabel-material-header'>
                        <tr>
                            <th class='th-name th-sort' width="30%" @click="sortMaterials('name')">
                                НАЗВАНИЕ
                                <b-icon v-show="sort.name.asc" icon="chevron-down" font-scale="1"></b-icon>
                                <b-icon v-show="sort.name.desc" icon="chevron-up" font-scale="1"></b-icon>
                            </th>
                            <th class='th-sort' @click="sortMaterials('code')">
                                КОД
                                <b-icon v-show="sort.code.asc" icon="chevron-down" font-scale="1"></b-icon>
                                <b-icon v-show="sort.code.desc" icon="chevron-up" font-scale="1"></b-icon>
                            </th>
                            <th class='th-sort' @click="sortMaterials('vendor')">
                                ЗАВОД
                                <b-icon v-show="sort.vendor.asc" icon="chevron-down" font-scale="1"></b-icon>
                                <b-icon v-show="sort.vendor.desc" icon="chevron-up" font-scale="1"></b-icon>
                            </th>
                            <th>
                                АРТИКУЛ
                            </th>
                            <th>
                                ЕД. ИЗМЕРЕНИЯ 
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
                <div class='c-tabel-material-body' :style="{ height: bodyHeight + 'px'}">
                    <div v-if="directoryMaterialsLoading" class="c-spinner_wrapper">
                        <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                    </div>
                    <div 
                        v-else-if="directoryMaterialsList.length == 0 & !directoryMaterialsLoading" 
                        class="c-empty-table"
                    >
                        <img src="@/assets/images/box.png">
                        <p>
                            Нет материалов
                        </p>
                    </div>
                    <template v-else>
                        <div v-for="(item, key) in directoryMaterialsList" :key="key">
                            <directory-materials-groupe-rows
                                v-if="item.isGroup"
                                :groupeProps="item" 
                                :unitsProps="unitsList"
                                :order="String(key + 1)" 
                                :levelProps="0" 
                            />
                            <directory-materials-rows
                                v-else
                                :materialProps="item" 
                                :order="String(key + 1)"
                                :levelProps="0"
                                :unitsProps="unitsList"
                                v-on:deleteElement="removeItem(item)"
                            />
                        </div>
                    </template>
                </div>
                <footer class='c-tabel-material__footer'>
                    <pagginate-table
                        v-if="directoryMaterialsSelectedList.length == 0"
                        :optionsProps="directoryMaterialsOptions"
                        :setListActionsNameProps="'materialsGetActions'"
                        :pagesOptionsProps="directoryMaterialsPagesOptions"
                        :setOptionsActionsNameProps="'directoryMaterialsSetOptionsActions'"
                    />
                    <template v-else>
                        <div class="c-selected">
                            <span class="c-selected_title" >Выбран {{ directoryMaterialsSelectedList.length }} из 10 элементов</span>
                            <div class="c-selected_icons">
                                <div @click="deleteMaterial()" v-b-modal.base-delete-modal>
                                    <base-icon 
                                        iconProps="trash" 
                                        sizeProps="md" 
                                    />
                                </div>
                                <div @click="showEditModal()" v-b-modal.directory-materials-modal-editl>
                                    <base-icon 
                                        iconProps="pen" 
                                        sizeProps="md" 
                                        v-show="directoryMaterialsSelectedList.length < 2" 
                                    />
                                </div>
                            </div>
                        </div>
                    </template>
                </footer>
            </div>
        </main>
        <directory-materials-modal-edit
            :unitsProps="unitsList"
        />
        <directory-materials-modal-edit-groupe/>
        <directory-materials-modal-add
            :unitsProps="unitsList"
            :materialGroupeIdProps="parentId"
        />
        <directory-materials-modal-info
            :unitsProps="unitsList"
        />
    </div>

</template>
<script>
    import { mapGetters, mapActions } from "vuex";

    import { heightDirectoryBodyMaterialTable } from '@/utils/heightBodyTable'

    import BaseIcon from "@/components/elements/BaseIcon"
    import InputSearch from '@/components/elements/InputSearch'
    import PagginateTable from '@/components/elements/PagginateTable'
    import DirectoryMaterialsGroupeRows from '@/components/directory/DirectoryMaterialsGroupeRows'
    import DirectoryMaterialsRows from '@/components/directory/DirectoryMaterialsRows' 
    import DirectoryMaterialsModalAdd from '@/components/directory/DirectoryMaterialsModalAdd'
    import DirectoryMaterialsModalEdit from '@/components/directory/DirectoryMaterialsModalEdit'
    import DirectoryMaterialsModalEditGroupe from '@/components/directory/DirectoryMaterialsModalEditGroupe'
    import DirectoryMaterialsModalInfo from '@/components/directory/DirectoryMaterialsModalInfo'

    export default {
        name: 'DirectoryMaterials',
        data(){
            return {
                isVisibleMenu: false,
                sort: {
                    name: {
                        asc: false,
                        desc: false,
                    },
                    created_at: {
                        asc: true,
                        desc: false,
                    },
                    vendor: {
                        asc: false,
                        desc: false,
                    },
                    code: {
                        asc: false,
                        desc: false,
                    },
                    unit_code: {
                        asc: false,
                        desc: false,
                    }
                },
                breadcrumb: [
                    { text: 'Материалы', href: '/crm/directory/materials' },
                ],
                bodyHeight: 0,
                valSearch: '',
                selectMaterial: {},
                parentId: '',
            }
        },
        components: {
            BaseIcon,
            InputSearch,
            PagginateTable,
            DirectoryMaterialsRows, 
            DirectoryMaterialsModalAdd,
            DirectoryMaterialsModalEdit,
            DirectoryMaterialsModalInfo,
            DirectoryMaterialsGroupeRows,
            DirectoryMaterialsModalEditGroupe,
        },
        computed: {
            ...mapGetters({
                unitsList: 'materialsUnitsOptionsGetter',
                materialsError: 'materialsErrorGetter',
                directoryMaterialsList: 'directoryMaterialsListGetter',
                directoryMaterialsOptions: 'directoryMaterialsOptionsGetter',
                directoryMaterialsLoading: 'directoryMaterialsLoadingGetter',
                profleDirectoryEditAccess: 'profleDirectoryEditAccessGetter',
                directoryMaterialsSelectedList: 'directoryMaterialsSelectedListGetter',
                directoryMaterialsPagesOptions: 'directoryMaterialsPagesOptionsGetter',
            }),
        },
        methods: {
            ...mapActions({ 
                getUnits: 'getUnitsActions',
                materialsGet: 'materialsGetActions',
                setOptionsProfile: 'setOptionsActions',
                setParamsDeleteModal: 'setParamsDeleteModalActions',
                directoryMaterialSort: 'directoryMaterialSortActions',
                resetCurrentGroupeUid: 'materialsResetCurrentGroupeUidActions',
                directoryMaterialsSetEdit: 'directoryMaterialsSetEditActions',
                directoryMaterialsSetOptions: 'directoryMaterialsSetOptionsActions',
                directoryMaterialsClearSelectedList: 'directoryMaterialsClearSelectedListActions',
                directoryMaterialsSetCurrentGroupeUid: 'directoryMaterialsSetCurrentGroupeUidActions',
            }),
            showMenu(){
                this.isVisibleMenu = !this.isVisibleMenu
            },
            showEditModal(){
                console.log(this.directoryMaterialsSelectedList[0])
                if(this.directoryMaterialsSelectedList[0].isGroup == true){
                    this.$bvModal.show('directory-materials-modal-edit-groupe')
                }else{
                    console.log('show edit material')
                    this.$bvModal.show('directory-materials-modal-edit')
                }
            },
            setGroupeUid(){
                if(this.directoryMaterialsSelectedList[0]?.isGroup == true){
                    console.log(this.directoryMaterialsSelectedList[0].id)
                    this.parentId = this.directoryMaterialsSelectedList[0].id
                }else{
                    this.parentId = ''
                }
            },
            deleteMaterial(){
                this.setParamsDeleteModal(
                    {
                        title: this.directoryMaterialsSelectedList.length > 1 ? 'материалы' : 'материал',
                        actionsName: 'directoryMaterialsDeleteActions',
                        data: this.directoryMaterialsSelectedList
                    }
                )
            },
            editMaterial(){
                this.directoryMaterialsSetCurrentGroupeUid(this.directoryMaterialsSelectedList[0].parent)
                this.directoryMaterialsSetEdit(this.directoryMaterialsSelectedList[0])
            },
            async sortMaterials(field){
                
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.directoryMaterialsOptions

                if(this.sort[field].asc){
                    let obj = {
                        [field] : 'ASC'
                    }
                    options.orderBy = obj
                }else{
                    let obj = {
                        [field] : 'DESC'
                    }
                    options.orderBy = obj
                }

                for(var key in this.sort){
                    if(key != field){
                        this.sort[key].asc = false
                        this.sort[key].desc = false
                    }
                }

                this.directoryMaterialsSetOptions(options)
                await this.materialsGet()
            },
        },
        async mounted(){
            this.bodyHeight = heightDirectoryBodyMaterialTable()
            
            await this.materialsGet()
            await this.getUnits()

            if(this.directoryMaterialsLoading == false){
                this.bodyHeight = heightDirectoryBodyMaterialTable()
            } 
            
        }
    }
</script>
<style>
</style>
