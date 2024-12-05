<template :id="this.delivery.id">
  <div>
    <table class="table rounded-0 table-sm table-striped master-table-delivery table-bordered"  >
      <thead>
      <tr>
        <th scope="col ">#</th>
        <th scope="col" class="text-center center-block align-middle" style="min-width: 10rem; width: 50%;">Материал</th>
        <th scope="col" class="text-center align-middle" style="max-width: 6rem;min-width: 5rem;">Кол-во <br> по заявке</th>
        <th scope="col" class="text-center align-middle" style="max-width: 6rem">Всего <br> по поставке</th>
        <th scope="col" class="text-center align-middle" style="max-width: 6rem">Всего <br> поставлено</th>
        <th scope="col" class="text-center align-middle"  style="max-width: 6rem">Всего <br> подтверждено</th>
        <th scope="col" class="text-center align-middle" style="max-width: 6rem">Поставили</th>
      </tr>
      </thead>
      <tbody>
      <tr  v-for="(material, nmat) in delivery.materials" :key="material.requisition_material_id">
        <th scope=" px-2" class="align-middle">{{nmat+1}}</th>
        <td class="col text-start" style="min-width: 10rem">{{material.requisition_material.directory_material.name}}</td>
        <td class="col text-center align-middle">{{material.requisition_material.quantity}}</td>
        <td class="col text-center align-middle">{{material.delivery_quantity }}</td>
        <td class="col text-center align-middle">{{material.remnant_quantity }}</td>
        <td class="col text-center align-middle">{{material.confirmed_quantity }}</td>
        <td class="col text-center align-middle " v-if="material.delivery_quantity - material.confirmed_quantity <= 0">
            100%
        </td>
        <td class="col text-center align-middle" v-else>
          <input  type="number" min="0"  v-model="materials[material.requisition_material_id]" @input="inputCountConfirm(material.requisition_material_id)">
          <!--
          <input  type="number"  placeholder="ПРИШЛО"  v-model="cofirmed_list[item.id][material.requisition_material_id]"  @input="() => { if(cofirmed_list[item.id][material.requisition_material_id] > material.delivery_quantity || cofirmed_list[item.id][material.requisition_material_id] < 0) { cofirmed_list[item.id][material.requisition_material_id] = material.delivery_quantity }}"  :id="item.id" :key="item.id" style="width: 8rem" class="text-center center mx-input center" >
          -->
        </td>
      </tr>
      </tbody>
      <tfoot>
      <tr>
        <td colspan="7" class="col text-end " >

          
          <b-button :disabled="!btnConfirmActive" class="c-button  shadow px-3 rounded-0 " @click="appalyConfirmed(delivery.id)">
            Подтвердить
          </b-button>

        </td>
      </tr>
      </tfoot>
    </table>

    <b-modal id="modal-1" title="Коментарий к подтверждению" class="confirm-modal" dialog-class="custom-modal-size" hide-backdrop content-class="shadow"  size="sm" centered  button-size="sm">
      <div class="mx-4">
       <label for="validationTooltip05" class="form-label"> Частичная поставка, коментарий:</label>
        <textarea v-model="confirm_description" rows="4"  id="validationTooltip05" cols="10" placeholder="Коментарий подтверждения поставки"  class="w-75 form-control center" required></textarea>
        <div class="text-danger"  v-if="description_error">{{description_error}}</div>
      </div>
      <template #modal-footer>
        <b-button class=" c-button-cancel rounded-0 m-3" variant="secondary" @click="$bvModal.hide('modal-1')">Отмена</b-button>
        <b-button  class=" c-button rounded-0 mb-2" variant="primary" @click="applayConfirmedSend">Подтвердить</b-button>
      </template>
    </b-modal>
  </div>
</template>

<style>
.modal-dialog {
  max-width: 50% !important; /* Применится ко всем модалкам */
}
</style>
<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: "RequisitionDeliveryConfirmed",
  props: {
    delivery: { }
  },
  emits: [
      'applayConfirmed'
  ],
  computed: {
    ...mapGetters({
      masterRequisitionDeliveryListConfirmedMaterials: "masterRequisitionDeliveryListConfirmedMaterials",
      masterRequisitionDeliveryGetActual:"masterRequisitionDeliveryGetActual"
    }),

    listtest() {
        return this.materials
    },
    deliveryMaterials() {
      return this.delivery.materials
    }
  },
  data() {
    return {
      materials: {},
      confirm_full: false,
      confirm_description: null,
      description_error: "",
      actualDeliveryId:"",
      btnConfirmActive: true
    }
  },
  mounted()  {
    console.log("=======================" + this.delivery.delivery_at + "==========delivery",this.delivery.id,"===========================")

    Object.entries(this.delivery.materials).forEach(material => {
      let key =  material[1].requisition_material_id
      let confirm_count = material[1].delivery_quantity
      //let comfirm_material = new Object()
      //this.materials = Object.assign(this.materials, { "sdf":"sdfsdf"})
      /*
      this.materials = {
        ...this.materials,
        "sdf":"sdfsdf"
      }
      */

      //Object.assign(this.materials,Object.prototype.propertyIsEnumerable.call(key, confirm_count))
      console.log("mounted",this.materials,key,confirm_count,material[1])

      console.log("materials:",this.materials)


        //Object.defineProperties(this.materials, key)
      //this.materials.add('requisition_material_id', material[1].requisition_material_id)

    })
  },
  methods:  {
    ...mapActions({
      masterRequisitionDeliveryConfirmed: "masterRequisitionDeliveryConfirmed",
      masterRequisitionDeliveryListConfirmedMaterialsActions: 'masterRequisitionDeliveryListConfirmedMaterialsActions',
      masterRequisitionDeliverySetActualActions:"masterRequisitionDeliverySetActualActions"
    }),
    inputCountConfirm(d_id) {

      let div_material = this.delivery.materials.find(material => material.requisition_material_id === d_id)
      if (this.materials[d_id] >=div_material.delivery_quantity) {
        if (parseInt(div_material.confirmed_quantity) > 0) {
          this.materials[d_id] = parseInt(div_material.delivery_quantity) - parseInt(div_material.confirmed_quantity)
          if (this.materials[d_id] === 0) {
            this.materials[d_id] = div_material.delivery_quantity
          }
        } else {
          this.materials[d_id] = parseInt(div_material.delivery_quantity)  ?? " "
        }
      }
      if (this.materials[d_id] == "") {
        this.materials[d_id] = " "
      }

      if (this.materials[d_id] == 0) {
        return
      }

      if (parseInt(this.materials[d_id]) <= 0) {
        this.materials[d_id] = "1"
      }
    },

    async appalyConfirmed(deliveryId) {
      this.btnConfirmActive = false
      await this.masterRequisitionDeliverySetActualActions(deliveryId)
      this.actualDeliveryId = deliveryId
      Object.entries(this.materials).forEach(([material_confirm, material_count]) => {
        let req_material = this.delivery.materials.find(material => material.requisition_material_id === material_confirm)

        if (material_count >= req_material.delivery_quantity) {
          this.confirm_full = true;
        } else {
          this.confirm_full = false;
        }
        console.log("forEach materials",material_count,req_material.delivery_quantity,this.confirm_full)
      })

  //    console.log("appalyConfirmed|map-end",this.materials)
       await this.masterRequisitionDeliveryListConfirmedMaterialsActions(this.materials)
      if (this.confirm_full == false) {

        console.log("this.confirm_full = false:",this.materials)
        this.$root.$emit('bv::show::modal', 'modal-1', '#focusThisOnClose')

      } else {
        this.confirm_description = this.delivery.id
        await this.applayConfirmedSend()
      }

    },

    async applayConfirmedSend()  {
      console.log("applayConfirmedSend| this.materials",this.masterRequisitionDeliveryListConfirmedMaterials)
      console.log(this.actualDeliveryId)
      if (!this.confirm_description) {
        this.description_error = "Пустой коментарий"
        return
        //this.$root.$emit('bv::show::modal', 'modal-1', '#focusThisOnClose')
      } else {
        this.$root.$emit('bv::hide::modal', 'modal-1', '#focusThisOnClose')
      }

      let data_materials = Object.entries(this.masterRequisitionDeliveryListConfirmedMaterials).map(([material_confirm, material_count]) => {
        console.log("data_materials:map", material_confirm)

        return {
          "confirmed_at": new Date(),
          "requisition_material": material_confirm,
          "quantity": material_count,
          "description": this.confirm_description,
          "files": []
        }

      })

      console.log("data_materials",this.masterRequisitionDeliveryListConfirmedMaterials)

      let data = {
          "materials": data_materials,
          "requisitionId": this.$route.params.id,
          "deliveryId" : this.masterRequisitionDeliveryGetActual
      }
      //console.log("req_id",this.$route.params.id)
      console.log("applayConfirmedSend DATA:",data)
       await this.masterRequisitionDeliveryConfirmed(data)
       //this.$emit( 'applayConfirmed', this.$event)
      /*
      Object.entries(this.materials).forEach(material => {
        data= {
          "material":material
        }
      })
      */


      /*
      var data = {
          "confirmed_at": new Date(),
          "requisition_material": "",
          "quantity": "",
          "description": this.confirm_description,
          "files": []
      }
      */


      console.log("applayConfirmedSend",data)
      this.btnConfirmActive = true
      this.$emit( 'applayConfirmed', this.$event)
    }
  }
}
</script>

<style>
.confirm-modal .modal-dialog {
  max-width: 50%; /* Установите нужный процент ширины */
  margin: auto; /* Центрирование */
}
</style>