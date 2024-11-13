<template>
   <div class="l-admin-main">
      <nav>
         <div class="c-logo">
            1
         </div>
         <div class="c-link">
            <router-link 
               class="c-link_item"
               active-class="c-link_item--active"
               :to="{ name: 'AdminCompany' }" 
            >  
               <svg width="23" height="23" ><use xlink:href="@/assets/icons/sprite.svg#build"></use></svg>
            </router-link>
            <router-link 
               class="c-link_item"
               active-class="c-link_item--active"
               :to="{ name: 'AdminAccounts' }" 
            >  
               <svg width="23" height="23" ><use xlink:href="@/assets/icons/sprite.svg#accounts"></use></svg>
            </router-link>
         </div>
         <div class="c-footer">
            <div class="c-footer_bell">
                  <svg width="18" height="24" ><use xlink:href="@/assets/icons/sprite.svg#bell"></use></svg>
            </div>
            <b-dropdown class="c-dropdown-header" size="lg"  variant="link" toggle-class="text-decoration-none" no-caret dropup offset="20">
               <template #button-content>
                  <BaseAvatar 
                     :avatarNameProps="profile.username"
                  />
               </template>
               <div class="c-dropdown-header_account">
                  <div class="ml-1 c-avatar-lg">
                     АС
                  </div>
                  <div class="c-dropdown-header_name">
                     {{ profile.username }}
                     <small>
                        {{ profile.email }}
                     </small>
                  </div>
               </div>
               <div class="c-dropdown-header_body">
                  <ul>
                     <li> 
                        <svg width="24" height="24" ><use xlink:href="@/assets/icons/sprite.svg#profile"></use></svg>
                        Профиль
                     </li>
                     <li> 
                        <svg width="24" height="24" ><use xlink:href="@/assets/icons/sprite.svg#cog-fill"></use></svg> 
                        Настройки
                     </li>
                     <li @click="() => logout()">
                        <svg width="24" height="24" ><use xlink:href="@/assets/icons/sprite.svg#logout"></use></svg> 
                        Выход
                     </li>
                  </ul>
               </div>
            </b-dropdown>
         </div>
      </nav>
      <main>
         <transition name="fade">
            <router-view/>
         </transition>
      </main>
   </div>
</template>

<script>
   import { mapActions, mapGetters } from "vuex";
   import BaseAvatar from '@/components/elements/avatars/BaseAvatar'
   
   export default {
      name: "AdminLayout",
      data(){
         return {
            isVisibleMenu: false
         }
      },
      components: {
         BaseAvatar 
      },
      computed: {
         ...mapGetters({
            profile: "profileGetter",
         }),
      },
      methods: {
         ...mapActions({ 
            logoutAction: "Logout",
            adminCompanySet: 'adminCompanySetActions' 
         }),
         showMenu(){
            this.isVisibleMenu = !this.isVisibleMenu
         },
         logout(){
            this.logoutAction()
            this.$router.push(`/login`)
         }
      },
      async mounted(){
            await this.adminCompanySet()
      }
   }

</script>

<style>
</style>