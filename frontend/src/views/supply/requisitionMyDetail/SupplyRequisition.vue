<template>
    <div class="requisitions-wrapper"> 
        <header>
            <div class="requisitions-wrapper__header-wrapper">
                <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
                <div class="left-container">
                    <button v-show="showCancelRequisitionComputed" class="c-button-add mr-1" v-b-modal.confirmation-modal >
                        Отменить заявку
                    </button>
                    <button 
                        v-b-modal.directory-contracts-supply-modal-add
                        class="c-button-add"
                    >
                        <b-icon icon="plus-lg" scale="1"></b-icon>
                        <span> Договор поставки </span>
                    </button>
                </div>
            </div>
            <div class="c-links">
                <router-link
                    active-class='c-links--active'
                    :to="`/crm/supply/requisition/my/info/${supplyMyRequisition.id}/detail`"
                >
                    Детали
                </router-link>
                <router-link
                    active-class='c-links--active'
                    :to="`/crm/supply/requisition/my/info/${supplyMyRequisition.id}/material`"
                >
                    Материал
                </router-link>
                <router-link
                    active-class='c-links--active'
                    :to="`/crm/supply/requisition/my/info/${supplyMyRequisition.id}/chat`"
                >
                    Чат
                </router-link>
            </div>
        </header>
        <div class="scroll-wrapper">
            <router-view></router-view>
        </div>
        <confirmation-modal 
            textProps="Отменить заявку?"
            colorProps="primary"
            :isDescription=true
            @confirmationEmit="confirm"
        />
        <directory-contracts-supply-modal-add
            :specificationsListProps="specificationAllList"
            :partnersListProps="directoryPartnersList"
        />
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import ConfirmationModal from '@/components/elements/ConfirmationModal.vue'
    import DirectoryContractsSupplyModalAdd from '@/components/directory/DirectoryContractsSupplyModalAdd'

    export default {
        name: 'SupplyRequisition',
        data(){
            return {
                breadcrumb: [
                    { text: 'Мои заявки', href: '/crm/supply/requisition/my' },
                ],
                requisition: {}
            }
        },
        components: {
            ConfirmationModal,
            DirectoryContractsSupplyModalAdd
        },
        watch: {
            '$route.params.id': {
                async handler() {
                    let id = window.location.href.split('/').reverse()[1];
                    await this.supplyMyRequisitionSet(id)
                    
                    this.requisition = this.supplyMyRequisition

                    this.breadcrumb = [
                        { text: 'Заявки', href: '/crm/supply/requisition/my' },
                        { text: this.requisition.number },
                    ]
                }
            }
        },
        computed: {
            ...mapGetters({
                supplyMyRequisition: 'supplyMyRequisitionGetter',
                specificationAllList: 'specificationAllListGetter',
                directoryPartnersList: 'directoryPartnersListGetter',
                supplyMyRequisitionError: 'supplyMyRequisitionErrorGetter'
            }),
            showCancelRequisitionComputed(){
                return !!this.requisition.fixed & this.requisition.status != 'completed' & this.requisition.status != 'inprogress'
            }
        },
        methods: {
            ...mapActions({
                supplyMyRequisitionSet: 'supplyMyRequisitionSetActions',
                specificationSetAllList: 'specificationSetAllListActions',
                directoryPartnersSetList: 'directoryPartnersSetListActions',
                supplyMyRequisitionCancel: 'supplyMyRequisitionCancelActions',
            }),
            async confirm(description){
   
                let params = {
                    description: description,
                    requisitionId: this.supplyMyRequisition.id
                }

                await this.supplyMyRequisitionCancel(params)

                if(!this.supplyMyRequisitionError){
                    this.$router.push('/crm/supply/requisition/my')
                }
            }
        },
        async mounted(){
            let id = window.location.href.split('/').reverse()[1];
            await this.supplyMyRequisitionSet(id)
    
            this.requisition = this.supplyMyRequisition

            this.breadcrumb = [
                { text: 'Заявки', to: '/crm/supply/requisition/my' },
                { text: this.requisition.number },
            ]

            await this.specificationSetAllList()
            await this.directoryPartnersSetList()
        }
    }
</script>