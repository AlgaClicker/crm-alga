<template>
    <b-modal
        id="finance-payments-modal-info"
        ref="finance-payments-modal-info"
        centered 
        content-class="c-modal-default c-modal-new-payments"
        @shown="mountedData"
    >
        <template #modal-header>
            <h5>Информация о платеже</h5>
            <button @click="hideModal">
                <b-icon icon="x" scale="1"></b-icon>
            </button>
        </template>
        <div class="c-body c-body-add-brigade">
            <b-row> 
                <b-col class="mt-2" cols="6">
                    <label class="label-custom"  for="input-type" >Выберите дату</label>
                    <date-picker
                        style="width: 100%" 
                        v-model="form.date"
                    >
                    </date-picker>
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
                        max-rows="6"
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
            </b-row>
            <label>

            </label>
            <b-row>

            </b-row>
        </div>
        <template #modal-footer>
            <button 
                @click="editPayments"  
                class="mt-4" 
                :class="{ 'c-button-save': !validationsComputed, 'c-button-save--success': validationsComputed }"
                :disabled="!validationsComputed"
            >
                Редактировать
            </button>
        </template>
    </b-modal>
</template>

<script>
    import { mapGetters } from "vuex";
    import { required } from 'vuelidate/lib/validators';
    import { validationMixin } from "vuelidate";
    import { money } from '@/utils/customValidations'

    export default {
        name: 'FinancePaymentsModalInfo',
        data(){
            return {
                specification: [],
                form: {
                    date: '',
                    amount: '',
                    type: {},
                    partner: '',
                    description: '',
                    company: {},
                },
                optionsType: [
                    { value: 'in', text: 'Входящий платеж' },
                    { value: 'out', text: 'Исходящий' },
                ],
                optionsTax: [
                    { value: '10', text: '10%' },
                    { value: '20', text: '20%' },
                ],
                taxNDS: false
            }
        },
        mixins: [validationMixin],
        props: {
            paymentsProps: { type: Object, default: () => {} },
            partnersListProps: { type: Array, default: function () {} },
        },
        validations: {
            form: {
                partner: { required },
                description: { required },
                amount: { money, required },
            }
        },
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
            editPayments(){
                console.log(1)
            },
            hideModal(){
                this.$refs['finance-payments-modal-info'].hide();
            },
            mountedData(){
                this.$v.$reset()
                
                this.form.partner = this.paymentsProps.partner
                this.form.description = ''
                this.form.type = 'out'
                this.form.amount = this.paymentsProps.amount
                this.form.date = this.paymentsProps.date
                this.form.specification = null
            },
        }
    }

</script>
