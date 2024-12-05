<template> 
    <div class='default-wrapper'>
        <div class="default-wrapper__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
            <div class="left-container">
                <button v-b-modal.finance-modal-new-payments class="c-button-add ml-1">
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
                    <filter-payment
                        :setFilterActionsNameProps="'paymentsSetFilterActions'"
                        :setListActionsNameProps="'paymentsSetActions'"
                    />
                    <filter-date 
                        :setListActionsNameProps="'paymentsSetActions'"
                        :setDateFilterActionsNameProps="'paymentsSetDateFilterActions'"
                    />
                    <div class="left-container">
                        <label for="input-upload" class="c-import" v-tooltip.top-center="'импортировать'" > 
                            <input 
                                name="input-upload" 
                                id="input-upload" 
                                type="file" 
                                ref="file" 
                                @change="importInvoiceFile()"
                            />
                            <b-icon icon="upload" scale="0.8"></b-icon>
                        </label>
                    </div>
                </div>  
                <div class="c-default-table">
                    <table>
                        <thead>
                            <tr>
                                <th width="140px" class="th-sort"  @click="sortPayments('date')">
                                    ДАТА
                                    <b-icon v-show="sort.date.asc" icon="chevron-down" font-scale="1" ></b-icon>
                                    <b-icon v-show="sort.date.desc" icon="chevron-up" font-scale="1" ></b-icon>
                                </th>
                                <th class="th-sort" @click="sortPayments('amount')" >  
                                    СУММА
                                    <b-icon v-show="sort.amount.asc" icon="chevron-down" font-scale="1"></b-icon>
                                    <b-icon v-show="sort.amount.desc" icon="chevron-up" font-scale="1"></b-icon>
                                </th>
                                <th width="140px" class="th-sort" @click="sortPayments('type')">
                                    ТИП
                                    <b-icon v-show="sort.type.asc" icon="chevron-down" font-scale="1"></b-icon>
                                    <b-icon v-show="sort.type.desc" icon="chevron-up" font-scale="1"></b-icon>
                                </th>
                                <th >ПАРТНЕР </th>
                                <th width="300px"> ОПИСАНИЕ </th>
                                <th> БАНК </th>
                                <th> СПЕЦИФИКАЦИИ </th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="paymentsLoading" class="c-spinner_wrapper c-empty-table">
                            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                        </div>
                        <div 
                            v-else-if="paymentsList.length == 0 | paymentsList == null"
                            class="c-empty-table"
                        >
                            <img src="@/assets/images/box.png">
                            <p>
                                Нет платежей
                            </p>
                        </div>
                        <table v-else>
                            <tbody>
                                <tr v-for="item in paymentsList" :key="item?.id">
                                    <td width="140px"><p>{{ item?.date | dateFilterWithoutTime }}</p></td>
                                    <td><p>{{ item?.amount | moneyFilter}}</p></td>
                                    <td width="140px"><p>{{ item?.type | paymentsTypeFilter }}</p></td>
                                    <td><p>{{ item.partner?.name }}</p></td>
                                    <td width="300px"><p>{{ item?.description }}</p></td>
                                    <td><p> {{item?.company_bank_account.bank.name}}</p></td>
                                    <td v-if="item.specification != ''">
                                        <p>{{ item?.specification.name }}</p>
                                    </td>
                                    <td v-else>
                                        <span @click="showSpecAdd(item)" class="c-link-label">
                                            <base-icon iconProps="link" sizeProps="md" />
                                            Прикрепить спецификацию
                                        </span>
                                    </td>
                                    <td>
                                        <router-link
                                            class="c-label-info"
                                            :to="`/crm/buxgalteria/payments/info/${item.id}`" 
                                        >
                                            <base-icon iconProps="info" sizeProps="md" />
                                            Информация
                                        </router-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="c-default-table-mobile"> 
                    <div class="c-default-table-mobile-col" v-for="item in paymentsList" :key="item.id">
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">ДАТА</b-col>
                            <b-col class="text"> {{ item?.date | dateFilterWithoutTime }} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">СУММА</b-col>
                            <b-col class="text"> {{ item?.amount | moneyFilter }} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">ТИП</b-col>
                            <b-col class="text"> {{ item?.type | paymentsTypeFilter }} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">ПАРТНЕР</b-col>
                            <b-col class="text"> {{ item.partner?.name }} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">ОПИСАНИЕ</b-col>
                            <b-col class="text"> {{ item?.description }} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">КОМПАНИЯ</b-col>

                            <b-col class="text"> {{ item?.company.name }} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">СПЕЦИФИКАЦИИ</b-col>
                            <b-col class="text" v-if="item.specification != ''"> 
                                {{ item?.specification.name }}
                            </b-col>
                            <b-col v-else class="text">
                                <span @click="showSpecAdd(item)" class="c-link-label">
                                    <div>
                                        <base-icon iconProps="link" sizeProps="md" />
                                    </div>
                                    Прикрепить спецификацию
                                </span>
                            </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title"></b-col>
                            <b-col class="text">       
                                <router-link
                                    class="c-label-info"
                                    :to="`/crm/buxgalteria/payments/info/${item.id}`" 
                                >
                                    <base-icon iconProps="info" sizeProps="md" />
                                    Информация
                                </router-link> 
                            </b-col>
                        </b-row>
                    </div>
                    <div v-if="paymentsLoading" class="c-spinner_wrapper c-empty-table">
                        <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                    </div>
                    <div 
                        v-else-if="paymentsList.length == 0 | paymentsList == null"
                        class="c-empty-table"
                    >
                        <img src="@/assets/images/box.png">
                        <p>
                            Нет платежей
                        </p>
                    </div>
                </div>
                <pagginate-table
                    :optionsProps="paymentsOptions"
                    :pagesOptionsProps="paymentsPagesOptions"
                    :setListActionsNameProps="'paymentsSetActions'"
                    :setOptionsActionsNameProps="'paymentsSetOptionsActions'"
                />
            </div>
        </div>
        <FinancePaymentsModalNew
            :partnersListProps="directoryPartnersList"
            :specificationsListProps="specificationAllList"
        />
        <FinancePaymentsModalInfo
            :partnersListProps="directoryPartnersList"
            :paymentsProps="selectPayment"
        />
        <FinancePaymentsModalAddSpec 
            :specificationsListProps="specificationAllList"
            :paymentsProps="selectPayment"
        />
    </div>
</template>

<script>
    import Vue from 'vue'
    import { mapGetters, mapActions } from "vuex";
    import { instance } from '@/services/instances.service';

    import BaseIcon from "@/components/elements/BaseIcon"
    import FilterDate from '@/components/elements/filters/FilterDate'
    import FilterPayment from '@/components/elements/filters/FilterPayment'
    import PagginateTable from '@/components/elements/PagginateTable'
    import FinancePaymentsModalNew from '@/components/finance/FinancePaymentsModalNew'
    import FinancePaymentsModalInfo from '@/components/finance/FinancePaymentsModalInfo'
    import FinancePaymentsModalAddSpec from '@/components/finance/FinancePaymentsModalAddSpec'

    export default {
        name: 'Payments',
        data(){
            return {
                valSearch: '',
                sort: {
                    date: {
                        asc: false,
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
                selectPayment: null,
                breadcrumb: [
                    { text: 'Платежи', href: '/crm/buxgalteria/payments' },
                ],
            }
        },
        components: {
            BaseIcon,
            FilterDate,
            FilterPayment,
            PagginateTable,
            FinancePaymentsModalNew,
            FinancePaymentsModalInfo,
            FinancePaymentsModalAddSpec,
        },
        computed: {
            ...mapGetters({
                paymentsList: 'paymentsListGetter',
                paymentsOptions: 'paymentsOptionsGetter',
                paymentsPagesOptions: 'paymentsPagesOptionsGetter',
                paymentsLoading: 'paymentsLoadingGetter',
                directoryPartnersList: 'directoryPartnersListGetter',
                specificationAllList: 'specificationAllListGetter',
                paymentsFilter: 'paymentsFilterGetter',
            }),
        },
        methods: {
            ...mapActions({ 
                paymentsSet: 'paymentsSetActions',
                paymentsSetOptions: 'paymentsSetOptionsActions',
                directoryPartnersGetList: 'directoryPartnersGetListActions',
                specificationSetAllList: 'specificationSetAllListActions',
            }),
            showInfo(item){
                this.selectPayment = item
                this.$bvModal.show('finance-payments-modal-info')
            },
            showSpecAdd(item){
                this.selectPayment = item
                this.$bvModal.show('finance-payments-modal-add-spec')
            },
            async sortPayments(field){
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.paymentsOptions
                console.log(options)
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

                this.paymentsSetOptions(options)
                await this.paymentsSet()
            },
            async importInvoiceFile(){
                
                this.file = this.$refs.file?.files[0];
                let formData = new FormData();

                formData.append('file', this.file);
                formData.append('type', '1c');

                instance
                    .post(`/api/v1/crm/payments/invoice/import/`, formData, {'Content-Type': 'multipart/form-data'})
                    .then( () => {
                        Vue.notify({
                            group: 'success',
                            title: 'Успех',
                            type: 'success',
                            text: 'Выгрузка завершена'
                        })
                        this.paymentsSet()
                    })
                    .catch(function (err) {
                        Vue.notify({
                            group: 'error',
                            title: 'Ошибка',
                            type: 'error',
                            text: err.message
                        })
                    })

            }
        },
        async mounted(){
            var params = {
                parent: ''
            }

            await this.paymentsSet()
            await this.directoryPartnersGetList( params )
            await this.specificationSetAllList()

            this.filter = this.paymentsFilter
        }
    }
</script>

