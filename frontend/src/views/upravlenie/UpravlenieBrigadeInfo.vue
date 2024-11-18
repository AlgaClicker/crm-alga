<<<<<<< HEAD
<template>
    <div class='default-wrapper pr-2'>
        <div class="default-wrapper__header">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb> 
            <div class="left-container">
                <button v-b-modal.master-brigade-modal-edit class="ml-1 c-button-add">
                    <b-icon icon="plus-lg" scale="1"></b-icon>
                    Редактировать бригаду
                </button>
            </div>
        </div>
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
        <master-brigades-modal-edit />
        <master-add-specifications-for-brigade
            :specificationsList=" masterSpecificationList"
        />
    </div>
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
                },
                breadcrumb: [
                    { text: 'Бригады', href: '/crm/directory/brigades' },
                ]
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
                directoryEmployeesGet: 'directoryEmployeesGetActions',
                directoryBrigadesSetFreeEmployees: 'directoryBrigadesSetFreeEmployeesActions',
                masterBrigadeSetSpecifications: 'masterBrigadeSetSpecificationsActions',
                accountsCompanySet: 'accountsCompanySetActions',
                masterSpecificationSetList: 'masterSpecificationSetListActions'
            }),
        },
        async mounted(){
            let id = window.location.href.split('/').reverse()[0];

            await this.masterBrigadeSet( id )
            this.brigade = this.masterBrigade

            this.masterSpecificationSetList()
            await this.accountsCompanySet()
            await this.directoryEmployeesGet()

            await this.masterBrigadeSetSpecifications()
            await this.directoryBrigadesSetFreeEmployees()

            this.breadcrumb.push(
                {   
                    text: this.brigade.name, 
                    href: `/crm/upravlenie/brigade/${id}` 
                },
            )

        }
    }
</script>
=======
<template>
    <div class='default-wrapper pr-2'>
        <div class="default-wrapper__header default-wrapper__header--non-padding">
            <b-breadcrumb :items="breadcrumb"></b-breadcrumb> 
            <div class="left-container">
                <button v-b-modal.master-brigade-modal-edit class="c-button-add">
                    <b-icon icon="plus-lg" scale="1"></b-icon>
                    Редактировать бригаду
                </button>
            </div>
        </div>
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
        <master-brigades-modal-edit />
        <master-add-specifications-for-brigade
            :specificationsList=" masterSpecificationList"
        />
    </div>
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
                },
                breadcrumb: [
                    { text: 'Бригады', to: '/crm/directory/brigades' },
                ]
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
                directoryBrigadesSetFreeEmployees: 'directoryBrigadesSetFreeEmployeesActions',
                masterBrigadeSetSpecifications: 'masterBrigadeSetSpecificationsActions',
                accountsCompanySet: 'accountsCompanySetActions',
                masterSpecificationSetList: 'masterSpecificationSetListActions'
            }),
        },
        async mounted(){
            let id = window.location.href.split('/').reverse()[0];

            await this.masterBrigadeSet( id )
            this.brigade = this.masterBrigade

            this.masterSpecificationSetList()
            await this.accountsCompanySet()

            await this.masterBrigadeSetSpecifications()
            await this.directoryBrigadesSetFreeEmployees()

            this.breadcrumb.push(
                {   
                    text: this.brigade.name, 
                    href: `/crm/upravlenie/brigade/${id}` 
                },
            )

        }
    }
</script>
>>>>>>> feature/f_requisitions_master
