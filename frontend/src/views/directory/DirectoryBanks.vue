<template>
    <div class='default-wrapper'>
        <div class="default-wrapper__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb> 
        </div>
        <div class="scroll-wrapper">
            <div class="c-default-table-container">
                <div class="c-default-table-container__header">
                    <input-search 
                        :searchActionsNameProps="'directoryBanksSearchActions'"
                        :setListActionsNameProps="'directoryBanksGetActions'"
                    /> 
                </div> 
                <div class="c-default-table">
                    <table>
                        <thead>
                            <tr>
                                <th class="th-sort" @click="sortBanks('name')">
                                    НАЗВАНИЕ
                                    <b-icon v-show="sort.name.asc" icon="chevron-down" font-scale="1"></b-icon>
                                    <b-icon v-show="sort.name.desc" icon="chevron-up" font-scale="1"></b-icon>   
                                </th>   
                                <th class="th-sort" @click="sortBanks('fullname')" >  
                                    ПОЛНОЕ НАЗВАНИЕ
                                    <b-icon v-show="sort.fullname.asc" icon="chevron-down" font-scale="1"></b-icon>
                                    <b-icon v-show="sort.fullname.desc" icon="chevron-up" font-scale="1"></b-icon>   
                                </th>
                                <th class="th-sort" @click="sortBanks('address')">
                                    АДРЕСС
                                    <b-icon v-show="sort.address.asc" icon="chevron-down" font-scale="1"></b-icon>
                                    <b-icon v-show="sort.address.desc" icon="chevron-up" font-scale="1"></b-icon>
                                </th>
                                <th width="150px">
                                    БИК    
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="directoryBanksLoading" class="c-spinner_wrapper c-empty-table">
                            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                        </div>
                        <div 
                            v-else-if="!directoryBanksLoading & directoryBanksList.length == 0"
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
                                    v-for="item in directoryBanksList" 
                                    :key="item.id" 
                                    class="row--selecte"
                                >
                                    <td @click="select(item)" ><p>{{ item.name }}</p></td>
                                    <td><p>{{ item.fullname }}</p></td>
                                    <td><p>{{ item.address }}</p></td>
                                    <td width="150px"><p>{{ item.bik }}</p></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <pagginate-table
                    :optionsProps="directoryBanksOptions"
                    :pagesOptionsProps="directoryBanksPagesOptions"
                    :setListActionsNameProps="'directoryBanksGetActions'"
                    :setOptionsActionsNameProps="'directoryBanksSetOptionsActions'"
                />
            </div>
        </div>
        <directory-bank-modal-info 
            :bankProps="selectBank"
        />
    </div>
</template>
  
<script>
    import { mapGetters, mapActions } from "vuex";

    import InputSearch from '@/components/elements/InputSearch'
    import PagginateTable from '@/components/elements/PagginateTable'
    import DirectoryBankModalInfo from '@/components/directory/DirectoryBankModalInfo'

    export default {
        name: 'DirectoryBanks',
        data(){
            return{
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
                breadcrumb: [
                    { text: 'Банки', href: '/crm/directory/banks' },
                ],
                selectBank: {},
            }   
        },
        components: {
            InputSearch,
            PagginateTable,
            DirectoryBankModalInfo
        },
        computed: {
            ...mapGetters({
                directoryBanksList: 'directoryBanksListGetter',
                directoryBanksLoading: 'directoryBanksLoadingGetter',
                directoryBanksOptions: 'directoryBanksOptionsGetter',
                directoryBanksPagesOptions: 'directoryBanksPagesOptionsGetter'
            })
        },
        methods: {
            ...mapActions({ 
                directoryBanksGet: 'directoryBanksGetActions',
                directoryBanksSetOptions: 'directoryBanksSetOptionsActions'
            }),
            async sortBanks(field){
                
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.directoryBanksOptions

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

                this.directoryBanksSetOptions(options)
                await this.directoryBanksGet()
            },
            select(item){
                this.selectBank = item
                this.$bvModal.show('directory-bank-modal-info')
            },
        },
        async mounted(){
            await this.directoryBanksGet()
        }
    }
</script>
  
 <style>
</style>