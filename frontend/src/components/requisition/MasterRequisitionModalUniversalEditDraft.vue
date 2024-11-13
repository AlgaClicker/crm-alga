<template>
    <b-modal
        id="master-requisition-modal-universal-edit-draft"
        ref="master-requisition-modal-universal-edit-draft"
        title="Редактировать универсальную заявку"
        content-class="c-modal-default c-modal-requisition"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Редактировать {{ requisitionProps.number }}</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col ms="12" md="6">
                    <b-row>
                        <b-col  ms="12" cols="12">
                            <label class="label-custom mt-2" for="input-name">Дата</label>
                            <date-picker
                                style="width: 100%" 
                                input-class='mx-input-modal'
                                placeholder="Укажите дату"
                                v-model.trim="$v.end_at.$model"
                                v-model="end_at"
                            >
                            </date-picker>
                        </b-col>    
                        <b-col  ms="12">
                            <label class="label-custom mt-2" for="input-name">Спецификация</label>
                            <multiselect 
                                v-model="value"  
                                placeholder="Выберите спецификацию" 
                                :options="masterSpecificationList" 
                                label="name"
                                track-by="name"
                                :show-labels="false"
                                @input=selectSpec()
                            >
                                <template slot="option" slot-scope="props">
                                    <div class="option__desc">
                                        <div class="c-list-spec">
                                            <span class="ml-1"> {{ props.option.name }} </span>
                                            <span class="c-list-spec__object"> ( {{ props.option?.objectName }} )</span>
                                        </div>
                                    </div>
                                </template>
                            </multiselect>
                        </b-col>
                    </b-row>
                </b-col>
                <b-col ms="12" md="6">
                    <label class="label-custom mt-2" for="input-name">Комментарий к заявке</label>
                    <b-form-textarea
                        id="input-description"
                        class="input-custom"
                        aria-describedby="input-description-feedback"
                        v-model="description"
                        placeholder="Введите описание..."
                        trim
                        rows="5"
                        no-resize
                    ></b-form-textarea>
                </b-col>
            </b-row>
            <b-row>
                 <b-col class="mt-4" cols="12">
                    <div class="material-wrapper-header">
                        <label class="label-custom" for="input-name">Материал</label>
                        <button @click="() => this.masterRequisitionUniversalAddRow()" v-tooltip.bottom-start="'добавить строку'" class="c-tools-button">
                            <b-icon scale="0.8" icon="plus-lg"></b-icon>
                        </button>
                    </div>
                    <div class="scroll-wrapper-table">
                        <div class='c-master-requisitions-universal-table mt-2'>
                            <table>
                                <thead>
                                    <tr>
                                        <th width="40px"></th>
                                        <th>Название</th>
                                        <th width="10%">Ед.изм</th>
                                        <th width="10%">Заказать</th>
                                        <th>Комментарий</th>
                                        <th width="120px"></th>
                                    </tr>
                                </thead>
                            </table>
                            <div class='c-master-requisitions-universal-table__body' :style="{ height: 400 + 'px'}" >
                                <table>
                                    <tbody>
                                        <tr v-for="item in masterRequisitionUniversalList" :key="item.id" >
                                            <td width="40px" class="text">
                                                <div class="center">
                                                    {{ item.position }}
                                                </div>
                                            </td>
                                            <td class="name-input">
                                                <template v-if="item?.directoryMaterialId == '' || typeof item.directoryMaterialId == 'undefined' ">
                                                    <input @click="resetSearch(item.fullname)" v-model="item.fullname" @input="inputMaterial($event, item)">
                                                    <div v-show="selectRowPosition == item.position && directoryMaterialsList.length > 0" class="select-material">
                                                        <ul>
                                                            <li @click="selectMaterial(material, item.position)" v-for="material in materialComputed" :key="material?.id">
                                                                 <div> 
                                                                    <p> {{ material.fullname }} </p> (справочник) 
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </template>
                                                <div class="material" v-else>
                                                    <div class="material__attached"> 
                                                        <span>
                                                            {{ item.directoryMaterial?.name }} 
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="c-specification-table-select" width="10%">
                                                <select v-model="item.unit">      
                                                    <option v-for="options in unitsList" :key="options.code"> {{ options.title }}</option>
                                                </select>
                                            </td>
                                            <td width="10%">
                                                <input v-model="item.quantity" />
                                            </td>
                                            <td>
                                                <base-textarea v-model="item.description" :valueProps="item.description" > </base-textarea>
                                            </td>
                                            <td width="120px">
                                                <button v-if="item.id" @click="deleteRow(item)"  class="c-button-add-sm">
                                                    Удалить
                                                </button>
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
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
                @click="send(true)"
            >
                Отправить заявку
            </button>
            <button 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
                @click="send(false)"
            >
                Редактировать черновик
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import { required } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";
    import BaseTextarea from '@/components/elements/BaseTextarea'
    import { dateMin } from '@/utils/customValidations';

    export default {    
        name: "MasterRequisitionModalUniversalEditDraft",
        data(){
            return {
                id: '',
                end_at: '',
                description: '',
                number: '',
                selectRowPosition: '',
                value: ''
            }
        },
        props: {
            requisitionProps: { type: Object, default: () => {} }
        },
        mixins: [validationMixin],
        validations: {
            end_at: { required, dateMin },
        },
        components: {
            BaseTextarea 
        },
        computed: {
            ...mapGetters({
                unitsList: 'materialsUnitsListGetter',
                directoryMaterialsList: 'directoryMaterialsListGetter',
                masterSpecificationList: 'masterSpecificationListGetter',
                masterRequisitionUniversalList: 'masterRequisitionUniversalListGetter',
            }),
            materialComputed(){

                let directoryMaterial = this.directoryMaterialsList.map(( item ) => {
                    
                    return { ...item, fullname: item.name, directoryMaterialId: item?.id }
                }).filter( item => this.masterRequisitionUniversalList.filter( material => material?.directoryMaterialId == item?.directoryMaterialId ).length == 0)
                
                return directoryMaterial
            },
            validationsComputed() {
                if( 
                    this.$v.end_at.$invalid == false
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
                directoryMaterialsSearch: 'directoryMaterialsSearchActions',
                masterRequisitionUniversalEdit: 'masterRequisitionUniversalEditActions',
                masterRequisitionUniversalAddRow: 'masterRequisitionUniversalAddRowActions',
                masterRequisitionUniversalSetDraft: 'masterRequisitionUniversalSetDraftActions',
                masterRequisitionUniversalEditDraft: 'masterRequisitionUniversalEditDraftActions',
                masterRequisitionUniversalDeleteRow: 'masterRequisitionUniversalDeleteRowActions',
                masterRequisitionUniversalSetMaterial: 'masterRequisitionUniversalSetMaterialActions',
                masterRequisitionUniversalSetEmptyList: 'masterRequisitionUniversalSetEmptyListActions',
                masterRequisitionUniversalUnAttachMaterial: 'masterRequisitionUniversalUnAttachMaterialActions',
            }),
            async send(fixed){

                let params = {
                    fixed: fixed,
                    requisitionId: this.id,
                    form: {
                        end_at: this.end_at,
                        description: this.description,
                        number: this.number,
                    }
                }

                await this.masterRequisitionUniversalEditDraft(params)

                this.hideModal()
            },
            deleteRow(item){
                this.masterRequisitionUniversalDeleteRow(item.position)
            },
            async inputMaterial(event, item){

                this.selectRowPosition = item.position 
                let value = event.target.value

                if(value == ''){
                    this.unAttachMaterial(item)
                }

                if(value.length > 2){
                    await this.directoryMaterialsSearch(value)
                }

            },
            selectSpec(){
                this.specifiactions = this.value
            },
            unAttachMaterial(material){
                this.selectRowPosition = null
                this.masterRequisitionUniversalUnAttachMaterial(material)
            },
            async resetSearch(value){
               await this.directoryMaterialsSearch(value)
            },
            selectMaterial(item, position){
                this.selectRowPosition = null

                let params = {
                    position: position,
                    material: item
                }
                this.masterRequisitionUniversalSetMaterial(params)
            },
            mountedData(){

                this.getUnits()
                this.masterRequisitionUniversalSetEmptyList()
                this.masterRequisitionUniversalSetDraft(this.requisitionProps.id)

                this.id = this.requisitionProps?.id
                this.end_at = this.requisitionProps.end_at
                this.description = this.requisitionProps.description
                this.number = this.requisitionProps.number 

                if(typeof this.requisitionProps.specifiaction == 'undefined'){
                    this.specifiaction = null
                }else {
                    this.specifiaction = this.requisitionProps.specifiaction
                    this.value = this.requisitionProps.specifiaction.id
                }

                this.end_at = new Date(this.end_at)

            },
            hideModal(){
                this.$refs['master-requisition-modal-universal-edit-draft'].hide();
            }
        }
    }
</script>