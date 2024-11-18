<template>
    <div class='default-wrapper'>
        <div class="default-wrapper__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb> 
            <div class="left-container">
                <button v-b-modal.directory-posts-modal-add class="c-button-add">
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
                                <th class="th-sort" @click="sortPosts('name')">  
                                    <p>
                                        НАЗВАНИЕ 
                                        <b-icon v-show="sort.name.asc" icon="chevron-down" font-scale="1"></b-icon>
                                        <b-icon v-show="sort.name.desc" icon="chevron-up" font-scale="1"></b-icon>
                                    </p>
                                </th>
                                <th class="th-sort" @click="sortPosts('fullname')" >
                                    <p> 
                                        ПОЛНОЕ НАЗВАНИЕ 
                                        <b-icon v-show="sort.fullname.asc" icon="chevron-down" font-scale="1"></b-icon>
                                        <b-icon v-show="sort.fullname.desc" icon="chevron-up" font-scale="1"></b-icon>
                                    </p>
                                </th>
                                <th class="th-sort" @click="sortPosts('created_at')" >
                                    <p> 
                                        ДАТА СОЗДАНИЯ
                                        <b-icon v-show="sort.created_at.asc" icon="chevron-down" font-scale="1"></b-icon>
                                        <b-icon v-show="sort.created_at.desc" icon="chevron-up" font-scale="1"></b-icon>
                                    </p>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="directoryPostsLoading" class="c-spinner_wrapper">
                            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                        </div>
                        <div 
                            v-else-if="!directoryPostsLoading & directoryPostsList.length == 0"
                            class="c-empty-table"
                        >
                            <svg 
                                width="68" 
                                height="68" 
                            >
                                <use xlink:href="@/assets/icons/sprite.svg#exclamation-outline"></use>
                            </svg> 
                            <p>
                                Нет должностей
                            </p>
                        </div>
                        <table v-else>
                            <tbody>
                                <tr 
                                    v-for="item in directoryPostsList" 
                                    :key="item.id" 
                                    class="row--selecte"
                                    @click="showModal(item)"
                                >
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.fullname }}</td>
                                    <td>{{ item.created_at | dateFilter }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>  
                </div>
                <pagginate-table
                    :optionsProps="directoryPostsOptions"
                    :pagesOptionsProps="directoryPostsPagesOptions"
                    :setListActionsNameProps="'directoryPostsGetListActions'"
                    :setOptionsActionsNameProps="'directoryPostsSetOptionsActions'"
                />
            </div>
        </div>      
        <directory-posts-modal-add/>
        <directory-posts-modal-edit
            :editPostProps="selectPost"
        />
    </div>
</template>
  
<script>
    import { mapGetters, mapActions } from "vuex";

    import PagginateTable from '@/components/elements/PagginateTable'
    import DirectoryPostsModalAdd from '@/components/directory/DirectoryPostsModalAdd.vue'
    import DirectoryPostsModalEdit from '@/components/directory/DirectoryPostsModalEdit.vue'
    
    export default {
        name: 'DirectoryPosts',
        data(){
            return {
                valSearch: '',
                selectPost: {},
                sort: {
                    fullname: {
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
                breadcrumb: [
                    { text: 'Должности', href: '/crm/directory/posts' },
                ],  
                edit: {}
            }
        },
        components: {
            PagginateTable,
            DirectoryPostsModalAdd,
            DirectoryPostsModalEdit,
        },
        computed: { 
            ...mapGetters({
                directoryPostsList: 'directoryPostsListGetter',
                directoryPostsLoading: 'directoryPostsLoadingGetter',
                directoryPostsOptions: 'directoryPostsOptionsGetter',
                directoryPostsPagesOptions: 'directoryPostsPagesOptionsGetter',
                directoryPostsError: 'directoryPostsErrorGetter',
                profleDirectoryEditAccess: 'profleDirectoryEditAccessGetter'
            })
        },
        methods: {
            ...mapActions({ 
                directoryPostsGetList: 'directoryPostsGetListActions',
                directoryPostsSetOptions: 'directoryPostsSetOptionsActions',
                setParamsDeleteModal: 'setParamsDeleteModalActions',
            }),
            async sortPosts(field){
                
                if(this.sort[field].asc == true){
                    this.sort[field].asc = false
                    this.sort[field].desc = true
                }else{
                    this.sort[field].desc = false
                    this.sort[field].asc = true
                } 

                let options = this.directoryPostsOptions

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

                this.directoryPostsSetOptions(options)
                await this.directoryPostsGetList()
            },
            showModal(item){
                this.selectPost = item
                this.$bvModal.show('directory-posts-modal-edit')
            },
        },
        async mounted(){
            await this.directoryPostsGetList()
            this.page = this.directoryPostsOptions.pagginate.page
            this.pages = this.directoryPostsOptions.pagginate.pages 
        }
    }
</script>
  
<style>
</style>