<template>
    <div class="l-specification">  
        <template v-if="specificationLoading">
            <div class="c-spinner_wrapper">
                <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
            </div>
        </template>
        <template v-else>
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
            <div class="c-specification">
                <header class="c-specification__header">
                    <label for="select" class="c-select">
                        <b-form-select 
                            v-model="selected" 
                            :options="specificationListIZM"
                        >
                        </b-form-select>
                    </label>       
                    <div class="links">
                        <button 
                            @click="toggleVisibleTableSpec('m')" 
                            :class="{ 'links__button--active': isVisibleMaterials }"
                            class="links__button"
                        >
                            Материалы
                        </button>
                        <button  
                            @click="toggleVisibleTableSpec('r')" 
                            :class="{ 'links__button--active': isVisibleResource }" 
                            class="links__button"
                        >
                            Ресурсы
                        </button>
                        <button  
                            @click="toggleVisibleTableSpec('t')"  
                            :class="{ 'links__button--active': isVisibleTypeWork }"
                            class="links__button"
                        >
                            Работы
                        </button>
                        <button v-b-modal.specification-modal-info class="c-button-info">
                            <span v-html="iconInfo"></span>
                            Информация
                        </button>
                        <button @click="deleteSpecification()" class="c-button-danger">
                            <b-icon icon="exclamation-diamond-fill" scale="1"></b-icon>
                            Удалить
                        </button>
                    </div>
                </header>   
                <main class="c-specification__wrapper">
                    <specification-material
                        v-show="isVisibleMaterials"
                        :specificationIdProps="specification.id"
                        :specificationIZMList="specificationListIZM"
                        :selectIZMId="selected"
                    />
                    <specification-resources
                        v-show="isVisibleResource"
                        :specificationIdProps="specification.id"
                        :specificationIZMList="specificationListIZM"
                        :selectIZMId="selected"
                    />
                    <specification-type-works
                        v-show="isVisibleTypeWork"
                        :specificationIdProps="specification.id"
                        :specificationIZMList="specificationListIZM"
                        :selectIZMId="selected"
                    />
                </main>
            </div>
        </template>
        <specification-add-IZM 
            :parentSpecificationProps="specification" 
        /> 
        <specification-modal-info 
            :specificationProps="specificationGet"
        />
    </div>
</template>
  
<script>
    import { mapActions, mapGetters } from 'vuex'
    import { required } from 'vuelidate/lib/validators';
    import { validationMixin } from "vuelidate";
    import { svgs } from '@/utils/svgList'

    import SpecificationAddIZM from '@/components/specification/SpecificationAddIZM'
    import SpecificationMaterial from '@/views/specifications/SpecificationMaterial'
    import SpecificationResources from '@/views/specifications/SpecificationResources'
    import SpecificationTypeWorks from '@/views/specifications/SpecificationTypeWorks'
    import SpecificationModalInfo from '@/components/specification/SpecificationModalInfo'

    export default {
        name: 'Specification',
        data() {
            return {
                specification: {
                    id: '',
                    name: '',
                    object_id: '',
                    object_name: '',
                    title: '',
                    description: '',
                    created_at: '',
                    responsibles: [],
                },
                iconInfo: '',
                viewFilesSiderbar: false,
                viewIZMSiderbar: false,
                selected: null,
                isVisibleMaterials: false,
                isVisibleResource: false,
                isVisibleTypeWork: false,
                iconFile: '',
                breadcrumb: []
            }
        },
        components: {
            SpecificationAddIZM,
            SpecificationMaterial,
            SpecificationResources,
            SpecificationTypeWorks,
            SpecificationModalInfo,
        },
        watch: {
            '$route.params.id': {
                async handler() {
                    let id = window.location.href.split('/').reverse()[0];

                    await this.specificationSetListIZM(id)
                    await this.specificationGetById({ id: id })
   
                    this.selected = this.specificationListIZM.filter( item => item.value == id)[0].value
                    
                    this.specification.id = this.specificationGet.id,
                    this.specification.name = this.specificationGet.name,
                    this.specification.object_id = this.specificationGet.objectId,
                    this.specification.object_name = this.specificationGet.objectName,
                    this.specification.title = this.specificationGet.name,
                    this.specification.description = this.specificationGet.description,
                    this.specification.created_at = this.specificationGet.created_at
                    this.specification.files = this.specificationGet.files
                    this.specification.responsibles = this.specificationGet.responsibles
                    this.specification.author = this.specificationGet.author
                }
            }
        },
        validations: {
            specification: {
                name: { required },
                description: { required },
            }
        },
        mixins: [validationMixin],
        computed: {
            ...mapGetters({
                specificationGet: 'specificationGetter',
                specificationList: 'specificationListGetter',
                specificationError: 'specificationErrorGetter',
                specificationLoading: 'specificationLoadingGetter',
                specificationListIZM: 'specificationListIZMGetter',
            }),
            validationsComputed() {
                if( 
                    this.$v.specification.name.$invalid  == false && 
                    this.$v.specification.description.$invalid  == false 
                ){
                    return true
                }else {
                    return false
                }
            }
        },
        methods: {
            ...mapActions({
                specificationEdit: 'specificationEditActions',
                setParamsDeleteModal: 'setParamsDeleteModalActions',
                specificationGetById: 'specificationGetByIdActions',
                specificationSetListIZM: 'specificationSetListIZMActions',
                specificationSetListContracts: 'specificationSetListContractsActions'
            }),
            async editSepc(){
                await this.specificationEdit(this.specification)
    
                if(!this.specificationError){
                    this.toggleAsside()
                }
            },
            async addFile(_, file){
                var files = this.specification.files
                files.push(file)
            },
            showSiderbar(sidebar){
                this.viewFilesSiderbar = false
                this.viewIZMSiderbar = false
                this[sidebar] = !this[sidebar]
            },
            closeSidebar(){
                this.viewFilesSiderbar = false
                this.viewIZMSiderbar = false
            },
            deleteResponsible(id){
                alert(id)
            },
            deleteSpecification(){
                this.setParamsDeleteModal(
                    {
                        title: 'Удалить спецификацию',
                        actionsName: 'specificationDeleteActions',
                        data: [ this.specification.id ],
                        redirect: `crm/objects/view/${this.specification.object_id}`
                    }
                )
                this.$bvModal.show('base-delete-modal')
            },
            toggleAsside(){
                this.isVisibleAsside = !this.isVisibleAsside
            },
            async removeFile(hash){
          
                await this.specificationEdit(this.specification)
                if(this.specificationError){
                    this.specification.files = this.specification.files.filter(item => item.hash != hash)
                    console.log(this.specification.files)
                }
            },
            toggleVisibleTableSpec(type){
                if(type == 'm'){
                    this.isVisibleMaterials = true,
                    this.isVisibleResource = false,
                    this.isVisibleTypeWork = false
                }
                else if(type == 'r'){
                    this.isVisibleMaterials = false,
                    this.isVisibleResource = true,
                    this.isVisibleTypeWork = false
                }
                else{
                    this.isVisibleMaterials = false,
                    this.isVisibleResource = false,
                    this.isVisibleTypeWork = true
                }
            }
        },
        async mounted(){
            let id = window.location.href.split('/').reverse()[0];

            this.iconInfo = svgs.info.md
            this.iconFile = svgs.files.md

            this.breadcrumb = [
                { text: 'Объекты', href: '/crm/objects' },
            ]

            await this.specificationGetById({ id: id })
            
            await this.specificationSetListIZM(id)
            this.isVisibleMaterials = true
            
            this.selected = this.specificationListIZM.filter( item => item.value == id )[0].value
            this.specification.id = this.specificationGet.id,
            this.specification.name = this.specificationGet.name,
            this.specification.object_id = this.specificationGet.objectId,
            this.specification.object_name = this.specificationGet.objectName,
            this.specification.title = this.specificationGet.name,
            this.specification.description = this.specificationGet.description,
            this.specification.created_at = this.specificationGet.created_at
            this.specification.files = this.specificationGet.files
            this.specification.responsibles = this.specificationGet.responsibles
            this.specification.author = this.specificationGet.author

            this.specificationSetListContracts(this.specificationGet.id)

            this.breadcrumb = [
                { text: 'Объекты', to: '/crm/objects' },
                { text: this.specification.object_name, to: `/crm/objects/view/${this.specification.object_id}` },
                { text: this.specification.name, to: 'Objects' },
            ]
        }
    }

</script>
  
<style>
</style>