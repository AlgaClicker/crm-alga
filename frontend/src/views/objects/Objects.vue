<template>
    <div class='default-wrapper'>
        <div class="default-wrapper__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
            <div class="left-container">
                <button v-b-modal.object-add-modal class="c-button-add ml-1">
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
                                <th @click="sortObject('name')" class="th-sort">
                                    НАЗВАНИЕ
                                    <b-icon v-show="sort.name.asc" icon="chevron-down" font-scale="0.9"></b-icon>
                                    <b-icon v-show="sort.name.desc" icon="chevron-up" font-scale="0.9"></b-icon>   
                                </th>
                                <th @click="sortObject('address')" class="th-sort">  
                                    АДРЕС
                                    <b-icon v-show="sort.address.asc" icon="chevron-down" font-scale="0.9"></b-icon>
                                    <b-icon v-show="sort.address.desc" icon="chevron-up" font-scale="0.9"></b-icon>
                                </th>
                                <th @click="sortObject('created_at')" class="th-sort">
                                    ДАТА СОЗДАНИЯ
                                    <b-icon v-show="sort.created_at.asc" icon="chevron-down" font-scale="0.9"></b-icon>
                                    <b-icon v-show="sort.created_at.desc" icon="chevron-up" font-scale="0.9"></b-icon>
                                </th>
                                <th>
                                    АВТОР
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="objectsListLoading" class="c-spinner_wrapper c-empty-table">
                            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                        </div>
                        <div 
                            v-else-if="objectsList.length == 0 | objectsList == null"
                            class="c-empty-table"
                        >
                            <img src="@/assets/images/box.png">
                            <p>
                                Нет объектов
                            </p>
                        </div>
                        <table v-else>
                            <tbody>
                                <tr v-for="item in objectsList" :key="item?.id">
                                    <td class="td-name" >
                                        <router-link
                                            :to="`/crm/objects/view/${item.id}`" 
                                        >
                                            <p>{{ item.name }}</p>
                                        </router-link>
                                    </td>
                                    <td><p>{{ item.address }}</p></td>
                                    <td><p>{{ item.created_at | dateFilter }}</p></td>
                                    <td><p>{{ item.autor.username }}</p></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <pagginate-table
                    :optionsProps="objectOptions"
                    :pagesOptionsProps="objectPagesOptions"
                    :setListActionsNameProps="'objectsSetListActions'"
                    :setOptionsActionsNameProps="'objectSetOptionsActions'"
                />
            </div>
        </div>
        <objects-add-modal/>
    </div>
</template>
<script>
    import { mapActions, mapGetters } from "vuex"
    import ObjectsAddModal from '@/components/object/ObjectsAddModal'
    import PagginateTable from '@/components/elements/PagginateTable'
    
    export default {
        name: 'Objects',
        data(){
            return {
                sort: {
                    name: {
                        asc: false,
                        desc: false,
                    },
                    address: {
                        asc: true,
                        desc: false,
                    },
                    created_at: {
                        asc: false,
                        desc: false,
                    },
                },
                iconSearch: '',
                valSearch: '',
                breadcrumb: [
                    { text: 'Объекты', to: '/crm/objects' },
                ]  
            }
        },
        components: {
            PagginateTable,
            ObjectsAddModal
        },
        computed: {
            ...mapGetters({
                objectsList: 'objectsListGetter',
                objectOptions: 'objectOptionsGetter',
                objectPagesOptions: 'objectPagesOptionsGetter',
                objectsListLoading: 'objectsListLoadingGetter'
            }),
        },
        methods: {
            ...mapActions({ 
                objectsSetList: 'objectsSetListActions',
                objectSetOptions: 'objectSetOptionsActions',
            }),
            async sortObject(field){
                
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.objectOptions

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

                this.objectSetOptions(options)
                await this.objectsSetList()
            },
        },
        async mounted() {
            await this.objectsSetList()
        }
    }
</script>

