<template>
    <div class="wrapper-progress-bar">
        <b-progress :value="orderedQuantityProps" :max="requiredQuantityProps" variant="success" height="0.6rem" ></b-progress>
        <div class="ml-1 procent">
            {{ procentComputed }} %
        </div>
    </div>
</template>

<script>
    export default {  
        name: 'ProgressRequisitions',
        props: {
            requiredQuantityProps:  { type: Number, default: () => 0 },
            orderedQuantityProps:  { type: Number, default: () => 0 },
        },
        computed: {
            procentComputed(){
                return Math.ceil( this.orderedQuantityProps / this.requiredQuantityProps * 100 )
            }
        }
    }
</script>

<style lang="scss">
    .wrapper-progress-bar{
        display: flex;
        align-items: center;
        .progress{
            border-radius: 1rem;
            background-color: #D6F7EC;
            width: 240px;
            .progress-bar{
                background-color: #4ED9A2;
                padding: 0;
            }
        }
        .procent{

        }   

        
    }
</style>