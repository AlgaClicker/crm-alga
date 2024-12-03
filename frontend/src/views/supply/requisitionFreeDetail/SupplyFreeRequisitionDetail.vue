<template>
    <b-row>
        <b-col sm="12" md="6">
            <div class="c-requisition-info-card">
                <header>
                    <div class="title">Звявка {{ supplyMyRequisition.number }}</div>
                    <status-requisition
                        class="ml-1"
                        :statusProps="supplyMyRequisition.status"
                    />
                </header>
                <b-row>
                    <b-col>
                        <label class="mt-3 c-requisition-info-card__label">Автор</label>
                        <div class="c-requisition-info-card__text">
                            <author-title 
                                :authorProps="supplyMyRequisition.autor"
                            />
                        </div>
                    </b-col>
                    <b-col>
                        <label class="mt-3 c-requisition-info-card__label">Дата создания</label>
                        <div class="c-requisition-info-card__text">{{ supplyMyRequisition.created_at | dateFilter }}</div>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col>
                        <label class="mt-3 c-requisition-info-card__label">Комментарий</label>
                        <div class="c-requisition-info-card__text"> 
                            {{ supplyMyRequisition.description}}
                        </div>
                    </b-col>
                    <b-col>
                        <label class="mt-3 c-requisition-info-card__label">Дедлайн</label>
                        <div class="c-requisition-info-card__text">{{ supplyMyRequisition.end_at | dateFilter }}</div>
                    </b-col>
                </b-row>
                <b-row v-show="typeof supplyMyRequisition?.specification != 'undefined'">
                    <b-col>
                        <label class="mt-3 c-requisition-info-card__label">Спецификация</label>
                        <router-link    
                            class="c-requisition-info-card__spec"
                            :to="`/crm/master/specification/${supplyMyRequisition.specification?.id}`"
                        >
                            {{ supplyMyRequisition.specification?.name }}
                            ( {{ supplyMyRequisition.specification?.objectName }} )
                        </router-link>
                    </b-col>
                </b-row>
            </div>
        </b-col>
    </b-row>
</template>

<script>
    import { mapGetters } from 'vuex'

    import AuthorTitle from '@/components/elements/AuthorTitle';
    import StatusRequisition from '@/components/requisition/StatusRequisition';

    export default {
        name: "SupplyFreeRequisitionDetail",
        components: {
            AuthorTitle,
            StatusRequisition 
        },
        computed: {
            ...mapGetters({
                supplyMyRequisition: 'supplyMyRequisitionGetter'
            })
        },
        
    }
</script>