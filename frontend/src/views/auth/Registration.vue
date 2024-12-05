<template>
  <div class="c-register-wrapper">

    <div class="c-register-form">
      <div class="c-register-form__header">
        <header class="c-register-form__header-text">Регистрация компании</header>
      </div>

      <!-- Поля ввода -->
      <div class="c-register-form__field" v-if="!registrationStatusGetter">
        <label class="c-register-form__label">E-mail:</label>
        <input
            class="c-register-form__input"
            type="email"
            placeholder="Введите ваш e-mail"
            v-model="email"
            :class="{ 'is-invalid': emailInvalid }"
            @input="validateEmail"
            required
        />
        <span v-if="emailInvalid" class="c-register-form__error">
          Некорректный E-mail.
        </span>
      </div>

      <div class="c-register-form__field" v-if="!registrationStatusGetter">
        <label class="c-register-form__label">ИНН Организации:</label>
        <input
            class="c-register-form__input"
            type="text"
            placeholder="Введите ИНН вашей организации"
            v-model="inn"
            :class="{ 'is-invalid': innInvalid }"
            @input="validateInn"
            required
        />
        <span v-if="innInvalid" class="c-register-form__error">
          Некорректный ИНН. ИНН должен содержать 10 или 12 цифр.
        </span>
      </div>

      <div class="c-register-form__field" v-if="!registrationStatusGetter">
        <label class="c-register-form__label">Пароль:</label>
        <input
            class="c-register-form__input"
            type="password"
            v-model="password"
            :class="{ 'is-invalid': passwordInvalid }"
            @input="validatePassword"
            placeholder="Введите пароль"
            required
        />
        <span v-if="passwordInvalid" class="c-register-form__error">
      Пароль должен содержать не менее 6 символов.
    </span>
      </div>

      <div class="c-register-form__field" v-if="!registrationStatusGetter">
        <label class="c-register-form__label">Подтвердите пароль:</label>
        <input
            class="c-register-form__input"
            type="password"
            v-model="confirmPassword"
            :class="{ 'is-invalid': confirmPasswordInvalid }"
            @input="validateConfirmPassword"
            placeholder="Повторите пароль"
            required
        />
        <span v-if="confirmPasswordInvalid" class="c-register-form__error">
      Пароли не совпадают.
    </span>
      </div>

      <div class="c-register-form__field" v-if="!registrationStatusGetter">
        <label class="c-register-form__label">Номер телефона:</label>
        <input
            class="c-register-form__input"
            type="tel"
            placeholder="Введите ваш номер телефона"
            v-model="phoneNumber"
            :class="{ 'is-invalid': phoneInvalid }"
            @input="validatePhone"
            required
        />
        <span v-if="phoneInvalid" class="c-register-form__error">
          Некорректный номер телефона. Используйте формат +7XXXXXXXXXX.
        </span>
      </div>
      <div class="c-register-form__field row" v-if="!registrationStatusGetter">
        <div class="col-6 text-center">
          <b-button class="c-button-info" @click="cancelreg">Отмена</b-button>
        </div>
        <div class="col-6 text-center">
          <b-button class="c-button" @click="regOk">Регистрация</b-button>
        </div>

      </div>
      <div class="c-register-form__field row" v-if="registrationErrorGetter">
        {{registrationErrorGetter}}
      </div>

      <div class="c-register-form__field text-center" v-if="registrationStatusGetter">
        Ваша регистрация прошла успешно. На указанный вами email-адрес отправлено письмо с подтверждением. Пожалуйста, проверьте почту и следуйте инструкциям в письме для завершения процесса регистрации.
        <p>
          <RouterLink to="/Login">Авторизация</RouterLink>
        </p>
      </div>
      <!-- Кнопка отправки -->
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      email: "sb@alga-corp.ru",
      emailInvalid: false,
      phoneNumber: "89124966126",
      phoneInvalid: false,
      inn: "5906173851",
      innInvalid: false,
      password: "pass23AlgaCms",
      confirmPassword: "pass23AlgaCms",
      passwordInvalid: false,
      confirmPasswordInvalid: false,
    };
  },
  computed: {
    ...mapGetters({
      registrationStatusGetter: "registrationStatusGetter",
      registrationErrorGetter: "registrationErrorGetter"
    }),
  },
  methods: {
    ...mapActions({
      companyRegistration: "companyRegistration"
    }),
    cancelreg() {
      this.$router.push('/')
    },
    async regOk() {
      let data = {
        "username": this.email,
        "password": this.password,
        "password_confirmation": this.confirmPassword,
        "inn": this.inn,
        "email": this.email,
        "phone_number": this.phoneNumber
      }
      console.log(data)
      await this.companyRegistration(data)
    },
    validateEmail() {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Проверяет стандартный формат E-mail
      this.emailInvalid = !emailRegex.test(this.email);
    },
    validateInn() {
      const innRegex = /^\d{10}$|^\d{12}$/; // Проверяет, что ИНН состоит из 10 или 12 цифр
      this.innInvalid = !innRegex.test(this.inn);
    },
    validatePhone() {
      const phoneRegex = /^\+7\d{10}$/; // Формат: +7XXXXXXXXXX
      this.phoneInvalid = !phoneRegex.test(this.phoneNumber);
    },
    validatePassword() {
      // Проверяем, что пароль не короче 6 символов
      this.passwordInvalid = this.password.length < 6;
      // Проверяем подтверждение пароля на совпадение
      this.validateConfirmPassword();
    },
    validateConfirmPassword() {
      // Проверяем, что подтверждение пароля совпадает с паролем
      this.confirmPasswordInvalid = this.password !== this.confirmPassword;
    },
  },
};
</script>

<style scoped>
.c-register-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}

.c-register-wrapper__logo {
  margin-bottom: 20px;
}

.c-register-form {
  background: #ffffff;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  display: flex;
  flex-direction: column;
  align-items: stretch;
}

.c-register-form__header {
  text-align: center;
  margin-bottom: 20px;
}

.c-register-form__header-text {
  font-size: 20px;
  font-weight: bold;
}

.c-register-form__field {
  margin-bottom: 15px;
}

.c-register-form__label {
  display: block;
  font-size: 14px;
  margin-bottom: 5px;
}

.c-register-form__input {
  width: 100%;
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.c-register-form__error {
  color: red;
  font-size: 12px;
  margin-top: 5px;
}

.is-invalid {
  border-color: red;
}

.c-register-form__button {
  margin-top: 20px;
  width: 100%;
}
</style>
