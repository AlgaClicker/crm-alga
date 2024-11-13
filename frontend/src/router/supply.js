export default [
    {
        path: "/crm/supply/requisition/free",
        name: "SupplyRequisitionsFree",
        component: () => import('@/views/supply/SupplyRequisitionsFree'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "snabzenie" ],
        }
    },
    {
        path: "/crm/supply/requisition/my",
        name: "SupplyRequisitionsMy",
        component: () => import('@/views/supply/SupplyRequisitionsMy'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "snabzenie" ],
        }
    },
    {
        path: "/crm/supply/requisition/my/info/:id",
        name: "SupplyRequisition",
        component: () => import('@/views/supply/requisitionMyDetail/SupplyRequisition'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["snabzenie" ,"upravlenie"],
        },
        children: [
            {
                path: "detail",
                name: "SupplyRequisitionDetail",
                component: () => import('@/views/supply/requisitionMyDetail/SupplyRequisitionDetail'),
                meta: { 
                    layout: "CRMLayout", 
                    requiresAuth: true, 
                    keepAlive: true, 
                    roles: ["snabzenie" ,"upravlenie"],
                },
            },
            {
                path: "material",
                name: "SupplyRequisionMaterial",
                component: () => import('@/views/supply/requisitionMyDetail/SupplyRequisitionMaterial'),
                meta: { 
                    layout: "CRMLayout", 
                    requiresAuth: true, 
                    keepAlive: true, 
                    roles: ["snabzenie" ,"upravlenie"],
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
                    roles: ["snabzenie" ,"upravlenie", "master"],
                },
            }

        ]
    },
    {
        path: "/crm/supply/requisition/free/info/:id",
        name: "SupplyFreeRequisition",
        component: () => import('@/views/supply/requisitionFreeDetail/SupplyFreeRequisition'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["snabzenie", "upravlenie"],
        },
        children: [
            {
                path: "detail",
                name: "SupplyFreeRequisitionDetail",
                component: () => import('@/views/supply/requisitionFreeDetail/SupplyFreeRequisitionDetail'),
                meta: { 
                    layout: "CRMLayout", 
                    requiresAuth: true, 
                    keepAlive: true, 
                    roles: ["snabzenie" ,"upravlenie"],
                },
            },
            {
                path: "material",
                name: "SupplyRequisionMaterial",
                component: () => import('@/views/supply/requisitionFreeDetail/SupplyFreeRequisitionMaterial'),
                meta: { 
                    layout: "CRMLayout", 
                    requiresAuth: true, 
                    keepAlive: true, 
                    roles: ["snabzenie" ,"upravlenie"],
                },
            },
        ]
    },
    {
        path: "/crm/supply/requisition/deliverys",
        name: "SupplyDeliverys",
        component: () => import('@/views/supply/SupplyDeliverys'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["snabzenie" ,"upravlenie"],
        },
    },
    {
        path: "/crm/supply/requisition/invoices",
        name: "SupplyRequisitionsInvoices",
        component: () => import('@/views/supply/SupplyRequisitionsInvoices'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["snabzenie" ,"upravlenie"],
        },
    },
    {
        path: "/crm/supply/specifications",
        name: "SupplySpecifications",
        component: () => import('@/views/supply/SupplySpecifications'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "snabzenie"],
        },
    },
    {
        path: "/crm/supply/specification/info/:id",
        name: "SupplySpecification",
        component: () => import('@/views/specifications/supply/SupplySpecification'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "snabzenie"],
        },
    },
]