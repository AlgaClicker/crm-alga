<template>
  <div class="wrapper-profile">
    <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
    <div class="avatar-container">
      <div class="avatar-container__wrapper-image">
        <div class="avatar-container__image">
        </div>
        <div class="avatar-container__upload">
          <label>
              <input name="c-chat-upload" id="c-chat-upload" type="file" ref="file" @change="fileUpload()"/>
              <base-icon iconProps="camera" sizeProps="md" />
          </label>
        </div>
      </div>
      <div class="avatar-container__name">
        <p>
          {{ form.surname }}
        </p>
        <p>
          {{ form.name }} {{ form.patronymic }}
        </p>
        <small>
          {{ this.profile.roles.name }}
        </small>
      </div>
    </div> 
    <b-row class="mt-4">
      <b-col ms="12" md="4" class="mt-2">
        <label class="label-custom-2" for="input-surname">Логин </label>
        <b-form-input
            id="input-surname"
            class="input-custom-2"
            aria-describedby="input-surname-feedback"
            v-model="form.username"
            placeholder="Введитите  элеткронную почту"
            trim
        ></b-form-input>
      </b-col>
      <b-col  ms="12" md="4" class="mt-2">
        <label class="label-custom-2" for="input-surname">Электронная почта</label>
        <b-form-input
            id="input-surname"
            class="input-custom-2"
            aria-describedby="input-surname-feedback"
            v-model="form.email"
            placeholder="Введитите  элеткронную почту"
            trim
        ></b-form-input>
      </b-col>
    </b-row>
    <b-row class="mt-2">
      <b-col  ms="12" md="4" class="mt-2">
        <label class="label-custom-2" for="input-surname">Номер телефона</label>
        <b-form-input
            id="input-surname"
            class="input-custom-2"
            aria-describedby="input-surname-feedback"
            v-model="form.phone_number"
            placeholder="Введитите номер телефона"
            trim
        ></b-form-input>
      </b-col>
    </b-row>
    <button class="mt-4 c-button-save">
      Редактировать
    </button>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import BaseIcon from "@/components/elements/BaseIcon"

  export default {
    name: 'Profile',
    data(){
      return {
        breadcrumb: [
          { text: 'Профиль', href: '/crm/profile' },
        ],
        form: {
          surname: '',
          name: '',
          patronymic: '',
          email: '',
          phone_number: '',
          username: ''
        }
      }
    },
    components: {
      BaseIcon
    },
    computed: {
      ...mapGetters({
        profile: "profileGetter",
      }),
    },
    methods: {
      fileUpload(){

      }
    },
    mounted(){
      this.form.surname = this.profile.workpeople?.surname 
      this.form.name = this.profile.workpeople?.name
      this.form.patronymic = this.profile.workpeople?.patronymic
      this.form.phone_number = this.profile.workpeople?.phone_number
      this.form.email = this.profile?.email
      this.form.username = this.profile.username
    }
  }
</script>
