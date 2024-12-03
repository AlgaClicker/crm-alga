<template>
    <b-modal
        id="kadry-brigade-modal-payments"
        ref="kadry-brigade-modal-payments"
        centered 
        content-class="c-modal-default с-modal-kadry-brigad-payments"
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Новая выплата</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class='c-body с-modal-kadry-brigad-payments_body'>
            <b-row>
                <b-col cols="6">
                    <label class="label-custom" for="input-name">Сумма</label>
                    <b-form-input
                        id="input-amount"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.amount.$error }"
                        aria-describedby="input-amount-feedback"
                        v-maska
                        data-maska="0.99"
                        data-maska-tokens="0:\d:multiple|9:\d:optional"
                        v-model="form.amount.moneyFilter"
                        v-model.trim="$v.form.amount.$model"
                        :state="!$v.form.amount.$error && null"
                        placeholder="Введите сумму"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.amount.required" id="input-amount-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.amount.numeric" id="input-amount-feedback">
                       Допустимы только цифры
                    </b-form-invalid-feedback>
                </b-col>
                <b-col cols="6">
                    <label class="label-custom" for="input-name">Тип</label>
                    <b-form-select 
                        id="input-type"
                        style="width: 100%"
                        v-model="form.type" 
                        v-model.trim="$v.form.type.$model"
                        :options="optionsType"
                    >
                    </b-form-select>
                </b-col>
            </b-row>
            <b-row class='mt-2'>
                <b-col cols="6">
                    <label class="label-custom" for="input-name">Бригада</label>
                    <multiselect 
                        v-model="form.brigade"  
                        v-model.trim="$v.form.brigade.$model"
                        placeholder="Выберите бригадира" 
                        :options="brigadesList" 
                        label="name"
                        track-by="name"
                        :show-labels="false"
                    >
                        <template slot="option" slot-scope="props">
                            <div class="option__desc">
                                <div class="c-avatar-list">
                                    <span class="option__title c-avatar">{{ props.option.name.split('')[0].toUpperCase() }}</span>
                                    <span class="ml-1"> {{ props.option.name}} </span>
                                </div>
                            </div>
                        </template>
                    </multiselect> 
                </b-col>
                <b-col cols="6">
                    <label class="label-custom" for="input-name">Дата</label>
                    <date-picker
                        style="width: 100%" 
                        input-class='mx-input-modal'
                        v-model.trim="$v.form.date.$model"
                        v-model="form.date"
                    >
                    </date-picker>
                </b-col>
            </b-row>
            <b-row class='mt-2'>
                <b-col cols="6">
                    <label class="label-custom" for="input-name">Описание</label>
                    <b-form-textarea
                        id="input-description"
                        class="input-custom"
                        aria-describedby="input-description-feedback"
                        v-model="form.description"
                        placeholder="Введите описание..."
                        trim
                        rows="3"
                        no-resize
                    ></b-form-textarea>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="newPayment"  class="mt-4" 
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
    import { required } from 'vuelidate/lib/validators';
    import { validationMixin } from "vuelidate";
    import { money } from '@/utils/customValidations'
    import { vMaska } from "maska"

    export default {
        name: 'KadryBrigadePaymentModal',
        directives: { maska: vMaska },
        data(){
            return {
                form: {
                    amount: '',
                    date: '',
                    brigade: '',
                    type: '',
                    description: ''
                },
                brigadesList: [],
                optionsType: [
                    { value: 'advance', text: 'Аванс' },
                    { value: 'salary', text: 'Зарплата' },
                    { value: 'other', text: 'Прочие выплаты' },
                ]
            }
        },
        props: {
            brigadesListProps: { type: Array, default: () => [] } ,
        },
        validations: {
            form: {
                amount: { money, required },
                brigade: { required },
                type: { required },
                date: { required }
            }
        },
        mixins: [validationMixin],
        computed: {
            ...mapGetters({
                kadryError: 'kadryErrorGetter',
            }),
            validationsComputed(){
                if( this.$v.form.amount.$invalid  == false && 
                    this.$v.form.brigade.$invalid == false &&
                    this.$v.form.type.$invalid == false &&
                    this.$v.form.date.$invalid == false
                ){
                    return true
                }
                else {
                    return false  
                }
            }
        },
        methods: {
            ...mapActions({ 
                kadryPaymentsBrigadeNew: 'kadryPaymentsBrigadeNewActions',
            }),
            hideModal(){
                this.$refs['kadry-brigade-modal-payments'].hide();
            },
            async newPayment(){
                let form = this.form
                form.brigade = this.form.brigade.id

                await this.kadryPaymentsBrigadeNew(form)
                if(!this.kadryError){
                    this.hideModal()
                }
            },
            mountedData(){
                this.$v.$reset()
                this.form.amount = ''
                this.form.date = ''
                this.form.brigade = ''
                this.form.type = ''
                this.form.description = ''
                this.brigadesList = this.brigadesListProps
            }
        }
    }
</script>