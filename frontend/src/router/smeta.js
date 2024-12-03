export default [
    {
        path: '/crm/smeta',
        name: 'Smeta',
        component: () => import('@/views/smeta/Smeta'),
        meta: {
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "smeta"] 
        }
    },
]