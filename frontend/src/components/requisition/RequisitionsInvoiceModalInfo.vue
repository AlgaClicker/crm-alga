<template>
    <b-modal
        id="requisitions-invoice-modal-info"
        ref="requisitions-invoice-modal-info"
        title="Информация"
        content-class="c-modal-default c-modal-requisition"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>{{ supplyInvoicesProps.number }}</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-body-invoice">
            <b-row>
                <b-col sm="12" lg="6">
                    <label class="label-custom" for="input-name">Дата</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        v-model="delivery_at"
                        placeholder="Введите дату"
                        disabled
                    ></b-form-input>
                </b-col>
                <b-col sm="12" lg="6">
                    <label class="label-custom" for="input-name">Договор</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        v-model="contractNumber"
                        placeholder="Введите дату"
                        disabled
                    ></b-form-input>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col sm="12" lg="6">
                    <label class="label-custom" for="input-name">Склад</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        v-model="stockName"
                        placeholder="Выберите склад"
                        disabled
                    ></b-form-input>
                    <label class="label-custom mt-3" for="input-name">Статус</label>
                    <b-form-select 
                        style="width: 100%;" 
                        v-model="status"
                        :options="statusComputed"
                    >
                    </b-form-select>
                </b-col>
                <b-col sm="12" lg="6">
                    <label class="label-custom" for="input-name">Комментарий</label>
                    <b-form-textarea
                        id="input-description"
                        class="input-custom"
                        aria-describedby="input-description-feedback"
                        v-model="description"
                        placeholder="Введите описание..."
                        trim
                        rows="5"
                        no-resize
                        disabled
                    ></b-form-textarea>
                </b-col>
            </b-row>
            <b-row class="scroll-wrapper-table">
                <label class="label-custom mt-4" for="input-name">Материалы</label>
                <div class='c-master-requisitions-universal-table mt-2'>
                    <table>
                        <thead>
                            <tr>
                                <th width="10%">Позиция</th>
                                <th>Название</th>
                                <th width="50px">Ед. изм</th>
                                <th width="10%">Заказать</th>
                                <th>Файлы</th>
                                <th width="10%">Ценна</th>
                                <th width="12%">Комментарий</th>
                            </tr>
                        </thead>
                    </table>
                    <div class='c-master-requisitions-universal-table__body' :style="{ maxHeight: 400 + 'px'}" >
                        <table>
                            <tbody>
                                <tr v-for="item in supplyInvoicesProps.materials" :key="item.id" >
                                    <td width="10%">
                                        <input v-model="item.directory_material.position" />
                                    </td>
                                    <td>
                                        <input v-model="item.directory_material.name" />
                                    </td>
                                    <td width="50px">
                                        <input v-model="item.directory_material.unit.title" />
                                    </td>
                                    <td width="10%">
                                        <input v-model="item.quantity" />
                                    </td>
                                    <td class="attach-files">
                                        <file
                                            v-for="file in item.files" :key="file.hash"
                                            :fileProps="file"
                                        />
                                    </td>
                                    <td width="10%">
                                        <input v-model="item.price" />
                                    </td>
                                    <td width="12%">
                                        {{ item.description }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="deleteInvoice()"   
                class="c-button-delete--success"
            >
                Удалить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex' 
    import { statusInvoices } from '@/utils/types'
    import File from '@/components/elements/files/File'

    export default {
        name: 'RequisitionsInvoiceModalInfo',
        data(){
            return {
                delivery_at: null,
                amount: null,
                created_at: null,
                description: null,
                contractNumber: null,
                stockName: null,
                status: null,
            }
        },
        props: {
            supplyInvoicesProps:  { type: Object, default: () => {}},
        },
        components: {
            File,
        },
        computed: {
            ...mapGetters({
                supplyRequisitionMaterialError: 'supplyRequisitionMaterialErrorGetter',
                supplyMyRequisition: 'supplyMyRequisitionGetter'
            }),
            statusComputed(){   
                return statusInvoices
            }
        },
        methods: {
            ...mapActions({
                supplyRequisitionMaterialDeleteInvoice: 'supplyRequisitionMaterialDeleteInvoiceActions',
            }),
            async deleteInvoice(){
                let params = {
                    idRequisition: this.supplyMyRequisition.id,
                    idInvoices: this.supplyInvoicesProps.id
                }

                await this.supplyRequisitionMaterialDeleteInvoice(params)

                console.log(this.supplyRequisitionMaterialError)

                if(!this.supplyRequisitionMaterialError){
                    this.hideModal()
                }
            },
            hideModal(){
                this.$refs['requisitions-invoice-modal-info'].hide();
            },
            mountedData(){
                this.delivery_at = this.supplyInvoicesProps.delivery_at.split('T')[0]
                this.amount = this.supplyInvoicesProps.amount
                this.description = this.supplyInvoicesProps.description
                this.contractNumber = this.supplyInvoicesProps.contract?.number
                this.stockName = this.supplyInvoicesProps.stock.name
            }
        }
    }
</script>