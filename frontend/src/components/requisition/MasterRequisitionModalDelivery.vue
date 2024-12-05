<template>
    <b-modal 
        id="master-requisition-modal-delivery"
        ref="master-requisition-modal-delivery"
        content-class="c-modal-default c-modal-requisition"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Поставка от {{ deliveryProps.delivery_at | dateFilterWithoutTime }}</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col cols="12">
                    <div class="material-wrapper-header">
                        <label class="label-custom" for="input-name">Материал</label>
                    </div>
                    <div class="scroll-wrapper-table">
                        <div class="c-master-requisitions-universal-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="40px"></th>
                                        <th>Название</th>
                                        <th width="10%">Ед.изм</th>
                                        <th width="10%">Всего заказанно по заявке</th>
                                        <th width="10%">Заказанно в поставке</th>
                                        <th width="10%">Поставленно</th>
                                        <th>Комментарий</th>
                                    </tr>
                                </thead>
                            </table>
                            <div class='c-master-requisitions-universal-table__body' :style="{ height: 500 + 'px'}" >
                                <table>
                                    <tbody>
                                        <tr v-for="item in masterRequisitionDeliveryMaterialList" :key="item.id" >
                                            <td width="40px" class="text">
                                                <input class='checkbox' type='checkbox' disabled :checked="item.checked"/>
                                            </td>
                                            <td class="text">
                                                {{ item.requisition_material.name }}  
                                            </td>
                                            <td width="10%">
                                                {{ item.requisition_material.unit.title }} 
                                            </td>
                                            <td width="10%">
                                                {{ item.requisition_material_quantity }}
                                            </td>
                                            <td width="10%">
                                                {{ item.delivery_quantity }}
                                            </td>
                                            <td width="10%">
                                                <input 
                                                    @input="input($event, item)"
                                                    v-model="item.confirmed_quantity"
                                                /> 
                                            </td>
                                            <td>
                                                <div class="comment">
                                                    <b-form-textarea
                                                        id="input-description"
                                                        class="input-custom"
                                                        aria-describedby="input-description-feedback"
                                                        v-model="item.description"
                                                        placeholder="Введите описание..."
                                                        trim
                                                        rows="3"
                                                        max-rows="12"
                                                        no-resize
                                                    ></b-form-textarea>
                                                    <files-block-attach 
                                                        class="mt-2"
                                                        :fileProps="item.requisition_material.files"
                                                        :idMaterialProps="item.requisition_material_id"
                                                        :titleProps="'Прикрепить файл'"
                                                        @addFileEmit="addFile"
                                                        @deleteFileEmit="deleteFile"
                                                    />
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>    
                            </div>
                        </div>
                    </div>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="confirmDelivery()"
                class="c-button-save--success"
            >
                <div v-if="masterRequisitionDeliveryPartiallyConfirmed"> Подтвердить </div>
                <div v-else> Подтвердить частично</div>
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex' 
    import FilesBlockAttach from '@/components/elements/files/FilesBlockAttach'

    export default {
        name: 'MasterRequisitionModalDelivery',
        data(){
            return {

            }
        },
        components: {
            FilesBlockAttach
        },
        props: {
            deliveryProps: { type: Object, default: () => {} }
        },
        computed: {
            ...mapGetters({
                masterRequisitionDeliveryError: 'masterRequisitionDeliveryErrorGetter',
                masterRequisitionDeliveryMaterialList: 'masterRequisitionDeliveryMaterialListGetter',
                masterRequisitionDeliveryPartiallyConfirmed: 'masterRequisitionDeliveryPartiallyConfirmedGetter'
            }),
        },
        methods: {
            ...mapActions({
                masterRequisitionDeliveryChecked: 'masterRequisitionDeliveryCheckedActions',
                masterRequisitionDeliveryConfirmed: 'masterRequisitionDeliveryConfirmedActions',
                masterRequisitionDeliveryAttachFile: 'masterRequisitionDeliveryAttachFileActions',
                masterRequisitionDeliverySetMaterialList: "masterRequisitionDeliverySetMaterialListActions",
                masterRequisitionDeliveryDeleteAttachFile: 'masterRequisitionDeliveryDeleteAttachFileActions'
            }),
            async confirmDelivery(){
                await this.masterRequisitionDeliveryConfirmed(this.deliveryProps.id)

                if(this.masterRequisitionDeliveryError == ''){
                    this.$refs['master-requisition-modal-delivery'].hide();
                }
            },
            addFile(params){
                this.masterRequisitionDeliveryAttachFile(params)
            },
            deleteFile(params){
                this.masterRequisitionDeliveryDeleteAttachFile(params.hash)
            },
            hideModal(){
                this.$refs['master-requisition-modal-delivery'].hide();
            },
            input($event, item){

                let params = {
                    requisition_material_id: item.requisition_material_id,
                    confirmed_quantity: $event.target.value
                }

                this.masterRequisitionDeliveryChecked(params)
            },
            async mountedData(){  
                this.masterRequisitionDeliverySetMaterialList(this.deliveryProps.materials)
            }
        }
    }
</script>