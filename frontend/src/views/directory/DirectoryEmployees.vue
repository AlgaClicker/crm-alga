<template>
    <div class='default-wrapper'>
        <div class="default-wrapper__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb> 
            <div class="left-container">
                <button v-b-modal.directory-employees-modal-add class="c-button-add">
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
                    <input-search 
                        :searchActionsNameProps="''"
                        :setListActionsNameProps="'directoryEmployeesSetListActions'"
                    /> 
                </div> 
                <div class="c-default-table">
                    <table>
                        <thead>
                            <tr>
                                <th class="th-sort" @click="sortEmployees('surname')">  
                                    ФАМИЛИЯ
                                    <b-icon v-show="sort.surname.asc" icon="chevron-down" font-scale="1"></b-icon>
                                    <b-icon v-show="sort.surname.desc" icon="chevron-up" font-scale="1"></b-icon>
                                </th>
                                <th class="th-sort" @click="sortEmployees('name')" >
                                    ИМЯ
                                    <b-icon v-show="sort.name.asc" icon="chevron-down" font-scale="1"></b-icon>
                                    <b-icon v-show="sort.name.desc" icon="chevron-up" font-scale="1"></b-icon>
                                </th>
                                <th> ОТЧЕСТВО </th>
                                <th> НОМЕР ТАБЕЛЯ </th>
                                <th> НОМЕР ТЕЛЕФОНА </th>
                                <th> ДОЛЖНОСТЬ </th>
                                <th> АККАУНТ </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="directoryEmployeesLoading" class="c-spinner_wrapper">
                            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                        </div>
                        <div 
                            v-else-if="!directoryEmployeesLoading & directoryEmployeesList.length == 0"
                            class="c-empty-table"
                        >
                            <img src="@/assets/images/box.png">
                            <p>
                                Пусто
                            </p>
                        </div>
                        <table v-else>
                            <tbody>
                                <tr 
                                    v-for="item in directoryEmployeesList" 
                                    :key="item.id"  
                                    class="row--selecte"
                                    @click="showModal(item)"
                                >
                                    <td><p>{{ item.surname }}</p></td>
                                    <td><p>{{ item.name }}</p></td>
                                    <td><p>{{ item.patronymic }}</p></td>
                                    <td><p>{{ item.tabel_number }}</p></td>
                                    <td><p>{{ item.phone_number }}</p></td>
                                    <td><p>{{ item.profession?.name }}</p></td>
                                    <td><p>{{ item.account?.username }}</p></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <pagginate-table
                    :optionsProps="directoryEmployeesOptions"
                    :pagesOptionsProps="directoryEmployeesPagesOptions"
                    :setListActionsNameProps="'directoryEmployeesSetListActions'"
                    :setOptionsActionsNameProps="'directoryEmployeesSetOptionsActions'"
                />
            </div>
        </div>
        <DirectoryEmployeesModalAdd/>
        <DirectoryEmployeesModalEdit
            :editEmployeesProps="selectEmployees"
        />
    </div>
</template>
  
<script>
    import { mapGetters, mapActions } from "vuex";

    import InputSearch from '@/components/elements/InputSearch'
    import PagginateTable from '@/components/elements/PagginateTable'
    import DirectoryEmployeesModalAdd from '@/components/directory/DirectoryEmployeesModalAdd.vue'
    import DirectoryEmployeesModalEdit from '@/components/directory/DirectoryEmployeesModalEdit.vue'

    export default {
        name: 'DirectoryEmployees',
        data(){
            return {
                valSearch: '',
                sort: {
                    surname: {
                        asc: false,
                        desc: false,
                    },
                    name: {
                        asc: false,
                        desc: false,
                    },
                    created_at: {
                        asc: true,
                        desc: false,
                    },
                },
                selectEmployees: {},
                breadcrumb: [
                    { text: 'Сотрудники', href: '/crm/directory/employees' },
                ]  
            }
        },
        components: {
            InputSearch,
            PagginateTable,
            DirectoryEmployeesModalAdd,
            DirectoryEmployeesModalEdit,
        },
        computed: {
            ...mapGetters({
                directoryEmployeesList: 'directoryEmployeesListGetter',
                directoryEmployeesSelectedList: 'directoryEmployeesSelectedListGetter',
                directoryEmployeesLoading: 'directoryEmployeesLoadingGetter',
                directoryEmployeesOptions: 'directoryEmployeesOptionsGetter',
                directoryEmployeesPagesOptions: 'directoryEmployeesPagesOptionsGetter',
                directoryEmployeesError: 'directoryEmployeesErrorGetter',
                directoryPostsNameOptions: 'directoryPostsNameOptionsGetter',
                profleDirectoryEditAccess: 'profleDirectoryEditAccessGetter',
            }),
        },  
        methods: {
            ...mapActions({ 
                directoryEmployeesSetList: 'directoryEmployeesSetListActions',
                directoryEmployeesSetOptions: 'directoryEmployeesSetOptionsActions',
                directoryEmployeesChecked: 'directoryEmployeesCheckedActions',
                directoryPostsGetList: 'directoryPostsGetListActions',
                directoryPostsSetAll: 'directoryPostsSetAllActions',
                accountsCompanySet: 'accountsCompanySetActions'
            }),
            async sortEmployees(field){
                
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.directoryEmployeesOptions

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

                this.directoryEmployeesSetOptions(options)
                await this.directoryEmployeesSetList()
            },
            showModal(item){
                this.selectEmployees = item
                
                this.$bvModal.show('directory-employees-modal-edit')
            }
        },
        async mounted(){
            await this.directoryEmployeesSetList()
            await this.directoryPostsSetAll()
            await this.accountsCompanySet()
        }
    }
</script>
  
<style>
</style>