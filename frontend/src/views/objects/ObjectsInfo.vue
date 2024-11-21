<template>
    <div class="l-object-view">
        <header class="l-object-view__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
            <div class="flex-left">
                <button v-b-modal.object-edit-modal class='c-button-add mr-1'>
                    <b-icon icon="pencil-fill" scale="1"></b-icon>
                    Изменить
                </button>
                <button @click="deleteObject" class='c-button-add ml-1'>
                    <b-icon icon="trash-fill" scale="1"></b-icon>
                    Удалить 
                </button>
            </div>
        </header>
        <div v-if="objectsLoading" class="c-spinner_wrapper">
            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
        </div>
        <div v-else>
            <b-row class="mt-3" >
                <b-col cols sm="12" md="12" xl="12">
                    <div class="mb-3 info-wrapper" >
                        <div class="info-wrapper__title">Информация</div>
                        <div class="info-wrapper__flex-wrapper mt-3">
                            <div>
                                <label class="label-custom">Название</label>
                                <div class="text">{{ object.name }}</div>
                            </div>
                            <div class="ml-2">
                                <label class="label-custom">Адрес</label>
                                <div class="text">{{ object.address }}</div>
                            </div>
                        </div>
                        <hr>
                        <div class="mt-3">
                            <div class="info-wrapper__title">Файлы</div>
                            <div class="file-wrapper">
                                <file
                                    class="mt-2 mr-1"
                                    v-for="file in object.files" :key="file.hash"
                                    :fileProps="file"
                                    :deleteProps=true
                                    @deleteFileEmit="deleteFile(file.hash)"
                                />
                            </div>
                            <file-upload 
                                @addFileEmit="addFile"
                            />
                        </div>
                    </div>
                </b-col>
            </b-row>
            <b-row>
                <b-col>
                    <specification-table
                        :isEditProps="true"
                        @deleteSpecEmit="deleteSpec"
                        :specificationListProps="objectSpecificationList"
                        :titleProps="'Прикрепленные спецификации'"
                    />
                </b-col>
            </b-row>
        </div>
        <objects-edit-modal  
            :objectProps='object'
        />
        <specification-modal-add
            :objectIdProps='object.id'
            :accountsProps='accountsListComputed'
        />

      <FileUploadModalForm title="Импорт спецификации" :endpoint="'/api/v1/crm/objects/'+objectGet.id+'/specification/import/'" :sendData=importData>
      </FileUploadModalForm>

    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    import File from '@/components/elements/files/File'
    import FileUpload from '@/components/elements/files/FileUpload'
    import ObjectsEditModal from '@/components/object/ObjectsEditModal'
    import SpecificationTable from '@/components/elements/tables/SpecificationTable'
    import SpecificationModalAdd from '@/components/specification/SpecificationModalAdd'
    import FileUploadModalForm from "@/views/utils/FileUploadModalForm.vue";

    export default {
        name: 'ObjectsInfo',
        data(){
            return {
                object: {
                    responsibles: [],
                    files: []
                },
                breadcrumb: [
                    { text: 'Объекты', to: '/crm/objects' },
                ],
            }
        },
        watch: {
            '$route.params.id': {
                async handler() {
                    let id = window.location.href.split('/').reverse()[0];
                    await this.objectSet(id)
                    this.objectSpecificationSetList(id);
                    this.object = await this.objectGet;
                }
            }
        },
        computed: {
            ...mapGetters({
                objectsLoading: 'objectsLoadingGetter',
                objectsListLoading: 'objectsListLoadingGetter',
                objectSpecificationList: 'objectSpecificationListGetter',
                objectGet: 'objectGetter',
                objectsError: 'objectsErrorGetter',
                profile: 'profileGetter',
                accountsCompanyList: 'accountsCompanyListGetter',
            }),
            importData() {
              return {
                data:this.object.id
              };
            },
            accountsListComputed(){
                return this.accountsCompanyList
                .filter( item => this.object.responsibles.filter( element => element.id == item.id).length == 0 )
            }
        },
        components: {
            FileUploadModalForm,
            File,
            FileUpload,
            ObjectsEditModal,
            SpecificationTable,
            SpecificationModalAdd,

        },
        methods: {
            ...mapActions({
                objectSet: 'objectSetActions',
                objectEdit: 'objectEditActions',
                accountsCompanySet: 'accountsCompanySetActions',
                setParamsDeleteModal: 'setParamsDeleteModalActions',
                objectSpecificationSetList: 'objectSpecificationSetListActions',
            }),
            deleteObject(){
                this.setParamsDeleteModal(
                    {
                        title: 'Удалить объект',
                        actionsName: 'objectDeleteActions',
                        data: [ this.object.id ],
                        redirect: 'crm/objects'
                    }
                )
                this.$bvModal.show('base-delete-modal')
            },
            async deleteFile(hash){

                let files = this.object.files.filter(item => item.hash != hash).map(item => item.hash)

                await this.objectEdit({
                    id: this.object.id,
                    name: this.object.name,
                    address: this.object.address,
                    files: files
                })

                if(!this.objectsError){
                    this.object.files = this.object.files.filter(item => item.hash != hash)
                }
            },
            deleteSpec(_, spec){
                console.log(spec)
            },
            async addFile(_,file){

                this.object.files.push(file)

                let files = this.object.files.map(item => item.hash)
                
                await this.objectEdit({
                    id: this.object.id,
                    name: this.object.name,
                    address: this.object.address,
                    files: files
                })
            }
        },
        async mounted() {
            let id = window.location.href.split('/').reverse()[0];

            await this.objectSet(id)
            this.objectSpecificationSetList(id);

            this.object = await this.objectGet;
            await this.accountsCompanySet()

            this.breadcrumb.push(
                {   
                    text: this.object.name, 
                    href: `/crm/objects/view/${this.object.id}` 
                },
            )
        },
    }
</script>