// Import router dependencies
import { createRouter, createWebHistory } from 'vue-router';

// Import pages
// const Welcome = () => import('../components/Welcome.vue');
import { LoginCallback, navigationGuard } from '@okta/okta-vue'
import {
    Home,
    Dashboard,
    Schools,
    Advice,
    Software,
    Hardware,
    Community,
    Partners,
    Events,
    SchoolSingle,
    UserProfile
} from './pages'
import BrowseSchools from "@/js/pages/BrowseSchools.vue";
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
            // component: LoginCallback
        },
        {
            name: 'browse-schools',
            path: '/browse/schools',
            component: BrowseSchools,
        },
        {
            name: 'schools',
            path: '/schools',
            component: Schools,
        },
        {
            name: 'schoolSingle',
            path: '/schools/:name',
            component: SchoolSingle
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
        {
            name: 'userProfile',
            path: '/profile/:userId',
            component: UserProfile
        },
        {
            path: '/login',
            component: LoginCallback
        },
        // {
        //     name: 'login',
        //     path: '/login',
        //     component: Login
        // }
    ],
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { top: 0 }
        }
    },
});

// router.beforeEach(navigationGuard);

export default router;
