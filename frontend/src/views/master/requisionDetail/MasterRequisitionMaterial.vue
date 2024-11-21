<template>
    <div class="c-default-table-container">
        <div class="c-default-table-container__header">

        </div>
        <div class="c-supply-requisition-material__table-wrapper mt-2">
            <div v-if="masterRequisitionLoading" style="height: 200px" class="px-4 c-spinner_wrapper c-empty-table">
                <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
            </div>
            <div v-else class="c-supply-requisition-material-table">
                <table>
                    <thead>
                        <tr>
                            <th width="40px"></th>
                            <th>Название</th>
                            <th width="5%">Ед. изм</th>
                            <th width="8%">Количество по заявке</th>
                            <th width="10%">Заказанно</th>
                        </tr>
                    </thead>
                </table>
                <div class='c-supply-requisition-material-table__body'>
                    <table>
                        <tbody>
                            <tr v-for="item in masterRequisitionMaterialList" :key="item.id">
                                <td width="40px" class="text">
                                    <div class="center">
                                        {{ item.index }}
                                    </div>
                                </td>
                                <td>
                                    {{ item.name }}
                                </td>
                                <td width="5%">
                                    {{ item.unit?.title }}
                                </td>
                                <td width="8%">
                                    {{ item.quantity }}
                                </td>
                                <td width="10%">
                                    {{ item.ordered }}
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
    import { mapActions, mapGetters } from 'vuex'
    //import MasterRequisitionDelivery from '@/components/requisition/MasterRequisitionDelivery'

    export default {
        name: 'MasterRequisitionMaterial',
        components: {
          //MasterRequisitionDelivery
        },
        computed: {
            ...mapGetters({
                masterRequisitionLoading: 'masterRequisitionLoadingGetter',
                masterRequisitionMaterialList: 'masterRequisitionMaterialListGetter'
            })
        },
        methods: {
            ...mapActions({
                masterRequisitionSet: 'masterRequisitionSetActions',
                masterRequisitionSetMaterial: 'masterRequisitionSetMaterialActions'
            })
        },
        async mounted() {
            let id = window.location.href.split('/').reverse()[1];
            await this.masterRequisitionSet(id)

            await this.masterRequisitionSetMaterial()
        },
    }
</script>