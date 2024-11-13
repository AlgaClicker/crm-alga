<template>
    <div class="default-wrapper">
        <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
        <div class="scroll-wrapper">
            <div class="c-default-table-container">
                <div class="c-default-table-container__header">
                    Спецификации
                </div>  
                <div class="c-default-table">
                    <table>
                        <thead>
                            <tr>
                                <th class="th-sort" @click="sortRequisitions('status')"> 
                                    НАЗВАНИЕ СПЕЦИФИКАЦИИ
                                </th>
                                <th> 
                                    НАЗВАНИЕ ОБЪЕКТА
                                </th>
                                <th class="th-sort" @click="sortRequisitions('specification')"> 
                                    ДАТА СОЗДАНИЯ
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="c-default-table__body">
                        <div v-if="supplySpecificationLoading" class="c-spinner_wrapper c-empty-table">
                            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
                        </div>
                        <div 
                            v-else-if="supplySpecificationList.length == 0 | supplySpecificationList == null"
                            class="c-empty-table"
                        >
                            <img src="@/assets/images/box.png">
                            <p>
                                Нет заявок
                            </p>
                        </div>
                        <table v-else>
                            <tbody>
                                <tr v-for="item in supplySpecificationList" :key="item?.id">
                                    <td class="td-name-spec">
                                        <base-icon  v-show="item.fixed" iconProps="loock" sizeProps="md"/>
                                        <router-link    
                                            :to="`/crm/supply/specification/info/${item.id}`"
                                        >
                                            {{ item.name }}
                                        </router-link>
                                    </td>
                                    <td>{{ item.objectName }}</td>
                                    <td class="td-date">{{ item.created_at | dateFilter }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import BaseIcon from "@/components/elements/BaseIcon"

    export default {
        name: 'SupplySpecifications',
        data(){
            return {
                breadcrumb: [
                    { text: 'Спецификации', to: '/crm/supply/specifications' },
                ]  
            }
        },
        components: {
            BaseIcon
        },
        computed: {
            ...mapGetters({
                supplySpecificationList: 'supplySpecificationListGetter',
                supplySpecificationLoading: 'supplySpecificationLoadingGetter'
            })
        },
        methods: {
            ...mapActions({
                supplySpecificationSetList: 'supplySpecificationSetListActions'
            })
        },
        async mounted(){
            await this.supplySpecificationSetList()
        }
    }
</script>