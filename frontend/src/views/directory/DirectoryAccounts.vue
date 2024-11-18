<template>
    <div class='default-wrapper'>
        <div class="default-wrapper__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb> 
            <div class="left-container">
                <button v-b-modal.directory-accounts-company-modal-add class="c-button-add">
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
                                <th class="th-sort" @click="sortAccount('username')">  
                                    <p>
                                        ИМЯ ПОЛЬЗОВАТЕЛЯ
                                        <b-icon v-show="sort.username.asc" icon="chevron-down" font-scale="1"></b-icon>
                                        <b-icon v-show="sort.username.desc" icon="chevron-up" font-scale="1"></b-icon>
                                    </p>
                                </th>
                                <th class="th-sort" @click="sortAccount('email')">  
                                    <p>
                                        ЭЛЕКТРОННАЯ ПОЧТА
                                        <b-icon v-show="sort.email.asc" icon="chevron-down" font-scale="1"></b-icon>
                                        <b-icon v-show="sort.email.desc" icon="chevron-up" font-scale="1"></b-icon>
                                    </p>
                                </th>
                                <th class="th-sort">  
                                    <p>
                                        ДАТА СОЗДАНИЯ
                                    </p>
                                </th>
                                <th class="th-sort">  
                                    <p>
                                        АКТИВЕН
                                    </p>
                                </th>
                                <th class="th-sort">  
                                    <p>
                                        РОЛЬ
                                    </p>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="accountsCompanyLoading" class="c-spinner_wrapper">
                            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                        </div>
                        <div v-else-if="accountsCompanyList.length == 0 & !accountsCompanyLoading"  class="c-empty-table">
                            <img src="@/assets/images/box.png">
                            <p>
                                Нет аккаунтов
                            </p>
                        </div>
                        <table v-else>
                            <tbody>
                                <tr 
                                    v-for="item in accountsCompanyList" 
                                    :key="item.id"
                                    class="row--selecte"
                                    @click="showModal(item)"
                                > 
                                    <td>{{ item.username }}</td>
                                    <td>{{ item.email }}</td>
                                    <td>{{ item.created_at | dateFilter }}</td>
                                    <td>{{ item.active | boolFilter}}</td>
                                    <td>{{ item.roles.name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>  
                </div>
                <pagginate-table
                    :optionsProps="accountsCompanyOptions"
                    :pagesOptionsProps="accountsCompanyPageOptions"
                    :setListActionsNameProps="'accountsCompanySetActions'"
                    :setOptionsActionsNameProps="'accountsCompanySetOptionsActions'"
                />
            </div>
        </div>      
        <DirectoryAccountsCompanyModalAdd/>
        <DirectoryAccountsCompanyModalEdit
            :editAccountProps="selectAccount"
        />
    </div>
</template>
<script>
    import { mapGetters, mapActions } from "vuex";

    import PagginateTable from '@/components/elements/PagginateTable'
    import DirectoryAccountsCompanyModalAdd from '@/components/directory/DirectoryAccountsCompanyModalAdd'
    import DirectoryAccountsCompanyModalEdit from '@/components/directory/DirectoryAccountsCompanyModalEdit'
    
    export default {
        name: 'DirectoryAccounts',
        data() {
            return {
                valSearch: '',
                selectAccount: null,
                sort: {
                    username: {
                        asc: true,
                        desc: false, 
                    },
                    email: {
                        asc: false,
                        desc: false,
                    },
                    created_at: {
                        asc: false,
                        desc: false,
                    },
                    active: {
                        asc: true,
                        desc: false,
                    },
                },
                breadcrumb: [
                    { text: 'Аккаунты', href: '/crm/directory/accounts' },
                ]  
            }
        },
        components: {
            PagginateTable,
            DirectoryAccountsCompanyModalAdd,
            DirectoryAccountsCompanyModalEdit
        },
        computed: {
            ...mapGetters({
                accountsCompanyList: 'accountsCompanyListGetter',
                profleDirectoryEditAccess: 'profleDirectoryEditAccessGetter',
                accountsCompanyPageOptions: 'accountsCompanyPageOptionsGetter',
                accountsCompanyOptions: 'accountsCompanyOptionsGetter',
                accountsCompanyLoading: 'accountsCompanyLoadingGetter'
            })
        },
        methods: {
            ...mapActions({ 
                accountsCompanySet: 'accountsCompanySetActions',
                accountsCompanySetRoles: 'accountsCompanySetRolesActions',
                accountsCompanySetOptions: 'accountsCompanySetOptionsActions'
            }),
            async sortAccount(field){
                
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.accountsCompanyOptions

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

                this.accountsCompanySetOptions(options)
                await this.accountsCompanySet() 
            },
            showModal(item){
                this.selectAccount = item
                this.$bvModal.show('directory-accounts-company-modal-edit')
            },
        },
        async mounted(){
            this.accountsCompanySetRoles()
            await this.accountsCompanySet()

        }
    }

</script>