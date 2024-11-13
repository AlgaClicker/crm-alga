<template>
    <b-modal 
        id="directory-materials-modal-set-material"
        ref="directory-materials-modal-set-material"
        title="Добавить материал"
        content-class="c-modal-default c-modal-specification-set-material"
        centered 
        hide-footer
        @shown="mountedData"
    >   
        <template #modal-header>
            <div>
                <h5>Прикрепить материал</h5>
                <div class="mb-1 header-set-material" v-show="materialNameProps != ''">
                    <div class="header-set-material__line ml-1"></div>
                    <div class="header-set-material__title">
                        {{ materialNameProps }} 
                    </div>
                </div>
            </div>  
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class='l-default'>
            <main>  
                <div class='c-panel'>
                    <input-search 
                        :searchActionsNameProps="'directoryMaterialsModalSearchActions'"
                        :setListActionsNameProps="'directoryMaterialsModalSetActions'"
                    /> 
                </div>
                <div class='c-tabel-material'>
                    <table> 
                        <thead class='c-tabel-material-header'>
                            <tr>
                                <th class='th-name' width="30%" >
                                    НАЗВАНИЕ
                                </th>
                                <th>
                                    КОД
                                </th>
                                <th>
                                    ЗАВОД
                                </th>
                                <th>
                                    АРТИКУЛ
                                </th>
                                <th>
                                    ЕД. ИЗМЕРЕНИЯ 
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class='c-tabel-material-body' :style="{ height: '50vh'}">
                        <div 
                            v-if="directoryMaterialsModalList.length == 0" 
                            class="c-empty-table"
                        >
                            <img src="@/assets/images/box.png">
                            <p>
                                Нет материалов
                            </p>
                        </div>
                        <template v-else>
                            <div v-for="(item, key) in directoryMaterialsModalList" :key="key">
                                <DirectoryMaterialsModalGroupeRows
                                    v-if="item.isGroup"
                                    :groupeProps="item" 
                                    :unitsProps="unitsList"
                                    :order="String(key + 1)" 
                                    :levelProps="0" 
                                />
                                <DirectoryMaterialsModalRows
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
                    <footer class="c-tabel-material__footer">
                        <pagginate-table
                            v-if="directoryMaterialsModalSelectedList.length == 0"
                            :optionsProps="directoryMaterialsModalOptions"
                            :pagesOptionsProps="directoryMaterialsModalPagesOptions"
                            :setListActionsNameProps="'directoryMaterialsModalSetActions'"
                            :setOptionsActionsNameProps="'directoryMaterialsModalSetOptionsActions'"
                        />
                        <template v-else>
                            <div class="c-selected">
                                <button class="c-button-save--success" @click="setMaterial()">
                                    Выбрать материал
                                </button>
                            </div>
                        </template>
                    </footer> 
                </div>
            </main>
        </div>
    </b-modal>
</template>
<script>
    import { mapActions, mapGetters } from "vuex";

    import InputSearch from '@/components/elements/InputSearch'
    import PagginateTable from '@/components/elements/PagginateTable'
    import DirectoryMaterialsModalRows from '@/components/specification/materialsModal/DirectoryMaterialsModalRows'
    import DirectoryMaterialsModalGroupeRows from '@/components/specification/materialsModal/DirectoryMaterialsModalGroupeRows'

    export default {
        name: 'DirectoryMaterialsModalSet',
        data (){
            return {
                selectedMaterial: {},
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
                },
            }
        },
        components: {
            InputSearch,
            PagginateTable,
            DirectoryMaterialsModalRows,
            DirectoryMaterialsModalGroupeRows,
        },
        props: {
            materialNameProps: { type: String, default: '' },
        },
        emits: ['setMaterialEmit'],
        computed: {
            ...mapGetters({
                unitsList: 'materialsUnitsOptionsGetter',
                directoryMaterialsModalList: 'directoryMaterialsModalListGetter',
                directoryMaterialsModalOptions: 'directoryMaterialsModalOptionsGetter',
                directoryMaterialsModalLoading: 'directoryMaterialsModalLoadingGetter',
                directoryMaterialsModalPagesOptions: 'directoryMaterialsModalPagesOptionsGetter',
                directoryMaterialsModalSelectedList: 'directoryMaterialsModalSelectedListGetter'
            }),
        },
        methods: {
            ...mapActions({
                getUnits: 'getUnitsActions',
                materialsGet: 'materialsGetActions', 
                directoryMaterialsModalSet: "directoryMaterialsModalSetActions",
                supplyRequisitionMaterialClearSelect: 'supplyRequisitionMaterialClearSelectActions',
                directoryMaterialsModalSelectedListClear: 'directoryMaterialsModalSelectedListClearActions',
            }),
            setMaterial(){
                this.$emit('setMaterialEmit', this.directoryMaterialsModalSelectedList[0])
                this.$refs['directory-materials-modal-set-material'].hide();
            },
            hideModal(){
                this.supplyRequisitionMaterialClearSelect()
                this.$refs['directory-materials-modal-set-material'].hide();
            },
            async sortDirectoryByName(){
                this.sort.vendor.asc = false
                this.sort.vendor.desc = false
                if(this.sort.name.asc == true){
                    this.sort.name.asc = false
                    this.sort.name.desc = true
                }else{
                    this.sort.name.desc = false
                    this.sort.name.asc = true
                } 

                let options = this.directoryMaterialsModalOptions
                options.orderBy = this.sort.name.asc ? { name: "ASC" } : { name: "DESC" }
                this.directoryMaterialsModalSetOptions(options)
                await this.directoryMaterialsModalSet({ parent: '' })
            },
            async sortDirectoryByVendor(){
                this.sort.name.asc = false
                this.sort.name.desc = false
                if(this.sort.vendor.asc == true){
                    this.sort.vendor.asc = false
                    this.sort.vendor.desc = true
                }else{
                    this.sort.vendor.asc = true
                    this.sort.vendor.desc = false
                }
                
                let options = this.directoryMaterialsModalOptions
                options.orderBy = this.sort.vendor.asc ? { vendor: "ASC" } : { vendor: "DESC" }
                this.directoryMaterialsModalSetOptions(options)
                await this.directoryMaterialsModalSet({ parent: '' })
            },
            async mountedData(){

                this.directoryMaterialsModalSelectedListClear()
                await this.directoryMaterialsModalSet()
                await this.getUnits()

            }
        }
    }
</script>