<template>
  <div class='default-wrapper'>
    <header class="default-wrapper__header">
      <b-breadcrumb :items="breadcrumb"></b-breadcrumb> 
      <div class="left-container">
        <button v-b-modal.directory-brigade-modal-add class="c-button-add mr-1">
          <b-icon icon="plus-lg" scale="1"></b-icon>
          Добавить бригаду
        </button>
        <button v-b-modal.master-brigade-modal-edit class="c-button-add">
          <b-icon icon="plus-lg" scale="1"></b-icon>
          Редактировать бригаду
        </button>
      </div>
    </header>
    <div v-if="masterBrigadesList.length > 0" class="link-wrapper">
      <div class="link-wrapper__link" v-for="(item, key) in masterBrigadesList" :key="key">
        <router-link 
          active-class='link-wrapper__link--active'
          :to="`/crm/master/brigades/info/${item.id}`"  
        >  
          {{ item.name }}
        </router-link> 
      </div>
    </div>
    <div class="scroll-wrapper mt-4">
      <div class="c-default-table-container c-default-table-container--none-background">
          <router-view></router-view>
      </div>
    </div>
    <directory-brigades-modal-add
      :mastersListProps="accountsCompanyList.filter(item => item.roles.service == 'master')"
      :specificationAllListProps="specificationAllList"
    />
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex'
  import DirectoryBrigadesModalAdd from '@/components/directory/DirectoryBrigadesModalAdd'

  export default {
    name: 'MasterBrigades',
    data() {
      return {
        breadcrumb: [
          { text: 'Бригады', href: '/crm/master/brigades' },
        ]  
      }
    },
    components: {
      DirectoryBrigadesModalAdd
    },
    computed: {
      ...mapGetters({
        masterBrigadesList: 'masterBrigadesListGetter',
        masterLoading: 'masterLoadingGetter',
        profile: 'profileGetter',
        accountsCompanyList: 'accountsCompanyListGetter',
        directoryEmployeesList: 'directoryEmployeesListGetter',
        specificationAllList: 'specificationAllListGetter',
      }),
    },
    methods: {
      ...mapActions({
        accountsCompanySet: 'accountsCompanySetActions',
        masterBrigadesSetList: 'masterBrigadesSetListActions',
        specificationSetAllList: 'specificationSetAllListActions',
        directoryEmployeesSetList: 'directoryEmployeesSetListActions',
        directoryBrigadesSetFreeEmployees: 'directoryBrigadesSetFreeEmployeesActions'
      }),
    },
    async mounted(){
      await this.masterBrigadesSetList( { idMaster: this.profile.id } )
 
      if(this.masterBrigadesList.length > 0){
        this.$router.push(`/crm/master/brigades/info/${this.masterBrigadesList[0].id}`).catch(() => {});
      }

      await this.accountsCompanySet()
      await this.directoryEmployeesSetList()
      await this.specificationSetAllList()
      await this.directoryBrigadesSetFreeEmployees()
    }
  }
</script>
