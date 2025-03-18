<template>
    <div class="default-wrapper">
        <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
        <div class="scroll-wrapper">
            <div class="c-default-table-container">
                <div class="c-default-table-container__header">
                    <filter-invoice
                        :setFilterActionsNameProps="'supplyMyRequisitionSetFilterActions'"
                        :setListActionsNameProps="'supplyMyRequisitionSetListActions'"
                    />
                    <filter-date 
                        :setListActionsNameProps="'supplyFreeRequisitionSetListActions'"
                        :setDateFilterActionsNameProps="'supplyFreeRequisitionSetDateFilterActions'"
                    />
                </div>
                <div class="c-default-table">
                    <table>
                        <thead>
                            <tr>
                                <th width="170" class="th-sort" @click="sortInvoices('status')"> 
                                    СТАТУС
                                    <b-icon v-show="sort.status.asc" icon="chevron-down" font-scale="0.9" ></b-icon>
                                    <b-icon v-show="sort.status.desc" icon="chevron-up" font-scale="0.9" ></b-icon>
                                </th>
                                <th width="170"> 
                                    НОМЕР СЧЕТА
                                </th>
                                <th class="th-sort" @click="sortInvoices('amount')"> 
                                    СУММА
                                    <b-icon v-show="sort.amount.asc" icon="chevron-down" font-scale="0.9" ></b-icon>
                                    <b-icon v-show="sort.amount.desc" icon="chevron-up" font-scale="0.9" ></b-icon>
                                </th>
                                <th class="th-sort"> 
                                    ДОГОВОР
                                </th>
                                <th class="th-sort" @click="sortInvoices('deliveryAt')"> 
                                    ДАТА ПОСТАВКИ
                                    <b-icon v-show="sort.deliveryAt.asc" icon="chevron-down" font-scale="0.9" ></b-icon>
                                    <b-icon v-show="sort.deliveryAt.desc" icon="chevron-up" font-scale="0.9" ></b-icon>
                                </th>
                                <th class="th-sort" @click="sortInvoices('createdAt')"> 
                                    СОЗДАНО
                                    <b-icon v-show="sort.createdAt.asc" icon="chevron-down" font-scale="0.9" ></b-icon>
                                    <b-icon v-show="sort.createdAt.desc" icon="chevron-up" font-scale="0.9" ></b-icon>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="supplyRequisitionInvoicesLoading" class="c-spinner_wrapper c-empty-table">
                            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                        </div>
                        <div 
                            v-else-if="supplyRequisitionInvoicesList.length == 0 |  supplyRequisitionInvoicesList== null"
                            class="c-empty-table"
                        >
                            <img src="@/assets/images/box.png">
                            <p>
                                Нет заявок
                            </p>
                        </div>
                        <table v-else>
                            <tbody>
                                <tr v-for="item in supplyRequisitionInvoicesList" :key="item?.id">
                                    <td width="170">
                                        <status-invoice
                                            :statusProps="item?.status"
                                        />
                                    </td>
                                    <td class="td-actions" @click="selectInvoice(item)" width="170">
                                        {{ item.number }}
                                    </td>
                                    <td>
                                        {{ item.amount | moneyFilter }}
                                    </td>
                                    <td class="td-actions" @click="selectContract(item.contract)"> 
                                        {{ item.contract.number }}
                                    </td>
                                    <td>
                                        {{ item.delivery_at | dateOnlyFilter }}
                                    </td>
                                    <td>
                                        {{ item.created_at | dateOnlyFilter }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <pagginate-table
                    :optionsProps="supplyRequisitionInvoicesOptions"
                    :pagesOptionsProps="supplyRequisitionInvoicesPagesOptions"
                    :setListActionsNameProps="'supplyRequisioinsInvoicesSetListActions'"
                    :setOptionsActionsNameProps="'supplyRequisioinsInvoicesSetOptionsActions'"
                />
                
           </div>
       </div>
       <requisitions-invoice-modal-info
            :supplyInvoicesProps="selectInvoiceProps"
        />
        <directory-contracts-supply-modal-info 
            :isEditProps="false"
            :specificationsListProps="[]"
            :partnersListProps="[]"
            :contractProps="selectedContract"
        />
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    import FilterDate from '@/components/elements/filters/FilterDate'
    import PagginateTable from '@/components/elements/PagginateTable'
    import StatusInvoice from '@/components/requisition/StatusInvoice'
    import FilterInvoice from '@/components/elements/filters/FilterInvoice'
    import RequisitionsInvoiceModalInfo from '@/components/requisition/RequisitionsInvoiceModalInfo'
    import DirectoryContractsSupplyModalInfo from '@/components/directory/DirectoryContractsSupplyModalInfo'
    import {dateOnlyFilter} from "@/filters/filters";

    export default {
        name: "SupplyRequisitionsInvoices",
        data(){
            return {
                breadcrumb: [
                    { text: 'Счета', href: '/crm/supply/requisition/invoices' },
                ],
                selectInvoiceProps: {},
                selectedContract: {},
                sort: {
                    createdAt: {
                       asc: false,
                       desc: true,
                    },
                    deliveryAt: {
                       asc: false,
                       desc: true,
                    },
                    status: {
                       asc: false,
                       desc: false,
                    },
                    amount: {
                        asc: false,
                        desc: false,
                    }
                },
            }
        },
        components: {
            FilterDate,
            FilterInvoice,
            StatusInvoice,
            PagginateTable,
            RequisitionsInvoiceModalInfo,
            DirectoryContractsSupplyModalInfo
        },
        computed: {
            ...mapGetters({
                supplyRequisitionInvoicesList: 'supplyRequisitionInvoicesListGetter',
                supplyRequisitionInvoicesLoading: 'supplyRequisitionInvoicesLoadingGetter',
                supplyRequisitionInvoicesOptions: 'supplyRequisitionInvoicesOptionsGetter',
                supplyRequisitionInvoicesPagesOptions: 'supplyRequisitionInvoicesPagesOptionsGetter'
            })
        },
        methods: {
          dateOnlyFilter,
            ...mapActions({
                supplyRequisioinsInvoicesSetList: 'supplyRequisioinsInvoicesSetListActions',
                supplyRequisioinsInvoicesSetOptions: 'supplyRequisioinsInvoicesSetOptionsActions'
            }),
            async sortInvoices(field){
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.supplyRequisitionInvoicesOptions

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

                this.supplyRequisioinsInvoicesSetOptions(options)
                await this.supplyRequisioinsInvoicesSetList()
            },
            selectContract(contract){
                this.selectedContract = contract
                this.$bvModal.show('directory-contracts-supply-modal-info')
            },
            selectInvoice(invoice){
                this.selectInvoiceProps = invoice
                this.$bvModal.show('requisitions-invoice-modal-info')
            }
        },
        async mounted(){
            await this.supplyRequisioinsInvoicesSetList()
            console.log(this.supplyRequisitionInvoicesList)
        }   
    }
</script>