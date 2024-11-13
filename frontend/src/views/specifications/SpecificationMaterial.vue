<template>
    <div class="c-specification-wrapper">
        <div v-if="specificationMaterialLoading" class="c-spinner_wrapper">
            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
        </div>
        <div v-else class="c-specification-wrapper__content">
            <header class="c-specification-wrapper__header">
                <base-button-save 
                    @click="save()"
                    :textProps="'Сохранить'"
                    :saveIsProps="specificationMaterialSaveIs"
                    :loadingProps="specificationMaterialEditLoading"
                />  
                <base-switch-fixed 
                    class="ml-1"
                    @switchEmit="setFixed"
                    :onTextProps="'Зафиксировать'"
                    :offTextProps="'Снять фиксацию'"
                    :onProps="specificationIsFixed"
                />
                <div class="left-wrapper">
                    <button v-tooltip.bottom-start="'добавить строку'" @click="specificationMaterialAdd()" class="c-tools-button mr-1">
                        <b-icon icon="plus-lg"></b-icon>
                    </button>
                    <button @click="downloadSpec" v-tooltip.bottom-start="'скачать'" class="c-tools-button">
                        <b-icon icon="download"></b-icon>
                    </button>  
                    <button v-b-modal.sepcification-add-izm class="c-button-add-spec">
                        <b-icon icon="plus" font-scale="1.7"></b-icon>
                        <span>
                            Создать изменение
                        </span>
                    </button>
                </div>
            </header>
            <ve-table    
                v-if="specificationIsFixed"
                ref="tableRef"
                rowKeyFieldName="rowKey"
                class="c-specification-table"
                :max-height="heightSpecificationsTable"
                :columns="columns" 
                :rowStyleOption="rowStyleOption"
                :table-data="specificationMaterialList"
                :clipboard-option="clipboardOption"
                border-y
            />
            <ve-table    
                v-else
                ref="tableRef"
                rowKeyFieldName="rowKey"
                class="c-specification-table"
                :max-height="heightSpecificationsTable"
                :columns="columns" 
                :rowStyleOption="rowStyleOption"
                :table-data="specificationMaterialList"
                :edit-option="editOption"
                :cell-autofill-option="cellAutofillOption"
                :clipboard-option="clipboardOption"
                :contextmenu-body-option="contextmenuBodyOption"
                border-y
            />
        </div>
        <directory-materials-modal-set 
            @setMaterialEmit="setMaterial"
            :materialIdProps="this.selectMaterial"  
        />
    </div>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    
    import csvExportMaterial from '@/mixins/csv.mixins'
    import BaseButtonSave from '@/components/elements/BaseButtonSave'
    import BaseSwitchFixed from '@/components/elements/BaseSwitchFixed'
    import DirectoryMaterialsModalSet from '@/components/specification/materialsModal/DirectoryMaterialsModalSet'
    
    export default {
        name: 'SpecificationMaterial',
        data(){
            return {
                specificationId: '',
                visibleHistory: false,
                unit: [],
                selectMaterial: '',
                selected: '',
                dragFile: true,
                heightTabel: 100,
                cellSelectionOption: {
                    enable: true,
                },
                rowStyleOption: {
                    clickHighlight: false,
                    hoverHighlight: false,
                },
                editOption: {
                    // cell value change
                    cellValueChange: ({ row }) => { 
                        var form = row
                        form.specificationId = this.specificationIdProps
                        this.specificationMaterialEdit(form)
                    },
                },
                cellAutofillOption: {
                    beforeAutofill: ({
                        sourceSelectionData,
                        targetSelectionData,
                    }) => {
                        var params = {
                            specificationId: this.specificationIdProps,
                            sourceSelectionData: sourceSelectionData,
                            targetSelectionData: targetSelectionData,
                        }
                        this.specificationMaterialAoutofile(params)
                        if( typeof sourceSelectionData[0].index != 'undefined' ){
                            alert('*')
                            return
                        }
                    }
                },
                clipboardOption: {
                    beforePaste: ({ data, selectionRangeKeys}) => {
                        var params = {
                            data,
                            specificationId: this.specificationIdProps,
                            selectionRangeKeys,
                        }
                        this.specificationMaterialCopyRow(params)
                    }
                },
                contextmenuBodyOption: {
                    afterMenuClick: ({ type, selectionRangeKeys, selectionRangeIndexes }) => {
                        
                        if(type === 'REMOVE_ROW')
                        {
                            if(selectionRangeKeys.endRowKey === selectionRangeKeys.startRowKey){
                                const params = {
                                    specificationId: this.specificationIdProps,
                                    key: selectionRangeKeys.endRowKey
                                }
                                this.specificationMaterialDeleteRow(params)
                            }
                            if(selectionRangeKeys.endRowKey !== selectionRangeKeys.startRowKey){
                                const params = {
                                    specificationId: this.specificationIdProps,
                                    start: selectionRangeIndexes.startRowIndex,
                                    end: selectionRangeIndexes.endRowIndex
                                }
                                this.specificationMaterialDeleteRows(params)
                            }
                        }

                        if(type === 'INSERT_ROW_ABOVE'){
                            let param = {
                                specificationId: this.specificationIdProps,
                                rowIndex: selectionRangeIndexes.endRowIndex
                            }
                            this.specificationMaterialAddRowAbove(param)
                        }

                        if(type === 'INSERT_ROW_BELOW'){
                            
                            let param = {
                                specificationId: this.specificationIdProps,
                                rowIndex: selectionRangeIndexes.endRowIndex
                            }
                            this.specificationMaterialAddRowBelow(param)
                        }

                        if( type == 'EMPTY_CELL'){
                            this.specificationMaterialClearCell(selectionRangeIndexes)
                        }

                        if( type == 'INSERT_GROUPE_CUSTOM'){

                            let params = {
                                id: this.specificationIdProps,
                                rowKey: selectionRangeKeys.endRowKey
                            }

                            this.specificationMaterialAddGroup(params)
                        }

                    },
                    contextmenus: [
                        {
                            type: "COPY",
                        },
                        {
                            type: "INSERT_ROW_ABOVE",
                        },
                        {
                            type: "INSERT_ROW_BELOW",
                        },
                        {
                            type: "SEPARATOR",
                        },  
                        {
                            type: "REMOVE_ROW",
                            label: "удаление",
                        },
                        {
                            type: "EMPTY_CELL",
                        },
                        {
                            type: "INSERT_GROUPE_CUSTOM",
                            label: "Создать группу",
                        },
                    ],
                },
                columns: [
                    {
                        field: "",
                        key: "a",
                        title: "",
                        width: 30,
                        align: "center",
                        fixed: "left",
                        operationColumn: true,
                        renderBodyCell: ({ rowIndex } ) => {
                            return rowIndex  + 0 + 1;
                        },
                    },
                    {
                        field: "position",
                        key: "position",
                        title: "Позиция",
                        align: "center",
                        width: "7%",
                        edit: true,
                    },
                    {
                        field: "fullname",
                        key: "fullname",
                        title: "Название",
                        align: "left",
                        width: "22%",
                        edit: true,
                        renderBodyCell: ( { row } ) => {
                            
                            let element;
                            if (row.is_group) {
                                element = <div class="c-specification-table-material__groupe"> 
                                    { row.fullname } 
                                </div>
                            } else {
                                element = <div> 
                                    { row.fullname }
                                </div>
                            }

                            return element  
                        }
                    },
                    {
                        field: "type",
                        key: "type",
                        title: "Тип, марка",
                        align: "left",
                        width: "12%",
                        edit: true,
                    },
                    {
                        field: "code",
                        key: "code",
                        title: "Код продукции",
                        align: "left",
                        width: 130,
                        edit: true,
                    },
                    {
                        field: "vendor",
                        key: "vendor",
                        title: "Завод производитель",
                        align: "left",
                        width: 120,
                        edit: true,
                    },
                    {
                        field: "unit",
                        key: "unit",
                        title: "Ед. изм",
                        align: "left",
                        width: 50,
                        renderBodyCell: ({ row }) => {
                            let element;
                            if (row.is_group) {
                                element = null
                            }else{
                                let unit;
                                if(typeof row.unit != 'undefined' & row.unit != ''){
                                    for(let i = 0; i < this.unitsList.length; i++){
                                        if(row.unit.includes(this.unitsList[i].title.replace(/[^А-Яа-яЁё].[0-10]/g, '').replace(/[\s.;,%]/g, ''))){
                                            if(this.unitsList[i].title.replace(/[^А-Яа-яЁё].[0-10]/g, '').replace(/[\s.;,%]/g, '').length == row.unit.replace(/[^А-Яа-яЁё].[0-10]/g, '').replace(/[\s.;,%]/g, '').length ){
                                                unit = this.unitsList[i].title;
                                                break;
                                            }
                                        }
                                    }
                                }   
    
                                element = <div class="c-specification-table-select">
                                    <select value={unit} onChange={ (event) => this.selectUnit(event.target.value, row) }>
                                        { this.unitsList.map( item => (             
                                            <option> { item.title }</option>
                                        ))}
                                    </select>
                                </div>
                            }

                            return element 
                        },
                    },
                    {   
                        field: "quantity",
                        key: "quantity",
                        title: "Количество",
                        align: "left",
                        width: 60,
                        edit: true,
                    },
                    {
                        field: "description",
                        key: "description",
                        title: "Примечание",
                        align: "left",
                        width: "11%",
                        edit: true,
                    },
                    {
                        field: "material",
                        key: "material",
                        title: "Материал",
                        align: "left",
                        width: "17%",
                        edit: true,
                        renderBodyCell: ( { row } ) => {
                            let element;
                            if (row.material != null) {
                                element = <div class="c-specification-table-material"> 
                                    <p class="c-specification-table-material__add">
                                        { row.material.name } 
                                        <span class='c-specification-table-material__delete' onClick={ () => this.resetMaterial(row) } > ( удалить ) </span>
                                    </p>
                                </div>
                            } else if(row.is_group == true) {
                                return null
                            } else {
                                element = <div class="c-specification-table-material"> 
                                    <div onClick={ () => this.showModal(row) } class="c-specification-table-material__add">
                                        ( Прикрепить из справочника )
                                    </div>
                                </div>
                            }

                            return element
                        }
                    }
                ],
            }   
        },
        props: {    
            specificationIZMList: { type: Array, default: () => [] },
            selectIZMId: { type: String, default: "" },
            specificationIdProps: { type: String, default: ""}
        },
        components: {
            DirectoryMaterialsModalSet,
            BaseSwitchFixed,
            BaseButtonSave
        },
        watch: {
            '$props.selectIZMId': {
                async handler() {
                    await this.specificationMaterialGetList(this.selectIZMId)
                    this.setCellSelection(this.specificationMaterialList[0].rowKey, 'fullname')
                }
            }
        },
        mixins: [csvExportMaterial],
        computed: {
            ...mapGetters({
                unitsList: 'materialsUnitsListGetter',
                specification: 'specificationGetter',
                materialsListName: 'materialsListNameGetter',
                materialsUnitsList: 'materialsUnitsListGetter',
                specificationIsFixed: 'specificationIsFixedGetter',
                specificationMaterialList: 'specificationMaterialListGetter',
                heightSpecificationsTable: 'heightSpecificationsTableGetter',
                specificationMaterialSaveIs: 'specificationMaterialSaveIsGetter',
                specificationMaterialLoading: 'specificationMaterialLoadingGetter',
                specificationMaterialEditLoading: 'specificationMaterialEditLoadingGetter',
            }),
        },
        methods: {
            ...mapActions({ 
                getUnits: 'getUnitsActions',
                materialsSearch: 'materialsSearchActions',
                specificationMaterialAdd: 'specificationMaterialAddActions',
                specificationMaterialSave: 'specificationMaterialSaveActions',
                specificationFixedActions: 'specificationFixedActions',
                specificationMaterialEdit: 'specificationMaterialEditActions',
                specificationMaterialAddRow: 'specificationMaterialAddRowActions',
                specificationUnFixedActions: 'specificationUnFixedActions',
                specificationMaterialCopyRow: 'specificationMaterialCopyRowActions',
                specificationMaterialGetList: 'specificationMaterialGetListActions',
                heightSpecificationsTableSet: 'heightSpecificationsTableSetActions',
                specificationMaterialAddGroup: 'specificationMaterialAddGroupActions',
                specificationMaterialClearCell: 'specificationMaterialClearCellActions',
                specificationMaterialAoutofile: 'specificationMaterialAoutofileActions',
                specificationMaterialDeleteRow: 'specificationMaterialDeleteRowActions',
                specificationMaterialDeleteRows: 'specificationMaterialDeleteRowsActions',
                specificationMaterialAddRowAbove: 'specificationMaterialAddRowAboveActions',
                specificationMaterialAddRowBelow: 'specificationMaterialAddRowBelowActions',
                specificationMaterialSetMaterial: 'specificationMaterialSetMaterialActions',
                specificationMaterialResetMaterial: 'specificationMaterialResetMaterialActions',
            }),
            async selectUnit(value, row){
                var form = row
                form.unit = value
                form.specificationId = this.specificationIdProps
                
                await this.specificationMaterialEdit(form)
            },
            downloadSpec(){
                this.csvExportMaterial(
                    {
                        data: this.specificationMaterialList,
                        type: 'material',
                        name: this.specification.name
                    }
                )
            },
            async setMaterial( selectedMaterial ){
                let params = {
                    specificationId: this.specificationIdProps,
                    row: this.selectMaterial,
                    material: selectedMaterial
                }
                this.specificationMaterialSetMaterial(params)
            },
            async resetMaterial(row){
                await this.specificationMaterialResetMaterial(row)
            },
            addRow(){
                this.specificationMaterialAddRow()
            },
            async setFixed(){
                if(this.specificationIsFixed){
                   await this.specificationUnFixedActions()
                }else{
                   await this.specificationFixedActions()
                }
            },
            showHistory(){
                this.visibleHistory = !this.visibleHistory
            },
            showModal(row){
                this.selectMaterial = row
                this.$bvModal.show('directory-materials-modal-set-material')
            },
            setCellSelection(rowKey, colKey) {
                this.$refs["tableRef"].setCellSelection({ rowKey, colKey });
            },
            async save(){
                await this.specificationMaterialSave(this.specificationIdProps)
            }
        },
        async mounted(){
            this.getUnits()
            this.unit = this.unitsList.map(item => item.title)
            this.heightSpecificationsTableSet(document.documentElement?.clientHeight - 50)
        }
    }
</script>