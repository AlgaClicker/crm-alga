<template>
    <div class="status-invoices" :style="{ backgroundColor: statusColor, color: textColor }">
        {{ statusText }}
    </div>
</template>

<script>
    export default {
        name: 'StatusRequisition',
        props: {
            statusProps:  { type: String, default: () => 'completed' },
        },
        computed: {
            statusColor(){
                if(this.statusProps == 'completed'){
                    return '#E2FBE8'
                } else {
                    return '#E1E7FD'
                }
            },
            statusText(){
                if(this.statusProps == 'completed'){
                    return 'Доставлено'
                } else {
                    return 'Не пришло'
                }
            },
            textColor(){
                if(this.statusProps == 'completed'){
                    return '#2D5D38'
                } else {
                    return '#4CA3FC'
                }
            }
        }
    }
</script>

<style>
    .status-invoices{
        cursor: pointer;
        border-radius: 6px;
        font-size: 0.85rem;
        padding: 2px;
        padding-top: 4px;
        padding-bottom: 4px;
        text-align: center;
        width: 140px;
    }
</style>