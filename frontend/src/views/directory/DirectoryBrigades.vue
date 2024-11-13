<template>
    <div class='default-wrapper'>
        <div class="default-wrapper__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb> 
            <div class="left-container">
                <button v-b-modal.directory-brigade-modal-add class="c-button-add">
                    <b-icon icon="plus-lg" scale="1"></b-icon>
                    <span>
                        Создать 
                    </span>
                </button>
            </div>
        </div> 
        <div class="scroll-wrapper">
            <div class="c-default-table-container">
                <div class="c-default-table-container__header"></div>
                <div class="c-default-table">
                    <table>
                        <thead>
                            <tr>
                                <th class="th-sort" @click="sortBrigade('name')">  
                                    <p>
                                        НАЗВАНИЕ
                                        <b-icon v-show="sort.name?.asc" icon="chevron-down" font-scale="1"></b-icon>
                                        <b-icon v-show="sort.name?.desc" icon="chevron-up" font-scale="1"></b-icon>
                                    </p>
                                </th>
                                <th class="th-sort" @click="sortBrigade('created_at')" >
                                    <p> 
                                        ДАТА СОЗДАНИЯ
                                        <b-icon v-show="sort.created_at?.asc" icon="chevron-down" font-scale="1"></b-icon>
                                        <b-icon v-show="sort.created_at?.desc" icon="chevron-up" font-scale="1"></b-icon>
                                    </p>
                                </th>
                                <th><p> МАСТЕР </p></th>
                                <th width="140"></th>
                                <th width="140"></th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="directoryBrigadesLoading" class="c-spinner_wrapper c-empty-table">
                            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                        </div>
                        <div 
                            v-else-if="directoryBrigadesList.length == 0 | directoryBrigadesList == null"
                            class="c-empty-table"
                        >
                            <img src="@/assets/images/box.png">
                            <p>
                                Нет бригад
                            </p>
                        </div>
                        <table v-else>
                            <tbody>
                                <tr v-for="item in directoryBrigadesList" :key="item.id" >
                                    <td>
                                        <router-link 
                                            active-class='link-wrapper__link--active'
                                            :to="`/crm/upravlenie/brigade/${item.id}`"  
                                        >  
                                            {{ item.name }}
                                        </router-link> 
                                    </td>
                                    <td><p>{{ item.created_at | dateFilter }}</p></td>
                                    <td>
                                        <p>{{ item.master.username}}</p>
                                    </td>
                                    <td width="140">
                                        <div @click="showInfo(item)"  class="c-label-info">
                                            <base-icon iconProps="info" sizeProps="md" />
                                            <div> Информация </div>
                                        </div>
                                    </td>
                                    <td width="140"> 
                                        <div @click="deleteBrigades(item)" v-b-modal.base-delete-modal class="c-label-delete">
                                            <base-icon iconProps="trash" sizeProps="md" />
                                            <div> Удалить </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>  
                <pagginate-table
                    :optionsProps="directoryBrigadesOptions"
                    :pagesOptionsProps="directoryBrigadesPagesOptions"
                    :setListActionsNameProps="'directoryBrigadesSetListActions'"
                    :setOptionsActionsNameProps="'directoryBrigadesSetOptionsActions'"
                />
            </div>  
        </div>
        <DirectoryBrigadesModalAdd
            :mastersListProps="accountsCompanyList.filter(item => item.roles.service == 'master')"
            :specificationAllListProps="specificationAllList"
        />
        <DirectoryBrigadesModalInfo 
            :mastersListProps="accountsCompanyList.filter(item => item.roles.service == 'master')"
            :specificationAllListProps="specificationAllList"
            :selectBrigadeProps="directoryBrigadesSelect"
        />
    </div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";

    import BaseIcon from "@/components/elements/BaseIcon"
    import PagginateTable from '@/components/elements/PagginateTable'
    import DirectoryBrigadesModalAdd from '@/components/directory/DirectoryBrigadesModalAdd'
    import DirectoryBrigadesModalInfo from '@/components/directory/DirectoryBrigadesModalInfo'

    export default {
        name: 'DirectoryBrigades',
        data(){
            return {
                valSearch: '',
                directoryBrigadesSelect: {},
                breadcrumb: [
                    { text: 'Бригады', href: '/crm/directory/brigades' },
                ],
                sort: {
                    name: {
                        asc: false,
                        desc: false,
                    },
                    created_at: {
                        asc: true,
                        desc: false,
                    },
                },  
            }
        },
        components: {
            BaseIcon,
            PagginateTable,
            DirectoryBrigadesModalAdd,
            DirectoryBrigadesModalInfo
        },
        computed: {
            ...mapGetters({
                accountsCompanyList: 'accountsCompanyListGetter',
                specificationAllList: 'specificationAllListGetter',
                directoryBrigadesList: 'directoryBrigadesListGetter',
                directoryEmployeesList: 'directoryEmployeesListGetter',
                directoryBrigadesLoading: 'directoryBrigadesLoadingGetter',
                directoryBrigadesOptions: 'directoryBrigadesOptionsGetter',
                directoryBrigadesPagesOptions: 'directoryBrigadesPagesOptionsGetter',
            })
        },
        methods: {
            ...mapActions({ 
                accountsCompanySet: 'accountsCompanySetActions',
                setParamsDeleteModal: 'setParamsDeleteModalActions',
                specificationSetAllList: 'specificationSetAllListActions',
                directoryBrigadesSetList: 'directoryBrigadesSetListActions',
                directoryBrigadesChecked: 'directoryBrigadesCheckedActions',
                directoryBrigadesShowInfo: 'directoryBrigadesShowInfoActions',
                directoryBrigadesSetOptions: 'directoryBrigadesSetOptionsActions',
                directoryBrigadesSetFreeEmployees: 'directoryBrigadesSetFreeEmployeesActions',
            }),
            showInfo(item){
                this.directoryBrigadesSelect = item
                this.$bvModal.show('directory-brigade-modal-info')
            },
            deleteBrigades(item){
                this.setParamsDeleteModal(
                    {
                        title: 'бригаду',
                        actionsName: 'directoryBrigadesDeleteActions',
                        data: [ item ]
                    }
                )
            },
            async sortBrigade(field){
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.directoryBrigadesOptions

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

                this.directoryBrigadesSetOptions(options)
                await this.directoryBrigadesSetList()
            }
        },
        async mounted(){
            await this.directoryBrigadesSetList()
            await this.accountsCompanySet()
            
            await this.specificationSetAllList()
            await this.directoryBrigadesSetFreeEmployees()
        }
    } 

</script>