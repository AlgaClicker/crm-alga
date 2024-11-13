<template>
    <div class="l-specification-master">
        <div class="l-specification-master__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
            <div class="left-container" v-show="profile.roles.service == 'master'">
                <button v-b-modal.master-requisition-modal-universal class="c-button-add mr-1">
                    <b-icon icon="plus-lg" scale="1"></b-icon>
                    <span> 
                        Универсальную заявку
                    </span>
                </button>
                <button v-b-modal.master-requisition-modal-new-for-specifications class="c-button-add">
                    <b-icon icon="plus-lg" scale="1"></b-icon>
                    <span> 
                        Создать заявку
                    </span>
                </button>
            </div>
        </div>
        <div class="c-specification">
            <header class="c-specification__header">
                <label for="select" class="c-select">
                    <b-form-select 
                        v-model="selected" 
                        :options="masterSpecificationListIZM"
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
                    <button v-b-modal.specification-master-modal-info class="c-button-info">
                        <base-icon iconProps="info" sizeProps="md" />
                        Информация
                    </button>
                </div>
            </header>
            <main class="c-specification__wrapper">
                <master-specification-material
                    v-show="isVisibleMaterials"
                    :specificationIdProps="specification.id"
                    :specificationIZMList="masterSpecificationListIZM"
                    :selectIZMId="selected"
                />
            </main>
        </div>
        <master-requisition-modal-new-for-specifications
            :specificationProps="masterSpecification"
        />
        <master-requisition-modal-universal
            :specificationsList="masterSpecificationList"
            :specificationProps="masterSpecification"
        />
        <specification-master-modal-info
            :specificationProps="masterSpecification" 
        />
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    import BaseIcon from "@/components/elements/BaseIcon"
    import MasterSpecificationMaterial from '@/views/specifications/master/MasterSpecificationMaterial.vue'
    import MasterRequisitionModalUniversal from '@/components/requisition/MasterRequisitionModalUniversal'
    import MasterRequisitionModalNewForSpecifications from '@/components/requisition/MasterRequisitionModalNewForSpecifications'
    import SpecificationMasterModalInfo from '@/components/specification/SpecificationMasterModalInfo'
    
    export default {
        name: 'MasterSpecification',
        data(){
            return {
                isVisibleMaterials: true,
                isVisibleResource: false,
                isVisibleTypeWork: false,
                selected: null,
                specification: {

                },
                breadcrumb: [
                    { text: 'Спецификации', href: '/crm/master/specifications' },
                ]  
            }
        },
        components: {
            BaseIcon,
            MasterSpecificationMaterial,
            SpecificationMasterModalInfo,
            MasterRequisitionModalUniversal,
            MasterRequisitionModalNewForSpecifications
        },
        watch: {
            '$route.params.id': {
                async handler() {
                    let id = window.location.href.split('/').reverse()[0];

                    await this.masterSpecificationGetById({ id: id })
                    await this.masterSpecificationSetIZM(id)

                    this.selected = this.masterSpecificationListIZM.filter( item => item.value == id)[0].value

                    this.specification.id = this.masterSpecification.id,
                    this.specification.name = this.masterSpecification.name,
                    this.specification.object_id = this.masterSpecification.objectId,
                    this.specification.object_name = this.masterSpecification.objectName,
                    this.specification.title = this.masterSpecification.name,
                    this.specification.description = this.masterSpecification.description,
                    this.specification.created_at = this.masterSpecification.created_at
                    this.specification.files = this.masterSpecification.files
                    this.specification.responsibles = this.masterSpecification.responsibles
                    this.specification.author = this.masterSpecification.author
                }
            }
        },
        computed: {
            ...mapGetters({
                profile: "profileGetter",
                masterSpecification: 'masterSpecificationGetter',
                masterSpecificationList: 'masterSpecificationListGetter',
                masterSpecificationListIZM: 'masterSpecificationListIZMGetter',
            })
        },
        methods: {
            ...mapActions({
                masterSpecificationSetIZM: 'masterSpecificationSetIZMActions',
                masterSpecificationGetById: 'masterSpecificationGetByIdActions'
            }),
            toggleVisibleTableSpec(type){
                if(type == 'm'){
                    this.isVisibleMaterials = true
                    this.isVisibleResource = false
                    this.isVisibleTypeWork = false
                }
                else if(type == 'r'){
                    this.isVisibleMaterials = false
                    this.isVisibleResource = true
                    this.isVisibleTypeWork = false
                }
                else{
                    this.isVisibleMaterials = false
                    this.isVisibleResource = false
                    this.isVisibleTypeWork = true
                }
            }
        },
        async mounted(){
            let id = window.location.href.split('/').reverse()[0];

            await this.masterSpecificationGetById({ id: id })
            await this.masterSpecificationSetIZM(id)

            this.selected = this.masterSpecificationListIZM.filter( item => item.value == id)[0].value

            this.specification.id = this.masterSpecification.id,
            this.specification.name = this.masterSpecification.name,
            this.specification.object_id = this.masterSpecification.objectId,
            this.specification.object_name = this.masterSpecification.objectName,
            this.specification.title = this.masterSpecification.name,
            this.specification.description = this.masterSpecification.description,
            this.specification.created_at = this.masterSpecification.created_at
            this.specification.files = this.masterSpecification.files
            this.specification.responsibles = this.masterSpecification.responsibles
            this.specification.author = this.masterSpecification.author

            this.breadcrumb = [
                { text: 'Спецификации', to: '/crm/master/specifications' },
                { text: this.specification.name, href: ''},
            ]
        }
    }
</script>