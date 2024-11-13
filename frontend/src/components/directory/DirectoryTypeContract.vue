<template>
    <div class="type-contract" :style="{ backgroundColor: statusColor, color: textColor }">
        {{ statusText }}
    </div>
</template>

<script>
    export default {
        name: 'DirectoryTypeContract',
        props: {
            typeProps: { type: String, default: "" }
        },
        computed: {
            statusColor(){
                if(this.typeProps == 'construction'){
                    return '#F6E9FD'
                } else if(this.typeProps == 'supply'){
                    return '#DBEDFE'
                } else {
                    return '#D7F8FD'
                } 
            },
            statusText(){
                if(this.typeProps == 'construction'){
                    return 'Подряда'
                } else if(this.typeProps == 'supply'){
                    return 'Поставки'
                } else {
                    return '#D7F8FD'
                } 
            },
            textColor(){
                if(this.typeProps == 'construction'){
                    return '#7A2183'
                } else if(this.typeProps == 'supply'){
                    return '#4CA3FC'
                } else {
                    return '#28586C'
                } 
            }
        }
    }
</script>

<style>
    .type-contract{
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