<template>
    <b-modal 
        id="directory-partners-modal-info"
        ref="directory-partners-modal-info"
        title="Информация об контрагенте"
        content-class="c-modal-default c-modal-partner-info"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>{{ form?.fullname }}</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-body-partner"> 
            <b-row>
                <div class='c-label-icon'>
                    <svg  
                        width="16" 
                        height="16" 
                    >
                        <use xlink:href="@/assets/icons/sprite.svg#info"></use>
                    </svg>
                    <label>
                        Информация
                    </label>
                </div>
                <b-col class="mt-2" cols="5">
                    <label class="label-custom" for="input-live">Тип</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        aria-describedby="input-live-help input-live-feedback"
                        v-model="form.type"
                        v-model.trim="$v.form.type.$model"
                        :state="!$v.form.type.$error && null"
                        placeholder="Введите описание"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-live-feedback">
                        Поле не должно быть пустым 
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="5">
                    <label class="label-custom" for="input-oktmo">ОКТМО</label>
                    <b-form-input
                        id="input-oktmo"
                        class="input-custom"
                        aria-describedby="input-oktmo-help input-oktmo-feedback"
                        v-model="form.oktmo"
                        placeholder="Введите описание"
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-oktmo-feedback">
                        Поле не должно быть пустым 
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <b-row>
                <b-col class="mt-2" cols="5">
                    <label class="label-custom" for="input-ogrn">ОГРН</label>
                    <b-form-input
                        id="input-ogrn"
                        class="input-custom"
                        aria-describedby="input-ogrn-help input-ogrn-feedback"
                        v-model="form.ogrn"
                        placeholder="Введите описание"
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-ogrn-feedback">
                        Поле не должно быть пустым 
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="5">
                    <label class="label-custom" for="input-inn">ИНН</label>
                    <b-form-input
                        id="input-inn"
                        class="input-custom"
                        aria-describedby="input-inn-help input-inn-feedback"
                        v-model="form.inn"
                        v-model.trim="$v.form.inn.$model"
                        :state="!$v.form.inn.$error && null"
                        placeholder="Введите описание"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-inn-feedback">
                        Поле не должно быть пустым 
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <b-row>
                <b-col class="mt-2" cols="5">
                    <label class="label-custom" for="input-okpo">ОКПО</label>
                    <b-form-input
                        id="input-okpo"
                        class="input-custom"
                        aria-describedby="input-okpo-help input-okpo-feedback"
                        v-model="form.okpo"
                        v-model.trim="$v.form.okpo.$model"
                        :state="!$v.form.okpo.$error && null"
                        placeholder="Введите описание"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-okpo-feedback">
                        Поле не должно быть пустым 
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="5">
                    <label class="label-custom" for="input-kpp">КПП</label>
                    <b-form-input
                        id="input-kpp"
                        class="input-custom"
                        aria-describedby="input-kpp-help input-kpp-feedback"
                        v-model="form.kpp"
                        placeholder="Введите описание"
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-kpp-feedback">
                        Поле не должно быть пустым 
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <div class='c-label-icon'>
                    <svg  
                        width="18" 
                        height="24" 
                    >
                        <use xlink:href="@/assets/icons/sprite.svg#locations"></use>
                    </svg>
                    <label>
                        {{ form.address?.split(',')[0] }},
                        {{ form.address?.split(',')[1] }},
                        {{ form.address?.split(',')[2] }}
                    </label>
                </div>
            </b-row>
            <b-row class="mt-4">
                <div class='c-label-icon'>
                    <svg  
                        width="16" 
                        height="16" 
                    >
                        <use xlink:href="@/assets/icons/sprite.svg#info"></use>
                    </svg>
                    <label>
                        Банковские счета 
                    </label>
                    <button v-b-modal.directory-partners-modal-add-bank-account class="c-button-add">
                        <b-icon icon="plus-lg" scale="1"></b-icon>
                        <span>
                            Добавить счет
                        </span>
                    </button>
                </div>
                <div class="с-bank-account-tabel mt-3">
                    <table>
                        <thead>
                            <tr>
                                <th>Название банка</th>
                                <th>№ Счета</th>
                                <th>Тип банковского счета</th>
                                <th>СБРБИК</th>
                                <th>Дата</th>
                                <th>Статус аккаунта</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="с-bank-account-tabel_body">
                        <table>
                            <tbody>
                                <tr v-for="item in bankAccountList" :key="item.id">
                                    <td>
                                        <p>
                                            {{ item.bank_name }}
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            {{ item.account }}
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            {{ item.regulation_account_type }}
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            {{ item.account_cbrbic }}
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            {{ item.date_in }}
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            {{ item.account_status}}
                                        </p>
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
                class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Редактировать
            </button>
        </template>
    </b-modal>
</template>
<script>
    import { mapGetters } from "vuex";
    import { validationMixin } from "vuelidate";
    import { required } from 'vuelidate/lib/validators';

    export default { 
        name: 'DirectoryPartnersModalInfo',
        data(){
            return {
                form: {
                    id: '',
                    name: '',
                    fullname: '',
                    address: '',
                    type: '',
                    ogrn: '',
                    okpo: '',
                    oktmo: '',
                    inn: '',
                    kpp: '',
                    created: '',
                    bank_accounts: []
                },
            }   
        },
        props: {
           partnersProps : { type: Object, default: () => {} }
        },
        validations: {
            form: {
                name: { required },
                fullname: { required },
                address: { required },
                type: { required },
                inn: { required },
                ogrn: { required },
                okpo: { required },
                oktmo: { required },
                kpp: { required },
            },
        },
        mixins: [validationMixin],
        computed: {
            ...mapGetters({
                directoryPartnersSelectedList: 'directoryPartnersSelectedListGetter',
                directoryPartnersError: 'directoryPartnersErrorGetter',
                directoryBanksList: 'directoryBanksListGetter'
            }),
            validationsComputed(){
                if( 
                    this.$v.form.name.$invalid == false && 
                    this.$v.form.fullname.$invalid  == false  && 
                    this.$v.form.address.$invalid  == false &&
                    this.$v.form.inn.$invalid  == false &&
                    this.$v.form.type.$invalid  == false
                ){
                    return true
                }
                else {
                    return false  
                }
            },
            bankAccountList(){
                let banksAccount = []
                
                if(this.form.bank_accounts != [] || this.form.bank_accounts != null){
                    for(var i = 0; i < this.form.bank_accounts.length; i++){

                        if(typeof this.form.bank_accounts[i].bank.bank_accounts != 'undefined' || this.form.bank_accounts[i].bank.bank_accounts != []){
                            banksAccount.push(...this.form.bank_accounts[i].bank.bank_accounts)
                        }

                    }
                }

                return banksAccount
            }
        },
        methods: {
            hideModal(){
                this.$refs['directory-partners-modal-info'].hide();
            },
            mountedData(){
                this.$v.$reset()

                this.form.id = this.partnersProps.id,
                this.form.name = this.partnersProps.name,
                this.form.fullname = this.partnersProps.fullname,
                this.form.address = this.partnersProps.address,
                this.form.type = this.partnersProps.type,
                this.form.ogrn = this.partnersProps.ogrn,
                this.form.okpo = this.partnersProps.okpo,
                this.form.oktmo = this.partnersProps.oktmo,
                this.form.inn = this.partnersProps.inn,
                this.form.kpp = this.partnersProps.kpp,
                this.form.created_at = this.partnersProps.created_at,
                this.form.bank_accounts = this.partnersProps.bank_accounts

            }
        }
    }
</script>