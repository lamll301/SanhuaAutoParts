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
        ]
    }
]

export default admin;