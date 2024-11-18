<template>
    <div class="l-specification-master">
        <div class="l-specification-master__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb>

        </div>
        <div class="c-specification">
            <header class="c-specification__header">
                <label for="select" class="c-select">
                    <b-form-select 
                        v-model="selected" 
                        :options="supplySpecificationListIZM"
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
                <supply-specification-material
                    v-show="isVisibleMaterials"
                    :specificationIdProps="specification.id"
                    :specificationIZMList="supplySpecificationListIZM"
                    :selectIZMId="selected"
                />
            </main>
        </div>
        <specification-master-modal-info
            :specificationProps="supplySpecification" 
        />
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    import BaseIcon from "@/components/elements/BaseIcon"
    import SupplySpecificationMaterial from '@/views/specifications/supply/SupplySpecificationMaterial'
    import SpecificationMasterModalInfo from '@/components/specification/SpecificationMasterModalInfo'

    export default {
        name: 'SupplySpecification',
        data(){
            return {
                isVisibleMaterials: true,
                isVisibleResource: false,
                isVisibleTypeWork: false,
                selected: null,
                specification: {

                },
                breadcrumb: [
                    { text: 'Спецификации', to: '/crm/master/specifications' },
                ],
            }
        },
        components: {
            BaseIcon,
            SupplySpecificationMaterial,
            SpecificationMasterModalInfo
        },
        watch: {
            '$route.params.id': {
                async handler() {
                    let id = window.location.href.split('/').reverse()[0];
                    
                    await this.supplySpecificationGetById({ id: id })
                    await this.supplySpecificationSetIZM(id)
                    
                    this.selected = this.supplySpecificationListIZM.filter( item => item.value == id)[0].value
                    
                    this.specification.id = this.supplySpecification.id,
                    this.specification.name = this.supplySpecification.name,
                    this.specification.object_id = this.supplySpecification.objectId,
                    this.specification.object_name = this.supplySpecification.objectName,
                    this.specification.title = this.supplySpecification.name,
                    this.specification.description = this.supplySpecification.description,
                    this.specification.created_at = this.supplySpecification.created_at
                    this.specification.files = this.supplySpecification.files
                    this.specification.responsibles = this.supplySpecification.responsibles
                    this.specification.author = this.supplySpecification.author

                    this.breadcrumb = [
                        { text: 'Спецификации', to: '/crm/supply/specifications' },
                        { text: this.specification.name, to: ''},
                    ]
                }
            }
        },
        computed: {
            ...mapGetters({
                profile: "profileGetter",
                supplySpecification: 'supplySpecificationGetter',
                supplySpecificationList: 'supplySpecificationListGetter',
                supplySpecificationListIZM: 'supplySpecificationListIZMGetter',
            })
        },
        methods: {
            ...mapActions({
                supplySpecificationSetIZM: 'supplySpecificationSetIZMActions',
                supplySpecificationGetById: 'supplySpecificationGetByIdActions'
            }),
        },
        async mounted(){
            let id = window.location.href.split('/').reverse()[0];
                   
            await this.supplySpecificationGetById({ id: id })
            await this.supplySpecificationSetIZM(id)

            this.selected = this.supplySpecificationListIZM.filter( item => item.value == id)[0].value

            this.specification.id = this.supplySpecification.id
            this.specification.name = this.supplySpecification.name
            this.specification.object_id = this.supplySpecification.objectId
            this.specification.object_name = this.supplySpecification.objectName
            this.specification.title = this.supplySpecification.name
            this.specification.description = this.supplySpecification.description
            this.specification.created_at = this.supplySpecification.created_at
            this.specification.files = this.supplySpecification.files
            this.specification.responsibles = this.supplySpecification.responsibles
            this.specification.author = this.supplySpecification.author

            this.breadcrumb = [
                { text: 'Спецификации', to: '/crm/supply/specifications' },
                { text: this.specification.name, to: ''},
            ]

        }
    }
</script>