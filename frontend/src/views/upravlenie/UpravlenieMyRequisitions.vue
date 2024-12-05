<template>
    <div class="default-wrapper">
        <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
        <div class="scroll-wrapper">
            Мои заявки
        </div>
    </div>
</template>
<script>
    export default {
        name: "UpravlenieMyRequisitions",
        data(){
            return {
                breadcrumb: [
                    { text: 'Заявки', to: '/crm/upravlenie/requisitions/my' },
                ],
            }
        }
    }
</script>