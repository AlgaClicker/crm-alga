<template>
    <b-modal 
        id="directory-accounts-company-modal-edit"
        ref="directory-accounts-company-modal-edit"
        title="Новый сотрудник"
        content-class="c-modal-default"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Редактировать аккаунт</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-username">Имя пользователя</label>
                    <b-form-input
                        id="input-username"
                        class="input-custom"
                        disabled
                        aria-describedby="input-name-feedback"
                        v-model="form.username"
                        v-model.trim="$v.form.username.$model"
                        :state="!$v.form.username.$error && null"
                        placeholder="Введите фамилию"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.email.email" id="input-username-feedback">
                       Заполните поле
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <div>
                        <label class="label-custom" for="label-custom">Роль</label>
                    </div>
                    <b-form-select v-model="form.roles" :options="accountsCompanyRolesList"></b-form-select>
                    <b-form-invalid-feedback id="input-roles-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <b-row>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-name">Электронная почта</label>
                    <b-form-input
                        id="input-email"
                        aria-describedby="input-email-feedback"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.email.$error }"
                        v-model="form.email"
                        v-model.trim="$v.form.email.$model"
                        :state="!$v.form.email.$error && null"
                        placeholder="Введите имя"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.email.email" id="input-email-feedback">
                        Электронная почта должна состоять из  из цифр (0-9), букв (A-Z) и символов «@», «-» и «_»
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.email.required" id="input-email-feedback">
                        Заполните поле
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-name">Повторите электронную почта</label>
                    <b-form-input
                        id="input-email_confirmation"
                        aria-describedby="input-email_confirmation-feedback"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.email_confirmation.$error }"
                        v-model="form.email_confirmation"
                        v-model.trim="$v.form.email_confirmation.$model"
                        :state="!$v.form.email_confirmation.$error && null"
                        placeholder="Введите имя"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.email_confirmation.email" id="input-confirmation-feedback">
                        Электронная почта должна состоять из  из цифр (0-9), букв (A-Z) и символов «@», «-» и «_»
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.email_confirmation.required" id="input-confirmation-feedback">
                        Заполните поле
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <b-row>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-password">Пароль</label>
                    <b-form-input
                        id="input-password"
                        type="password"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.password.$error }"
                        aria-describedby="input-password-feedback"
                        v-model="form.password"
                        v-model.trim="$v.form.password.$model"
                        :state="!$v.form.password.$error && null"
                        placeholder="Введите пароль"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.password.minLength">
                        Пароль должен быть длиннее 6 символов
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.password.required">
                        Заполните поле
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-name">Повторите пароль</label>
                    <b-form-input
                        id="input-password_confirmation"
                        type="password"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.password_confirmation.$error }"
                        aria-describedby="input-password_confirmation-feedback"
                        v-model="form.password_confirmation"
                        v-model.trim="$v.form.password_confirmation.$model"
                        :state="!$v.form.password_confirmation.$error && null"
                        placeholder="Введите имя"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.password_confirmation.sameAsPassword">
                        Пароли должны совпадать
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.password_confirmation.required">
                        Заполните поле
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="editAccountsCompany"  class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Сохранить
            </button>
            <button 
                @click="deleteAccount()"   
                class="c-button-delete--success mt-4"
                v-b-modal.base-delete-modal
            >
                Удалить
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import { required, minLength, email, sameAs } from 'vuelidate/lib/validators'
    import { validationMixin } from "vuelidate";

    export default {
        name: 'DirectoryAccountsCompanyModalAdd',
        data(){
            return {
                form: {
                    username: '',
                    roles: '',
                    email: '',
                    email_confirmation: '',
                    password: '',
                    password_confirmation: '',
                },
                old: {
                    email: '',
                    email_confirmation: ''
                }
            }
        },
        props: {
            editAccountProps: { type: Object, default: () => {} },
        },
        validations: {
            form: {
                username: { required },
                email: { 
                    required,
                    email,
                },
                email_confirmation: {
                    required,
                    email,
                    sameEmailassword: sameAs('email')
                },
                password: {
                    required,
                    minLength: minLength(6),
                },
                password_confirmation: {
                    required,
                    sameAsPassword: sameAs('password') 
                },
                roles: { required },
            },
        },
        mixins: [validationMixin],
        computed: {
            ...mapGetters({
                accountsCompanyError: 'accountsCompanyErrorGetter',
                accountsCompanyRolesList: 'accountsCompanyRolesListGetter',
            }),
            validationsComputed(){
                if( 
                    this.$v.form.username.$invalid  == false && 
                    this.$v.form.email.$invalid  == false  && 
                    this.$v.form.roles.$invalid  == false
                ){
                    return true
                }else {
                    return false
                }
            }
        },
        methods: {
            ...mapActions({
                accountsCompanyAdd: 'accountsCompanyAddActions',
                accountsCompanyEdit: 'accountsCompanyEditActions',
                setParamsDeleteModal: 'setParamsDeleteModalActions',
            }),
            async editAccountsCompany(){

                let form = {
                    roles: {

                    }
                }
                form.roles.service = this.form.roles
                form.id = this.form.id

                if( this.old.email != this.form.email & this.form.email == this.form.email_confirmation){
                    form.email = this.form.email
                    form.email_confirmation = this.form.email_confirmation
                }

                if( this.form.password == this.form.password_confirmation & this.form.password != this.form.password_confirmation){
                    form.password = this.form.password
                    form.password_confirmation = this.form.password_confirmation
                }
                
                await this.accountsCompanyEdit(form)
                if(!this.accountsCompanyError){
                    this.hideModal()
                }
            },
            deleteAccount(){
                this.setParamsDeleteModal(
                    {
                        title: 'сотрудника',
                        actionsName: 'directoryEmployeesDeleteActions',
                        data: [ this.form ]
                    }
                )
                this.hideModal()
            },
            hideModal(){
                this.$refs['directory-accounts-company-modal-edit'].hide();
            },
            mountedData(){

                this.form.id = this.editAccountProps.id
                this.form.username = this.editAccountProps.username
                this.form.email = this.editAccountProps.email
                this.form.email_confirmation = this.editAccountProps.email
                this.form.roles = this.editAccountProps.roles.service
                this.form.password = this.editAccountProps?.password
                this.form.password_confirmation = this.editAccountProps?.password_confirmation

                this.old.email = this.editAccountProps.email
                this.old.email_confirmation = this.editAccountProps.email
            }
        }
    }
</script>