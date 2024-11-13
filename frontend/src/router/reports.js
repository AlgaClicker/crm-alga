export default [
    {
        path: "/crm/reports",
        name: "Reports",
        component: () => import('@/views/documents/Reports'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie"] 
        },
        children: [
            {
                path: '/crm/documents/coming-tmc',
                name: 'Reports1',
                component: () => import('@/views/documents/ComingTMC'),
                meta: { 
                    layout: "CRMLayout", 
                    requiresAuth: true, 
                    keepAlive: true, 
                    roles: ["upravlenie"],
                },
                children: [
                    {
                        path: '/crm/documents/coming-tmc',
                        name: 'ComingTMC',
                        component: () => import('@/views/documents/ComingTMC'),
                        meta: { 
                            layout: "CRMLayout", 
                            requiresAuth: true, 
                            keepAlive: true, 
                            roles: ["upravlenie"],
                        },
                    },
                ]
            },
        ]
    }
]