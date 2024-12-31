const admin = [
    {
        path: '/admin',
        component: () => import('@/layouts/AdminLayout.vue'),
        children: [
            {
                path: '',
                name: 'admin',
                component: () => import('@/pages/admin/AdminHome.vue'),
            }
        ]
    }
]

export default admin;