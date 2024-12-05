<template>
    <div class="c-specification-wrapper">
        <div v-if="masterSpecificationMaterialLoading" class="c-spinner_wrapper" style="height: 90vh">
            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
        </div>
        <div v-else class="c-specification-wrapper__content">
            <ve-table
                class="c-specification-table"   
                ref="tableRef"
                rowKeyFieldName="rowKey"
                :max-height="heightMasterSpecificationsTable"
                :columns="columns" 
                :table-data="masterSpecificationMaterialList"
                border-y
            />
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    
    export default {
        name: 'MasterSpecificationMaterial',
        data(){ 
            return {
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
                        edit: false,
                    },
                    {
                        field: "fullname",
                        key: "fullname",
                        title: "Название",
                        align: "left",
                        width: "22%",
                        edit: false,
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
                        edit: false,
                    },
                    {
                        field: "code",
                        key: "code",
                        title: "Код продукции",
                        align: "left",
                        width: 130,
                        edit: false,
                    },
                    {
                        field: "vendor",
                        key: "vendor",
                        title: "Завод производитель",
                        align: "left",
                        width: 120,
                        edit: false,
                    },
                    {
                        field: "unit",
                        key: "unit",
                        title: "Ед. изм",
                        align: "left",
                        width: 50,
                        edit: false
                    },
                    {   
                        field: "quantity",
                        key: "quantity",
                        title: "Количество",
                        align: "left",
                        width: 80,
                        edit: false,
                    },
                    {   
                        field: "ordered",
                        key: "ordered",
                        title: "Заказанно",
                        align: "left",
                        width: 80,
                        edit: false,
                    },
                    {
                        field: "description",
                        key: "description",
                        title: "Примечание",
                        align: "left",
                        width: "18%",
                        edit: false,
                    }
                ]
            }
        },
        props: {    
            specificationIZMList: { type: Array, default: () => [] },
            selectIZMId: { type: String, default: "" },
            specificationIdProps: { type: String, default: "" }
        },
        watch: {
            '$props.selectIZMId': {
                async handler() {
                    await this.masterSpecificationMaterialSetList(this.selectIZMId)
                    //this.setCellSelection(this.specificationMaterialList[0].rowKey, 'fullname')
                }
            }
        },
        methods: {
            ...mapActions({
                getUnits: 'getUnitsActions',
                heightMasterSpecificationsTableSet: 'heightMasterSpecificationsTableSetActions',
                masterSpecificationMaterialSetList: 'masterSpecificationMaterialSetListActions',
            })
        },
        computed: {
            ...mapGetters({
                masterSpecificationMaterialList: 'masterSpecificationMaterialListGetter',
                heightMasterSpecificationsTable: 'heightMasterSpecificationsTableGetter',
                masterSpecificationMaterialLoading: 'masterSpecificationMaterialLoadingGetter',
            })
        },
        async mounted(){
            this.getUnits()
            this.heightMasterSpecificationsTableSet(document.documentElement?.clientHeight)
        }
    }
</script>