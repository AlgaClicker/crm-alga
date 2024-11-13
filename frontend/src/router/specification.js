export default [
    {
        path: "/crm/specification/:id",
        name: "Specification",
        component: () => import('@/views/specifications/Specification.vue') ,
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["master", "upravlenie", "snabzenie"],
        },
        
    }
]

