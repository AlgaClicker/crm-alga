<template>
    <div @click="select()" class="c-supply-invoices mb-1 mt-1">
        <div class="c-supply-invoices__header">
            <div class="title">
                Счет
            </div>
            <div class="price">
                {{ supplyInvoicesProps.amount | moneyFilter }}
            </div>
        </div>
        <div class="c-supply-invoices__number">
            {{ supplyInvoicesProps.number }}
        </div>
    </div>
</template>

<script>
    export default {
        name: "SupplyInvoices",
        props: {
            supplyInvoicesProps:  { type: Object, default: () => {}},
        },
        emits: ['selectInvoicesEmit'],
        methods: {
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