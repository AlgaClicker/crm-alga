<template>
    <div class="requisitions-wrapper">
        <header>
            <div class="requisitions-wrapper__header-wrapper">
                <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
                <button v-show="!!requisition.fixed" class="c-button-add" @click="pickUp()" v-b-modal.confirmation-modal >
                    Забрать в работу
                </button>
            </div>
            <div class="c-links">
                <router-link
                    active-class='c-links--active'
                    :to="`/crm/supply/requisition/free/info/${supplyMyRequisition.id}/detail`"
                >
                    Детали
                </router-link>
                <router-link
                    active-class='c-links--active'
                    :to="`/crm/supply/requisition/free/info/${supplyMyRequisition.id}/material`"
                >
                    Материал
                </router-link>
            </div>
        </header>
        <div class="scroll-wrapper">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        name: 'SupplyFreeRequisition',
        data(){
            return {
                requisition: {},
                breadcrumb: [
                    { text: 'Заявки', href: '/crm/supply/requisition/free' },
                ],
            }
        },
        components: {
            
        },
        computed: {
            ...mapGetters({
                supplyMyRequisition: 'supplyMyRequisitionGetter',
                supplyMyRequisitionError: 'supplyMyRequisitionErrorGetter'
            })
        },
        methods: {
            ...mapActions({
                supplyMyRequisitionSet: 'supplyMyRequisitionSetActions',
                supplyMyRequisitionCancel: 'supplyMyRequisitionCancelActions',
                supplyFreeRequisitionPickUp: 'supplyFreeRequisitionPickUpActions',
            }),
            async pickUp(){
                await this.supplyFreeRequisitionPickUp(this.supplyMyRequisition.id)
            }
        },
        async mounted(){
            let id = window.location.href.split('/').reverse()[1];
            await this.supplyMyRequisitionSet(id)
    
            this.requisition = this.supplyMyRequisition

            this.breadcrumb = [
                { text: 'Заявки', to: '/crm/supply/requisition/free' },
                { text: this.requisition.number },
            ]
        }   
    }
</script>