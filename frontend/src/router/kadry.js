export default [
    {
        path: "/crm/kadry/payments",
        name: "KadryBrigadesPayments",
        component: () => import('@/views/kadry/KadryBrigadesPayments'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["kadry", "upravlenie"],
        },
    },
]