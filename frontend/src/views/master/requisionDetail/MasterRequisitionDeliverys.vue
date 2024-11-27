<template>
    <div class="c-default-table-container">

      <div v-if="onload" style="height: 200px" class="px-4 c-spinner_wrapper c-empty-table">
        Загрузка
        <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
      </div>

      <div class="accordion master-accordion-delivery" role="tablist" v-if="!onload">
        <b-card no-body class="mb-1 my-2 my-3  shadow-sm border-2 rounded-0" v-for="item, num in masterRequisitionDeliveryList" :key="item.id">
          <b-card-header header-class="row p-0 m-0 accordion-header card-delivery-master" header-tag="header" class="p-1" role="tab" >
                <div class="col-5 px-2 p-0 m-0 "  data-bs-toggle="collapse" v-b-toggle="['accordion-'+item.id+'']">
                  <use xlink:href="@/assets/icons/list-delivery.svg"></use>
                  #{{num+1}}
                   Поставка от {{item.delivery_at | dateOnlyFilter}}</div>
                <div class="col-1  p-0 m-0 " >
                </div>
                <div class="col-6  p-0 m-0 text-end  px-3" >
                  <button class=" rounded-0 shadow px-4 m-0 c-button" v-b-toggle="['accordion-'+item.id+'']" :key="item.id"> Обзор поставки</button>
                </div>
          </b-card-header>


          <b-collapse :id="'accordion-'+item.id" accordion="my-accordion" visible class="accordion-collapse collapse show "   role="tabpanel">
            <b-card-body class="p-0 m-0">
                <table class="table rounded-0 table-sm table-striped master-table-delivery"  >
                  <thead>
                  <tr>
                    <th scope="col ">#</th>
                    <th scope="col" style="min-width: 10rem">Материал</th>
                    <th scope="col">Всего по заявке</th>
                    <th scope="col">Всего по поставке</th>
                    <th scope="col">Всего поставлено</th>
                    <th scope="col">Всего подтверждено</th>
                    <th scope="col">Поставили</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr  v-for="material, nmat in item.materials" :key="material.requisition_material_id">
                    <th scope="row">{{nmat+1}}</th>
                    <td class="col text-start" style="min-width: 10rem">{{material.requisition_material.directory_material.name}}</td>
                    <td class="col text-center">{{material.requisition_material.quantity}}</td>
                    <td class="col text-center">{{material.delivery_quantity }}</td>
                    <td class="col text-center">{{material.remnant_quantity }}</td>
                    <td class="col text-center">{{material.confirmed_quantity }}</td>
                    <td class="col text-center">
                      <input type="text"   v-model="material.delivery_quantity"  :key="item.id" style="width: 8rem" class="text-center mx-input center" >
                    </td>
                  </tr>
                  </tbody>
                </table>
            </b-card-body>
            <b-card-footer class="text-end py-2 m-0">
                  <b-button class="c-button  shadow " @click="onConfirmed(item.id)">
                    Подтвердить
                  </b-button>
            </b-card-footer>
          </b-collapse>
        </b-card>
      </div>

        <!--
        <div class="c-supply-requisition-material__table-wrapper mt-2">

            <div v-if="masterRequisitionDeliveryLoading" style="height: 300px" class="px-4 c-spinner_wrapper c-empty-table">
                <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
            </div>
            <div v-else class="c-supply-requisition-material-table">
                <table>
                    <thead>
                        <tr>
                            <th width="30%">Дата</th>
                            <th>Склад</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
                <div class='c-supply-requisition-material-table__body'>
                    <table>
                        <tbody>
                            <tr v-for="item in masterRequisitionDeliveryList" :key="item.id">
                                <td width="30%">{{  item.delivery_at | dateFilterWithoutTime }}</td>
                                <td>{{  }}</td>
                                <td>
                                    <status-delivery 
                                        :statusProps="item.status"
                                    />
                                </td>
                                <td @click="selectDelivery(item)">
                                    <div class="info">
                                        Информация
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        -->
        <master-requisition-modal-delivery 
            :deliveryProps="selectdDelivery"
        />
    </div>  
</template>
<style>
.master-table-delivery   {
  background-color: #0D4F00;
}


</style>

<script>
    import { mapActions, mapGetters } from 'vuex'
    //import StatusDelivery from '@/components/requisition/StatusDelivery.vue'
    import MasterRequisitionModalDelivery from '@/components/requisition/MasterRequisitionModalDelivery'
    import {dateOnlyFilter} from "@/filters/filters";

    export default {
        name: "MasterRequisitionDeliverys",
        data(){
            return {
                selectdDelivery: {},
                cofirmed_list: [],
                material:[],
                onload: true
            }
        },
        components: {
          //  StatusDelivery,
            MasterRequisitionModalDelivery
        },
        computed: {
            ...mapGetters({
                masterRequisitionDeliveryList: 'masterRequisitionDeliveryListGetter',
                masterRequisitionDeliveryLoading: 'masterRequisitionDeliveryLoadingGetter',

            })
        },
        methods: {
          onConfirmed(id_delivery){
            console.log(id_delivery)
                console.log(this.cofirmed_list)

          },
          dateOnlyFilter,
            ...mapActions({
                masterRequisitionSet: 'masterRequisitionSetActions',
                masterRequisitionDeliverySetList: 'masterRequisitionDeliverySetListActions',
                masterRequisitionGetDelivery: 'masterRequisitionGetDelivery',
              masterRequisitionDeliveryLoadList: 'masterRequisitionDeliveryLoadList'
            }),
            selectDelivery(delivery){
                this.selectdDelivery = delivery
                this.$bvModal.show('master-requisition-modal-delivery')
            }
        },
        async mounted() {
          this.onload = true
            //await this.masterRequisitionSet(this.$route.params.id)
            await this.masterRequisitionGetDelivery(this.$route.params.id)
            await this.masterRequisitionDeliveryLoadList(this.$route.params.id)
            //await this.masterRequisitionDeliverySetList()
          this.onload =  !this.onload
        },
    }
</script>