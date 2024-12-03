<template>
    <div class="c-default-table-container">

      <div v-if="onload" style="height: 200px" class="px-4 c-spinner_wrapper c-empty-table">
        Загрузка
        <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
      </div>

      <div class="accordion master-accordion-delivery" role="tablist" v-if="!onload" >
        <b-card no-body class="mb-1 my-2 my-3  shadow-sm border-2 rounded-0" v-for="(item, num) in masterRequisitionDeliveryList" :key="item.id">
          <b-card-header header-class="row p-0 m-0 accordion-header card-delivery-master" header-tag="header" class="p-1" role="tab" @click="thisSelectdDelivery(item)">
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
            <b-card-body class="p-0 m-3">
              <RequisitionDeliveryConfirmed :delivery="item" :key="item.id">
              </RequisitionDeliveryConfirmed>
            </b-card-body>
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
    import RequisitionDeliveryConfirmed from "@/components/requisition/MasterRequisitionDeliveryConfirmed.vue"
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
            MasterRequisitionModalDelivery,
            RequisitionDeliveryConfirmed
        },
        computed: {
            ...mapGetters({
                masterRequisitionDeliveryList: 'masterRequisitionDeliveryListGetter',
                masterRequisitionDeliveryLoading: 'masterRequisitionDeliveryLoadingGetter',

            }),

        },
        methods: {
          confirmHandleOk(id) {
            console.log("================confirmHandleOk===============")
            console.log(id)
            console.log(this.cofirmed_list)
            this.cofirmed_list.forEach((data,idx)=> {
              console.log("=========== "+ idx +" ====confirmHandleOk:forEach=============")
              console.log(data)
            })
          },
          deliveryMaterials(delivery,material)  {
            console.log(delivery,material)
            return delivery.id
          },
          async onConfirmed(deliveryId)  {
            console.log(this.cofirmed_list)
            console.log("deliveryId",deliveryId)
            //let delivery =  this.masterRequisitionDeliveryList
            //delivery = delivery.find((item) => item.id == deliveryId)
            //console.log("delivery",delivery.materials)
            //await this.masterRequisitionGetDeliveryOne({deliveryId:this.cofirmed_list[deliveryId].id})
            //await this.masterRequisitionDeliveryConfirmed()
            //this.reloadData()
          },
          async reloadData() {

            this.onload = true
            await this.masterRequisitionGetDelivery(this.$route.params.id)
            await this.masterRequisitionDeliveryLoadList(this.$route.params.id)

            this.initConfirmedList();
            this.onload =  !this.onload

          },
          delivery_confirm_ref() {
            console.log("delivery_confirm_ref")
          },
          thisSelectdDelivery(delivery) {
            console.log("thisSelectdDelivery",delivery)

          },
          initConfirmedList() {
            /*
            this.masterRequisitionDeliveryList.forEach(item => {
              item.materials.forEach(material => {
                this.cofirmed_list[material.requisition_material_id] = material.delivery_quantity || 0;
              });
            });

             */
          },
          dateOnlyFilter,
            ...mapActions({
                masterRequisitionSet: 'masterRequisitionSetActions',
                masterRequisitionDeliverySetList: 'masterRequisitionDeliverySetListActions',
                masterRequisitionGetDelivery: 'masterRequisitionGetDelivery',
                masterRequisitionDeliveryLoadList: 'masterRequisitionDeliveryLoadList',
                masterRequisitionDeliveryConfirmed: 'masterRequisitionDeliveryConfirmed',
                masterRequisitionGetDeliveryOne: 'masterRequisitionGetDeliveryOne'
            }),
            selectDelivery(delivery){
                this.selectdDelivery = delivery
                this.$bvModal.show('master-requisition-modal-delivery')
            }
        },
        async mounted() {
          await this.reloadData()
        },
    }
</script>