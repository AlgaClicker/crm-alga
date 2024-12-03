<template>
    <b-modal 
        id="directory-partners-modal-edit"
        ref="directory-partners-modal-edit"
        centered 
        content-class="c-modal-default c-modal-partner"
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Редактировать партнера</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-live">Название</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        aria-describedby="input-live-help input-live-feedback"
                        v-model="formPartner.name"
                        v-model.trim="$v.formPartner.name.$model"
                        :state="!$v.formPartner.name.$error && null"
                        placeholder="Введите описание"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-live-feedback">
                        Описание должно быть длиннее шести символов 
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-live">Полное название</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        aria-describedby="input-live-help input-live-feedback"
                        v-model="formPartner.fullname"
                        v-model.trim="$v.formPartner.fullname.$model"
                        :state="!$v.formPartner.fullname.$error && null"
                        placeholder="Введите описание"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-live-feedback">
                        Полное название должно быть длиннее шести символов 
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-live">Адрес</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        aria-describedby="input-live-help input-live-feedback"
                        v-model="formPartner.address"
                        v-model.trim="$v.formPartner.address.$model"
                        :state="!$v.formPartner.address.$error && null"
                        placeholder="Введите описание"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-live-feedback">
                        Поле не должно быть пустым  
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-live">Тип</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        aria-describedby="input-live-help input-live-feedback"
                        v-model="formPartner.type"
                        v-model.trim="$v.formPartner.type.$model"
                        :state="!$v.formPartner.type.$error && null"
                        placeholder="Введите описание"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-live-feedback">
                        Поле не должно быть пустым 
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-live">ОГРН</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        aria-describedby="input-live-help input-live-feedback"
                        v-model="formPartner.ogrn"
                        placeholder="Введите описание"
                    ></b-form-input>
                </b-col>
                <b-col class="mt-2 mb-2" cols="6">
                    <label class="label-custom" for="input-inn">ИНН</label>
                    <b-form-input
                        id="input-inn"
                        class="input-custom"
                        aria-describedby="input-inn-help input-inn-feedback"
                        v-model="formPartner.inn"
                        v-model.trim="$v.formPartner.inn.$model"
                        :state="!$v.formPartner.inn.$error && null"
                        placeholder="Введите описание"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-inn-feedback">
                        Поле не должно быть пустым 
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="editPartner" class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Сохранить
            </button>
            <button 
                @click="deleteParner()"   
                v-b-modal.base-delete-modal
                class="c-button-delete--success mt-4"
            >
                Удалить
            </button>
        </template>
    </b-modal>
</template>
<script>
    import { mapActions, mapGetters } from "vuex";
    import { required } from 'vuelidate/lib/validators';
    import { validationMixin } from "vuelidate";

    export default {    
        name: 'DirectoryPartnersModalEdit',
        data(){
            return {
                formPartner: {
                    id: '',
                    name: '',
                    fullname: '',
                    address: '',
                    type: '',
                    ogrn: '',
                    inn: '',
                    parent: '',
                    isGroup: false,
                    bank_accounts: []
                },
                banksOptions: [],
                valueBank: {
                    bank_accounts: []
                },
                valueBankAccount: '',

                isLoading: false,
                isVisibleBankAccount: false,
                isVisibleAccount: false,
            }
        },
        validations: {
            formPartner: {
                name: { required },
                fullname: { required },
                address: { required },
                type: { required },
                inn: { required },
            }
        },
        mixins: [validationMixin],
        props: {
            partnerProps: { type: Object, default: () => {} },
        },
        computed: {
            ...mapGetters({
                directoryPartnersSelectedList: 'directoryPartnersSelectedListGetter',
                directoryPartnersError: 'directoryPartnersErrorGetter',
                directoryBanksList: 'directoryBanksListGetter'
            }),
            validationsComputed(){
                if( 
                    this.$v.formPartner.name.$invalid == false && 
                    this.$v.formPartner.fullname.$invalid  == false  && 
                    this.$v.formPartner.address.$invalid  == false &&
                    this.$v.formPartner.inn.$invalid  == false &&
                    this.$v.formPartner.type.$invalid  == false
                ){
                    return true
                }
                else {
                    return false  
                }
            },
            validationsAccountComputed(){
                if(
                    this.$v.account.$invalid == false 
                ){
                    return true
                }
                else {
                    return false 
                }
            },
            bankAccountsOptionsComputed(){
                return this.valueBank.bank_accounts.map( item => {
                    const select = {}
                    select.value = item
                    select.text = item.account
                    return select
                })
            }
        },
        methods: {
            ...mapActions({
                setParamsDeleteModal: 'setParamsDeleteModalActions',
                directoryPartnersEdit: 'directoryPartnersEditActions',
                directoryBanksSearch: 'directoryBanksSearchActions',
                directoryBanksGet: 'directoryBanksGetActions',
                directoryPartnersClearSelectedList: 'directoryPartnersClearSelectedListActions'
            }),
            hideModal(){
                this.$refs['directory-partners-modal-edit'].hide();
            },
            async editPartner(){

                this.form = this.formPartner

                delete this.form.bank_accounts

                await this.directoryPartnersEdit(this.form)

                if(!this.directoryPartnersError){
                    this.hideModal()
                }
            },  
            deleteParner(){
                this.setParamsDeleteModal(
                    {
                        title: 'договор',
                        actionsName: 'directoryPartnersDeleteActions',
                        data: [ this.formPartner ]
                    }
                )
                this.hideModal()
            },
            async asyncFind(query){
                this.isLoading = false

                this.directoryBanksSearch(query)

                this.banksOptions = this.directoryBanksList
                this.isLoading = true
            },
            addBank(){
                this.newBankAccount.idBank = this.valueBank.id
                this.isVisibleBankAccount = true
            },
            removeBank(){
                this.account = null
                this.isVisibleBankAccount = false
            },
            async deleteBankAccount(){

                this.form.bank_accounts = []

                await this.directoryPartnersEdit(this.form)
            },
   /*          async addBankAccount(){

                this.form.bank_accounts.push({ 
                    idBank: this.valueBank.id,
                    idAccBank: this.valueBankAccount.id,
                    account: this.account
                })

                if( typeof this.form.bank_accounts.find( item => item.account == this.account ) != 'undefined'){
                    await this.directoryPartnersEdit(this.form)
                    if(!this.directoryPartnersError){
                        this.formPartner.bank_accounts = this.directoryPartnersSelectedList[0].bank_accounts
                    }
                }else{
                    Vue.notify({
                        group: 'foo',
                        title: 'Предупреждение',
                        type: 'warn',
                        text: 'Уже есть такой счет'
                    })
                }
            }, */
            async mountedData(){
                this.formPartner.id = this.partnerProps.id
                this.formPartner.name = this.partnerProps.name
                this.formPartner.fullname = this.partnerProps.fullname
                this.formPartner.address = this.partnerProps.address
                this.formPartner.type = this.partnerProps.type
                this.formPartner.ogrn = this.partnerProps.ogrn
                this.formPartner.inn = this.partnerProps.inn
                this.formPartner.parent = this.partnerProps.parent
                this.formPartner.bank_accounts = this.partnerProps.bank_accounts

                this.form.id = this.partnerProps.id

                await this.directoryBanksGet()
                this.banksOptions = this.directoryBanksList
                this.$v.$reset()
            }
       }
    }
</script>
