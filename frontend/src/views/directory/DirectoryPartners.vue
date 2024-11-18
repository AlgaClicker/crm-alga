<template>
    <div class='default-wrapper'>
        <div class="default-wrapper__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb> 
            <div class="left-container">
                <button v-b-modal.directory-partners-modal-add class="c-button-add">
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
                        :searchActionsNameProps="'directoryPartnerSearchActions'"
                        :setListActionsNameProps="'directoryPartnersSetListActions'"
                    /> 
                </div>
                <div class="c-default-table">
                    <table>
                        <thead class="c-default-table-thead-desktop">
                            <tr>
                                <th class="th-sort" @click="sortPartners('name')">  
                                    <p>
                                        НАЗВАНИЕ
                                        <b-icon v-show="sort.name.asc" icon="chevron-down" font-scale="1"></b-icon>
                                        <b-icon v-show="sort.name.desc" icon="chevron-up" font-scale="1"></b-icon>
                                    </p>
                                </th>
                                <th class="th-sort" @click="sortPartners('fullname')" >
                                    <p> 
                                        ПОЛНОЕ НАЗВАНИЕ
                                        <b-icon v-show="sort.fullname.asc" icon="chevron-down" font-scale="1"></b-icon>
                                        <b-icon v-show="sort.fullname.desc" icon="chevron-up" font-scale="1"></b-icon>
                                    </p>
                                </th>
                                <th class="th-sort" @click="sortPartners('address')" >
                                    <p> 
                                        АДРЕСС
                                        <b-icon v-show="sort.address.asc" icon="chevron-down" font-scale="1"></b-icon>
                                        <b-icon v-show="sort.address.desc" icon="chevron-up" font-scale="1"></b-icon>
                                    </p>
                                </th>
                                <th><p> ДАТА СОЗДАНИЯ </p></th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="directoryPartnersLoading" class="c-spinner_wrapper c-empty-table">
                        <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                    </div>
                    <div 
                        v-else-if="!directoryPartnersLoading & directoryPartnersList.length == 0"
                        class="c-empty-table"
                    >
                        <img src="@/assets/images/box.png">
                        <p>
                             Пусто
                        </p>
                    </div>
                        <table v-else>
                            <tbody>
                                <tr v-for="item in directoryPartnersList" :key="item.id" >
                                    <td>
                                        <router-link
                                            class="c-label-info"
                                            :to="`/crm/directory/partners/view/${item.id}`" 
                                        >
                     
                                           {{ item.name }}
                                        </router-link>
                                    </td>
                                    <td><p>{{ item.fullname }}</p></td>
                                    <td><p>{{ item.address }}</p></td>
                                    <td>
                                        <p>{{ item.created_at | dateFilter }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <pagginate-table
                    :optionsProps="directoryPartnersOptions"
                    :pagesOptionsProps="directoryPartnersPagesOptions"
                    :setListActionsNameProps="'directoryPartnersSetListActions'"
                    :setOptionsActionsNameProps="'directoryPartnersSetOptionsActions'"
                />
            </div>
        </div>
        <DirectoryPartnersModalAdd /> 
        <DirectoryPartnersModalEdit />
        <DirectoryPartnersModalEditGroupe />
        <DirectoryPartnersModalAddBankAccount />
        <DirectoryPartnersModalInfo 
            :partnersProps="selectParter" 
        />
    </div>
</template>
  
<script>
    import { mapGetters, mapActions } from "vuex";

    import InputSearch from '@/components/elements/InputSearch'
    import PagginateTable from '@/components/elements/PagginateTable';
    import DirectoryPartnersModalAdd from '@/components/directory/DirectoryPartnersModalAdd';
    import DirectoryPartnersModalEdit from '@/components/directory/DirectoryPartnersModalEdit';
    import DirectoryPartnersModalInfo from '@/components/directory/DirectoryPartnersModalInfo';
    import DirectoryPartnersModalEditGroupe from '@/components/directory/DirectoryPartnersModalEditGroupe';
    import DirectoryPartnersModalAddBankAccount from '@/components/directory/DirectoryPartnersModalAddBankAccount';

    export default {
        name: 'DirectoryPartners',
        data() {
            return {
                valSearch: '',
                sort: {
                    fullname: {
                        asc: false,
                        desc: false,
                    },
                    name: {
                        asc: false,
                        desc: false,
                    },
                    address: {
                        asc: true,
                        desc: false,
                    },
                },
                selectParter: {},
                breadcrumb: [
                    { text: 'Контрагенты', href: '/crm/directory/partners' },
                ],
            }
        },
        components: {
            InputSearch,
            PagginateTable,
            DirectoryPartnersModalAdd,
            DirectoryPartnersModalEdit,
            DirectoryPartnersModalInfo,
            DirectoryPartnersModalEditGroupe,
            DirectoryPartnersModalAddBankAccount
        },
        computed: { 
            ...mapGetters({
                directoryPartnersList: 'directoryPartnersListGetter',
                directoryPartnersLoading: 'directoryPartnersLoadingGetter',
                directoryPartnersOptions: 'directoryPartnersOptionsGetter',
                profleDirectoryEditAccess: 'profleDirectoryEditAccessGetter',
                directoryPartnersPagesOptions: 'directoryPartnersPagesOptionsGetter',
            }),
        },
        methods: {
            ...mapActions({ 
                setCurrentEditUid: 'setCurrentEditUidActions',
                setParamsDeleteModal: 'setParamsDeleteModalActions',
                directoryPartnersSetList: 'directoryPartnersSetListActions',
                directoryPartnersSetOptions: 'directoryPartnersSetOptionsActions',
            }),
            async sortPartners(field){
                
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.directoryPartnersOptions

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

                this.directoryPartnersSetOptions(options)
                await this.directoryPartnersSetList()
            },
            showInfo(item){
                this.selectParter = item
                this.$bvModal.show('directory-partners-modal-info')
            },
        },
        async mounted(){
            await this.directoryPartnersSetList()
        }
    }
</script>
  
<style>
</style>