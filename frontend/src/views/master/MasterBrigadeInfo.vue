<template>
    <main>
        <div v-if="masterLoading" style="height: 76vh;" class="c-spinner_wrapper">
            <svg class="spinner_aj0A" width="45" height="45" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"/></svg>
        </div>
        <template v-else>
            <b-row class="mt-4 mb-4">
                <b-col>
                    <workpeople-table
                        :workpeopleProps="brigade.workpeoples"
                        :titleProps="'Сотрудники'"
                    />
                </b-col>
            </b-row>
            <b-row>
                <b-col sm="12" md="6" xl="6" class="mt-2 mb-4">
                    <income-table 
                        :incomeProps="masterBrigadeIncome"
                        :titleProps="'Поступления'"
                    />
                </b-col>
                <b-col sm="12" md="6" xl="6" class="mt-2 mb-4">
                    <specification-brigade-table
                        :specificationListProps="masterBrigadeSpecificationsList"
                    />
                </b-col>
            </b-row>
        </template>
        <master-brigades-modal-edit />
        <master-add-specifications-for-brigade
            :specificationsList=" masterSpecificationList"
        />
    </main>
</template>

<script> 
    import { mapGetters, mapActions } from 'vuex'

    import WorkpeopleTable from '@/components/elements/tables/WorkpeopleTable'
    import IncomeTable from '@/components/elements/tables/IncomeTable' 
    import MasterBrigadesModalEdit from "@/components/master/MasterBrigadesModalEdit"
    import SpecificationBrigadeTable from '@/components/elements/tables/SpecificationBrigadeTable'
    import MasterAddSpecificationsForBrigade from '@/components/master/MasterAddSpecificationsForBrigade.vue'

    export default {
        name: 'MasterBrigadeInfo',
        data(){
            return {
                brigade: {
                    workpeoples: [],
                    specifications: [],
                }
            }
        },
        components: {
            WorkpeopleTable,
            IncomeTable,
            MasterBrigadesModalEdit,
            SpecificationBrigadeTable,
            MasterAddSpecificationsForBrigade
        },
        watch: {
            '$route.params.id': {
                async handler() {
                    let id = window.location.href.split('/').reverse()[0];
                    await this.masterBrigadeSet( id )
                    
                    this.brigade = this.masterBrigade

                    await this.masterBrigadeSetSpecifications()
                    await this.directoryBrigadesSetFreeEmployees()
                }
            },
            'masterBrigade': {
                handler(){
                    this.brigade = this.masterBrigade
                }
            }
        },
        computed: {
            ...mapGetters({
                masterBrigade: 'masterBrigadeGetter',
                masterLoading: 'masterLoadingGetter',
                masterBrigadeIncome: 'masterBrigadeIncomeGetter',
                masterSpecificationList: 'masterSpecificationListGetter',
                masterBrigadeSpecificationsList: 'masterBrigadeSpecificationsListGetter'
            }),
        },
        methods: {
            ...mapActions({
                masterBrigadeSet: 'masterBrigadeSetActions',
                accountsCompanySet: 'accountsCompanySetActions',
                masterBrigadesSetList: 'masterBrigadesSetListActions',
                masterSpecificationSetList: 'masterSpecificationSetListActions',
                masterBrigadeSetSpecifications: 'masterBrigadeSetSpecificationsActions',
                directoryBrigadesSetFreeEmployees: 'directoryBrigadesSetFreeEmployeesActions',
            }),
        },
        async mounted(){
            let id = window.location.href.split('/').reverse()[0];

            await this.masterBrigadeSet( id )
            this.brigade = this.masterBrigade

            this.masterSpecificationSetList()
            await this.accountsCompanySet()
            await this.masterBrigadesSetList()

            await this.masterBrigadeSetSpecifications()
            await this.directoryBrigadesSetFreeEmployees()

        }
    }
</script>

