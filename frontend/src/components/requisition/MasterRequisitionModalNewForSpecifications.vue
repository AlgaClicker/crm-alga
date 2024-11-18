<template>
    <b-modal
        id="master-requisition-modal-new-for-specifications"
        ref="master-requisition-modal-new-for-specifications"
        title="Новая заявка"
        content-class="c-modal-default c-modal-requisition"
        centered 
        @shown="mountedData"
    >   
        <template #modal-header>
            <h5>Новая заявка</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col cols="6">
                    <b-row>
                        <b-col cols="12">
                            <label class="label-custom mt-2" for="input-name">Дата поставки</label>
                            <date-picker
                                style="width: 100%" 
                                input-class='mx-input-modal'
                                placeholder="Укажите дату"
                                v-model.trim="$v.form.end_at.$model"
                                v-model="form.end_at"
                            >
                            </date-picker>
                        </b-col>
                        <b-col>
                            <label class="label-custom mt-2" for="input-name">Спецификация</label>
                            <multiselect 
                                v-model="selectSpecifiactions"  
                                placeholder="Выберите спецификацию" 
                                :options="masterSpecificationList" 
                                label="name"
                                track-by="name"
                                :show-labels="false"
                                @input=selectSepc()
                            >
                            <template slot="option" slot-scope="props">
                                <div class="option__desc">
                                    <div class="c-avatar-list">
                                        <div class="c-list-spec">
                                            <span class="ml-1"> {{ props.option.name }} </span>
                                            <span class="c-list-spec__object"> ( {{ props.option?.objectName }} )</span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            </multiselect>
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
                <div v-if="masterRequisitionForSpecificationLoading" class="c-spinner_wrapper c-empty-table mt-4">
                    <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                </div>
                <b-col v-else v-show="idSpecifiactions" cols="12">
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
                                                <input v-if="!item.is_group" class='checkbox' type='checkbox' disabled :checked="item.checked"/>
                                            </td>
                                            <td width="78px" class="text">
                                                {{ item.position }}
                                            </td>
                                            <td style="min-width: 200px !important;" :class="{ 'groupe': item.is_group }">
                                                {{ item.fullname }}
                                            </td>
                                            <td style="min-width: 80px;" width="10%"> 
                                                <input v-model="item.unit"/> 
                                            </td>
                                            <td width="13%"> {{ item.quantity }} </td>
                                            <td width="10%"> 
                                                <input @input="inputOrder(item)" v-model="item.order"/> 
                                            </td>
                                            <td  width="10%"> 
                                                <template v-if="!item.is_group">
                                                    {{ item.ordered }}
                                                </template>
                                            </td>
                                            <td> 
                                                <base-textarea v-model="item.description" :valueProps="item.description"> </base-textarea>
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
                class="mt-4"
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
                @click="send(false)"  
            >
                Сохранить как черновик
            </button>
            <button 
                @click="send(true)" class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Отправить заявку
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import { required } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";
    import { dateMin } from '@/utils/customValidations';

    import BaseTextarea from '@/components/elements/BaseTextarea'

    export default {
        name: 'MasterRequisitionModalNewForSpecifications',
        data(){
            return {
                form: {
                    end_at: null,
                    description: ''
                },
                selectSpecifiactions: null,
                idSpecifiactions: null,
            }
        },
        props: {
            specificationProps: { type: Object, default: () => {} }
        },
        components: {
            BaseTextarea
        },
        validations: {
            form: {
                end_at: { required, dateMin },
            }
        },
        mixins: [validationMixin],
        computed: {
            ...mapGetters({
                unitsList: 'materialsUnitsListGetter',
                masterSpecificationList: 'masterSpecificationListGetter',
                accountsCompanyListByRole: 'accountsCompanyListByRoleGetter',
                masterRequisitionForSpecificationList: 'masterRequisitionForSpecificationListGetter',
                masterRequisitionForSpecificationLoading: 'masterRequisitionForSpecificationLoadingGetter',
                masterRequisitionForSpecificationValidations: 'masterRequisitionForSpecificationValidationsGetter',
            }),
            validationsComputed() {
                if( 
                    this.$v.form.end_at.$invalid  == false &&
                    this.masterRequisitionForSpecificationValidations == true
                ){
                    return true
                }else {
                    return false
                }
            }
        },
        methods: {
            ...mapActions({
                getUnits: 'getUnitsActions',
                accountsCompanySetActions: 'accountsCompanySetActions',
                masterSpecificationsSetList: 'masterSpecificationSetListActions',
                masterRequisitionForSpecificationCreate: 'masterRequisitionForSpecificationCreateActions',
                masterRequisitionForSpecificationChecked: 'masterRequisitionForSpecificationCheckedActions',
                masterRequisitionForSpecificationSetList: 'masterRequisitionForSpecificationSetListActions',
            }),
            async selectSepc(){
                this.idSpecifiactions = this.selectSpecifiactions.id
                await this.masterRequisitionForSpecificationSetList(this.idSpecifiactions)
            },
            inputOrder(item){
                this.masterRequisitionForSpecificationChecked(item)
            },
            async send(fixed){
                let form = {
                    idSpecifiactions: this.idSpecifiactions,
                    description: this.form.description,
                    end_at: this.form.end_at,
                    isFixed: fixed
                }
                await this.masterRequisitionForSpecificationCreate(form)
                this.hideModal()
            },
            hideModal(){
                this.$refs['master-requisition-modal-new-for-specifications'].hide();
                this.idSpecifiactions = ''
                this.masterRequisitionForSpecificationSetList()
                this.selectSpecifiactions = null
            },
            async mountedData(){
                await this.getUnits()
                await this.accountsCompanySetActions()
                
                if(typeof this.specificationProps.id != 'undefined' ){
                    this.idSpecifiactions = this.specificationProps.id
                    this.selectSpecifiactions = this.specificationProps
                    this.masterRequisitionForSpecificationSetList(this.idSpecifiactions)
                } 
            },
        }
    }
</script>