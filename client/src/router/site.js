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
                component: () => import('../pages/site/AboutUs.vue'),
            },
            {
                path: 'lien-he',
                name: 'contact',
                component: () => import('../pages/site/ContactForm.vue'),
            },
            {
                path: 'cai-dat',
                name: 'settings',
                component: () => import('../pages/site/AccountSettings.vue'),
            },
            {
                path: 'theo-doi-don-hang/:id',
                name: 'orderTrackingDetail',
                component: () => import('../pages/site/OrderTrackingDetail.vue'),
            },
            {
                path: 'theo-doi-don-hang',
                name: 'orderTracking',
                component: () => import('../pages/site/OrderTracking.vue'),
            },
            {
                path: 'don-hang',
                name: 'order',
                component: () => import('../pages/site/OrderForm.vue'),
            },
            {
                path: 'gio-hang',
                name: 'cart',
                component: () => import('../pages/site/ShoppingCart.vue'),
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
            {
                path: 'san-pham/:id',
                name: 'productDetail',
                component: () => import('../pages/site/ProductDetail.vue'),
            },
            {
                path: 'tin-tuc',
                name: 'newsList',
                component: () => import('../pages/site/NewsList.vue'),
            },
            {
                path: 'tin-tuc/:slug',
                name: 'newsDetail',
                component: () => import('../pages/site/NewsDetail.vue'),
            },
        ]
    }
]

export default site;