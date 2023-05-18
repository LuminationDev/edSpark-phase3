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
    UserMessage
} from './pages';
import DashboardNew from './pages/DashboardNew.vue';
import BrowseSchools from "@/js/pages/BrowseSchools.vue";
import AdviceSingle from "@/js/pages/AdviceSingle.vue";
import SoftwareSingle from "@/js/components/software/softwareSingle/SoftwareSingle.vue";
import BaseSearchPage from "@/js/components/bases/BaseSearchPage.vue";
import UserProfile from  '@/js/components/userprofile/UserProfile.vue';
import HardwareSingle from '@/js/pages/HardwareSingle.vue';

import ProfileWork from '@/js/components/userprofile/ProfileWork.vue'
import ProfileInfo  from '@/js/components/userprofile/ProfileInfo.vue'
import ProfileMessages  from '@/js/components/userprofile/ProfileMessages.vue'



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
            component: DashboardNew
            // component: Dashboard
            // component: LoginCallback
        },
        {
            name: 'browse-schools',
            path: '/browse/schools',
            component: BrowseSchools,
        },
        {
            name: 'browse-pages',
            path: '/browse/:type',
            component: BaseSearchPage,
        },
        {
            name: 'schools',
            path: '/schools',
            component: Schools,
        },
        {
            name: 'school-single',
            path: '/schools/:name',
            component: SchoolSingle
        },
        {
            name: 'advice',
            path: '/advice',
            component: Advice
        },
        {
            name: 'advice-single',
            path: '/advice/resources/:id',
            component: AdviceSingle,
            params: true
        },
        {
            name: 'software',
            path: '/software',
            component: Software
        },
        {
            name: "software-single",
            path: "/software/resources/:id",
            component: SoftwareSingle,
            params: true

        },
        {
            name: 'hardware',
            path: '/hardware',
            component: Hardware
        },
        {
            name: 'hardware-single',
            path: '/hardware/resources/:id',
            component: HardwareSingle,
            params: true
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
            component: UserProfile,
            children: [
                {
                    path: '',
                    name:'userProfileInfo',
                    component: ProfileInfo
                },
                {
                    path: 'work',
                    name: 'userProfileWork',
                    component: ProfileWork
                },
                {
                    path: 'messages',
                    name:'userProfileMessages',
                    component: ProfileMessages
                }
        ]
        },
        {
            name: 'userMessage',
            path: '/message/:userId',
            component: UserMessage
        },
        {
            path: '/login/callback',
            name: 'login',
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

router.beforeEach(navigationGuard);

export default router;
