<template>
    <div class="requisitions-wrapper">
        <header>
            <div class="requisitions-wrapper__header-wrapper">
                <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
                <button v-show="!!requisition.fixed" class="c-button-add" v-b-modal.confirmation-modal >
                    Отменить заявку
                </button>
            </div>
            <div class="c-links">
                <router-link
                    active-class='c-links--active'
                    :to="`/crm/master/requisition/info/${requisition.id}/detail`" 
                >
                    Детали
                </router-link>
                <router-link
                    active-class='c-links--active'
                    :to="`/crm/master/requisition/info/${requisition.id}/material`"  
                >
                    Материал
                </router-link>
                <router-link
                    active-class='c-links--active'
                    :to="`/crm/master/requisition/info/${requisition.id}/deliverys`"  
                >
                    Поставки
                </router-link>
                <router-link
                    active-class='c-links--active'
                    :to="`/crm/master/requisition/info/${requisition.id}/chat`" 
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
            @confirmationEmit="confirm()"
        />
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import ConfirmationModal from '@/components/elements/ConfirmationModal.vue'

    export default {
        name: 'MasterRequisition',
        data() {
            return {
                breadcrumb: [
                    { text: 'Заявки', href: '/crm/master/requisition' },
                ],
                requisition: {}
            }
        },
        watch: {
            '$route.params.id': {
                async handler() {
                    let id = window.location.href.split('/').reverse()[0];
                    await this.masterRequisitionSet(id)
                    this.requisition = this.masterRequisition
                }
            }
        },
        computed: {
            ...mapGetters({
                masterRequisition: 'masterRequisitionGetter',
                masterRequisitionLoading: 'masterRequisitionLoadingGetter'
            })
        },
        components: {
            ConfirmationModal   
        },
        methods: {
            ...mapActions({
                masterRequisitionSet: 'masterRequisitionSetActions',
                masterRequisitionDelete: 'masterRequisitionDeleteActions',
                masterRequisitionUnFixed: 'masterRequisitionUnFixedActions'
            }),
            async confirm(){
                await this.masterRequisitionUnFixed( this.requisition.id )
                this.$router.push( '/crm/master/requisitions' )
            }
        },
        async mounted(){
            let id = window.location.href.split('/').reverse()[1];
            await this.masterRequisitionSet(id)
            this.requisition = this.masterRequisition

            this.breadcrumb = [
                { text: 'Заявки', to: '/crm/master/requisitions' },
                { text: this.requisition.number },
            ]
        }  
    }
</script>