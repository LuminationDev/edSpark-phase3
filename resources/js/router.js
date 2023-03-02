// Import router dependencies
import { createRouter, createWebHistory } from 'vue-router';

// Import pages
// const Welcome = () => import('../components/Welcome.vue');

import {
    Home,
    Dashboard,
    Schools,
    Advice,
    Software,
    Hardware,
    Community,
    Partners,
    Events
} from './pages'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            name: 'home',
            path: '/',
            component: Home
        },
        {
            name: 'dashboard',
            path: '/dashboard',
            component: Dashboard
        },
        {
            name: 'schools',
            path: '/schools',
            component: Schools
        },
        {
            name: 'advice',
            path: '/advice',
            component: Advice
        },
        {
            name: 'software',
            path: '/software',
            component: Software
        },
        {
            name: 'hardware',
            path: '/hardware',
            component: Hardware
        },
        {
            name: 'community',
            path: '/community',
            component: Community
        },
        {
            name: 'partners',
            path: '/partners',
            component: Partners
        },
        {
            name: 'events',
            path: '/events',
            component: Events
        },
    ]
});

export default router;
