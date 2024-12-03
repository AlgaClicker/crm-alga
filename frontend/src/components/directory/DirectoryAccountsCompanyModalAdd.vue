<template>
    <b-modal 
        id="directory-accounts-company-modal-add"
        ref="directory-accounts-company-modal-add"
        title="Новый сотрудник"
        content-class="c-modal-default"
        centered 
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Новый аккаунт</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body">
            <b-row>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-username">Логин</label>
                    <b-form-input
                        id="input-username"
                        aria-describedby="input-username-feedback"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.username.$error}"
                        v-model="form.username"
                        v-model.trim="$v.form.username.$model"
                        :state="!$v.form.username.$error && null"
                        placeholder="Введите логин"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.username" id="input-username-feedback">
                       Заполните поле
                    </b-form-invalid-feedback>
                </b-col>
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
                <b-row>
                    <b-col class="mt-2" cols="6">
                        <div>
                            <label class="label-custom" for="label-custom">Роли</label>
                        </div>
                        <b-form-select v-model="form.roles" :options="accountsCompanyRolesList"></b-form-select>
                        <b-form-invalid-feedback id="input-roles-feedback">
                            Незаполненное поле
                        </b-form-invalid-feedback>
                    </b-col>
                </b-row>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="addAccountsCompany"  class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Сохранить
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
        data() {
            return {
                form: {
                    username: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    roles: ''
                }
            }
        },
        validations: {
            form: {
                username: { required },
                email: { 
                    required,
                    email,
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
            }
        },
        mixins: [validationMixin],
        computed: {
            ...mapGetters({
                accountsCompanyError: 'accountsCompanyErrorGetter',
                accountsCompanyRolesList: 'accountsCompanyRolesListGetter'
            }),
            validationsComputed() {
                if( 
                    this.$v.form.username.$invalid  == false && 
                    this.$v.form.email.$invalid  == false  && 
                    this.$v.form.roles.$invalid  == false &&
                    this.$v.form.password.$invalid == false && 
                    this.$v.form.password_confirmation.$invalid  == false
                ){
                    return true
                }else {
                    return false
                }
            }
        },
        methods: {
            ...mapActions({
                accountsCompanyAdd: 'accountsCompanyAddActions'
            }),
            async addAccountsCompany(){
                this.$v.$touch(); 
                if(!this.$v.$invalid){

                    await this.accountsCompanyAdd(this.form)
                    //alert(this.accountsCompanyError)
                    if(!this.accountsCompanyError){
                        this.hideModal()
                    }
                }
            },
            hideModal(){
                this.$refs['directory-accounts-company-modal-add'].hide();
            },
            mountedData(){
    
                this.form.username = ''
                this.form.email = ''
                this.form.roles = ''
                this.form.password = ''
                this.form.fixed = true
                this.form.password_confirmation = ''
               
                this.$v.$reset()
            }
        }
    }
</script>