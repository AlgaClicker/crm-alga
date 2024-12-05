<template>
    <b-modal
        id="directory-contracts-work-modal-add"
        ref="directory-contracts-work-modal-add"
        centered 
        content-class="c-modal-default directory-contracts"
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Создать договор подряда</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col class="mt-3" cols="6">
                    <label class="label-custom" for="input-name">Контрагент</label>
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
                <b-col class="mt-3" cols="6">
                    <label class="label-custom" for="input-type" >Фиксация договора</label>
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
                        :class="{ 'input-custom--invalid': $v.form.credit_amount.$error}"
                        aria-describedby="input-credit_amount-feedback"
                        v-model="form.credit_amount"
                        v-model.trim="$v.form.credit_amount.$model"
                        v-maska
                        data-maska="0.99"
                        data-maska-tokens="0:\d:multiple|9:\d:optional"
                        :state="!$v.form.credit_amount.$error && null"
                        placeholder="Сумма отсрочки"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-credit_amount-feedback" v-if="!$v.form.credit_amount.money">
                        Недопустимое значение
                    </b-form-invalid-feedback>
                </b-col>
                <b-col ms="12" lg="6">
                    <label class="label-custom" for="input-live">Кол-во дней отсрочки</label>
                    <b-form-input
                        id="input-amount"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.credit_at_days.$error}"
                        aria-describedby="input-amount-feedback"
                        v-model="form.credit_at_days"
                        v-model.trim="$v.form.credit_at_days.$model"
                        v-maska
                        :state="!$v.form.credit_at_days.$error && null"
                        placeholder="Кол-во дней отсрочки"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-description-feedback" v-if="!$v.form.credit_at_days.integer">
                        Недопустимое значение
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="6" class="mt-1">
                    <label class="label-custom" >Файлы</label>
                    <file-upload-modal @addFileEmit="addFile" />
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
                @click="newContract"  
                class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Сохранить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import { required, integer } from 'vuelidate/lib/validators';
    import { validationMixin } from "vuelidate";
    import { money } from '@/utils/customValidations'
    import { vMaska } from "maska"

    import FileUploadModal from '@/components/elements/files/FileUploadModal'
    import BaseSwitchFixed from '@/components/elements/BaseSwitchFixed'
    import SpecificationBlock from '@/components/specification/SpecificationBlock'

    export default {
        name: 'DirectoryContractsWorkModalAdd',
        data() {
            return {
                form: {
                    specifications: [],
                    partner: null,
                    start_at: null,
                    end_at: null,
                    number: '',
                    amount: null,
                    credit_at_days: 0,
                    credit_amount: 0,
                    tax: 0,
                    description: '',
                    fixed: false,
                    files: []
                },
                specification: null,
                specificationsList: [],
                contractIsFixed: false,
                taxNDS: false,
                date: null,
            }
        },
        directives: { maska: vMaska },
        mixins: [validationMixin],
        props: {
            specificationsListProps: { type: Array, default: () => [] },
            partnersListProps: { type: Array, default: () => [] },
        },
        components: {
            BaseSwitchFixed,
            FileUploadModal,
            SpecificationBlock,
        },
        validations: {
            form: {
                partner: { required },
                description: { required },
                amount: { money, required },
                start_at: { required },
                end_at: { required },
                number: { required },
                credit_at_days: { integer },
                credit_amount: { money },
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
                    this.$v.form.amount.$invalid == false 
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
                directoryContractsSetList: 'directoryContractsSetListActions',
                directoryContractsWorkCreate: 'directoryContractsWorkCreateActions',
                directoryContractsSupplyCreate: 'directoryContractsSupplyCreateActions',
            }),
            async newContract(){
                let form = this.form

                form.specifications = this.form.specifications.map( item => { 
                    const select = {}
                    select.id = item.id
                    return select
                })

                if(form.credit_amount == 0){
                    delete form.credit_amount
                    delete form.credit_at_days
                }

                form.partner =  { id: this.form.partner?.id }

                await this.directoryContractsWorkCreate(form)

                if(!this.directoryContractsError){
                    this.hideModal()
                }

                this.directoryContractsSetList()
            },
            addFile( _, file){
                this.form.files.push(file.hash)
            },
            removeSpec(spec){
                this.form.specifications = this.form.specifications.filter( item => item.id != spec.id ) 
                this.specificationsList.unshift( spec )
            },
            addSpecification(){
                this.form.specifications.push( this.specification )
                this.specificationsList = this.specificationsList.filter( item => item.id != this.specification.id )
                this.specification = null
            },
            contractFixate(){
                this.contractIsFixed = !this.contractIsFixed
                this.form.fixed = this.contractIsFixed
            },
            hideModal(){
                this.$refs['directory-contracts-work-modal-add'].hide();
            },
            mountedData(){
                this.specificationsList = this.specificationsListProps

                this.form.specifications = []
                this.form.partner = null
                this.form.start_at = null
                this.form.end_at = null
                this.form.number = ''
                this.form.amount = 0
                this.form.tax = 0
                this.form.description = ''
                this.form.credit_at_days = 0
                this.form.credit_amount = 0
                this.form.fixed = false
                this.form.files = []

                this.specification = null
                this.date = null
                this.taxNDS = false
                this.contractIsFixed = false
            }
        }
    }
</script>