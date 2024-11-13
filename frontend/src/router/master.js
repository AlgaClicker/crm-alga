export default [
    {
        path: "/crm/master/brigades",
        name: "MasterBrigades",
        component: () => import('@/views/master/MasterBrigades'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["master", "kadry", "upravlenie"],
        },
        children: [
            {
                path: '/crm/master/brigades/info/:id',
                name: 'MasterBrigadeInfo',
                component: () => import('@/views/master/MasterBrigadeInfo'),
                meta: {
                    layout: "CRMLayout", 
                    requiresAuth: true, 
                    keepAlive: true, 
                    roles: ["master", "upravlenie"],
                },
            }
        ]
    },
    {
        path: "/crm/master/requisitions",
        name: "MasterRequisitions",
        component: () => import('@/views/master/MasterRequisitions'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["master","upravlenie"],
        },
    },
    {
        path: "/crm/master/requisition/info/:id",
        name: "MasterRequisition",
        component: () => import('@/views/master/requisionDetail/MasterRequisition'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["master","upravlenie"],
        },
        children: [
            {
                path: "detail",
                name: "MasterRequisitionDetail",
                component: () => import('@/views/master/requisionDetail/MasterRequisitionDetail'),
                meta: { 
                    layout: "CRMLayout", 
                    requiresAuth: true, 
                    keepAlive: true, 
                    roles: ["master" ,"upravlenie"],
                },
            },
            {
                path: "material",
                name: "MasterRequisionMaterial",
                component: () => import('@/views/master/requisionDetail/MasterRequisitionMaterial'),
                meta: { 
                    layout: "CRMLayout", 
                    requiresAuth: true, 
                    keepAlive: true, 
                    roles: ["master" ,"upravlenie"],
                },
            },
            {
                path: "deliverys",
                name: "MasterRequisitionDeliverys",
                component: () => import('@/views/master/requisionDetail/MasterRequisitionDeliverys'),
                meta: { 
                    layout: "CRMLayout", 
                    requiresAuth: true, 
                    keepAlive: true, 
                    roles: ["master" ,"upravlenie"],
                },
            },
            {
                path: "chat",
                name: "RequisitionChat",
                component: () => import('@/views/chat/RequisitionChat'),
                meta: { 
                    layout: "CRMLayout", 
                    requiresAuth: true, 
                    keepAlive: true, 
                    roles: ["master" ,"upravlenie"],
                },
            },
        ]
    },

    {
        path: "/crm/master/specifications",
        name: "MasterSpecifications",
        component: () => import('@/views/master/MasterSpecifications'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["master", "upravlenie", "snabzenie"],
        },
    },
    {
        path: "/crm/master/specification/:id",
        name: "MasterSpecification",
        component: () => import('@/views/specifications/master/MasterSpecification'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["master", "upravlenie", "snabzenie"],
        },
    },
]