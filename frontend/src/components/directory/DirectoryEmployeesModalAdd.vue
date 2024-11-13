<template>
    <b-modal 
        id="directory-employees-modal-add"
        ref="directory-employees-modal-add"
        title="Новый сотрудник"
        content-class="c-modal-default c-modal-employees"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Добавить сотрудника</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-body-employees">
            <b-row>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom  mt-2" for="input-name">Дата Рождения</label>
                    <date-picker
                        style="width: 100%;"
                        input-class='mx-input-modal'
                        v-model="form.brith_at"
                        v-model.trim="$v.form.brith_at.$model"
                    ></date-picker> 
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom  mt-2" for="input-name">Номер табеля</label>
                    <b-form-input
                        id="input-tabelNumber"
                        class="input-custom"
                        aria-describedby="input-tabelNumber-feedback"
                        v-model="form.tabelNumber"
                        v-model.trim="$v.form.tabel_number.$model"
                        :state="!$v.form.tabel_number.$error && null"
                        placeholder="Номер табеля"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-tabelNumber-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <b-row>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-surname">Фамилия</label>
                    <b-form-input
                        id="input-surname"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.surname.$error }"
                        aria-describedby="input-surname-feedback"
                        v-model="form.surname"
                        v-model.trim="$v.form.surname.$model"
                        :state="!$v.form.surname.$error && null"
                        placeholder="Введите фамилию"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-surname-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-name">Имя</label>
                    <b-form-input
                        id="input-name"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.name.$error}"
                        aria-describedby="input-name-feedback"
                        v-model="form.name"
                        v-model.trim="$v.form.name.$model"
                        :state="!$v.form.name.$error && null"
                        placeholder="Введите имя"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-name-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-patronymic">Отчество</label>
                    <b-form-input
                        id="input-patronymic"
                        aria-describedby="input-patronymic-feedback"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.patronymic.$error}"
                        v-model="form.patronymic"
                        v-model.trim="$v.form.patronymic.$model"
                        :state="!$v.form.patronymic.$error && null"
                        placeholder="Введите отчество"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-patronymic-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-phoneNumber">Номер телефона</label>
                    <b-form-input
                        id="input-phoneNumber"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.phone_number.$error }"
                        aria-describedby="input-phoneNumber-help input-phoneNumber-feedback"
                        v-model="form.phone_number"
                        v-model.trim="$v.form.phone_number.$model"
                        v-maska 
                        data-maska="+7-###-###-##-##"
                        :state="!$v.form.phone_number.$error && null"
                        placeholder="+7 999 999 99 99"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-phoneNumber-feedback">
                        Введите 11 цифр
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <b-row class="mt-2">
                <b-col cols="6" class="mt-1">
                    <div v-if="!!form.account" class="">
                        <label class="label-custom" for="input-tabelNumber">Прикрепленный аккаунт</label>
                        <base-account
                            @deleteAccountEmit="removeAccount()"
                            :accountProps="form.account.value"
                            :isEdit="false"
                        />
                    </div>
                    <div v-else >
                        <label class="label-custom" for="input-tabelNumber">Прикрепить аккаунт</label>
                        <multiselect
                            v-model="form.account" 
                            placeholder="Прикрепить аккаунт" 
                            :searchable="true"
                            :options="accountsCompanyOptionsFree"
                            label="username"
                            track-by="username"
                            :loading="accountsCompanyLoading"
                            :internal-search="false"
                            :clear-on-select="false" 
                            :options-limit="300" 
                            :limit="3" 
                            :max-height="600" 
                            :show-no-results="true" 
                            :show-labels="false" 
                            @search-change="asyncFind" 
                        >
                            <template slot="option" slot-scope="props">
                                <div class="option__desc">
                                    {{ props.option.username }}
                                </div>
                            </template>
                        </multiselect>
                    </div>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom">
                        Должность 
                    </label>
                    <b-form-select 
                        style="width: 100%;" 
                        v-model="form.profession" 
                        :options="directoryPostsNameOptions"
                    >
                    </b-form-select>
                    <b-form-invalid-feedback id="input-profession-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>

        </div>
        <template #modal-footer>
            <button 
                @click="addEmployees"  class="mt-4" 
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
    import { phoneNumber } from '@/utils/customValidations'

    import BaseAccount from '@/components/elements/BaseAccount'

    export default {
        name: 'DirectoryEmployeesModalAdd',
        data() {
            return {
                form: {
                    name: null,
                    surname: null,
                    patronymic: null,
                    phone_number: null,
                    brith_at: null,
                    tabel_number: null,
                    profession: null,
                    account: null
                }
            }
        },
        validations: {
            form: {
                surname: { required },
                name: { required },
                patronymic: { required },
                phone_number: {
                    required,
                    phoneNumber,
                    minLength: minLength(16),
                    maxLength: maxLength(16)
                },
                brith_at: { required },
                tabel_number: { required },
                profession: { required }
            }
        },
        mixins: [validationMixin],
        components: {
            BaseAccount,
        },
        computed: {
            ...mapGetters({
                accountsCompanyOptions: 'accountsCompanyOptionsGetter',
                accountsCompanyLoading: 'accountsCompanyLoadingGetter',
                directoryEmployeesError: 'directoryEmployeesErrorGetter',
                directoryPostsNameOptions: 'directoryPostsNameOptionsGetter',
                accountsCompanyOptionsFree: 'accountsCompanyOptionsFreeGetter',
            }),
            validationsComputed() {
                if( 
                    this.$v.form.surname.$invalid  == false && 
                    this.$v.form.name.$invalid  == false  && 
                    this.$v.form.patronymic.$invalid  == false &&
                    this.$v.form.phone_number.$invalid == false && 
                    this.$v.form.brith_at.$invalid == false &&
                    this.$v.form.tabel_number.$invalid  == false && 
                    this.$v.form.profession.$invalid == false 
                ){
                    return true
                }else {
                    return false
                }
            }
        },
        methods: {
            ...mapActions({
                accountsCompanySet: 'accountsCompanySetActions',
                directoryEmployeesAdd: 'directoryEmployeesAddActions',
                directoryPostsGetList: 'directoryPostsGetListActions',
                directoryEmployeesGet: 'directoryEmployeesGetActions',
                accountsCompanySetFilter: 'accountsCompanySetFilterActions',
                accountCompanyRemoveEmployees: 'accountCompanyRemoveEmployeesActions',
            }),
            async addEmployees(){

                this.$v.$touch(); 
                if(!this.$v.$invalid){

                    let newEmployees = {
                        surname: this.form.surname,
                        name: this.form.name,
                        patronymic: this.form.patronymic,
                        brithAt :new Date(this.form.brith_at).toLocaleString(),
                        phoneNumber: this.form.phone_number,
                        tabelNumber: this.form.tabel_number,
                        profession: this.form.profession,
                    }

                    if(this.form.account != null){
                        newEmployees.account = this.form.account.value
                    }else{ 
                        newEmployees.account = ''
                    }

                    await this.directoryEmployeesAdd(newEmployees)
                    if(!this.directoryEmployeesError){
                        this.directoryEmployeesGet()
                        this.hideModal()
                    }
                }
            },
            async asyncFind(query){
                if(query != ''){
                    this.accountsCompanySetFilter(
                        { username: query }
                    )
                } else {
                    this.accountsCompanySetFilter(null)
                }
                await this.accountsCompanySet()
            },
            removeAccount(){
                this.form.account = null
            },
            hideModal(){
                this.$refs['directory-employees-modal-add'].hide();
            },
            mountedData(){
                this.directoryPostsGetList()
                this.form.name = ''
                this.form.surname = ''
                this.form.patronymic = ''
                this.form.phone_number = ''
                this.form.tabel_number = ''
                this.form.profession = {}
                this.form.account = null
                
                this.$v.$reset()
            }
        }
    }
</script>