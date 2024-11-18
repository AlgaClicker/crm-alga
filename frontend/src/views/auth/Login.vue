<template>
  <div class="c-login-wrapper">

    <div class="c-login-wrapper__logo">
      <base-icon iconProps="logo" sizeProps="lg" />
    </div>
    <div class="c-login-form">
      <div class="c-login-form__header">
        <header class="c-login-form__header-text">Вход в систему</header> 
      </div> 
      <label class="c-login-form__label mt-4" >Логин:</label>
      <input
        v-model="form.username"
        class="c-login-form__input"
        type="email"
      >
      <label class="c-login-form__label mt-3" >Пароль:</label>
      <input
        v-model="form.password"
        class="c-login-form__input mb-3"
        type="password"
      >
      <div v-if="error" class="c-login-form__error">
        <base-icon iconProps="error" sizeProps="md" />
        <span v-html="iconError"></span>
        {{ error }}
      </div>
      <base-button-loading
        class="mt-4"
        textProps="Войти" 
        :loadingProps="loginLoading"
        @click="Auth">
      </base-button-loading>
    </div>
  </div>

</template>

<script>
  import { mapActions, mapGetters } from "vuex";
  import { required } from 'vuelidate/lib/validators'
  import { validationMixin } from "vuelidate";
  import BaseIcon from "@/components/elements/BaseIcon"

  import BaseButtonLoading from '@/components/elements/BaseButtonLoading'

  export default {
    name: "Login",
    data(){
      return {
        form: {
          username: '',
          password: '',
        },
        disable: true,
      }
    },
    validations: {
      form: {
        username: {
          required,
        },
        password: {
          required,
        }
      }
    },
    mixins: [validationMixin],
    components: {
      BaseButtonLoading,
      BaseIcon
    },
    computed: {
      ...mapGetters({
        error: 'loginErrorGetter',
        loginLoading: 'loginLoadingGetter',
        roles: 'rolesGetter',
      }),
    },
    methods: {
      ...mapActions({
        login: "loginActions",
      }),
      async Auth(){
        this.$v.$touch(); 
        if(!this.$v.form.username.$error && !this.$v.form.password.$error){
          await this.login(this.form)
          if(!this.error){
            document.removeEventListener('keydown', this.enterAuth)
            this.$router.push('/')
          }
        }
      },
      enterAuth(event){
        if (event.key === 'Enter') {
          this.Auth()
        }
      }
    },
    mounted(){
      document.addEventListener('keydown', this.enterAuth)
    }
}
</script>

<style>
</style>

