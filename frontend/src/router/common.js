export default [
    {
        path: '/crm/profile',
        name: 'Profile',
        component: () => import('@/views/user/Profile'),
        meta: {
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "master", "snabzenie", "kadry", "buxgalteriia"] 
        }
    },
    {
        path: '/crm/directory/materials',
        name: 'DirectoryMaterials',
        component: () => import('@/views/directory/DirectoryMaterials'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "master", "snabzenie"] 
        },
    },
    {
        path: "/crm/directory/banks",
        name: "DirectoryBanks",
        component: () => import('@/views/directory/DirectoryBanks'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "master", "snabzenie"] 
        }
    },
    {
        path: "/crm/directory/partners",
        name: "DirectoryPartners",
        component: () => import('@/views/directory/DirectoryPartners'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "master", "snabzenie"] 
        }
    },
    {
        path: "/crm/directory/partners/view/:id",
        name: "DirectoryPartnerInfo",
        component: () => import('@/views/directory/DirectoryPartnerInfo'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "master", "snabzenie"] 
        }
    },
    {
        path: "/crm/directory/employees",
        name: "DirectoryEmployees",
        component: () => import('@/views/directory/DirectoryEmployees'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "master", "snabzenie", "kadry"] 
        }
    },
    {
        path: "/crm/directory/posts",
        name: "DirectoryPosts",
        component: () => import('@/views/directory/DirectoryPosts'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "master", "snabzenie", "kadry"] 
        }
    },
    {
        path: "/crm/directory/accounts",
        name: "DirectoryAccounts",
        component: () => import('@/views/directory/DirectoryAccounts'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "master", "snabzenie"] 
        }
    },
    {
        path: "/crm/directory/brigades",
        name: "DirectoryBrigades",
        component: () => import('@/views/directory/DirectoryBrigades'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "master", "snabzenie"] 
        }
    },
    {
        path: "/crm/directory/contracts",
        name: "DirectoryContracts",
        component: () => import('@/views/directory/DirectoryContracts'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles: ["upravlenie", "master", "snabzenie"] 
        }
    },
    {
        path: "/crm/documents",
        name: "Documents",
        component: () => import('@/views/documents/Documents'),
        meta: { 
            layout: "CRMLayout", 
            requiresAuth: true, 
            keepAlive: true, 
            roles:  ["upravlenie", "master", "snabzenie", "kadry", "buxgalteriia"] 
        },
    }
]
