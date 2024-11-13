export default [
    {
        path: "/crm/upravlenie/specification/:id",
        name: "Specification",
        component: () => import('@/views/specifications/Specification.vue') ,
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie"],
        },
    },  
    {
        path: "/crm/upravlenie/requisitions/my",
        name: "UpravlenieMyRequisitions",
        component: () => import('@/views/upravlenie/UpravlenieMyRequisitions'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie"],
        },
    },
    {
        path: "/crm/upravlenie/requisitions/all",
        name: "UpravlenieRequisitions",
        component: () => import('@/views/upravlenie/UpravlenieRequisitions'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie"],
        },
    },
    {
        path: "/crm/upravlenie/requisition/info/:id/material",
        name: "UpravlenieRequisitionInfo",
        component: () => import('@/views/upravlenie/UpravlenieRequisitionInfo'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie"],
        },
    },
    {
        path: "/crm/upravlenie/brigade/:id",
        name: "UpravBrigades",
        component: () => import('@/views/upravlenie/UpravlenieBrigadeInfo'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie"],
        },
    }
]