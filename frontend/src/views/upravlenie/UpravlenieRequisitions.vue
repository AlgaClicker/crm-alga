<template>
    <div class="default-wrapper">
        <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
        <div class="scroll-wrapper">
            <div class="c-default-table-container">
                <div class="c-default-table-container__header">
                    <filter-requisition
                        :setFilterActionsNameProps="'upravlenieRequisitionsSetFilterActions'"
                        :setListActionsNameProps="'upravlenieRequisitionsSetListActions'"
                        :mastersListProps="accountsCompanyListByRoleOptions('master')"
                        :supllyListProps="accountsCompanyListByRoleOptions('snabzenie')"
                        :specificationOptionsProps="specificationAllListNameOptions"
                    />
                    <filter-date 
                        :setListActionsNameProps="'upravlenieRequisitionsSetListActions'"
                        :setDateFilterActionsNameProps="'upravlenieRequisitionsSetDateFilterActions'"
                    />
                </div>
                <div class="c-default-table">
                    <table>
                        <thead>
                            <tr>
                                <th class="th-sort" @click="sortRequisitions('status')"> 
                                    СТАТУС
                                    <b-icon v-show="sort.status.asc" icon="chevron-down" font-scale="0.9" ></b-icon>
                                    <b-icon v-show="sort.status.desc" icon="chevron-up" font-scale="0.9" ></b-icon>
                                </th>
                                <th> 
                                    НОМЕР ЗАЯВКИ
                                </th>
                                <th class="th-sort" @click="sortRequisitions('specification')"> 
                                    СПЕЦИФИКАЦИИ
                                    <b-icon v-show="sort.specification.asc" icon="chevron-down" font-scale="0.9" ></b-icon>
                                    <b-icon v-show="sort.specification.desc" icon="chevron-up" font-scale="0.9" ></b-icon>
                                </th>
                                <th class="th-sort" @click="sortRequisitions('autor')"> 
                                    МАСТЕР
                                    <b-icon v-show="sort.autor.asc" icon="chevron-down" font-scale="0.9" ></b-icon>
                                    <b-icon v-show="sort.autor.desc" icon="chevron-up" font-scale="0.9" ></b-icon>
                                </th>
                                <th class="th-sort" @click="sortRequisitions('manager')"> 
                                    МЕНЕДЖЕР
                                    <b-icon v-show="sort.manager.asc" icon="chevron-down" font-scale="0.9" ></b-icon>
                                    <b-icon v-show="sort.manager.desc" icon="chevron-up" font-scale="0.9" ></b-icon>
                                </th>
                                <th class="th-date">
                                    СООБЩЕНИЕ
                                </th>
                                <th class="th-sort" @click="sortRequisitions('createdAt')"> 
                                    СОЗДАНО
                                    <b-icon v-show="sort.createdAt.asc" icon="chevron-down" font-scale="0.9" ></b-icon>
                                    <b-icon v-show="sort.createdAt.desc" icon="chevron-up" font-scale="0.9" ></b-icon>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="upravlenieRequisitionsListLoading" class="c-spinner_wrapper c-empty-table">
                            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                        </div>
                        <div 
                            v-else-if="upravlenieRequisitionsList.length == 0 | upravlenieRequisitionsList == null"
                            class="c-empty-table"
                        >
                            <img src="@/assets/images/box.png">
                            <p>
                                Нет заявок
                            </p>
                        </div>
                        <table v-else>
                            <tbody>
                                <tr v-for="item in upravlenieRequisitionsList" :key="item?.id">
                                    <td>
                                        <status-requisition
                                            :statusProps="item.status"
                                        />
                                    </td>
                                    <td>
                                    <router-link    
                                        :to="`/crm/upravlenie/requisition/info/${item.id}/material`"
                                    >
                                        {{ item.number }}
                                    </router-link>
                                    </td>
                                    <td>
                                        <router-link    
                                            :to="`/crm/upravlenie/specification/${item.specification?.id}`"
                                        >
                                            {{ item.specification?.name }}
                                        </router-link> ( {{ item.specification?.objectName }} )
                                    </td>
                                    <td>
                                        <author-title 
                                            :authorProps="item?.autor"
                                        />
                                    </td>
                                    <td>
                                        {{ item.manager?.username }}
                                        <template v-if="item.status != 'canceled' && item.status != 'completed'">
                                            <div @click="selectRequisition(item)" class="c-label-info"> Назначить </div>
                                        </template>
                                    </td>
                                    <td>
                                        <p class="td-text">{{ item.description }}</p> 
                                    </td>
                                    <td>
                                        {{ item.created_at | dateFilter}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="c-default-table-mobile"> 
                        <div class="c-default-table-mobile-col" v-for="item in upravlenieRequisitionsList" :key="item.id">
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Статус</b-col>
                            <b-col class="text">  
                                <status-requisition
                                    :statusProps="item?.status"
                                /> 
                            </b-col>
                        </b-row>
                    </div>
                    <div v-if="upravlenieRequisitionsListLoading" class="c-spinner_wrapper c-empty-table">
                        <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                    </div>
                    <div 
                        v-else-if="upravlenieRequisitionsList.length == 0 | upravlenieRequisitionsList == null"
                        class="c-empty-table"
                    >
                        <img src="@/assets/images/box.png">
                        <p>
                            Нет заявок
                        </p>
                    </div>
                    </div>
                </div>
                <pagginate-table
                    :optionsProps="upravlenieRequisitionsOptions"
                    :pagesOptionsProps="upravlenieRequisitionsPagesOptions"
                    :setListActionsNameProps="'upravlenieRequisitionsSetListActions'"
                    :setOptionsActionsNameProps="'upravlenieRequisitionsSetOptionsActions'"
                />
            </div>
        </div>
        <upravleine-requisition-set-manage 
            :requisitionProps="selectedRequisition"
            :manageListProps="accountsCompanyListByRoleOptions('snabzenie')"
        />
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    
    import AuthorTitle from '@/components/elements/AuthorTitle'
    import FilterDate from '@/components/elements/filters/FilterDate'
    import PagginateTable from '@/components/elements/PagginateTable'
    import StatusRequisition from '@/components/requisition/StatusRequisition'
    import FilterRequisition from '@/components/elements/filters/FilterRequisition'
    import UpravleineRequisitionSetManage from '@/components/requisition/UpravleineRequisitionSetManage'
    
    export default {
        name: "UpravlenieRequisitions",
        data(){
            return {
                breadcrumb: [
                    { text: 'Заявки', to: '/crm/upravlenie/requisitions/my' },
                ],
                selectedRequisition: {},
                sort: {
                    autor: {
                        asc: false,
                        desc: false,
                    },
                    createdAt: {
                        asc: false,
                        desc: true,
                    },
                    status: {
                        asc: false,
                        desc: false,
                    },
                    manager: {
                        asc: false,
                        desc: false,
                    },
                    specification: {
                        asc: false,
                        desc: false,
                    }
                },
            }
        },
        components: {
            FilterDate,
            AuthorTitle, //
            PagginateTable,
            StatusRequisition, //
            FilterRequisition,
            UpravleineRequisitionSetManage,
        },
        computed: {
            ...mapGetters({
                upravlenieRequisitionsList: 'upravlenieRequisitionsListGetter',
                upravlenieRequisitionsOptions: 'upravlenieRequisitionsOptionsGetter',
                specificationAllListNameOptions: 'specificationAllListNameOptionsGetter',
                accountsCompanyListByRoleOptions: 'accountsCompanyListByRoleOptionsGetter',
                upravlenieRequisitionsListLoading: 'upravlenieRequisitionsListLoadingGetter',
                upravlenieRequisitionsPagesOptions: 'upravlenieRequisitionsPagesOptionsGetter',
            })
        },
        methods: {
            ...mapActions({
                accountsCompanySet: 'accountsCompanySetActions',
                specificationSetAllList: 'specificationSetAllListActions',
                upravlenieRequisitionsSetList: 'upravlenieRequisitionsSetListActions',
                upravlenieRequisitionsSetOptions: 'upravlenieRequisitionsSetOptionsActions',
            }),
            selectRequisition(req){
                this.selectedRequisition = req
                this.$bvModal.show('upravleine-requisition-set-manage')
            },
            async sortRequisitions(field){
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.upravlenieRequisitionsOptions

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

                this.upravlenieRequisitionsSetOptions(options)
                await this.upravlenieRequisitionsSetList() 
            }, 
        },
        async mounted(){
            await this.upravlenieRequisitionsSetList()
            await this.accountsCompanySet()
            await this.specificationSetAllList()
        }
    }
</script>