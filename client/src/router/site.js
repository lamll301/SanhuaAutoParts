const site = [
    {
        path: '/',
        component: () => import('../layouts/SiteLayout.vue'),
        children: [
            {
                path: '',
                name: 'home',
                component: () => import('../pages/site/SiteHome.vue'),
            },
            {
                path: 've-chung-toi',
                name: 'aboutUs',
                component: () => import('../pages/site/SiteHome.vue'),
            },
            {
                path: 'lien-he',
                name: 'contact',
                component: () => import('../pages/site/SiteHome.vue'),
            },
            {
                path: 'danh-muc',
                name: 'allProducts',
                component: () => import('../pages/site/ProductCategories.vue'),
            },
            {
                path: 'danh-muc/:slug',
                name: 'categoryProducts',
                component: () => import('../pages/site/ProductCategories.vue'),
            },
        ]
    }
]

export default site;