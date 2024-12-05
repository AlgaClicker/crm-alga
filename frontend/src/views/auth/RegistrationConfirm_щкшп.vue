<template>
  <div class="c-login-wrapper" style="width: 40rem !important;">
    <div class="c-login-form">
      <div class="c-login-form__header">
        <header class="c-login-form__header-text">Подтверждение e-mail адреса</header>
      </div>
      <b-list-group>
        <b-list-group-item>Подключение...
          <b-spinner v-if="!checkConnect"  small ></b-spinner>
          <b-icon-check-circle-fill v-if="checkConnect" ></b-icon-check-circle-fill>
          {{companyConfirmed}}
        </b-list-group-item>
        <b-list-group-item>Проврека ключа
          <b-spinner v-if="!checkKey"  small ></b-spinner>
          <b-icon-check-circle-fill v-if="checkKey" ></b-icon-check-circle-fill>
        </b-list-group-item>
        <b-list-group-item>Результат
          <b-spinner v-if="!checkResult"  small ></b-spinner>
          <b-icon-check-circle-fill v-if="checkResult" ></b-icon-check-circle-fill>
        </b-list-group-item>
        <b-list-group-item>Комапания
          <b-spinner v-if="company.length == 0"  small ></b-spinner>
          <b-icon-check-circle-fill v-if="company.length > 0" ></b-icon-check-circle-fill>
        </b-list-group-item>
        <b-list-group-item>Ссылка на вход
          <b-spinner v-if="!linkLogin"  small ></b-spinner>
          <b-icon-check-circle-fill v-if="linkLogin" ></b-icon-check-circle-fill>
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
      checkConnect: false,
      checkKey: false,
      checkResult: false,
      company: {},
      linkLogin: false
    };
  },
   async mounted() {
    console.log(this.$route.query.key)
    await this.companyConfirmed(this.$route.query.key);
  },
  computed: {
    ...mapGetters({
       companyGetter:"companyGetter"
    }),
  },
  methods: {
    ...mapActions({
      companyConfirmed: "companyConfirmed"
    }),
  },
}
</script>