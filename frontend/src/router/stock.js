export default [
    {
        path: "/crm/stock",
        name: "Stock",
        component: () => import('@/views/stock/Stock'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["sklad", "upravlenie" ],
        }
    },
]