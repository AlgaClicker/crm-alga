<template>
    <div class="c-master-requisitions-detail">
        <div class="c-master-requisitions-detail__header">
            <base-icon iconProps="stock" sizeProps="md" />
            <div class="title-wrapper">

                <div class="title-wrapper__date">
                    {{ deliveryProps.delivery_at | dateFilter }}
                </div>
                <div class="title-wrapper__stock-address">
                    {{ deliveryProps.stock.name }}
                </div>
            </div>
        </div>
        <div class="c-master-requisitions-detail__footer mt-2">
            <div class="status">
                Заказанно {{ deliveryProps.quantity }} {{ deliveryProps.unitTitle }}
            </div>
            <button>
                Подтвердить
            </button>
        </div>
    </div>
</template>

<script>
    import BaseIcon from "@/components/elements/BaseIcon"

    export default {
        name: 'MasterRequisitionDelivery',
        props: {
            deliveryProps:  { type: Object, default: () => {} },
            quantityProps: { type: String, default: "" }
        },
        components: {
            BaseIcon
        }
    }
</script>

<style lang="scss">
    .c-master-requisitions-detail{
        border: 1px solid #ECECEC;
        background-color: #F9F9F9;
        border-radius: 6px;
        padding: 1rem;
        width: 100%;
        &__header{
            display: flex;
            align-items: center;
            .title-wrapper{
                margin-left: 0.8rem;
                &__date{

                }
                &__stock-address{
                    font-size: 0.9rem;
                    color: #5D5E60;
                }
            }
        }
        &__footer{
            display: flex;
            .status{
                display:inline-block;
                position: relative;
                min-width: auto;
                border: 1px solid #70CC74;
                background-color: #DFF0E0;
                border-radius: 8px;
                color: #70CC74;
                text-align: center;
                padding: 0.2rem;
                padding-left: 0.6rem;
                padding-right: 0.6rem;
            }

            button{
                border: none;
                border-radius: 7px;
                background-color: #3d95fb;
                color: #fff;
                cursor: pointer;
                margin-left: auto;
                padding: 0.4rem;
                font-size: 0.8rem;
            }
            button:hover{

            }
        }
    }
</style>