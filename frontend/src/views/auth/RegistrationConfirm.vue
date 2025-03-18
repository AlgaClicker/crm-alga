<template>
  <div class="c-login-wrapper" style="width: 40rem !important;">
    <div class="c-login-form">
      <div class="c-login-form__header">
        <header class="c-login-form__header-text">Подтверждение e-mail адреса</header>
      </div>
      <b-list-group>
        <!--
        <b-list-group-item>Подключение...
          <b-spinner v-if="!checkConnect"  small ></b-spinner>
          <b-icon-check-circle-fill v-if="checkConnect" ></b-icon-check-circle-fill>
          {{companyConfirmed}}
        </b-list-group-item>
        -->
        <b-list-group-item>Проврека ключа
          <b-icon-check-circle-fill v-if="this.companyConfirmedGetter" ></b-icon-check-circle-fill>
        </b-list-group-item>
        <b-list-group-item>Комапания: {{this.companyConfirmedGetter.fullname}}
          <b-spinner v-if="company.length == 0"  small ></b-spinner>
          <b-icon-check-circle-fill v-if="company.length > 0" ></b-icon-check-circle-fill>
        </b-list-group-item>
        <b-list-group-item v-if="loginLoadingGetter" class="text-center">
          <RouterLink to="/Login">Авторизация</RouterLink>
        </b-list-group-item>

      </b-list-group>
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";

export default {
  data() {
    return {

      checkKey: false,
      checkResult: false,
      company: {},
      linkLogin: false
    };
  },
  async mounted() {
    //console.log(this.$route.query.key)

    try {
      await this.companyConfirmed(this.$route.query.key);
    } catch (e) {
      console.log("error",e.message)
    }

    console.log("companyGetter",this.companyConfirmedGetter)
  },
  computed: {
    ...mapGetters(["companyGetter","companyConfirmedGetter","loginLoadingGetter"]),
    checkConnect: ()=> {
      console.log("checkConnect",this.companyConfirmedGetter)
      return this.companyConfirmedGetter
    }
  },
  methods: {
    ...mapActions({
      companyConfirmed: "companyConfirmed"
    }),
  },
}
</script>