<template>
    <b-modal
        id="master-requisition-modal-edit-for-specifications-draft"
        ref="master-requisition-modal-edit-for-specifications-draft"
        title="Новая заявка"
        content-class="c-modal-default c-modal-requisition"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>{{ form.number }}</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col ms="12" lg="6">
                    <b-row>
                        <b-col cols="12">
                            <label class="label-custom mt-2" for="input-name">Дата</label>
                            <date-picker
                                style="width: 100%" 
                                input-class='mx-input-modal'
                                placeholder="Укажите дату"
                                v-model.trim="$v.form.end_at.$model"
                                v-model="form.end_at"
                            >
                            </date-picker>
                        </b-col>
                        <b-col cols="12">
                            <label class="label-custom mt-2" for="input-name">Спецификация</label>
                            <b-form-input
                                id="input-live"
                                aria-describedby="input-live-help input-live-feedback"
                                disabled
                                class="input-custom"
                                v-model="selectSpecifiactions.name"
                            ></b-form-input>
                        </b-col>
                    </b-row>
                </b-col>
                <b-col cols="6">
                    <label class="label-custom mt-2" for="input-name">Комментарий к заявке</label>
                    <b-form-textarea
                        id="input-description"
                        class="input-custom"
                        aria-describedby="input-description-feedback"
                        v-model="form.description"
                        placeholder="Введите описание..."
                        trim
                        rows="5"
                        no-resize
                    ></b-form-textarea>
                </b-col>
            </b-row>
            <b-row>
                <label class="label-custom mt-2" for="input-name">Материалы</label>
                <div class="scroll-wrapper-table">
                    <div class='c-master-requisitions-universal-table mt-2'>
                        <table>
                            <thead>
                                <tr>
                                    <th width="50px"></th>
                                    <th width="78px">Позиция</th>
                                    <th style="min-width: 200px;">
                                        Название
                                    </th>
                                    <th width="10%" style="min-width: 80px;">Ед. изм</th>
                                    <th width="13%">Количество по спецификации</th>
                                    <th width="10%">Заказать</th>
                                    <th width="10%">Заказано</th>
                                    <th>Примечание</th>
                                </tr>
                            </thead>
                        </table>
                        <div class='c-master-requisitions-universal-table__body' :style="{ maxHeight: 400 + 'px'}" >
                            <table>
                                <tbody>
                                    <tr v-for="item in masterRequisitionForSpecificationList" :key="item.id" >
                                        <td width="50px">
                                            <input  
                                                v-show="!item.is_group" 
                                                class='checkbox' 
                                                type='checkbox' 
                                                disabled 
                                                :checked="item.checked"
                                            />
                                        </td>
                                        <td width="78px" class="text">
                                            {{ item.position }}
                                        </td>
                                        <td style="min-width: 200px !important;" :class="{ 'groupe': item.is_group }">
                                            {{ item.fullname }}
                                        </td>
                                        <td style="min-width: 80px;" width="10%"> 
                                            <input 
                                                v-show="!item.is_group" 
                                                v-model="item.unit"
                                            /> 
                                        </td>
                                        <td width="13%"> {{ item.quantity }} </td>
                                        <td width="10%"> 
                                            <input
                                                v-show="!item.is_group"
                                                @input="inputOrder(item)" 
                                                v-model="item.order"
                                            /> 
                                        </td>
                                        <td width="10%"> 
                                            {{ item.ordered }}
                                        </td>
                                        <td> 
                                            <base-textarea v-model="item.description" :valueProps="item.description" > </base-textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                class="c-button-save--success"
                @click="send(true)"
            >
                Отправить заявку
            </button>
            <button 
                class="c-button-save--success"
                @click="send(false)"
            >
                Редактировать черновик
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import { required } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";
    
    import BaseTextarea from '@/components/elements/BaseTextarea'

    export default {
        name: "MasterRequisitionModalEditForSpecificationsDraft",
        data(){
            return {
                form: {
                    id: '',
                    number: '',
                    end_at: '',
                    description: ''
                },
                selectSpecifiactions: {
                    name: null
                },
                idSpecifiactions: '',
            }
        },
        props: {
            requisitionProps: { type: Object, default: () => {} }
        },
        validations: {
            form: {
                end_at: { required },
                description: '',
                fixed: false
            }
        },
        mixins: [validationMixin],
        components: {
            BaseTextarea
        },
        computed: {
            ...mapGetters({
                unitsList: 'materialsUnitsListGetter',
                masterRequisition: 'masterRequisitionGetter',
                masterSpecificationList: 'masterSpecificationListGetter',
                masterRequisitionForSpecificationList: 'masterRequisitionForSpecificationListGetter',
                masterRequisitionForSpecificationError: 'masterRequisitionForSpecificationErrorGetter'
            }),
        },
        methods: {
            ...mapActions({
                masterRequisitionSet: 'masterRequisitionSetActions',
                masterRequisitionForSpecificationChecked: 'masterRequisitionForSpecificationCheckedActions',
                masterRequisitionForSpecificationDraftEdit: 'masterRequisitionForSpecificationDraftEditActions',
                masterRequisitionForSpecificationDraftSetList: 'masterRequisitionForSpecificationDraftSetListActions',
            }),
            hideModal(){
                this.$refs['master-requisition-modal-edit-for-specifications-draft'].hide();
            },
            selectSpec(){
                this.idSpecifiactions = this.selectSpecifiactions.id
            },
            inputOrder(item){
                this.masterRequisitionForSpecificationChecked(item)
            },
            async send(fixed){
                this.form.fixed = fixed
                await this.masterRequisitionForSpecificationDraftEdit(this.form)

                if(!this.masterRequisitionForSpecificationError){
                    this.hideModal()
                }
            },
            async mountedData(){
                await this.masterRequisitionSet(this.requisitionProps.id)

                this.form.number = this.masterRequisition.number
                this.form.end_at = new Date(this.masterRequisition.end_at)
                this.form.description = typeof this.masterRequisition.description != 'undefined' ? this.masterRequisition.description : ''
                this.selectSpecifiactions = this.masterRequisition.specification
                this.idSpecifiactions = this.masterRequisition.specification.id
                this.form.id = this.masterRequisition.id

                let param = {
                    specificationId: this.idSpecifiactions,
                    materials: this.masterRequisition.materials,
                }

                this.masterRequisitionForSpecificationDraftSetList(param)
            },
        }
    }

</script>