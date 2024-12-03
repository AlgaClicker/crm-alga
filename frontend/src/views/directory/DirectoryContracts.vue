<template>
    <div class='default-wrapper'>
        <div class="default-wrapper__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
            <div class="left-container">
                <button v-b-modal.directory-contracts-supply-modal-add class="c-button-add mr-1">
                    <b-icon icon="plus-lg" scale="1"></b-icon>
                    <span> Договор поставки </span>
                </button>
                <button v-b-modal.directory-contracts-work-modal-add class="c-button-add">
                    <b-icon icon="plus-lg" scale="1"></b-icon>
                    <span> Договор подряда </span>
                </button>
            </div>
        </div>  
        <div class="scroll-wrapper">
            <div class="c-default-table-container">
                <div class="c-default-table-container__header">
                    <!-- <input-search 
                        :searchActionsNameProps="'directoryContractsSearchActions'"
                        :setListActionsNameProps="'directoryPartnersSetListActions'"
                    /> -->
                </div> 
                <div class="c-default-table">
                    <table>
                        <thead>
                            <tr>
                                <th> 
                                    НОМЕР 
                                </th>
                                <th>
                                    ТИП 
                                </th>
                                <th> 
                                    ЗАКАЗЧИК
                                </th>
                                <th class="th-sort" @click="sortContracts('amount')"> 
                                    СУММА
                                    <b-icon v-show="sort.amount.asc" icon="chevron-down" font-scale="1"></b-icon>
                                    <b-icon v-show="sort.amount.desc" icon="chevron-up" font-scale="1"></b-icon>
                                </th> 
                                <th class="th-sort" @click="sortContracts('createdAt')"> 
                                    ДАТА СОЗДАНИЯ 
                                    <b-icon v-show="sort.createdAt.asc" icon="chevron-down" font-scale="1"></b-icon>
                                    <b-icon v-show="sort.createdAt.desc" icon="chevron-up" font-scale="1"></b-icon>
                                </th>
                                <th class="th-sort" @click="sortContracts('startAt')"> 
                                    ДАТА НАЧАЛА 
                                    <b-icon v-show="sort.startAt.asc" icon="chevron-down" font-scale="1"></b-icon>
                                    <b-icon v-show="sort.startAt.desc" icon="chevron-up" font-scale="1"></b-icon>
                                </th>
                                <th class="th-sort" @click="sortContracts('endAt')"> 
                                    ДАТА ОКОНЧАНИЯ 
                                    <b-icon v-show="sort.endAt.asc" icon="chevron-down" font-scale="1"></b-icon>
                                    <b-icon v-show="sort.endAt.desc" icon="chevron-up" font-scale="1"></b-icon>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="directoryContractsLoading" class="c-spinner_wrapper c-empty-table">
                            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                        </div>
                        <div 
                            v-else-if="directoryContractsList.length == 0 | directoryContractsList == null"
                            class="c-empty-table"
                        >
                            <img src="@/assets/images/box.png">
                            <p>
                                Нет договоров
                            </p>
                        </div>
                        <table v-else>
                            <tbody>
                                <tr v-for="item in directoryContractsList" :key="item?.id">
                                    <td>
                                        <div class="c-contract-label" @click="selectContract(item)">
                                            {{ item.number_sys }} ( {{ item.number }} ) 
                                            <base-icon 
                                                v-show="item.fixed" 
                                                iconProps="loock" 
                                                sizeProps="md"
                                            />
                                        </div>
                                    </td>
                                    <td>
                                        <directory-type-contract
                                            :typeProps="item.type"
                                        />
                                    </td>
                                    <td>
                                        {{ item.partner?.name }}
                                    </td>
                                    <td>
                                        {{ item?.amount | moneyFilter }}
                                    </td>
                                    <td>
                                        {{ item.created_at | dateFilterWithoutTime }}
                                    </td>
                                    <td>
                                        {{ item.start_at | dateFilterWithoutTime }}
                                    </td>
                                    <td>
                                        {{ item.end_at | dateFilterWithoutTime }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="c-default-table-mobile"> 
                    <div class="c-default-table-mobile-col" v-for="item in directoryContractsList" :key="item.id">
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Номер договора</b-col>
                            <b-col class="text"> 
                                <div class="c-contract-label" @click="selectContract(item)">
                                    {{ item.number_sys }} ( {{ item.number }} ) 
                                    <base-icon  v-show="item.fixed" iconProps="loock" sizeProps="md"/>
                                </div>
                            </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Тип договора</b-col>
                            <b-col class="text">  
                                <directory-type-contract
                                    :typeProps="item.type"
                                /> 
                            </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Заказчик</b-col>
                            <b-col class="text"> {{ item.partner?.name }} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Сумма</b-col>
                            <b-col class="text"> {{ item?.amount | moneyFilter }} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Дата начала договора</b-col>
                            <b-col class="text"> {{ item.start_at | dateFilterWithoutTime }} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Дата окончания действия договора</b-col>
                            <b-col class="text"> {{ item.end_at | dateFilterWithoutTime }} </b-col>
                        </b-row>
                    </div>  
                </div>
                <pagginate-table
                    :optionsProps="directoryContractsOptions"
                    :pagesOptionsProps="directoryContractsPagesOptions"
                    :setListActionsNameProps="'directoryContractsSetListActions'"
                    :setOptionsActionsNameProps="'directoryContractsSetOptionsActions'"
                />
            </div>
        </div>
        <directory-contracts-work-modal-add
            :specificationsListProps="specificationAllList"
            :partnersListProps="directoryPartnersList"
        />
        <directory-contracts-work-modal-info 
            :specificationsListProps="specificationAllList"
            :partnersListProps="directoryPartnersList"
            :contractProps="selectedContract"
        />
        <directory-contracts-supply-modal-add 
            :specificationsListProps="specificationAllList"
            :partnersListProps="directoryPartnersList"
        />
        <directory-contracts-supply-modal-info 
            :specificationsListProps="specificationAllList"
            :partnersListProps="directoryPartnersList"
            :contractProps="selectedContract"
        />
    </div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex"

    import BaseIcon from "@/components/elements/BaseIcon"
    //import InputSearch from '@/components/elements/InputSearch'
    import PagginateTable from '@/components/elements/PagginateTable'
    import DirectoryTypeContract from '@/components/directory/DirectoryTypeContract'
    import DirectoryContractsWorkModalAdd from '@/components/directory/DirectoryContractsWorkModalAdd'
    import DirectoryContractsWorkModalInfo from '@/components/directory/DirectoryContractsWorkModalInfo'
    import DirectoryContractsSupplyModalAdd from '@/components/directory/DirectoryContractsSupplyModalAdd'
    import DirectoryContractsSupplyModalInfo from '@/components/directory/DirectoryContractsSupplyModalInfo'
    
    export default {
        name: "DirectoryContracts",
        data() {
            return {
                breadcrumb: [
                    { text: 'Договоры', href: '/crm/directory/contracts' },
                ],
                selectedContract: {},
                sort: {
                    amount: {
                        asc: false,
                        desc: false,
                    },
                    createdAt: {
                        asc: true,
                        desc: false,
                    },
                    startAt: {
                        asc: false,
                        desc: false,
                    },
                    endAt: {
                        asc: false,
                        desc: false,
                    },
                },
            }
        },
        components: {
            BaseIcon,
            //InputSearch,
            PagginateTable,
            DirectoryTypeContract,
            DirectoryContractsWorkModalAdd,
            DirectoryContractsWorkModalInfo,
            DirectoryContractsSupplyModalAdd,
            DirectoryContractsSupplyModalInfo,
        },
        computed: {
            ...mapGetters({
                profile: "profileGetter",
                specificationAllList: 'specificationAllListGetter',
                directoryPartnersList: 'directoryPartnersListGetter',
                directoryContractsList: 'directoryContractsListGetter',
                directoryContractsLoading: 'directoryContractsLoadingGetter',
                directoryContractsOptions: 'directoryContractsOptionsGetter',
                directoryContractsPagesOptions: 'directoryContractsPagesOptionsGetter',
            })
        },
        methods: {
            ...mapActions({
                specificationSetAllList: 'specificationSetAllListActions',
                directoryPartnersSetList: 'directoryPartnersSetListActions',
                directoryContractsSetList: 'directoryContractsSetListActions',
                directoryContractsSetOptions: 'directoryContractsSetOptionsActions',
                directoryContractsSupplyCreate: 'directoryContractsSupplyCreateActions'
            }),
            selectContract(contract){
                this.selectedContract = contract

                if(contract.type == 'supply'){
                    this.$bvModal.show('directory-contracts-supply-modal-info')
                }else{
                    this.$bvModal.show('directory-contracts-work-modal-info')
                }
            },
            async sortContracts(field){
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.directoryContractsOptions

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

                this.directoryContractsSetOptions(options)
                await this.directoryContractsSetList()
            }
        },
        async mounted() {
            await this.directoryContractsSetList()
            await this.specificationSetAllList()
            await this.directoryPartnersSetList()
        },
    }
</script>
