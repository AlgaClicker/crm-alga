<template>
    <b-modal
        id="directory-employees-modal-edit"
        ref="directory-employees-modal-edit"
        title="Новый сотрудник"
        content-class="c-modal-default c-modal-employees"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Редактировать сотрудника</h5>
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
                    ></date-picker> 
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom  mt-2" for="input-name">Номер табеля</label>
                    <b-form-input
                        id="input-tabelNumber"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.tabel_number.$error}"
                        aria-describedby="input-tabelNumber-feedback"
                        v-model="form.tabel_number"
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
                        :class="{ 'input-custom--invalid': $v.form.surname.$error}"
                        aria-describedby="input-surname-feedback"
                        v-model="form.surname"
                        v-model.trim="$v.form.surname.$model"
                        :state="!$v.form.surname.$error && null"
                        placeholder="Введитите фамилию"
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
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.patronymic.$error}"
                        aria-describedby="input-patronymic-feedback"
                        v-model="form.patronymic"
                        v-model.trim="$v.form.patronymic.$model"
                        :state="!$v.form.patronymic.$error && null"
                        placeholder="Введитите отчество"
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
                        :class="{ 'input-custom--invalid': $v.form.phone_number.$error}"
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
                <b-col cols="6">
                    <div v-if="form.account" class="mt-3">
                        <label class="label-custom" for="input-tabelNumber">Прикрепленный аккаунт</label>
                        <base-account
                            @deleteAccountEmit="removeAccount()"
                            :accountProps="form.account"
                            :isEdit="false" 
                        />
                    </div>
                    <div v-else>
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
                    <label class="label-custom" for="input-profession">Должность</label>
                    <b-form-select 
                        style="width: 100%;" 
                        v-model="form.profession.id"
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
                @click="editEmployees" class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Сохранить
            </button>
            <button 
                @click="deleteEmployees()"   
                class="c-button-delete--success mt-4"
                v-b-modal.base-delete-modal
            >
                Удалить
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
        name: 'DirectoryEmployeesModalEdit',
        data(){
            return {
                form: {
                    id: '',
                    name: null,
                    surname:null,
                    patronymic: null,
                    phone_number: null,
                    tabel_number: null,
                    brith_at: null,
                    profession: '',
                    account: null
                }
            }
        },
        props: {
            editEmployeesProps: { type: Object, default: () => {} },
        },
        validations: {
            form: {
                name: { required },
                surname: { required },
                patronymic: { required },
                phone_number: {
                    required,
                    phoneNumber,
                    minLength: minLength(16),
                    maxLength: maxLength(16)
                },
                tabel_number: { required },
                profession: { required }
            }
        },
        mixins: [validationMixin],
        components: {
           BaseAccount
        },
        computed: {
            ...mapGetters({
                accountsCompanyLoading: 'accountsCompanyLoadingGetter',
                directoryEmployeesError: 'directoryEmployeesErrorGetter',
                directoryPostsNameOptions: 'directoryPostsNameOptionsGetter',
                accountsCompanyOptionsFree: 'accountsCompanyOptionsFreeGetter',
                directoryEmployeesSelectedList: 'directoryEmployeesSelectedListGetter',
            }),
            validationsComputed() {
                if( 
                    this.$v.form.surname.$invalid  == false && 
                    this.$v.form.name.$invalid  == false  && 
                    this.$v.form.patronymic.$invalid  == false &&
                    this.$v.form.phone_number.$invalid == false && 
                    this.$v.form.tabel_number.$invalid  == false 
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
                setParamsDeleteModal: 'setParamsDeleteModalActions',
                directoryEmployeesEdit: 'directoryEmployeesEditActions',
                accountsCompanySetFilter: 'accountsCompanySetFilterActions',
                accountsCompanyRemoveEmployees: 'accountCompanyRemoveEmployeesActions',
            }),
            async editEmployees(){
                this.$v.$touch(); 
                
                if(!this.$v.$invalid){
                    let form = { ...this.form }

                    let editEmployees = {
                        id: form.id,
                        surname: form.surname,
                        name: form.name,
                        patronymic: form.patronymic,
                        phoneNumber: form.phone_number,
                        tabelNumber: form.tabel_number,
                        profession: form.profession.id, 
                    }

                    if(form.brith_at != null){
                        editEmployees.brithAt = new Date(form.brith_at).toLocaleString()
                    } else {
                        editEmployees.brithAt = null
                    }
                    
                    if(this.form.account != null && typeof this.form.account != 'undefined' && this.form.account != ''){
                        editEmployees.account = form.account.value
                    }else{ 
                        editEmployees.account = ''
                    }

                    await this.directoryEmployeesEdit(editEmployees)
                    if(!this.directoryEmployeesError){
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
            hideModal(){
                this.$refs['directory-employees-modal-edit'].hide();
            },
            removeAccount(){
                this.form.account = null
            },
            deleteEmployees(){  
                this.setParamsDeleteModal(
                    {
                        title: 'сторудника',
                        actionsName: 'directoryEmployeesDeleteActions',
                        data: [ this.form ]
                    }
                )
                this.hideModal()
            },
            mountedData(){
                
                this.form.id = this.editEmployeesProps.id
                this.form.surname = this.editEmployeesProps.surname
                this.form.name = this.editEmployeesProps.name
                this.form.patronymic = this.editEmployeesProps?.patronymic
                this.form.phone_number = this.editEmployeesProps.phone_number
                this.form.tabel_number = this.editEmployeesProps.tabel_number

                if(typeof this.editEmployeesProps.brith_at != 'undefined'){
                    this.form.brith_at = new Date(this.editEmployeesProps?.brith_at)
                } else {
                    this.form.brith_at = null
                }

                if( typeof this.editEmployeesProps.profession != 'undefined' ){
                    this.form.profession = this.editEmployeesProps.profession
                }else {
                    this.form.profession = ''
                }

                if( typeof this.editEmployeesProps.account != 'undefined'){
                    this.form.account = this.editEmployeesProps.account
                }else {
                    this.form.account = null
                }
            }
        }
    }
</script>