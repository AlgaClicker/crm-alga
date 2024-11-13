<template>
  <div class='default-wrapper'> 
    <div class="default-wrapper__header">
      <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
      <div class="left-container">
          <button v-b-modal.stock-modal-add class="c-button-add ml-1">
              <b-icon icon="plus-lg" scale="1"></b-icon>
              <span>
                  Создать 
              </span>
          </button>
      </div>
    </div>
    <div class="scroll-wrapper">
      <div class="c-default-table-container">
        <div class="c-default-table-container__header">
          Склады            
        </div> 
        <div class="c-default-table">
            <table>
              <thead>
                <tr>
                  <th> НАЗВАНИЕ </th>
                  <th> ПОЛНОЕ НАЗВАНИЕ </th>
                  <th> АДРЕС </th>
                  <th> </th>
                </tr>
              </thead>
            </table>     
            <div class="c-default-table__body">
              <div v-if="stockLoading" class="c-spinner_wrapper c-empty-table">
                  <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
              </div>
              <div 
                  v-else-if="stockList.length == 0 | stockList == null"
                  class="c-empty-table"
              >
                <img src="@/assets/images/box.png">
                <p>
                    Нет платежей
                </p>
              </div>
              <table v-else>
                <tbody>
                  <tr v-for="item in stockList" :key="item?.id">
                      <td><div class="c-contract-label" @click="selectStock(item)">{{ item.name }}</div></td>
                      <td><p>{{ item.fullname }}</p></td>
                      <td><p>{{ item?.address }}</p></td>
                      <td @click="deleteStock(item)" v-b-modal.base-delete-modal class="c-label-delete">
                          <div> Удалить </div>
                      </td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>
      </div>
    </div>
    <StockModalAdd />
    <StockModalEdit 
      :stockProps="selected"
    />
  </div>
</template>

<script>

import { mapActions, mapGetters } from 'vuex';
import StockModalAdd from '@/components/stock/StockModalAdd'
import StockModalEdit from '@/components/stock/StockModalEdit'

export default {
    name: 'Stock',
    data(){
      return {
        selected: null,
        breadcrumb: [
          { text: 'Склады', href: '' },
        ],
      }
    },
    components: {
      StockModalAdd,
      StockModalEdit
    }, 
    computed: {
      ...mapGetters({
        stockList: 'stockListGetter',
        stockLoading: 'stockLoadingGetter',
        stockOptions: 'stockOptionsGetter'
      })
    },
    methods: {
      ...mapActions({
        stockSetList: 'stockSetListActions',
        setParamsDeleteModal: 'setParamsDeleteModalActions',
      }),
      selectStock(stock){
        this.selected = stock
        this.$bvModal.show('stock-modal-edit')
      },
      deleteStock(item){
        this.setParamsDeleteModal(
            {
                title: 'Склад',
                actionsName: 'stockDeleteActions',
                data: [ item ]
            }
        )
      }
    },
    async mounted(){
      await this.stockSetList()
    }
}
</script>
