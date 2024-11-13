<template>
    <b-modal
        id="directory-contracts-supply-modal-info"
        ref="directory-contracts-supply-modal-info"
        centered 
        content-class="c-modal-default directory-contracts"
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Договор ( {{ form.number_sys }} )</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-name">Партнер</label>
                    <multiselect 
                        v-model="form.partner"  
                        placeholder="Выберите партнера" 
                        :options="partnersListProps" 
                        label="name"
                        track-by="name"
                        :show-labels="false"
                    >
                        <template slot="option" slot-scope="props">
                            <div class="option__desc">
                                {{ props.option.name }}
                            </div>
                        </template>
                    </multiselect>
                </b-col>
                <b-col class="mt-3" cols="6">
                    <label class="label-custom" for="input-type" >Дата начала и завершения договора</label>
                    <date-picker
                        input-class='mx-input-modal'
                        style="width: 100%" 
                        v-model="date"
                        range
                    ></date-picker>
                </b-col>
            </b-row>
            <b-row>
                <b-col class="mt-3" cols="6">
                    <label class="label-custom" for="input-type" >Номер договора</label>
                    <b-form-input
                        id="input-number"
                        aria-describedby="input-number-feedback"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.number.$error}"
                        v-model="form.number"
                        v-model.trim="$v.form.number.$model"
                        :state="!$v.form.number.$error && null"
                        trim 
                        placeholder="Введите номер договора"
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-number-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
                <b-col cols="6" v-if="isEditProps">
                    <label  class="label-custom mt-3" for="input-type" >Фиксация договора</label>
                    <base-switch-fixed
                        @switchEmit="contractFixate"
                        :onTextProps="'Зафиксировать'"
                        :offTextProps="'Снять фиксацию'"
                        :onProps="contractIsFixed"
                    />
                </b-col>
            </b-row>
            <b-row class="mt-3">
                <b-col cols="6">
                    <b-row>
                        <b-col cols="12">
                            <label class="label-custom" for="input-surname">Сумма</label>
                            <b-form-input
                                id="input-amount"
                                class="input-custom"
                                :class="{ 'input-custom--invalid': $v.form.amount.$error}"
                                aria-describedby="input-amount-feedback"
                                v-model="form.amount"
                                v-model.trim="$v.form.amount.$model"
                                v-maska
                                data-maska="0.99"
                                data-maska-tokens="0:\d:multiple|9:\d:optional"
                                :state="!$v.form.amount.$error && null"
                                placeholder="Сумма"
                                trim
                            ></b-form-input>
                            <b-form-invalid-feedback v-if="!$v.form.amount.required" id="input-amount-feedback">
                                Незаполненное поле
                            </b-form-invalid-feedback>
                            <b-form-invalid-feedback v-if="!$v.form.amount.money" id="input-amount-feedback">
                                Недопустимое значение
                            </b-form-invalid-feedback>
                        </b-col>
                        <b-col cols="12" class="mt-3"> 
                            <label class="label-custom" for="input-type" >Налог</label>
                            <b-form-group>
                                <b-form-radio v-model="taxNDS" name="some-radios" :value="false">Без НДС</b-form-radio>
                                <b-form-radio v-model="taxNDS" name="some-radios" :value="true">с НДС</b-form-radio>
                            </b-form-group>
                            <div v-show="taxNDS" class='ml-2 custom-radio-child'>
                                <b-form-radio v-model="form.tax" value="10">10%</b-form-radio>
                                <b-form-radio v-model="form.tax" value="20">20%</b-form-radio>
                            </div>
                        </b-col>
                    </b-row>
                </b-col>
                <b-col cols="6">
                    <label class="label-custom" for="input-live">Описание</label>
                    <b-form-textarea
                        id="input-description"
                        aria-describedby="input-description-feedback"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.description.$error }"
                        v-model="form.description"
                        v-model.trim="$v.form.description.$model"
                        :state="!$v.form.description.$error && null"
                        placeholder="Введите описание..."
                        trim
                        rows="5"
                        max-rows="12"
                        no-resize
                    ></b-form-textarea>
                    <b-form-invalid-feedback id="input-description-feedback" v-if="!$v.form.description.required">
                        Незаполненное поле 
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col ms="12" lg="6">
                    <label class="label-custom" for="input-live">Сумма отсрочки</label>
                    <b-form-input
                        id="input-amount"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.credit.$error}"
                        aria-describedby="input-credit-feedback"
                        v-model="form.credit"
                        v-model.trim="$v.form.credit.$model"
                        v-maska
                        data-maska="0.99"
                        data-maska-tokens="0:\d:multiple|9:\d:optional"
                        :state="!$v.form.credit.$error && null"
                        placeholder="Сумма отсрочки"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-credit-feedback" v-if="!$v.form.credit.money">
                        Недопустимое значение
                    </b-form-invalid-feedback>
                </b-col>
                <b-col ms="12" lg="6">
                    <label class="label-custom" for="input-live">Кол-во дней отсрочки</label>
                    <b-form-input
                        id="input-amount"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.credit_days.$error }"
                        aria-describedby="input-amount-feedback"
                        v-model="form.credit_days"
                        v-model.trim="$v.form.credit_days.$model"
                        v-maska
                        :state="!$v.form.credit_days.$error && null"
                        placeholder="Кол-во дней отсрочки"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-description-feedback" v-if="!$v.form.credit_days.integer">
                        Недопустимое значение
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="6" class="mt-1">
                    <label class="label-custom" >Файлы</label>
                    <div class="d-flex flex-wrap">
                        <file
                            class="mt-2 mr-1"
                            v-for="file in form.files" :key="file.hash"
                            :fileProps="file"
                            :deleteProps=true
                            @deleteFileEmit="deleteFile(file.hash)"
                        />
                    </div>
                    <file-upload 
                        @addFileEmit="addFile"
                    />
                </b-col>
                <b-col cols="6">
                    <label class="label-custom" for="input-surname">Выберите спецификацию</label>
                    <multiselect 
                        v-model="specification" 
                        :options="specificationListComputed"
                        placeholder="Выберите спецификацию" 
                        label="name"
                        track-by="name"
                        :show-labels="false"
                        @input=addSpecification()
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
                    <specification-block 
                        v-for="item in form.specifications" :key="item.id"
                        :specificationProps="item"
                        @removeSpecEmit="removeSpec(item)"
                    />
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button
                v-if="isEditProps"  
                @click="editContract"  
                class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Сохранить
            </button>
            <button 
                v-if="isEditProps" 
                @click="deleteContract()"   
                v-b-modal.base-delete-modal
                class="c-button-delete--success mt-4"
            >
                Удалить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import { required, integer } from 'vuelidate/lib/validators';
    import { validationMixin } from "vuelidate";
    import { money } from '@/utils/customValidations'
    import { vMaska } from "maska"

    import File from '@/components/elements/files/File'
    import FileUpload from '@/components/elements/files/FileUpload'
    import BaseSwitchFixed from '@/components/elements/BaseSwitchFixed'
    import SpecificationBlock from '@/components/specification/SpecificationBlock'

    export default {
        name: 'DirectoryContractsSupplyModalInfo',
        data() {
            return {
                form: {
                    id: '',
                    description: '',
                    start_at: null,
                    end_at: null,
                    partner: null,
                    specifications: [],
                    type: '',
                    tax: '',
                    amount: 0,
                    created_at: '',
                    files: [],
                    fixed: false,
                    credit_days: 0,
                    credit: 0,
                },
                date: null,
                taxNDS: false,
                specification: null,
                contractIsFixed: false,
                specificationsList: [],
            }
        },
        props: {
            specificationsListProps: { type: Array, default: () => [] },
            partnersListProps: { type: Array, default: () => [] },
            contractProps: { type: Object, default: () => {} },
            isEditProps: { type: Boolean, default: () => true}
        },
        directives: { maska: vMaska },
        mixins: [validationMixin],
        components: {
            File,
            FileUpload,
            BaseSwitchFixed,
            SpecificationBlock
        },
        validations: {
            form: {
                partner: { required },
                description: { required },
                amount: { money, required },
                start_at: { required },
                end_at: { required },
                number: { required },
                credit_days: { integer },
                credit: { money },
            }
        },
        watch: {
            'date': {
                handler() {
                    if(this.date[0] != null){
                        this.form.start_at = new Date(this.date[0]).toLocaleString().split(',')[0].split('.').reverse().join('-'),
                        this.form.end_at = new Date(this.date[1]).toLocaleString().split(',')[0].split('.').reverse().join('-')
                    }else{
                        this.form.start_at = null
                        this.form.end_at = null
                    }
                }
            }
        }, 
        computed: {
            ...mapGetters({
                directoryContractsError: 'directoryContractsErrorGetter'
            }),
            validationsComputed(){
                if( this.$v.form.partner.$invalid  == false && 
                    this.$v.form.description.$invalid == false && 
                    this.$v.form.number.$invalid == false && 
                    this.$v.form.amount.$invalid == false && 
                    this.$v.form.start_at.$invalid == false && 
                    this.$v.form.end_at.$invalid == false && 
                    !this.form.fixed
                ){
                    return true
                }
                else {
                    return false  
                }
            },
            specificationListComputed(){
                return this.specificationsList
            }
        },
        methods: {
            ...mapActions({
                setParamsDeleteModal: 'setParamsDeleteModalActions',
                directoryContractsEdit: 'directoryContractsEditActions',
                directoryContractsFixate: 'directoryContractsFixateActions',
                directoryContractsSetList: 'directoryContractsSetListActions',
                directoryContractsRemoveFixation: 'directoryContractsRemoveFixationActions',
                directoryContractsAddSpecification: 'directoryContractsAddSpecificationActions',
                directoryContractsRemoveSpecification: 'directoryContractsRemoveSpecificationActions',
            }),
            hideModal(){
                this.form.specifications = []
                this.specificationsList = []
                this.$refs['directory-contracts-supply-modal-info'].hide();
            },
            async editContract(){

                let form = { ...this.form }

                form.specifications = this.form.specifications.map( item => { 
                    const select = {}
                    select.id = item.id
                    return select
                }),

                form.partner = { id: this.form.partner?.id }

                if(form.credit == 0){
                    delete form.credit
                    delete form.credit_days
                }

                form.files = this.form.files.map( item => item.hash).concat();

                await this.directoryContractsEdit(form)

                if(!this.directoryContractsError){
                    this.hideModal()
                }
            },
            async deleteFile(hash){
                this.form.files = this.form.files.filter(item => item.hash != hash)
            },
            async removeSpec(spec){

                let params = {
                    contractId: this.form.id,
                    specificationId: spec.id,
                }

                await this.directoryContractsRemoveSpecification(params)

                if(!this.directoryContractsError){
                    this.form.specifications = this.form.specifications.filter( item => item.id != spec.id ) 
                    this.specificationsList.unshift( spec )
                }
            },
            async addSpecification(){
                    
                let params = {
                    contractId: this.form.id,
                    specificationId: this.specification.id,
                }

                await this.directoryContractsAddSpecification(params)

                if(!this.directoryContractsError){
                    this.form.specifications.push( this.specification )
                    this.specificationsList = this.specificationsList.filter( item => item.id != this.specification.id )
                    this.specification = null
                }
            },
            async deleteContract(){
                
                 this.setParamsDeleteModal(
                    {
                        title: 'договор',
                        actionsName: 'directoryContractsDeleteActions',
                        data: [ this.form ]
                    }
                )
                this.hideModal()

            },
            addFile(_, file){
                this.form.files.push(file)
            },
            async contractFixate(){

                if(!this.contractIsFixed){
                    await this.directoryContractsFixate(this.form.id)
                } else {
                    await this.directoryContractsRemoveFixation(this.form.id)
                }

                if(!this.directoryContractsError){
                    this.contractIsFixed = !this.contractIsFixed
                    this.form.fixed = this.contractIsFixed
                }
            },
            mountedData(){
                this.form.id = this.contractProps.id
                this.form.number = this.contractProps.number
                this.form.number_sys = this.contractProps.number_sys
                this.form.specifications = this.contractProps.specifications.concat();
                this.form.partner = this.contractProps.partner
                
                this.form.description = this.contractProps.description 
                this.form.start_at = new Date(this.contractProps.start_at)
                this.form.end_at = new Date(this.contractProps.end_at) 
                this.form.type = this.contractProps.type
                this.form.tax = this.contractProps.tax
                this.form.amount = this.contractProps.amount
                this.form.fixed = this.contractProps.fixed
                
                if( typeof this.contractProps.files == 'undefined' ){
                    this.form.files = []
                } else {
                    this.form.files = this.contractProps.files
                }

                this.form.credit = 0
                this.form.credit_days = 0

                if( typeof this.contractProps.credit != 'undefined' ){
                    this.form.credit = this.contractProps.credit
                }

                if( typeof this.contractProps.credit_days != 'undefined' ){
                    this.form.credit_days = this.contractProps.credit_days
                }

                this.date = [new Date(this.contractProps.start_at), new Date(this.contractProps.end_at)]

                this.contractIsFixed = this.form.fixed 

                if( this.form.tax > 0){
                    this.taxNDS = true
                }

                for(let i = 0; i < this.specificationsListProps.length; i++){
                    if( this.form.specifications.findIndex( item => item.id == this.specificationsListProps[i].id) == -1){
                        this.specificationsList.push(this.specificationsListProps[i])
                    }
                }
                
            }
        }
    }
</script>