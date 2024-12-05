<template>
    <b-modal
        id="finance-modal-new-payments"
        ref="finance-modal-new-payments"
        centered 
        content-class="c-modal-default c-modal-new-payments"
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Новый платеж</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-base c-modal-new-payments_body">
            <b-row>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-surname">Фирма</label>
                    <multiselect 
                        v-model="form.partner" 
                        :options="partnersListProps"
                        placeholder="Выберите фирму" 
                        label="name"
                        track-by="name"
                        :show-labels="false"
                    >
                        <template slot="option" slot-scope="props">
                            {{ props.option.name }}
                        </template>
                    </multiselect>   
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-surname">Сумма</label>
                    <b-form-input
                        id="input-amount"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.amount.$error}"
                        aria-describedby="input-amount-feedback"
                        v-model="form.amount"
                        v-model.trim="$v.form.amount.$model"
                        v-maska
                        data-maska="0.99"
                        data-maska-tokens="0:\d:multiple|9:\d:optional"
                        :state="!$v.form.amount.$error && null"
                        placeholder="Сумма"
                        trim
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.amount.required" id="input-amount-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.amount.money" id="input-amount-feedback">
                        Недопустимое значение
                    </b-form-invalid-feedback>
                </b-col>
            </b-row>
            <b-row>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-surname">Выберите спецификацию</label>
                    <multiselect 
                        v-model="form.specification" 
                        :options="specificationsListProps"
                        placeholder="Выберите спецификацию" 
                        label="name"
                        track-by="name"
                        :show-labels="false"
                    >
                        <template slot="option" slot-scope="props">
                            {{ props.option.name }}
                        </template>
                    </multiselect>   
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom"  for="input-type" >Выберите дату</label>
                    <date-picker
                        input-class='mx-input-modal'
                        style="width: 100%" 
                        v-model="form.date"
                    >
                    </date-picker>
                </b-col>
            </b-row>
            <b-row>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-description">Описание</label>
                    <b-form-textarea
                        id="input-description"
                        class="input-custom"
                        :class="{ 'input-custom--invalid': $v.form.description.$error }"
                        aria-describedby="input-description-feedback"
                        v-model="form.description"
                        v-model.trim="$v.form.description.$model"
                        :state="!$v.form.description.$error && null"
                        placeholder="Введите описание..."
                        trim
                        rows="3"
                        no-resize
                    ></b-form-textarea>
                    <b-form-invalid-feedback v-if="!$v.form.description.required" id="input-description-feedback">
                        Незаполненное поле
                    </b-form-invalid-feedback>
                </b-col>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom"  for="input-type" >Тип платежа</label>
                    <b-form-select 
                        id="input-type"
                        style="width: 100%"
                        v-model="form.type" 
                        :options="optionsType"
                    >
                    </b-form-select>
                </b-col>
            </b-row>
            <b-row>
                <b-col class="mt-2" cols="6">
                    <label class="label-custom" for="input-type" >Налог</label>
                    <b-form-group>
                        <b-form-radio v-model="taxNDS" name="some-radios" :value="false">Без НДС</b-form-radio>
                        <b-form-radio v-model="taxNDS" name="some-radios" :value="true">с НДС</b-form-radio>
                    </b-form-group>
                    <div v-show="taxNDS" class='ml-2 custom-radio-child'>
                        <b-form-radio v-model="form.tax" value="10">10%</b-form-radio>
                        <b-form-radio v-model="form.tax" value="20">20%</b-form-radio>
                    </div>
                </b-col>
            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="newPayments"  
                class="mt-4" 
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
        name: 'FinanceModalNewPayments',
        directives: { maska: vMaska },
        data(){
            return {
                form: {
                    partner: null,
                    description: '',
                    type: 'out',
                    amount: 0,
                    date: '',
                    specification: null,
                    tax: 0
                },
                optionsType: [
                    { value: 'in', text: 'Входящий платеж' },
                    { value: 'out', text: 'Исходящий' },
                ],
                optionsTax: [
                    { value: '10', text: '10%' },
                    { value: '20', text: '20%' },
                ],
                taxNDS: false,
            }
        },
        props: {
            partnersListProps: { type: Array, default: function () {} },
            specificationsListProps: { type: Array, default: function () {} },
        },
        validations: {
            form: {
                partner: { required },
                description: { required },
                amount: { money, required },
            }
        },
        mixins: [validationMixin],
        computed: {
            ...mapGetters({
                directoryPartnersError: 'directoryPartnersErrorGetter',
                paymentsError: 'paymentsErrorGetter'
            }),
            validationsComputed(){
                if( this.$v.form.partner.$invalid  == false && 
                    this.$v.form.description.$invalid == false && 
                    this.$v.form.amount.$invalid == false 
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
                paymentsNew: 'paymentsNewActions',
                paymentsSet: 'paymentsSetActions'
            }),
            newPayments(){
                let form = this.form
                form.partner = this.form.partner.id
                form.specification = this.form.specification?.id
                form.amount = this.form.amount
 
                this.paymentsNew(form)

                if(!this.paymentsError){
                    this.paymentsSet()
                    this.hideModal()
                }
            },
            hideModal(){
                this.$refs['finance-modal-new-payments'].hide();
            },
            mountedData(){
                this.$v.$reset()
                this.form.partner = null
                this.form.description = ''
                this.form.type = 'out'
                this.form.amount = 0
                this.form.date = ''
                this.form.specification = null
            }
        }
    }

</script>
