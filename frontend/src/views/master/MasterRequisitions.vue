<template>
    <div class='default-wrapper'>
        <div class="default-wrapper__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
            <div class="left-container">
                <button v-b-modal.master-requisition-modal-universal class="c-button-add mr-1">
                    <b-icon icon="plus-lg" scale="1"></b-icon>
                    <span> 
                        Универсальную заявку
                    </span>
                </button>
                <button v-b-modal.master-requisition-modal-new-for-specifications class="c-button-add">
                    <b-icon icon="plus-lg" scale="1"></b-icon>
                    <span> 
                        Создать по спецификации
                    </span>
                </button>
            </div>
        </div>  
        <div class="scroll-wrapper">
            <div class="c-default-table-container">
                <div class="c-default-table-container__header">
                    <filter-requisition
                        :setFilterActionsNameProps="'masterRequisitionSetFilterActions'"
                        :setListActionsNameProps="'masterRequisitionSetListActions'"
                        :mastersListProps="[]"
                        :supllyListProps="accountsCompanyListByRoleOptions('snabzenie')"
                        :specificationOptionsProps="specificationAllListNameOptions"
                    />
                    <filter-date 
                        :setListActionsNameProps="'masterRequisitionSetListActions'"
                        :setDateFilterActionsNameProps="'masterRequisitionSetDateFilterActions'"
                    />
                </div> 
                <div class="c-default-table">
                    <table>
                        <thead>
                            <tr>
                                <th class="th-sort" @click="sortRequisitions('status')"> 
                                    Статус
                                    <b-icon v-show="sort.status.asc" icon="chevron-down" font-scale="0.8" ></b-icon>
                                    <b-icon v-show="sort.status.desc" icon="chevron-up" font-scale="0.8" ></b-icon>
                                </th>
                                <th> 
                                    Номер заявки
                                </th>
                                <th class="th-sort" @click="sortRequisitions('specification')"> 
                                    Спецификация
                                    <b-icon v-show="sort.specification.asc" icon="chevron-down" font-scale="0.8" ></b-icon>
                                    <b-icon v-show="sort.specification.desc" icon="chevron-up" font-scale="0.8" ></b-icon>
                                </th>
                                <th class="th-date"> 
                                    Менеджер
                                </th>
                                <th class="th-date"> 
                                    Тема
                                </th>
                                <th class="th-sort" @click="sortRequisitions('createdAt')"> 
                                    Создано
                                    <b-icon v-show="sort.createdAt.asc" icon="chevron-down" font-scale="0.8" ></b-icon>
                                    <b-icon v-show="sort.createdAt.desc" icon="chevron-up" font-scale="0.8" ></b-icon>
                                </th>
                                <th width="180px"></th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="masterRequisitionLoading" class="c-spinner_wrapper c-empty-table">
                            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                        </div>
                        <div 
                            v-else-if="masterRequisitionList.length == 0 | masterRequisitionList == null"
                            class="c-empty-table"
                        >
                            <img src="@/assets/images/box.png">
                            <p>
                                Нет заявок
                            </p>
                        </div>
                        <table v-else>
                            <tbody>
                                <tr v-for="item in masterRequisitionList" :key="item?.id">
                                    <td> 
                                        <status-requisition
                                            :statusProps="item.status"
                                        />
                                    </td>
                                    <td>
                                        <div @click="selectedRequisition(item)" class="c-link-label" v-if="item.status == 'draft' || item.status == 'new'">
                                            {{ item.number }}
                                        </div>
                                        <router-link 
                                            v-else   
                                            :to="`/crm/master/requisition/info/${item.id}/detail`"
                                        >
                                            {{ item.number }}
                                        </router-link>
                                    </td>
                                    <td>
                                        <div v-if="item.type == 'other'">
                                            Универсальная заявка
                                        </div>
                                        <div v-else>
                                            <router-link    
                                            :to="`/crm/master/specification/${item.specification?.id}`"
                                        >
                                            {{ item.specification?.name}}
                                        </router-link>
                                        ( {{ item.specification.objectName }} )
                                        </div> 
                                    </td>
                                    <td>
                                        <author-title
                                            v-if="typeof item.manager != 'undefined'" 
                                            :authorProps="item.manager"
                                        />
                                    </td>
                                    <td>
                                        <p class="td-text"> {{ item.description }} </p>
                                    </td>
                                    <td>
                                        {{ item.created_at | dateFilter }}
                                    </td>
                                    <td width="180px">

                                        <div 
                                            @click="setConfirm(item)" 
                                            v-b-modal.confirmation-modal 
                                            v-if="item.status == 'draft'" 
                                            class="c-link-label"
                                        >
                                            Удалить черновик
                                        </div>
                                        <div 
                                            @click="setConfirm(item)"
                                            v-b-modal.confirmation-modal 
                                            v-if="item.status == 'new'"
                                            class="c-link-label"
                                        >
                                            Отменить заявку
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="c-default-table-mobile"> 
                    <div class="c-default-table-mobile-col" v-for="item in masterRequisitionList" :key="item.id">
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Статус</b-col>
                            <b-col class="text">

                                <status-requisition
                                    :statusProps="item?.status"
                                /> 
                            </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Номер заявки</b-col>
                            <b-col class="text"> 
                                <div @click="selectedRequisition(item)" class="c-link-label" v-if="item.status == 'draft' || item.status == 'new'">
                                    {{ item.number }}
                                </div>
                                <router-link 
                                    v-else   
                                    :to="`/crm/master/requisition/info/${item.id}/detail`"
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
                            <b-col class="title">Менеджер</b-col>
                            <b-col class="text">                                 
                                 <author-title 
                                    v-if="typeof item.manager != 'undefined'" 
                                    :authorProps="item.manager" 
                                 />
                            </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Тема</b-col>
                            <b-col class="text"> {{ item.description }} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title">Создано</b-col>
                            <b-col class="text"> {{ item.created_at | dateFilter  }} </b-col>
                        </b-row>
                        <b-row class="c-default-table-mobile-col__row">
                            <b-col class="title"></b-col>
                            <b-col class="text"> 
                                <div 
                                    @click="setConfirm(item)" 
                                    v-b-modal.confirmation-modal 
                                    v-if="item?.status == 'draft'" 
                                    class="c-link-label"
                                >
                                    Удалить черновик
                                </div>
                                <div 
                                    @click="setConfirm(item)"
                                    v-b-modal.confirmation-modal 
                                    v-if="item?.status == 'new'"
                                    class="c-link-label"
                                >
                                    Отменить заявку
                                </div>
                            </b-col>
                        </b-row>
                    </div>
                    <div v-if="masterRequisitionLoading" class="c-spinner_wrapper c-empty-table">
                        <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                    </div>
                    <div 
                        v-else-if="masterRequisitionList.length == 0 | masterRequisitionList == null"
                        class="c-empty-table"
                    >
                        <img src="@/assets/images/box.png">
                        <p>
                            Нет заявок
                        </p>
                    </div>
                </div>
                <pagginate-table
                    :optionsProps="masterRequisitionOptions"
                    :pagesOptionsProps="masterRequisitionPagesOptions"
                    :setListActionsNameProps="'masterRequisitionSetListActions'"
                    :setOptionsActionsNameProps="'masterRequisitionSetOptionsActions'"
                />
            </div>
        </div>
        <master-requisition-modal-universal
            :specificationsList="masterSpecificationList"
        />
        <master-requisition-modal-edit-for-specifications-draft
            :requisitionProps="selectRequisition"
        />
        <master-requisition-modal-universal-edit-draft
            :requisitionProps="selectRequisition"
        />
        <master-requisitions-modal-info
            :requisitionProps="selectRequisition"
        />
        <master-requisition-modal-new-for-specifications />
        <confirmation-modal 
            :textProps="textConfirm"
            :colorProps="colorConfirm"
            @confirmationEmit="confirm()"
        />
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    import AuthorTitle from '@/components/elements/AuthorTitle'
    import FilterDate from '@/components/elements/filters/FilterDate'
    import PagginateTable from '@/components/elements/PagginateTable'
    import StatusRequisition from '@/components/requisition/StatusRequisition'
    import ConfirmationModal from '@/components/elements/ConfirmationModal'
    import FilterRequisition from '@/components/elements/filters/FilterRequisition'
    import MasterRequisitionsModalInfo from '@/components/requisition/MasterRequisitionsModalInfo'
    import MasterRequisitionModalUniversal from '@/components/requisition/MasterRequisitionModalUniversal'
    import MasterRequisitionModalUniversalEditDraft from '@/components/requisition/MasterRequisitionModalUniversalEditDraft'
    import MasterRequisitionModalNewForSpecifications from '@/components/requisition/MasterRequisitionModalNewForSpecifications'
    import MasterRequisitionModalEditForSpecificationsDraft from '@/components/requisition/MasterRequisitionModalEditForSpecificationsDraft'

    export default {
        name: 'MasterRequisitions',
        data(){
            return {
                breadcrumb: [
                    { text: 'Заявки', href: '/crm/master/applications' },
                ],
                sort: {
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
                selectRequisition: {},
                textConfirm: '',
                colorConfirm: '',
                selectItem: ''
            }
        },
        components: {
            FilterDate,
            AuthorTitle,
            PagginateTable,
            StatusRequisition,
            ConfirmationModal,
            FilterRequisition,
            MasterRequisitionsModalInfo,
            MasterRequisitionModalUniversal,
            MasterRequisitionModalUniversalEditDraft,
            MasterRequisitionModalNewForSpecifications,
            MasterRequisitionModalEditForSpecificationsDraft,
        },
        computed: {
            ...mapGetters({
                masterRequisitionList: 'masterRequisitionListGetter',
                masterRequisitionLoading: "masterRequisitionLoadingGetter",
                masterRequisitionOptions: "masterRequisitionOptionsGetter",
                masterSpecificationList: 'masterSpecificationListGetter',
                masterRequisitionFilter: 'masterRequisitionFilterGetter',
                masterRequisitionPagesOptions: "masterRequisitionPagesOptionsGetter",
                specificationAllListNameOptions: 'specificationAllListNameOptionsGetter',
                accountsCompanyListByRoleOptions: 'accountsCompanyListByRoleOptionsGetter',
            })
        },
        methods: {
            ...mapActions({
                accountsCompanySet: 'accountsCompanySetActions',
                specificationSetAllList: 'specificationSetAllListActions',
                masterRequisitionSetList: 'masterRequisitionSetListActions',
                masterRequisitionDelete: 'masterRequisitionDeleteActions',
                masterRequisitionUnFixed: 'masterRequisitionUnFixedActions',
                masterSpecificationSetList: 'masterSpecificationSetListActions',
                masterRequisitionSetOptions: 'masterRequisitionSetOptionsActions',
                masterRequisitionSetFilter: 'masterRequisitionSetFilterActions',
                masterRequisitionSetDateFilter: 'masterRequisitionSetDateFilterActions'
            }),
            selectedRequisition(requisition){
                this.selectRequisition = requisition
                
                if(requisition.type == "other" & requisition.status == "draft"){
                    this.$bvModal.show('master-requisition-modal-universal-edit-draft')
                }else if(requisition.status == "new"){
                    this.$bvModal.show('master-requisitions-modal-info')
                }else{
                    this.$bvModal.show('master-requisition-modal-edit-for-specifications-draft')
                }
                
            },
            async confirm(){
                if(this.selectItem.fixed){
                    await this.masterRequisitionUnFixed(this.selectItem.id)
                } else {
                    await this.masterRequisitionDelete(this.selectItem.id)
                }
                await this.masterRequisitionSetList()
            },
            setConfirm(item){
                this.selectItem = item
      
                if( item.status == 'draft' ){
                    this.textConfirm = 'Удалить черновик ?'
                    this.colorConfirm = 'danger'
                }

                if( item.status == 'new' ){
                    this.textConfirm = 'Отменить заявку ?'
                    this.colorConfirm = 'primary'
                }
            },
            async sortRequisitions(field){
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.masterRequisitionOptions

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
                    console.log(key)
                    if(key != field){
                        this.sort[key].asc = false
                        this.sort[key].desc = false
                    }
                }

                this.masterRequisitionSetOptions(options)
                await this.masterRequisitionSetList()
            },
        },
        async mounted(){
            await this.masterRequisitionSetList()
            await this.masterSpecificationSetList()
            await this.specificationSetAllList()

            this.accountsCompanySet()
            
        }
    }
</script>