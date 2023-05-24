// Import router dependencies
import { createRouter, createWebHistory } from 'vue-router';

// Import pages
// const Welcome = () => import('../components/Welcome.vue');
import { LoginCallback, navigationGuard } from '@okta/okta-vue'
import {
    Home,
    Schools,
    Advice,
    Software,
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
import Hardware from "@/js/pages/Hardware.vue";


const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            name: 'home',
            path: '/',
            component: Home,
        },
        {
            name: 'dashboard',
            path: '/dashboard',
            component: DashboardNew,
            meta: {
                navigation: true
            }
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
            meta: {
                navigation: true
            }
        },
        {
            name: 'school-single',
            path: '/schools/:name',
            component: SchoolSingle
        },
        {
            name: 'advice',
            path: '/advice',
            component: Advice,
            meta: {
                navigation: true
            }
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
            component: Software,
            meta: {
                navigation: true,
                dropdownItem: true
            }
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
            component: Hardware,
            meta: {
                navigation: true,
                dropdownItem: true
            }
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
            component: Community,
            meta: {
                navigation: false
            }
        },
        {
            name: 'partners',
            path: '/partners',
            component: Partners,
            meta: {
                navigation: true
            }
        },
        {
            name: 'events',
            path: '/events',
            component: Events,
            meta: {
                navigation: true
            }
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
