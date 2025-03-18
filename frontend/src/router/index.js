import Vue from 'vue'
import VueRouter from 'vue-router'

import masterRouter from '@/router/master'
import stockRouter from '@/router/stock'
import snabzenieRouter from '@/router/supply'
import commonRouter from "@/router/common"
import objectsRouter from '@/router/objects'
import buxgalteriaRouter from '@/router/buxgalteria'
import kadryRouter from '@/router/kadry'
import upravlenieRouter from "@/router/upravlenie"
import smetaRouter from '@/router/smeta'

import notFound from '@/views/NotFound'

import store from '@/store'

Vue.use(VueRouter)

let routes = [
  {
    path:  '/',
    meta: { 
      layout: "CRMLayout", 
      requiresAuth: true,
      roles: ["upravlenie", "buxgalteriia", "master", "snabzenie", "kadry", "smeta", "sklad"],
    },
    redirect: () => {
      if(store.getters.rolesGetter == 'master'){
        return { path: '/crm/master/requisitions' }
      }
      if(store.getters.rolesGetter == 'snabzenie'){
        return { path: '/crm/supply/requisition/my' }
      }
      if(store.getters.rolesGetter == 'buxgalteriia'){
        return { path: '/crm/buxgalteria/payments' }
      }
      if(store.getters.rolesGetter == 'upravlenie'){
        return { path: '/crm/objects' }
      }  
      if(store.getters.rolesGetter == 'kadry'){
        return { path: '/crm/kadry/payments' }
      }  
      if(store.getters.rolesGetter == 'smeta'){
        return { path: '/crm/smeta'}
      }
      if(store.getters.rolesGetter == 'sklad'){
        return { path: '/crm/stock'}
      }
    }
  },
  {
    path: '/chat',
    name: 'Chat',
    component: () => import('@/views/chat/ChatView.vue'),
    meta: { 
      layout: "CRMLayout",
      requiresAuth: true,
      roles: ["upravlenie", "master", "snabzenie", "buxgalteriia", "kadry"] 
    },
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/views/auth/Login'),
    meta: { 
      layout: "EmptyLayout", 
      requiresAuth: false 
    },
  },

  {
    path: '/Registration',
    name: 'Registration',
    component: () => import('@/views/auth/Registration'),
    meta: {
      layout: "EmptyLayout",
      requiresAuth: false
    },
  },
  {
    path: '/regok',
    name: 'RegistrationConfirm',
    component: () => import('@/views/auth/RegistrationConfirm'),
    meta: {
      layout: "EmptyLayout",
      requiresAuth: false
    },
  },

  {
    path: '*',
    name: 'NotFound',
    component: notFound,
    meta: { 
      layout: "EmptyLayout", 
      requiresAuth: true, 
      keepAlive: true,  
    }
  },
]

routes = routes.concat(
  objectsRouter, 
  masterRouter,
  stockRouter,
  snabzenieRouter,
  commonRouter,
  buxgalteriaRouter,
  kadryRouter,
  upravlenieRouter,
  smetaRouter
)

const router = new VueRouter({
  mode: 'history',
  routes,
})

router.beforeEach( async (to, from, next) => {
  
  if(to.name == 'NotFound'){
    history.back()
    return  
  }

  if(to.fullPath == "/" & localStorage.getItem('token') == null){
    next('Login')
  }
  
  if(to.matched.some(record => record.meta.requiresAuth)) {
    
    let flag = false

    if(localStorage.getItem('token')){
      await store.dispatch('getProfileActions')
    } else{
      next('Login') 
      return
    }

    flag = to.matched.some(record => record.meta.roles.includes(store.getters.rolesGetter))

    if(localStorage.getItem('token') && flag) {
      next()
      return
    }
    next()
    return
    
  }else {
    
    if (to.path == '/') {
      if(localStorage.getItem('token')){
        await store.dispatch('getProfileActions')
        if(store.getters.rolesGetter == 'master'){
          next('/crm/master/brigades')
        }
        else if(store.getters.rolesGetter == 'snabzenie'){
          next('/crm/supply/requisition/my')
        }
        else if(store.getters.rolesGetter == 'buxgalteriia'){
          next('/crm/buxgalteria/payments')
        }
        else if(store.getters.rolesGetter == 'upravlenie'){
          next('/crm/objects')
        }  
        else if(store.getters.rolesGetter == 'kadry'){
          next('/crm/kadry/payments')
        } 
        else if(store.getters.rolesGetter == 'smeta'){
          return { path: '/crm/smeta'}
        }
        else if(store.getters.rolesGetter == 'sklad'){
          return { path: '/crm/stock'}
        }
        else {
          next('Login') 
        }
      } else {
        next('Login') 
        return
      }

    } 
    
    next() 
  } 

})

export default router