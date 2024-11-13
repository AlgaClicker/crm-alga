export default [
    {
        path: '/crm/buxgalteria/payments',
        name: 'PaymentsView',
        component: () => import('@/views/buxgalteria/Payments'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["buxgalteriia", "upravlenie"] 
        },
    },
    {
        path: '/crm/buxgalteria/payments/info/:id',
        name: 'PaymentsInfo',
        component: () => import('@/views/buxgalteria/PaymentsInfo'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["buxgalteriia", "upravlenie"] 
        },
    }
]

//buxalter
//buxalter