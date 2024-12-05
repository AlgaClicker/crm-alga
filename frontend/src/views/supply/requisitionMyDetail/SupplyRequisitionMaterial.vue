<template>
    <div class="c-default-table-container">
        <div v-show="!supplyRequisitionMaterialLoading" class="c-default-table-container__header">
            <div>Материал</div>
            <progress-requisitions 
                class="mt-1 ml-1"
                :requiredQuantityProps="supplyRequisitionMaterialQuantity.requiredQuantity"
                :orderedQuantityProps="supplyRequisitionMaterialQuantity.orderedQuantity"
            />
        </div>
        <div class="c-supply-requisition-material__table-wrapper mt-2">
            <div v-if="supplyRequisitionMaterialLoading" style="height: 200px" class="px-4 c-spinner_wrapper c-empty-table">
                <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
            </div>
            <div v-else class="c-supply-requisition-material-table">
                <table>
                    <thead>
                        <tr>
                            <th width="5%"></th>
                            <th>Название</th>
                            <th width="5%">Ед. изм</th>
                            <th width="10%">Количество по заявке</th>
                            <th width="8%">Заказано</th>
                            <th>Счета</th>
                            <th>Комментарий</th>
                        </tr>
                    </thead>
                </table>
                <div class='c-supply-requisition-material-table__body' >
                    <table>
                        <tbody>
                            <tr v-for="item in supplyRequisitionMaterialList" :key="item.id">
                                <td width="5%" class="checked">
                                    <input 
                                        class="с-checkbox" 
                                        type="checkbox"
                                        :checked="item.selected"
                                        @change="checkMaterial(item)"
                                    >
                                </td>
                                <td class="material">
                                    <div v-if="item.directory_material">
                                        {{ item.fullname }}
                                        <div class="material__attached"> 
                                            <base-icon iconProps="more" sizeProps="sm" /> 
                                            <span>
                                                {{ item.directory_material.name }} 
                                                <div @click="resetMaterial(item)" v-show="supplyMyRequisition.status == 'manage'" class="material__delete"> ( удалить ) </div>
                                            </span>
                                        </div>
                                    </div>
                                    <div v-else>
                                        {{ item.fullname }}
                                        <span @click="showModalDirectory(item)" class="material__attach">Прикрепить материал</span>
                                    </div>
                                </td>
                                <td width="5%">
                                    {{ item.unit }}
                                </td>
                                <td width="10%">
                                    {{ item.quantity }}
                                </td>
                                <td width="8%">
                                    {{ item.ordered }}
                                </td>
                                <td>
                                    <div v-for="invoice in item.attached_invoices" :key="invoice.id">
                                        <supply-invoices
                                            :supplyInvoicesProps="invoice"
                                            @selectInvoicesEmit="selectInvoice"
                                        />
                                    </div>
                                </td>
                                <td>
                                    {{ item.description }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>  
            </div>
            <footer class="c-supply-requisition-material-table__footer mt-3">
                <div class="button-wrapper">
                    <button 
                        v-show="supplyRequisitionMaterialSelectedList.length != 0"
                        @click="deleteMaterial()"   
                        class="c-button-delete-md--success mr-1"
                    >
                        Удалить Материал
                    </button>
                    <button v-show="supplyRequisitionMaterialSelectedList.length != 0 & supplyRequisitionMaterialIsAllAttachedMaterial" v-b-modal.supply-requisition-modal-add-invoice class="c-button-add">
                        <b-icon icon="plus-lg" scale="1"></b-icon>
                        <span>
                            Добавить счет
                        </span>
                    </button>
                </div>
            </footer>
        </div>
        <supply-requisition-modal-add-invoice 
            :MaterialsProps="supplyRequisitionMaterialSelectedList"
            :StocksProps="stockList"
            :ContractsProps="directoryContractsList"
        />
        <directory-materials-modal-set 
            @setMaterialEmit="setMaterial"
            :materialIdProps="selectMaterial"  
            :materialNameProps="selectMaterial.fullname"
        />
        <requisitions-invoice-modal-info
            :supplyInvoicesProps="selectInvoiceProps"
        />
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';

    import BaseIcon from "@/components/elements/BaseIcon"
    import SupplyInvoices from '@/components/requisition/SupplyInvoices'
    import ProgressRequisitions from '@/components/requisition/ProgressRequisitions'
    import DirectoryMaterialsModalSet from '@/components/specification/materialsModal/DirectoryMaterialsModalSet'
    import RequisitionsInvoiceModalInfo from '@/components/requisition/RequisitionsInvoiceModalInfo'
    import SupplyRequisitionModalAddInvoice from '@/components/requisition/SupplyRequisitionModalAddInvoice'

    export default {
        name: "SupplyRequisitionMaterial",
        data(){ 
            return {
                heightTabel: '',
                selectMaterial: '',
                selectInvoiceProps: {}
            }
        },
        components: {
            BaseIcon,
            SupplyInvoices,
            ProgressRequisitions,
            DirectoryMaterialsModalSet,
            RequisitionsInvoiceModalInfo,
            SupplyRequisitionModalAddInvoice,
        },
        computed: {
            ...mapGetters({
                stockList: 'stockListGetter',
                supplyMyRequisition: 'supplyMyRequisitionGetter',
                directoryContractsList: 'directoryContractsListGetter',
                supplyRequisitionMaterialList: 'supplyRequisitionMaterialListGetter',
                supplyRequisitionMaterialLoading: 'supplyRequisitionMaterialLoadingGetter',
                supplyRequisitionMaterialQuantity: 'supplyRequisitionMaterialQuantityGetters',
                supplyRequisitionMaterialSelectedList: 'supplyRequisitionMaterialSelectedListGetter',
                supplyRequisitionMaterialIsAllAttachedMaterial: 'supplyRequisitionMaterialIsAllAttachedMaterialGetter',
            })
        },
        methods: {
            ...mapActions({
                getUnits: 'getUnitsActions',
                stockSetList: 'stockSetListActions',
                supplyMyRequisitionSet: 'supplyMyRequisitionSetActions',
                directoryContractsSetList: 'directoryContractsSetListActions',
                supplyRequisitionMaterialSet: 'supplyRequisitionMaterialSetActions',
                supplyRequisitionMaterialDelete: 'supplyRequisitionMaterialDeleteActions',
                supplyRequisitionMaterialSelect: 'supplyRequisitionMaterialSelectActions',
                supplyRequisitionMaterialSetLoading: 'supplyRequisitionMaterialSetLoadingActions',
                supplyRequisitionMaterialSetQuantity: 'supplyRequisitionMaterialSetQuantityActions',
                supplyRequisitionMaterialSetInvoices: 'supplyRequisitionMaterialSetInvoicesActions',
                supplyRequisitionMaterialSetMaterial: 'supplyRequisitionMaterialSetMaterialActions',
                supplyRequisitionMaterialResetMaterial: 'supplyRequisitionMaterialResetMaterialActions',
            }),
            checkMaterial(row){
                this.selectMaterial = row
                this.supplyRequisitionMaterialSelect(row)
            },
            showModal(modal){
                if(modal == "directory"){
                    this.$bvModal.show('directory-materials-modal-set-material')
                }else if(modal == "invoice"){
                    this.$bvModal.show('supply-requisition-modal-add-invoice')
                }
            },
            showModalDirectory(item){
                this.selectMaterial = item
                this.$bvModal.show('directory-materials-modal-set-material')
            },
            setMaterial(selectedMaterial){
                let params = {
                    specificationId: this.specificationIdProps,
                    row: this.selectMaterial,
                    material: selectedMaterial
                }
                
                this.supplyRequisitionMaterialSetMaterial(params)
            },
            deleteMaterial(){
                this.supplyRequisitionMaterialDelete(this.supplyMyRequisition.id)
            },
            resetMaterial(item){
                let params = {
                    idRequisition: this.supplyMyRequisition.id,
                    idMaterial: item.id,
                    idDirectoryMaterial: item.directory_material.id
                }

                this.supplyRequisitionMaterialResetMaterial(params)
            },
            selectInvoice(_, invoice){
                this.selectInvoiceProps = invoice
                this.$bvModal.show('requisitions-invoice-modal-info')
            }
        },
        async mounted(){
            this.getUnits()
            this.stockSetList()
            this.supplyRequisitionMaterialSetLoading()
            this.directoryContractsSetList()

            let id = window.location.href.split('/').reverse()[1];
            await this.supplyMyRequisitionSet(id)

            this.supplyRequisitionMaterialSet(this.supplyMyRequisition.materials)

            await this.supplyRequisitionMaterialSetInvoices(id)

            this.supplyRequisitionMaterialSetLoading()
        }
    }
</script>

