import { createRouter, createWebHistory } from 'vue-router';
import admin from './admin.js';
import site from './site.js';

const routes = [
    ...admin,
    ...site,
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: () => import('@/pages/NotFound.vue')
    }
];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    // scrollBehavior(to, from, savedPosition) {
    //     if (savedPosition) {
    //         return savedPosition;
    //     }
    //     return { top: 0 };
    // },
    routes
});

export default router;
