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
      <div class="p-0 m-0">
        <div  >
          <b-progress class="my-1 mx-5 success"  v-if=" requisition.progress == 100"  value="100"  :max="100"  variant="success" striped animated>
            <b-progress-bar  value="100">
              Выполненна 100%
            </b-progress-bar>
          </b-progress>

          <b-progress v-if="requisition.progress>0 && requisition.progress<100" class="my-1 mx-5"   :max="100" show-progress animated>
            <b-progress-bar  :value="requisition.progress">
              <span v-if="requisition.progress>10"><strong>  Выполнено: {{ requisition.progress.toFixed(2) }}%</strong></span>
              <span v-if="requisition.progress <= 10"><strong>  {{ requisition.progress.toFixed(2) }}%</strong></span>
            </b-progress-bar>
          </b-progress>
        </div>
        <!--
        <div class="progress my-2 mx-5">
          <div class="progress-bar progress-bar-striped" role="progressbar" :aria-valuenow="45" aria-valuemin="12" aria-valuemax="100"></div>
        </div>
        -->

        <div class="scroll-wrapper">
          <router-view></router-view>
        </div>
        <confirmation-modal
            textProps="Отменить заявку?"
            colorProps="primary"
            @confirmationEmit="confirm()"
        />

      </div>
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
                masterRequisitionLoading: 'masterRequisitionLoadingGetter',
                masterRequisitionDeliveryList: 'masterRequisitionDeliveryListGetter',
            })
        },
        components: {
            ConfirmationModal   
        },
        methods: {
            ...mapActions({
                masterRequisitionSet: 'masterRequisitionSetActions',
                masterRequisitionDelete: 'masterRequisitionDeleteActions',
                masterRequisitionUnFixed: 'masterRequisitionUnFixedActions',
                masterRequisitionGetDelivery: 'masterRequisitionGetDelivery',
            }),
            async confirm(){
                await this.masterRequisitionUnFixed( this.requisition.id )
                this.$router.push( '/crm/master/requisitions' )
            }
        },
        async mounted(){
            let id = window.location.href.split('/').reverse()[1];
            await this.masterRequisitionSet(id)
          //await this.masterRequisitionGetDelivery(this.$route.params.id)
          //await this.masterRequisitionDeliveryLoadList(this.$route.params.id)

            this.requisition = this.masterRequisition

            this.breadcrumb = [
                { text: 'Заявки', to: '/crm/master/requisitions' },
                { text: this.requisition.number },
            ]
        }  
    }
</script>