<template>
   <div class="l-crm">
      <header class="c-header">
         <b-icon @click="toggleNavbar()" ref="menuicon" class="c-header__menu" icon="justify" scale="1.8"></b-icon>
         <div class="c-header__wrapper-left">
            <span v-html="iconLogo" ></span>
         </div>
         <div class="c-header__wrapper-right">
            <div class="c-bell">
               <b-dropdown 
                  :active="true" 
                  ref="dropdown"
                  class="dropdown-notification"
                  variant="link" 
                  toggle-class="text-decoration-none" 
                  no-caret 
                  left
               >
                  <template #button-content>
                     <span 
                        v-show="notificationListNew.length > 0" 
                        class="c-bell__badge"
                     >
                     </span>
                     <span class="icon-bell" v-html="iconBell"></span>
                  </template>
                  <div class="c-notifications-dialog">
                     <div class="c-notifications-dialog__header">
                        <b-icon 
                           icon="x" 
                           scale="1.6"
                           class="icon"
                           @click="closeNotifications"
                        >
                        </b-icon>
                     </div>
                     <div @scroll="scrollEvent" ref="list" class="c-notifications-dialog__messages">
                        <notifications-dialog-item 
                           v-for="(item, key) in notificationListNew" :key="key"
                           :notificationProps="item"
                        /> 
                     </div>
                  </div>
               </b-dropdown>
            </div>
  
            <div class="c-button-profile ml-1" >
               <div class="c-button-profile__wrapper">
                  <div class="c-button-profile__user-info">
                     <template v-if="isSetEmployeesComputed">
                        <div class='c-username'>{{ profile.workpeople.name }} {{ profile.workpeople.surname }}</div>
                        <small>
                           {{ profile.workpeople.profession.name }}
                        </small>  
                     </template>
                     <template v-else>
                        <div class='c-username'>{{ profile.username }}</div>
                        <small>
                           {{ profile.email }}
                        </small>     
                     </template>
                  </div>
                  <b-icon 
                     class="c-button-profile__chevron c-button-profile__chevron--active"
                     icon="chevron-down"
                     font-scale="0.65" 
                  ></b-icon>
                  <b-icon 
                     class="c-button-profile__chevron"
                     icon="chevron-up"
                     font-scale="0.65" 
                  ></b-icon>
               </div>
               
               <ul class="c-button-profile__drop-down" id="profile-drop-down">
                  <div class="header">
                     <b-icon icon="building"></b-icon>
                     <span class="title">
                        {{ profile.company.name }}
                     </span>
                  </div>
                  <li>
                     <span v-html="iconGear"></span>
                     <router-link 
                        :to="{ name: 'Profile' }" 
                     >  
                        Настройки профиля
                     </router-link> 
                  </li>
                  <li  @click="() => logout()" class="option-logout">
                     <span v-html="iconLogout"></span>
                     <div>
                        Выход
                     </div>
                  </li>
               </ul>
            </div>
         </div>
      </header>

      <div class="c-wrapper">
         <nav ref="navbar" v-show="isVisibleNavbar" class="c-navbar">
            <div class="c-navbar-links">
               <div v-for="(item, key) in links" :key="key">
                  <router-link 
                     v-if="!item.children"
                     class="c-navbar-links_link"
                     active-class='c-navbar-links_link--active'
                     :to="{ name: item.to }" 
                  >  
                     <span class='c-navbar-links_mark'></span>
                     <div class="icon-wrapper">
                        <span v-html="item.svg"></span>
                     </div>
                     <p>
                        {{ item.name }}
                     </p>
                  </router-link>

                  <template v-else>
                     <children-navbar
                        :childrensProps="item"
                     />
                  </template>
               </div>
               <div>
                  <router-link
                     active-class='c-chat-navbar-link--active'
                     class="c-chat-navbar-link"
                     :to="{ name: 'Chat' }" 
                  >
                     <span class='c-navbar-links_mark'></span>
                     <div class="icon-wrapper" >
                        <span v-html="iconChat"></span>
                        <span class="badge"></span>
                     </div>
                     <p>Чат</p>
                  </router-link>
               </div>
            </div>
         </nav>
         <main class="c-main">
            <transition name="fade">
               <router-view/>
            </transition>
            <!--<div 
               ref="dialog"
               class="c-notifications-dialog"
            >
               <div class="c-notifications-wrap">
                  <div class="c-notifications-wrap__header">
                     <span @click="toggleNotifications" v-html="iconX"></span>
                  </div>
                  <div 
                     ref="list"
                     @scroll="scrollEvent($event)" 
                     class="c-notifications-wrap__messages"
                  >
                     <NotificationsDialogItem 
                        v-for="(item, key) in notificationListNew" :key="key"
                        :notificationProps="item"
                     />
                  </div>
               </div>
            </div> -->
         </main>
      </div>
   </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import { dropDownNavbar } from '@/utils/dropDown'

import { linksList } from '@/utils/linksRoleList'
import { svgs } from '@/utils/svgList'
import { ColseDropDown } from '@/utils/dropDown'
//import BaseIcon from "@/components/elements/BaseIcon"
import ChildrenNavbar from '@/components/navbar/ChildrenNavbar'
import NotificationsDialogItem from '@/components/notifications/NotificationsDialogItem'

export default {
   name: "CRMLayout",
   data() {
      return {
         isVisibleNavbar: true,
         iconBell: '',
         iconX: '',
         iconLogo: '',
         iconChat: '',
         iconGear:'',
         iconLogout: '',
         links: {},
         dialogHeight: 0,
         startOffset: 0,
         start: 0,
         end: null,
      }
   },
   computed: {
      ...mapGetters({
         profile: "profileGetter",
         objectsList: "objectsListGetter",
         networkStatus: 'networkStatusGetter',
         notificationListNew: 'notificationListNewGetter'
      }),
      isSetEmployeesComputed(){
         return typeof this.profile.workpeople != 'undefined' 
      }
   },
   components: {
      //BaseIcon,
      ChildrenNavbar,
      NotificationsDialogItem
   },
   methods: {
      ...mapActions({ 
         exit: "logoutActions", 
         getObjects: "getObjectsActions",
         notificationSet: 'notificationSetActions',
         notificationPagginate: 'notificationPagginateActions'
      }),
      toggleNavbar(){
         this.$refs.navbar.classList.toggle('c-navbar--is-visible')
      },
      async scrollEvent() {
         if(this.$refs.list.scrollTop == 0){
            var oldLengthList = this.notificationListNew.length
            await this.notificationPagginate()
            if(oldLengthList < this.notificationListNew.length ){
               this.$refs.list.scrollTop = this.$refs.list.scrollTop + 600
            }
         }
                   
      },
      closeNotifications(){
         this.$refs.dropdown.hide(true)
         this.dialogHeight = this.$refs.list.clientHeight
         this.$refs.list.scrollTop = this.$refs.list.scrollTop + 500 
      },
      newObjectRedirect(){
         this.$router.push('/crm/object-new')
      },
      logout(){
         this.exit()
         this.$router.push(`/Login`)
      }
   },
   mounted() {
      ColseDropDown()
      this.links = linksList(this.profile.roles.service)

      this.iconBell = svgs.bell.md
      this.iconX = svgs.x.md
      this.iconLogo = svgs.logo.md
      this.iconChat = svgs.chat.md
      this.iconGear = svgs.gear.md
      this.iconLogout = svgs.logout.md

      this.notificationSet()

      this.start = 0;
      this.end = this.start + this.visibleCount;

      dropDownNavbar()
   }
}
</script>

<style>
</style>