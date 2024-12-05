<template>
    <b-row >
        <b-col cols sm="12" md="12">
            <div class="c-requisition-info-card mr-1" >
                <header>
                    <div class="title">Заявка {{ masterRequisition.number }}</div>
                    <status-requisition
                        class="ml-1"
                        :statusProps="masterRequisition.status"
                    />
                </header>
                <b-row>
                    <b-col>
                        <label class="mt-3 c-requisition-info-card__label">Менеджер</label>
                        <author-title 
                            v-if="typeof masterRequisition.manager != 'undefined'"
                            :authorProps="masterRequisition.manager"
                        />
                    </b-col>
                    <b-col>
                        <label class="mt-3 c-requisition-info-card__label">Дата создания</label>
                        <div class="c-requisition-info-card__text">{{ masterRequisition.created_at | dateFilter }}</div>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col>
                        <label v-if="masterRequisition.description" class="mt-3 c-requisition-info-card__label">Комментарий</label>
                        <div class="c-requisition-info-card__text"> 
                            {{ masterRequisition.description }} 
                        </div>
                    </b-col>
                    <b-col>
                        <label class="mt-3 c-requisition-info-card__label">Дедлайн</label>
                        <div class="c-requisition-info-card__text">{{ masterRequisition.end_at | dateFilter }}</div>
                    </b-col>
                </b-row>
                <b-row v-if="masterRequisition.specification">
                    <b-col>
                        <label class="mt-3 c-requisition-info-card__label">Спецификация</label>
                        <router-link    
                            class="c-requisition-info-card__spec"
                            :to="`/crm/master/specification/${masterRequisition.specification?.id}`"
                        >
                            {{ masterRequisition.specification?.name }}
                            ( {{ masterRequisition.specification?.objectName }} )
                        </router-link>
                    </b-col>
                </b-row>
            </div>
        </b-col>
    </b-row>
</template>

<script>
    import { mapGetters } from 'vuex';

    import AuthorTitle from '@/components/elements/AuthorTitle';
    import StatusRequisition from '@/components/requisition/StatusRequisition';
    
    export default {
        name: 'MasterRequisitionDetail',
        components: {
            AuthorTitle,
            StatusRequisition,
        },
        computed: {
            ...mapGetters({
                masterRequisition: 'masterRequisitionGetter',
            })
        },
    }
</script>