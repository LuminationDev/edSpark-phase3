// Import router dependencies
import { createRouter, createWebHistory } from 'vue-router';
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
import BaseSearchPage from "@/js/components/search/BaseSearchPage.vue";
import UserProfile from  '@/js/components/userprofile/UserProfile.vue';
import HardwareSingle from '@/js/pages/HardwareSingle.vue';
import EventSingle from "@/js/pages/EventSingle.vue";

import ProfileWork from '@/js/components/userprofile/ProfileWork.vue'
import ProfileInfo  from '@/js/components/userprofile/ProfileInfo.vue'
import ProfileMessages  from '@/js/components/userprofile/ProfileMessages.vue'
import Hardware from "@/js/pages/Hardware.vue";
import SchoolSearch from "@/js/components/search/SchoolSearch.vue";
import SoftwareSearch from "@/js/components/search/SoftwareSearch.vue";
import AdviceSearch from "@/js/components/search/AdviceSearch.vue";
import HardwareLaptopSection from "@/js/components/svg/hardware/HardwareLaptopSection.vue";
import HardwareSearch from "@/js/components/search/HardwareSearch.vue";


const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            name: 'home',
            path: '/',
            component: Home,
        },
        {
            name: "admin",
            path:"/admin",
            component:DashboardNew,
            beforeEnter(to, from,next){
                window.location.href = "http://localhost:8000/admin/login"
            }
        },
        {
            name: 'dashboard',
            path: '/dashboard',
            component: DashboardNew,
            meta: {
                navigation: true
            }
        },
        // {
        //     name: 'browse-schools',
        //     path: '/browse/schools',
        //     component: BrowseSchools,
        // },
        {
            name: 'browse-pages',
            path: '/browse',
            children:[
                {
                    name: 'browseSchools',
                    path: 'schools',
                    component: SchoolSearch
                },{
                    name: 'browseAdvices',
                    path: 'advices',
                    component: AdviceSearch
                },{
                    name: 'browseSoftwares',
                    path: 'softwares',
                    component: SoftwareSearch
                },{
                    name: 'browseHardwares',
                    path: 'hardwares',
                    component: HardwareSearch
                },{
                    name: 'browseEvents',
                    path: 'events',
                    component: HardwareSearch
                },
            ]
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
            params: true,
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
            name: 'event-single',
            path: '/event/resources/:id',
            component: EventSingle,
            params: true
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
