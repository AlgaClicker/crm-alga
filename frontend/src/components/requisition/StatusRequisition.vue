<template>
    <div class="c-status-requisition" :style="{ backgroundColor: statusColor, color: textColor }">
        {{ statusText }}
    </div>
</template>

<script>
    export default {
        name: 'StatusRequisition',
        props: {
            statusProps:  { type: String, default: () => 'draft' },
        },
        computed: {
            statusColor(){
                if(this.statusProps == 'new'){
                    return '#F6E9FD'
                } else if(this.statusProps == 'manage'){
                    return '#DBEDFE'
                } else if(this.statusProps == 'inprogress'){
                    return '#D7F8FD'
                } else if(this.statusProps == 'agreement'){
                    return '#FDEED9'
                } else if(this.statusProps == 'completed'){
                    return '#E2FBE8'
                } else if(this.statusProps == 'canceled'){
                    return '#FAC4C4'
                } else {
                    return '#E1E7FD'
                }
            },
            statusText(){
                if(this.statusProps == 'new'){
                    return 'Не рассмотрена'
                } else if(this.statusProps == 'manage'){
                    return 'Взята в работу'
                } else if(this.statusProps == 'inprogress'){
                    return 'В процессе'
                } else if(this.statusProps == 'agreement'){
                    return 'Согласование'
                } else if(this.statusProps == 'completed'){
                    return 'Завершена'
                } else if(this.statusProps == 'canceled'){
                    return 'Отменена'
                } else {
                    return 'Черновик'
                }
            },
            textColor(){
                if(this.statusProps == 'new'){
                    return '#7A2183'
                } else if(this.statusProps == 'manage'){
                    return '#4CA3FC'
                } else if(this.statusProps == 'inprogress'){
                    return '#28586C'
                } else if(this.statusProps == 'agreement'){
                    return '#BF5A29'
                } else if(this.statusProps == 'completed'){
                    return '#2D5D38'
                } else if(this.statusProps == 'canceled'){
                    return '#C61A1A'
                } else {
                    return '#343394'
                }
            }
        }
    }
</script>
