<template>
    <div class="default-wrapper">
        <div class="default-wrapper__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
            <div class="left-container">
                <button v-b-modal.kadry-brigade-modal-payments class="c-button-add">
                    <b-icon icon="plus-lg" scale="1"></b-icon>
                    <span>
                        Создать 
                    </span>
                </button>
            </div>  
        </div>
        <div class="scroll-wrapper">
            <div class="c-default-table-container">
                <div class="c-default-table-container__header">
                    
                </div>
                <div class="c-default-table">
                    <table>
                        <thead>
                            <tr>
                                <th class="th-sort" @click="sortPayments('date')">
                                    ДАТА
                                    <b-icon v-show="sort.date.asc" icon="chevron-down" font-scale="1" ></b-icon>
                                    <b-icon v-show="sort.date.desc" icon="chevron-up" font-scale="1" ></b-icon>
                                </th>
                                <th class="th-sort" @click="sortPayments('amount')" >  
                                    СУММА
                                    <b-icon v-show="sort.amount.asc" icon="chevron-down" font-scale="1"></b-icon>
                                    <b-icon v-show="sort.amount.desc" icon="chevron-up" font-scale="1"></b-icon>
                                </th>
                                <th class="th-sort" @click="sortPayments('type')">
                                    ТИП
                                    <b-icon v-show="sort.type.asc" icon="chevron-down" font-scale="1"></b-icon>
                                    <b-icon v-show="sort.type.desc" icon="chevron-up" font-scale="1"></b-icon>
                                </th>
                                <th>
                                    БРИГАДА
                                </th>
                                <th>
                                    СПЕЦИФИКАЦИИ
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="kadryLoading" class="c-spinner_wrapper c-empty-table">
                            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                        </div>
                        <div 
                            v-else-if="!kadryLoading & kadryPaymentsBrigadeList.length == 0"
                            class="c-empty-table"
                        >
                            <img src="@/assets/images/box.png">
                            <p>
                                Пока нет платежей
                            </p>
                        </div>
                        <table v-else>
                            <tbody>
                                <tr v-for="item in kadryPaymentsBrigadeList" :key="item.id" > 
                                    <td><p>{{ item.date | dateFilterWithoutTime }}</p></td>
                                    <td><p>{{ item.amount | moneyFilter }}</p></td>
                                    <td><p>{{ item.type | paymentsTypeFilter }}</p></td>
                                    <td><p>{{ item.brigade.name }}</p></td>
                                    <td><p>{{ item.specification }}</p></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <pagginate-table
                    :optionsProps="kadryPaymentsBrigadeOptions"
                    :pagesOptionsProps="kadryPaymentsPageOptions"
                    :setListActionsNameProps="'kadryPaymentsBrigadeSetActions'"
                    :setOptionsActionsNameProps="'kadryPaymentsBrigadeSetOptionsActions'"
                />
            </div>
        </div>
        <KadryBrigadePaymentModal
            :brigadesListProps="directoryBrigadesList"
        />
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import KadryBrigadePaymentModal from '@/components/kadry/KadryBrigadePaymentModal'
    import PagginateTable from '@/components/elements/PagginateTable'

    export default {
        name: 'KadryBrigadesPayments',
        data(){
            return {
                valSearch: '',
                limits: [
                    { value: 5, text: '5'},
                    { value: 10, text: '10'},
                    { value: 25, text: '25'},
                    { value: 50, text: '50'},
                    { value: 100, text: '100'},
                ],
                sort: {
                    date: {
                        asc: true,
                        desc: false,
                    },
                    amount: {
                        asc: false,
                        desc: false,
                    },
                    type: {
                        asc: false,
                        desc: false,
                    }
                },
                isFilter: false,
                filter: {
                    type: null,
                    amount: {
                        start: null,
                        end: null,
                    }
                },
                optionsType: [
                    { value: 'advance', text: 'Аванс' },
                    { value: 'salary', text: 'Заработная плата' },
                    { value: 'other', text: 'Прочая выплата'}
                ],
                dateFilter: null,
                page: 1,
                limit: 10,
                breadcrumb: [
                    { text: 'Начисления бригадам', href: '/crm/kadry/payments' },
                ],
            }
        },
        components: {
            PagginateTable,
            KadryBrigadePaymentModal,
        },
        computed: {
            ...mapGetters({
                kadryPaymentsBrigadeList: 'kadryPaymentsBrigadeListGetter',
                directoryBrigadesList: 'directoryBrigadesListGetter',
                kadryPaymentsPageOptions: 'kadryPaymentsPageOptionsGetter',
                kadryPaymentsBrigadeOptions: 'kadryPaymentsBrigadeOptionsGetter',
                kadryLoading: 'kadryLoadingGetter'
            }),
        },
        methods: {
            ...mapActions({
                directoryBrigadesSetList: 'directoryBrigadesSetListActions',
                kadryPaymentsBrigadeSet: 'kadryPaymentsBrigadeSetActions',
                kadryPaymentsSetOptions: 'kadryPaymentsBrigadeSetOptionsActions',
                kadryPaymentsBrigadeSetDateFilter: 'kadryPaymentsBrigadeSetDateFilterActions',
                kadryPaymentsBrigadeSetFilter: 'kadryPaymentsBrigadeSetFilterActions'
            }),
            async sortPayments(field){
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.kadryPaymentsBrigadeOptions

                if(this.sort[field].asc){
                    let obj = {
                        [field] : 'ASC'
                    }
                    options.orderBy = obj
                }else{
                    let obj = {
                        [field] : 'DESC'
                    }
                    options.orderBy = obj
                }

                for(var key in this.sort){
                    if(key != field){
                        this.sort[key].asc = false
                        this.sort[key].desc = false
                    }
                }

                this.kadryPaymentsSetOptions(options)
                await this.kadryPaymentsBrigadeSet()
            },
        },
        async mounted(){
            await this.directoryBrigadesSetList({})
            await this.kadryPaymentsBrigadeSet()
        }
    }
</script>