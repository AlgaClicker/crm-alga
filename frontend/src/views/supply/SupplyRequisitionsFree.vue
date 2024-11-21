<template>
    <div class="default-wrapper">
        <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
        <div class="scroll-wrapper">
            <div class="c-default-table-container">
                <div class="c-default-table-container__header">
                    <filter-requisition
                        :setFilterActionsNameProps="'supplyFreeRequisitionSetFilterActions'"
                        :setListActionsNameProps="'supplyFreeRequisitionSetListActions'"
                        :mastersListProps="accountsCompanyListByRoleOptions('master')"
                        :supllyListProps="[]"
                        :isStatusProps="false"
                        :specificationOptionsProps="specificationAllListNameOptions"
                    />
                    <filter-date 
                        :setListActionsNameProps="'supplyFreeRequisitionSetListActions'"
                        :setDateFilterActionsNameProps="'supplyFreeRequisitionSetDateFilterActions'"
                    />
                </div>  
                <div class="c-default-table">
                   <table>
                       <thead>
                           <tr>
                               <th width="170" class="th-sort" @click="sortRequisitions('status')" > 
                                    Статус
                                    <b-icon v-show="sort.status.asc" icon="chevron-down" font-scale="0.9" ></b-icon>
                                    <b-icon v-show="sort.status.desc" icon="chevron-up" font-scale="0.9" ></b-icon>
                               </th>
                               <th width="170"> 
                                   НОМЕР ЗАЯВКИ
                               </th>
                               <th class="th-sort" @click="sortRequisitions('specification')" > 
                                    СПЕЦИФИКАЦИЯ
                                    <b-icon v-show="sort.specification.asc" icon="chevron-down" font-scale="0.9" ></b-icon>
                                    <b-icon v-show="sort.specification.desc" icon="chevron-up" font-scale="0.9" ></b-icon>
                               </th>
                               <th class="th-sort" @click="sortRequisitions('autor')">  
                                    МАСТЕР
                                    <b-icon v-show="sort.autor.asc" icon="chevron-down" font-scale="0.9" ></b-icon>
                                    <b-icon v-show="sort.autor.desc" icon="chevron-up" font-scale="0.9" ></b-icon>
                               </th>
                               <th width="300" class="th-date"> 
                                   СООБЩЕНИЕ
                               </th>
                               <th width="200" class="th-sort" @click="sortRequisitions('createdAt')"> 
                                    СОЗДАННО
                                    <b-icon v-show="sort.createdAt.asc" icon="chevron-down" font-scale="0.9" ></b-icon>
                                    <b-icon v-show="sort.createdAt.desc" icon="chevron-up" font-scale="0.9" ></b-icon>
                               </th>
                               <th></th>
                           </tr>
                       </thead>
                   </table>
                  <div class="c-default-table__body">
                    <div v-if="supplyFreeRequisitionLoading" class="c-spinner_wrapper c-empty-table">
                      <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                    </div>
                    <div
                        v-else-if="supplyFreeRequisitionList.length == 0 | supplyFreeRequisitionList == null"
                        class="c-empty-table"
                    >
                      <img src="@/assets/images/box.png">
                      <p>
                        Нет заявок
                      </p>
                    </div>
                       <table v-else>
                           <tbody>
                               <tr v-for="item in supplyFreeRequisitionList" :key="item?.id">
                                    <td width="170">
                                        <status-requisition
                                            :statusProps="item.status"
                                        />
                                    </td>
                                    <td width="170">
                                        <router-link    
                                            :to="`/crm/supply/requisition/free/info/${item.id}/material`"
                                        >
                                            {{ item.number }}
                                        </router-link>
                                    </td>
                                    <td v-if="item.specification">
                                        <router-link    
                                            :to="`/crm/supply/specification/info/${item.specification?.id}`"
                                        >
                                            {{ item.specification?.name }}
                                        </router-link> ( {{ item.specification?.objectName }} )
                                    </td>
                                    <td v-else class="center"></td>

                                    <td>
                                        <author-title 
                                            :authorProps="item.autor"
                                        />
                                    </td>
                                    <td width="300">
                                        <p class="td-text"> 
                                            {{ item.description }} 
                                        </p>
                                    </td>
                                    <td width="200">
                                        {{ item.created_at | dateFilter }}
                                    </td>
                                    <td>
                                        <button @click="pickUp(item.id)" class="c-button-add">
                                            Забрать заявку
                                        </button>
                                    </td>
                               </tr>
                           </tbody>
                       </table>
                   </div>
                </div>
                <div class="c-default-table-mobile">
                   <div class="c-default-table-mobile-col" v-for="item in supplyFreeRequisitionList" :key="item.id">
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Статус</b-col>
                            <b-col class="text">  
                                <status-requisition
                                    :statusProps="item.status"
                                /> 
                            </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                           <b-col class="title">Номер заявки</b-col>
                           <b-col class="text"> 
                               <router-link    
                                   :to="`/crm/supply/requisition/free/info/${item.id}/material`"
                               >
                                   {{ item.number }}
                               </router-link>
                           </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Спецификация</b-col>
                            <b-col class="text"> {{ item.specification?.name}} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Мастер</b-col>
                            <b-col class="text"> 
                                <author-title 
                                    :authorProps="item.autor"
                                />
                            </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Тема</b-col>
                            <b-col class="text"> {{ item.description }} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Обнавлено</b-col>
                            <b-col class="text"> {{ item.created_at | dateFilter  }} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title"></b-col>
                            <b-col class="text"> 
                                <button @click="pickUp(item.id)" class="c-button-add">
                                    Забрать заявку
                                </button>    
                            </b-col>
                        </b-row>
                    </div>
                </div>
                <pagginate-table
                    :optionsProps="supplyFreeRequisitionOptions"
                    :pagesOptionsProps="supplyFreeRequisitionPagesOptions"
                    :setListActionsNameProps="'supplyFreeRequisitionSetListActions'"
                    :setOptionsActionsNameProps="'supplyFreeRequisitionSetOptionsActions'"
                />
           </div>
       </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    
    import AuthorTitle from '@/components/elements/AuthorTitle'
    import FilterDate from '@/components/elements/filters/FilterDate'
    import PagginateTable from '@/components/elements/PagginateTable'
    import StatusRequisition from '@/components/requisition/StatusRequisition'
    import FilterRequisition from '@/components/elements/filters/FilterRequisition'
    
    export default {
        name: "SupplyRequisitionsFree",
        data(){
            return {
                breadcrumb: [
                    { text: 'Заявки', href: '/crm/supply/requisition/free' },
                ],
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
                    specification: {
                       asc: false,
                       desc: false,
                    }
                },
            }
        },
        components: {
            FilterDate,
            AuthorTitle,
            PagginateTable,
            FilterRequisition,
            StatusRequisition,
        },
        computed: {
           ...mapGetters({
               supplyFreeRequisitionList: 'supplyFreeRequisitionListGetter',
               supplyFreeRequisitionLoading: 'supplyFreeRequisitionLoadingGetter',
               supplyFreeRequisitionOptions: 'supplyFreeRequisitionOptionsGetter',
               specificationAllListNameOptions: 'specificationAllListNameOptionsGetter',
               accountsCompanyListByRoleOptions: 'accountsCompanyListByRoleOptionsGetter',
               supplyFreeRequisitionPagesOptions: 'supplyFreeRequisitionPagesOptionsGetter',
           })
       },
       methods: {
            ...mapActions({
                accountsCompanySet: 'accountsCompanySetActions',
                specificationSetAllList: 'specificationSetAllListActions',
                supplyFreeRequisitionPickUp: 'supplyFreeRequisitionPickUpActions',
                supplyFreeRequisitionSetList: 'supplyFreeRequisitionSetListActions',
                supplyFreeRequisitionSetFilter: 'supplyFreeRequisitionSetFilterActions',
                supplyFreeRequisitionSetOptions: "supplyFreeRequisitionSetOptionsActions",
                supplyFreeRequisitionSetDateFilter: 'supplyFreeRequisitionSetDateFilterActions'
            }),
            async pickUp(id){
                await this.supplyFreeRequisitionPickUp(id)
            },
            async sortRequisitions(field){
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.supplyFreeRequisitionOptions

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

                this.supplyFreeRequisitionSetOptions(options)
                await this.supplyFreeRequisitionSetList()
            }, 
       },
       async mounted(){
           await this.supplyFreeRequisitionSetList()
           await this.accountsCompanySet()
           await this.specificationSetAllList()
       }   
   }
</script>