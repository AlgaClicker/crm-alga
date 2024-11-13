<template>
    <b-modal 
        id="directory-partners-modal-add-bank-account"
        ref="directory-partners-modal-add-bank-account"
        centered 
        content-class="c-modal-default c-directory-partners-modal-add-bank-account"
    >
        <template #modal-header>
            <h5>Добавить счет</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-body-partner">
            <b-row>
                <b-col cols="12" >
                    <label class="label-custom">Банк</label>
                    <multiselect 
                        v-model="bank" 
                        :options="banksOptions"
                        label="name"
                        placeholder="Введите название банка" 
                        :searchable="true"
                        :loading="directoryBanksSearchLoading"
                        :internal-search="false"
                        :clear-on-select="false" 
                        :options-limit="300" 
                        :limit="3" 
                        :max-height="600" 
                        :show-no-results="true" 
                        :show-labels="false" 
                        @search-change="asyncFind" 
                        class='mb-2' 
                        :class="{ 'input-custom--invalid': $v.bank.$error}"
                    >
                        <template slot="option" slot-scope="props">
                            <div class="option__desc">
                                {{ props.option.name }}
                            </div>
                        </template>
                    </multiselect>
                </b-col>
                <b-col cols="12">
                    <label class="label-custom" for="from-select">Счет</label>
                    <b-form-input
                        id="input-live"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.account.$error}"
                        aria-describedby="input-live-help input-live-feedback"
                        v-model="account"
                        v-model.trim="$v.account.$model"
                        :state="!$v.account.$error && null"
                        placeholder="Введите банковский счет"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-live-feedback">
                        Длинна банковского счета должна быть 20 символов
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="addBankAccount"  
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
    import { mapActions, mapGetters } from "vuex";
    import { required, minLength, maxLength } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";

    export default {
        name: 'DirectoryPartnersModalAddBankAccount',
        data(){
            return {
                isLoading: false,
                bank: null,
                account: 0,
                banksOptions: [],
            }
        },
        props: {
            partnerProps: { type: Object, default: () => {} },
        },
        mixins: [validationMixin],
        validations: {
            bank: { required },
            account: { 
                required, 
                minLength: minLength(20),
                maxLength: maxLength(20)
            }
        },
        computed: {
            ...mapGetters({
                directoryBanksList: 'directoryBanksListGetter',
                directoryBanksSearchLoading: 'directoryBanksSearchLoadingGetter',
                directoryPartnersError: 'directoryPartnersErrorGetter'
            }),
            validationsComputed() {
                if(
                    this.$v.account.$invalid == false &&
                    this.$v.bank.$invalid  == false 
                ){
                    return true
                }
                else {
                    return false 
                }
            },
        },
        methods: {
            ...mapActions({
                directoryBanksSearch: 'directoryBanksSearchActions',
                directoryBanksGet: 'directoryBanksGetActions',
                directoryPartnersAddBankAccount: 'directoryPartnersAddBankAccountActions'
            }),
            hideModal(){
                this.$refs['directory-partners-modal-add-bank-account'].hide();
            },
            async addBankAccount(){

                var form = {
                    idBank: this.bank.id,
                    account: this.account,
                    idPartner: this.partnerProps.id
                }

                await this.directoryPartnersAddBankAccount(form)

                if(this.directoryPartnersError == ''){
                    this.hideModal()
                }
            },
            async asyncFind(query){
                if(query.split('').length > 2){
                    this.isLoading = true
                    await this.directoryBanksSearch(query)
                    this.banksOptions = this.directoryBanksList
                    this.isLoading = false
                }
            },
        }
    }

/*
    Здравствуйте мне калега нравится 
*/
</script>

