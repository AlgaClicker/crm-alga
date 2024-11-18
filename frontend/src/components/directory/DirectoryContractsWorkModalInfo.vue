<<<<<<< HEAD
<template>
    <b-modal
        id="directory-contracts-modal-info"
        ref="directory-contracts-modal-info"
        centered 
        content-class="c-modal-default c-modal-supply-requisition-add-invoice"
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
                    <label class="label-custom" for="input-surname">Выбирите спецификацию</label>
                    <multiselect 
                        v-model="form.specification" 
                        :options="specificationsListProps"
                        placeholder="Выберите спецификацию" 
                        label="name"
                        track-by="name"
                        :show-labels="false"
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
            </b-row>
            <b-row>
                <b-col class="mt-3" cols="6">
                    <label class="label-custom" for="input-type" >Дата начала и завершения договора</label>
                    <date-picker
                        input-class='mx-input-modal'
                        style="width: 100%" 
                        v-model="date"
                        range
                    ></date-picker>
                </b-col>
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
            </b-row>
            <b-row class="mt-2">
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
                        <b-col cols="12" class="mt-2"> 
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
                        aria-describedby="input-description-feedback"
                        id="input-description"
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
                <b-col cols="6">
                    <label class="label-custom" for="input-type" >Фиксация договора</label>
                    <base-switch-fixed
                        @switchEmit="contractFixate"
                        :onTextProps="'Зафиксировать'"
                        :offTextProps="'Снять фиксацию'"
                        :onProps="contractIsFixed"
                    />
                </b-col>
            </b-row>
            <hr>
            <div class="d-flex flex-wrap">
                <file
                    class="mt-2 mr-1"
                    v-for="file in form.files" :key="file.hash"
                    :fileProps="file"
                    :deleteProps=true
                    @deleteFileEmit="deleteFile(file.hash)"
                />
            </div>
            <label for="upload-file" class="mt-3 upload-file"> 
                <svg width="20" height="18" viewBox="0 0 20 18">
                    <path d="M9.74263 1.17157L2.31802 8.59619C0.56066 10.3535 0.56066 13.2028 2.31802 14.9601C4.07537 16.7175 6.92462 16.7175 8.68197 14.9601L17.5208 6.12134C18.6924 4.94974 18.6924 3.05024 17.5208 1.87869C16.3492 0.707104 14.4497 0.707104 13.2782 1.87869L4.43932 10.7175C3.85357 11.3033 3.85357 12.253 4.43932 12.8388C5.02512 13.4246 5.97488 13.4246 6.56068 12.8388L13.9853 5.41419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span> Прикрепить файл </span>
                <input name="input-upload" id="upload-file" type="file" ref="file" @change="fileUpload()"/>
            </label>
        </div>
        <template #modal-footer>
            <button 
                @click="editContract"  
                class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Сохранить
            </button>
            <button 
                @click="deleteContract()"   
                class="c-button-delete--success mt-4"
            >
                Удалить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import { required } from 'vuelidate/lib/validators';
    import { validationMixin } from "vuelidate";
    import { money } from '@/utils/customValidations'
    import { vMaska } from "maska"
    import { instance } from '@/services/instances.service';

    import File from '@/components/elements/files/File'
    import BaseSwitchFixed from '@/components/elements/BaseSwitchFixed'

    export default {
        name: 'DirectoryContractsModalInfo',
        data() {
            return {
                form: {
                    id: '',
                    description: '',
                    start_at: null,
                    end_at: null,
                    partner: null,
                    specification: '',
                    type: '',
                    tax: '',
                    amount: 0,
                    created_at: '',
                    files: [],
                    fixed: false
                },
                date: null,
                taxNDS: false,
                contractIsFixed: false,
            }
        },
        directives: { maska: vMaska },
        mixins: [validationMixin],
        props: {
            specificationsListProps: { type: Array, default: () => [] },
            partnersListProps: { type: Array, default: () => [] },
            contractProps: { type: Object, default: () => {} },
        },
        components: {
            File,
            BaseSwitchFixed,
        },
        validations: {
            form: {
                partner: { required },
                description: { required },
                amount: { money, required },
                start_at: { required },
                end_at: { required },
                number: { required }
            }
        },
        watch: {
            'date': {
                handler() {
                    if(this.date[0] != null){
                        console.log(this.date)
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
            }
        },
        methods: {
            ...mapActions({
                directoryContractsEdit: 'directoryContractsEditActions',
                directoryContractsDelete: 'directoryContractsDeleteActions',
                directoryContractsFixate: 'directoryContractsFixateActions',
                directoryContractsSetList: 'directoryContractsSetListActions',
                directoryContractsRemoveFixation: 'directoryContractsRemoveFixationActions'
            }),
            editContract(){
                let newForm = {}
                newForm = this.form
                newForm.files = this.form.files.map( item => item.hash)
                newForm.partner_id = this.form.partner.id
                newForm.specification_id = this.form.specification.id

                delete newForm.specification
                delete newForm.partner

                console.log(newForm)

                this.directoryContractsEdit(newForm)

                if(!this.directoryContractsError){
                    this.hideModal()
                }
            },
            async deleteFile(hash){
                this.form.files = this.form.files.filter(item => item.hash != hash)
            },
            async deleteContract(){
                
                await this.directoryContractsDelete(this.contractProps.id)

                if(!this.directoryContractsError){
                    this.hideModal()
                    this.directoryContractsSetList()
                }
            },
            fileUpload(){
                let file = event.srcElement.files[0]

                let formData = new FormData();
                formData.append('upload', file);

                var config = {
                    headers: {'Content-Type': 'multipart/form-data'},
                };

                instance
                .post(`/api/v1/services/file/upload/`, formData, config)
                .then( (res) => {
                    console.log('then')
                    let newFile = {
                        name: res.data.data[0].name, 
                        hash: res.data.data[0].hash,  
                        size: res.data.data[0].size,
                        message: res.data.message,
                    }

                    this.form.files.push(newFile)
                })
            },
            hideModal(){
                this.$refs['directory-contracts-modal-info'].hide();
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
                
                console.log(this.partnersListProps)

                this.form.id = this.contractProps.id
                this.form.number = this.contractProps.number
                this.form.number_sys = this.contractProps.number_sys
                this.form.specification = this.contractProps.specification
                this.form.partner = this.contractProps.partner

                this.form.description = this.contractProps.description 
                this.form.start_at = new Date(this.contractProps.start_at)
                this.form.end_at = new Date(this.contractProps.end_at) 
                this.form.type = this.contractProps.type
                this.form.tax = this.contractProps.tax
                this.form.amount = this.contractProps.amount
                this.form.fixed = this.contractProps.fixed
                this.form.files = this.contractProps.files

                this.date = [new Date(this.contractProps.start_at), new Date(this.contractProps.end_at)]

                this.contractIsFixed = this.form.fixed 

                if( this.form.tax > 0){
                    this.taxNDS = true
                }

            }
        }
    }
</script>
=======
<template>
    <b-modal
        id="directory-contracts-work-modal-info"
        ref="directory-contracts-work-modal-info"
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
                <b-col class="mt-3" cols="6">
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
            <b-row class="mt-2">
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
                        <b-col cols="12" class="mt-2"> 
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
                        aria-describedby="input-description-feedback"
                        id="input-description"
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
                @click="editContract"  
                class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Сохранить
            </button>
            <button 
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
    import { required } from 'vuelidate/lib/validators';
    import { validationMixin } from "vuelidate";
    import { money } from '@/utils/customValidations'
    import { vMaska } from "maska"

    import File from '@/components/elements/files/File'
    import FileUpload from '@/components/elements/files/FileUpload'
    import BaseSwitchFixed from '@/components/elements/BaseSwitchFixed'
    import SpecificationBlock from '@/components/specification/SpecificationBlock'

    export default {
        name: 'DirectoryContractsWorkModalInfo',
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
                    credit: 0,
                    credit_days: 0
                },
                date: null,
                taxNDS: false,
                specification: null,
                contractIsFixed: false,
                specificationsList: []
            }
        },
        directives: { maska: vMaska },
        mixins: [validationMixin],
        props: {
            specificationsListProps: { type: Array, default: () => [] },
            partnersListProps: { type: Array, default: () => [] },
            contractProps: { type: Object, default: () => {} },
        },
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
                number: { required }
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
            hideModal(){
                this.$refs['directory-contracts-work-modal-info'].hide();
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
                this.form.specifications = this.contractProps.specifications
                this.form.partner = this.contractProps.partner

                this.form.description = this.contractProps.description 
                this.form.start_at = new Date(this.contractProps.start_at)
                this.form.end_at = new Date(this.contractProps.end_at) 
                this.form.type = this.contractProps.type
                this.form.tax = this.contractProps.tax
                this.form.amount = this.contractProps.amount
                this.form.fixed = this.contractProps.fixed
                this.form.files = this.contractProps.files

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
<<<<<<< HEAD:frontend/src/components/directory/DirectoryContractsModalInfo.vue
</script>
>>>>>>> feature/f_requisitions_master
=======
</script>
>>>>>>> origin/feature/f_requisitions_master:frontend/src/components/directory/DirectoryContractsWorkModalInfo.vue
