const admin = [
    {
        path: '/admin',
        component: () => import('@/layouts/AdminLayout.vue'),
        children: [
            {
                path: '',
                name: 'admin',
                component: () => import('@/pages/admin/AdminHome.vue'),
            },
            {
                path: "autoPart",
                name: "admin.autoParts",
                component: () => import("../pages/admin/products/ProductList.vue"),
            },
            {
                path: "autoPart/trash",
                name: "admin.autoParts.trash",
                component: () => import("../pages/admin/products/ProductList.vue"),
            },
            {
                path: "autoPart/create",
                name: "admin.autoParts.create",
                component: () => import("../pages/admin/products/ProductForm.vue"),
            },
            {
                path: "autoPart/edit/:id",
                name: "admin.autoParts.edit",
                component: () => import("../pages/admin/products/ProductForm.vue"),
            },
            // permission
            {
                path: "permission",
                name: "admin.permissions",
                component: () => import("../pages/admin/permissions/PermissionList.vue"),
            },
            {
                path: "permission/trash",
                name: "admin.permissions.trash",
                component: () => import("../pages/admin/permissions/PermissionList.vue"),
            },
            {
                path: "permission/create",
                name: "admin.permissions.create",
                component: () => import("../pages/admin/permissions/PermissionForm.vue"),
            },
            {
                path: "permission/edit/:id",
                name: "admin.permissions.edit",
                component: () => import("../pages/admin/permissions/PermissionForm.vue"),
            },
            // role
            {
                path: "role",
                name: "admin.roles",
                component: () => import("../pages/admin/roles/RoleList.vue"),
            },
            {
                path: "role/trash",
                name: "admin.roles.trash",
                component: () => import("../pages/admin/roles/RoleList.vue"),
            },
            {
                path: "role/create",
                name: "admin.roles.create",
                component: () => import("../pages/admin/roles/RoleForm.vue"),
            },
            {
                path: "role/edit/:id",
                name: "admin.roles.edit",
                component: () => import("../pages/admin/roles/RoleForm.vue"),
            },
            // user
            {
                path: "user",
                name: "admin.users",
                component: () => import("../pages/admin/users/UserList.vue"),
            },
            {
                path: "user/trash",
                name: "admin.users.trash",
                component: () => import("../pages/admin/users/UserList.vue"),
            },
            {
                path: "user/create",
                name: "admin.users.create",
                component: () => import("../pages/admin/users/UserForm.vue"),
            },
            {
                path: "user/edit/:id",
                name: "admin.users.edit",
                component: () => import("../pages/admin/users/UserForm.vue"),
            },
        ]
    }
]

export default admin;