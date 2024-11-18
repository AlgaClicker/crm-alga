<template>
    <div class="c-default-table-container">
        <div class="c-default-table-container__header">
            
        </div>
        <div class="c-supply-requisition-material__table-wrapper mt-2">
            <div v-if="masterRequisitionDeliveryLoading" style="height: 300px" class="px-4 c-spinner_wrapper c-empty-table">
                <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
            </div>
            <div v-else class="c-supply-requisition-material-table">
                <table>
                    <thead>
                        <tr>
                            <th width="30%">Дата</th>
                            <th>Склад</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
                <div class='c-supply-requisition-material-table__body'>
                    <table>
                        <tbody>
                            <tr v-for="item in masterRequisitionDeliveryList" :key="item.id">
                                <td width="30%">{{  item.delivery_at | dateFilterWithoutTime }}</td>
                                <td>{{  }}</td>
                                <td>
                                    <status-delivery 
                                        :statusProps="item.status"
                                    />
                                </td>
                                <td @click="selectDelivery(item)">
                                    <div class="info">
                                        Информация
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <master-requisition-modal-delivery 
            :deliveryProps="selectdDelivery"
        />
    </div>  
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import StatusDelivery from '@/components/requisition/StatusDelivery.vue'
    import MasterRequisitionModalDelivery from '@/components/requisition/MasterRequisitionModalDelivery'

    export default {
        name: "MasterRequisitionDeliverys",
        data(){
            return {
                selectdDelivery: {}
            }
        },
        components: {
            StatusDelivery,
            MasterRequisitionModalDelivery
        },
        computed: {
            ...mapGetters({
                masterRequisitionDeliveryList: 'masterRequisitionDeliveryListGetter',
                masterRequisitionDeliveryLoading: 'masterRequisitionDeliveryLoadingGetter'
            })
        },
        methods: {
            ...mapActions({
                masterRequisitionSet: 'masterRequisitionSetActions',
                masterRequisitionDeliverySetList: 'masterRequisitionDeliverySetListActions'
            }),
            selectDelivery(delivery){
                this.selectdDelivery = delivery
                this.$bvModal.show('master-requisition-modal-delivery')
            }
        },
        async mounted() {
            let id = window.location.href.split('/').reverse()[1];
            await this.masterRequisitionSet(id)

            await this.masterRequisitionDeliverySetList()
        },
    }
</script>