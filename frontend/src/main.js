import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import Vuelidate from 'vuelidate'
import Notifications from 'vue-notification'
import DatePicker from 'vue2-datepicker'
import { vMaska } from "maska"
import FloatingVue from 'floating-vue'
import 'vue2-datepicker/locale/ru'
import 'floating-vue/dist/style.css'

import VueEasytable from "vue-easytable"
import { VeLocale } from "vue-easytable"
import Multiselect from 'vue-multiselect'
import Ru from "vue-easytable/libs/locale/lang/ru-RU"

import io from 'socket.io-client'

import "vue-easytable/libs/theme-default/index.css"
import "vue-multiselect/dist/vue-multiselect.min.css"
import 'vue2-datepicker/index.css'

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import {
  dateFilter,
  sizeFilter,
  moneyFilter,
  boolFilter,
  paymentsTypeFilter,
  dateFilterWithoutTime,
  phoneFilter,
  notificationsDateFilter,
  dateFilterMonth,
  dateOnlyFilter
} from '@/filters/filters'

Vue.component('multiselect', Multiselect)

VeLocale.use(Ru)
Vue.config.productionTip = false

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(DatePicker)
Vue.use(Notifications)
Vue.use(VueEasytable)
Vue.use(Vuelidate)
Vue.use(FloatingVue)
Vue.directive("maska", vMaska)

Vue.filter('dateFilter', dateFilter)
Vue.filter('dateOnlyFilter', dateOnlyFilter)
Vue.filter('sizeFilter', sizeFilter)
Vue.filter('moneyFilter', moneyFilter)
Vue.filter('phoneFilter', phoneFilter)
Vue.filter('paymentsTypeFilter', paymentsTypeFilter)
Vue.filter('dateFilterWithoutTime', dateFilterWithoutTime)
Vue.filter('notificationsDateFilter', notificationsDateFilter)
Vue.filter('dateFilterMonth', dateFilterMonth)
Vue.filter('boolFilter', boolFilter)

window.io = io;

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')