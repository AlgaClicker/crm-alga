<template>
    <b-modal
        id="master-requisition-modal-universal"
        ref="master-requisition-modal-universal"
        title="Новая заявка"
        content-class="c-modal-default c-modal-requisition"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Универсальная заявка</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col cols="6">
                    <b-row>
                        <b-col cols="12">
                            <label class="label-custom  mt-2" for="input-name">Дата поставки</label>
                            <date-picker
                                style="width: 100%" 
                                input-class='mx-input-modal'
                                v-model.trim="$v.form.end_at.$model"
                                v-model="form.end_at"
                            >
                            </date-picker>
                        </b-col>
                        <b-col cols="12">
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
                    <label class="label-custom mt-2" for="input-live">Описание</label>
                    <b-form-textarea
                        id="input-description"
                        class="input-custom"
                        aria-describedby="input-description-feedback"
                        v-model="form.description"
                        placeholder="Введите описание"
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
                                        <th width="10%">Ед. изм</th>
                                        <th width="10%">Заказать</th>
                                        <th>Комментарий</th>
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
                                                <template v-if="item.directoryMaterialId == '' || typeof item.directoryMaterialId == 'undefined'">
                                                    <input @click="resetSearch(item.fullname)" v-model="item.fullname" @input="inputMaterial($event, item)">
                                                    <div v-show="selectRowPosition == item.position && directoryMaterialsList.length > 0" class="select-material">
                                                        <ul>
                                                            <li @click="selectMaterial(material, item.position)" v-for="material in materialComputed" :key="material.id">
                                                                <div v-if="typeof material.directoryMaterialId != 'undefined' && material.directoryMaterialId != '' "> 
                                                                    <p> {{ material.fullname }} </p> (справочник) 
                                                                </div>
                                                                <div v-else> 
                                                                    <p> {{ material.fullname }} </p> (спецификация) 
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </template>
                                                <div class="material" v-else>
                                                    <div class="material__attached"> 
                                                        <base-icon iconProps="more" sizeProps="sm" /> 
                                                        <span>
                                                            {{ item.directoryMaterial?.name }} 
                                                            <div class="material__delete" @click="unAttachMaterial(item)"> ( удалить ) </div>
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
                                                <base-textarea v-model="item.description" > </base-textarea>
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
                @click="sendRequisition(false)"  
            >
                Сохранить как черновик
            </button>
            <button 
                @click="sendRequisition(true)" class="mt-4" 
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
    import BaseIcon from "@/components/elements/BaseIcon"
    import BaseTextarea from '@/components/elements/BaseTextarea'

    export default {
        name: 'MasterRequisitionModalUniversal',
        data(){
            return {
                selectSpecifiactions: null,
                selectRowPosition: null,
                selectUnitList: null,
                form: {
                    end_at: '',
                    specificationId: '',
                    description: ''
                }
            }
        },
        props: {
            specificationProps: { type: Object, default: () => {} }
        },
        validations: {
            form: {
                end_at: { required, dateMin },
            }
        },
        mixins: [validationMixin],
        components: {
            BaseIcon,
            BaseTextarea
        },
        computed: {
            ...mapGetters({
                unitsList: 'materialsUnitsListGetter',
                directoryMaterialsList: 'directoryMaterialsListGetter',
                masterSpecificationList: 'masterSpecificationListGetter',
                accountsCompanyListByRole: 'accountsCompanyListByRoleGetter',
                masterRequisitionUniversalList: 'masterRequisitionUniversalListGetter',
                masterRequisitionUniversalError: 'masterRequisitionUniversalErrorGetter'
            }),
            validationsComputed() {
                if( 
                    this.$v.form.end_at.$invalid == false &&
                    this.masterRequisitionUniversalList.filter( item => item.fullname != '' && item.unit != '').length == this.masterRequisitionUniversalList.filter( item => item.fullname != '' ).length
                ){
                    return true
                }else {
                    return false
                }
            },
            materialComputed(){
                let directoryMaterial = this.directoryMaterialsList.map(( item ) => {
                    return { ...item, fullname: item.name, directoryMaterialId: item.id }
                }).filter( item => this.masterRequisitionUniversalList.filter( material => material.directoryMaterialId == item.directoryMaterialId ).length == 0)
                
                return directoryMaterial
            },
        },
        methods: {
            ...mapActions({
                getUnits: 'getUnitsActions',
                directoryMaterialsSearch: 'directoryMaterialsSearchActions',
                masterSpecificationsSetList: 'masterSpecificationSetListActions',
                masterRequisitionUniversalSend: 'masterRequisitionUniversalSendActions',
                masterRequisitionUniversalAddRow: 'masterRequisitionUniversalAddRowActions',
                masterRequisitionUniversalSetMaterial: 'masterRequisitionUniversalSetMaterialActions',
                masterRequisitionUniversalSetEmptyList: 'masterRequisitionUniversalSetEmptyListActions',
                masterRequisitionUniversalUnAttachMaterial: 'masterRequisitionUniversalUnAttachMaterialActions',
            }),
            async resetSearch(value){
               await this.directoryMaterialsSearch(value)
            },
            async sendRequisition(fixed){
                
                console.log(this.masterRequisitionUniversalError)

                let params = {
                    form: {},
                    fixed: false
                }

                let form = {
                    end_at: this.form.end_at,
                    description: this.form.description,
                    materials: this.masterRequisitionUniversalList.filter(item => item.quantity > 0).map( ( item ) => {

                        if(item.directoryMaterialId != ""){
                            return {
                                name: item.fullname,
                                unit: { code: this.unitsList.filter(element => element.title == item.unit)[0].code },
                                material: { id: item.directoryMaterialId },
                                quantity: Number(item.quantity),
                                description: item.description
                            }
                        } else {

                            return {
                                name: item.fullname,
                                unit: { code: this.unitsList.filter(element => element.title == item.unit)[0].code },
                                quantity: Number(item.quantity),
                                description: item.description
                            }
                        }

                    })
                }

                if(this.form.specificationId != '' && this.form.specificationId != null){
                    form.specificationId = this.form.specificationId
                }

                params.form = form
                params.fixed = fixed

                this.masterRequisitionUniversalSend(params)

                if(!this.masterRequisitionUniversalError){
                    this.hideModal()
                }
            },
            selectMaterial(item, position){
                this.selectRowPosition = null

                let params = {
                    position: position,
                    material: item
                }
                this.masterRequisitionUniversalSetMaterial(params)
            },
            async selectUnit(value, row){
                var form = row
                form.unit = value
                form.specificationId = this.specificationIdProps
                
                await this.specificationMaterialEdit(form)
            },
            async selectSepc(){
                this.form.specificationId = this.selectSpecifiactions?.id
            },
            async inputMaterial(event, item){

                this.selectRowPosition = item.position 
                let value = event.target.value
                console.log(item)

                if(value == ''){
                    this.unAttachMaterial(item)
                }

                if(value.length > 2){
                    await this.directoryMaterialsSearch(value)
                }

            },
            unAttachMaterial(material){
                this.selectRowPosition = null
                this.masterRequisitionUniversalUnAttachMaterial(material)
            },
            hideModal(){
                this.selectRowPosition = null
                this.$refs['master-requisition-modal-universal'].hide();
            },
            async mountedData(){

                if( typeof this.specificationProps != 'undefined'){
                    console.log(this.specificationProps)
                    this.selectSpecifiactions = this.specificationProps
                } else {
                    this.selectSpecifiactions = null
                }

                this.selectRowPosition = null
                this.selectUnitList = null

                this.form = {
                    end_at: '',
                    specificationId: '',
                    description: ''
                }

                this.masterRequisitionUniversalSetEmptyList()
                await this.getUnits()
            }
        }
    }
</script>