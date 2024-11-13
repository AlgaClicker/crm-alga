<template>
    <div class="c-default-table-container">
        <div class="c-default-table-container__header">
            <div>Материал</div>
        </div>
        <div class="c-supply-requisition-material__table-wrapper mt-2">
            <div v-if="supplyRequisitionMaterialLoading" style="height: 200px" class="px-4 c-spinner_wrapper c-empty-table">
                <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
            </div>
            <div v-else class="c-supply-requisition-material-table">
                <table>
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th width="5%">Ед. изм</th>
                            <th width="21%">Количество по заявке</th>
                            <th>Комментарий</th>
                        </tr>
                    </thead>
                </table>
                <div class='c-supply-requisition-material-table__body' >
                    <table>
                        <tbody>
                            <tr v-for="item in supplyRequisitionMaterialList " :key="item.id">
                                <td>
                                    {{ item.fullname }}
                                </td>
                                <td width="5%">
                                    {{ item.unit }}
                                </td>
                                <td width="21%">
                                    {{ item.quantity }}
                                </td>
                                <td>
                                    {{ item.description }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        name: "SupplyFreeRequisitionMaterial",
        data(){ 
            return {
                heightTabel: '',
                selectMaterial: '',
                selectInvoiceProps: {}
            }
        },
        components: {
 
        },
        computed: {
            ...mapGetters({
                supplyMyRequisition: 'supplyMyRequisitionGetter',
                supplyRequisitionMaterialList: 'supplyRequisitionMaterialListGetter',
                supplyRequisitionMaterialLoading: 'supplyRequisitionMaterialLoadingGetter',
            })
        },
        methods: {
            ...mapActions({
                getUnits: 'getUnitsActions',
                supplyMyRequisitionSet: 'supplyMyRequisitionSetActions',
                supplyRequisitionMaterialSet: 'supplyRequisitionMaterialSetActions',
                supplyRequisitionMaterialSetLoading: 'supplyRequisitionMaterialSetLoadingActions',
                supplyRequisitionMaterialSetMaterial: 'supplyRequisitionMaterialSetMaterialActions',
            }),
        },
        async mounted(){
            let id = window.location.href.split('/').reverse()[1];
            await this.supplyMyRequisitionSet(id)

            this.supplyRequisitionMaterialSetLoading()

            this.supplyRequisitionMaterialSet(this.supplyMyRequisition.materials)

            this.supplyRequisitionMaterialSetLoading()
        }
    }
</script>

