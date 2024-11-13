<template>
     <div class='l-payments'>
        <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
        <b-row align-h="between">
            <b-col cols sm="12" md="5" xl="5" class="mb-3">
                <div class="c-requisition-info-card">
                    <header>
                        <div class="title"> Информация </div>
                    </header>
                    <b-row>
                        <b-col>
                            <label class="mt-3 c-requisition-info-card__label">Дата платежа</label>
                            <div class="c-requisition-info-card__text">{{ payment.date | dateFilter}}</div>
                        </b-col>
                        <b-col>
                            <label class="mt-3 c-requisition-info-card__label">Сумма</label>
                            <div class="c-requisition-info-card__text">{{ payment.amount }}</div>
                        </b-col>
                        <b-col cols="12">
                            <label class="mt-3 c-requisition-info-card__label">Описание</label>
                            <div class="c-requisition-info-card__text">{{ payment.descriptions }}</div>
                        </b-col>    
                    </b-row>
                    <b-row>
                        <b-col>
                            <label class="mt-3 c-requisition-info-card__label">Автор</label>
                            <div class="c-requisition-info-card__text">{{ payment.autor?.username }}</div>
                        </b-col>
                        <b-col>
                            <label class="mt-3 c-requisition-info-card__label">Партнер</label>
                            <div class="c-requisition-info-card__text">{{ payment.partner?.name }}</div>
                        </b-col>
                    </b-row>
                </div>
            </b-col>
        </b-row>
    </div>
</template>

<script>

    import { mapActions, mapGetters } from 'vuex'

    export default {
        name: 'PaymentsInfo',
        data(){
            return {
                payments: {},
                breadcrumb: [
                    { text: 'Платежи', to: 'crm/buxgalteria/payments' },
                    { text: 'Информация о платеже', href: '' },
                ]  
            }
        },
        computed: {
            ...mapGetters({
                'payment': 'paymentGetter'
            })
        },
        methods: {
            ...mapActions({
                'paymentsSetPayment': 'paymentsSetPaymentActions'
            })
        },
        async mounted(){
            let id = window.location.href.split('/').reverse()[0];
            await this.paymentsSetPayment(id)
        }
    }
</script>