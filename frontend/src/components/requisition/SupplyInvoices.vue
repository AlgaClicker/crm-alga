<template>
    <div @click="select()" class="c-supply-invoices mb-1 mt-1">
        <div class="c-supply-invoices__header">
            <div class="title">
              {{ supplyInvoicesProps.number }} от {{supplyInvoicesProps.created_at | dateOnlyFilter}}
            </div>
            <div class="price">
                {{ supplyInvoicesProps.amount | moneyFilter }}
            </div>
        </div>
        <div class="c-supply-invoices__number">
            Дата поставки {{supplyInvoicesProps.delivery_at | dateOnlyFilter}}
        </div>
      <div class="c-supply-invoices__number">
        <b-progress :max="100"  class="mb-1">
          <b-progress-bar v-if="supplyInvoicesProps.progress>0" variant="success" striped :value="supplyInvoicesProps.progress">
            <span v-if="supplyInvoicesProps.progress > 49">{{supplyInvoicesProps.progress}}% {{supplyInvoicesProps.status}}</span>
          </b-progress-bar>
          <b-progress-bar  v-if="supplyInvoicesProps.progress<100"  variant="dark"  :value="100-supplyInvoicesProps.progress">
            <span v-if="supplyInvoicesProps.progress < 50">{{supplyInvoicesProps.progress}}% {{supplyInvoicesProps.status}}</span>
          </b-progress-bar>
        </b-progress>
      </div>
    </div>
</template>

<script>
    import {dateOnlyFilter} from "@/filters/filters";

    export default {
        name: "SupplyInvoices",
        props: {
            supplyInvoicesProps:  { type: Object, default: () => {}},
        },
        emits: ['selectInvoicesEmit'],
        methods: {
          dateOnlyFilter,
            select(){
                this.$emit('selectInvoicesEmit', this.$event, this.supplyInvoicesProps)
            }
        }
    }
</script>

<style lang="scss" scoped >
    .c-supply-invoices{
        background-color: #F9F9F9;
        border: 1px solid #ECECEC;
        border-radius: 6px;
        cursor: pointer;
        padding: 0.3rem;
        &__header{
            display: flex;
            .title{

            }
            .price{
                margin-left: auto;
                color: #9D9D9D;
            }
        }
        &__number{
            color: #A4A4A4;
        }
    }
    .c-supply-invoices:hover{
        transition: 1s all;
        border: 1px solid #bcd4f8;
    }
</style>