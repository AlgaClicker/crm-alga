<template>
   <div class="l-empty">
      <div v-show="!networkStatus" class="c-network_error"> Переподключение... </div>
      <router-view/>
   </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    name: "EmptyLayout",
    computed: {
      ...mapGetters({
         networkStatus: 'networkStatusGetter',
      }),
  },
}
</script>

<style>
</style>