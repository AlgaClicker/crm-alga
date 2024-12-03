export default [
    {
        path: "/crm/objects",
        name: "Objects",
        component: () => import('@/views/objects/Objects'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie"] 
        },
    },
    {
        path: '/crm/objects/view/:id',
        name: 'ObjectsInfo',
        component: () => import('@/views/objects/ObjectsInfo'),
        meta: {
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie"],
        },
    },
]