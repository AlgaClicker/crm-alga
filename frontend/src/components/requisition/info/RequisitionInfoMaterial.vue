<template>
    <div class="c-requisition-material">
        <ve-table 
            ref="tableRef"
            class="c-specification-master-main__table"
            :table-data="materialTableComputed"
            :rowStyleOption="rowStyleOption"
            :edit-option="editOption"
            :max-height="'380px'"
            :columns="columns" 
            border-y
        />
    </div>
</template>

<script>
    export default {
        name: "RequisitionInfoMaterial",
        data(){
            return {
                editOption: {
                    // cell value change
                    cellValueChange: ({ row }) => { 
                        console.log(row)
                    },
                },
                rowStyleOption: {
                    clickHighlight: false,
                    hoverHighlight: false,
                },
                columns: [
                    {
                        field: "position",
                        key: "position",
                        title: "Позиция",
                        align: "center",
                        width: "20%",
                        edit: false,
                    },
                    {
                        field: "fullname",
                        key: "fullname",
                        title: "Название",
                        align: "center",
                        width: "60%",
                        edit: false,
                    },
                    {
                        field: "quantity",
                        key: "quantity",
                        title: "Количество",
                        align: "center",
                        width: "20%",
                        edit: false,
                    },
                ],
            }
        },
        computed:{
            materialTableComputed(){
                var material 
                if(typeof this.requisitionProps.materials == 'undefined'){

                    material = []

                } else {

                    material = this.requisitionProps.materials.map( item => {
                        var material = {}
                        material.position = item.specification_material.position
                        material.fullname = item.specification_material.fullname
                        material.quantity = item.quantity
                        return material
                    })
            
                }

                return material
            }
        },
        props: {
            requisitionProps: { 
                type: Object,
                default: () => {}
            },
        },
    }
</script>