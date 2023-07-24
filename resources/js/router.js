// Import router dependencies
import { createRouter, createWebHistory } from 'vue-router';
// Import pages
import {
    Home,
    Schools,
    Advice,
    Software,
    Community,
    Partners,
    Events,
    SchoolSingle,
    UserMessage,
    Forbidden,
    EdsparkPageNotFound,
} from './pages';
import DashboardNew from './pages/DashboardNew.vue';
import AdviceSingle from "@/js/pages/AdviceSingle.vue";
import SoftwareSingle from "@/js/components/software/softwareSingle/SoftwareSingle.vue";
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
import HardwareSearch from "@/js/components/search/HardwareSearch.vue";
import {useUserStore} from "@/js/stores/useUserStore";
import axios from 'axios';
import {appURL, serverURL} from "@/js/constants/serverUrl";
import { useAuthStore } from './stores/useAuthStore';
import PartnerSingle from "@/js/pages/PartnerSingle.vue";
import PartnerSearch from "@/js/components/search/PartnerSearch.vue";
import EventSearch from "@/js/components/search/EventSearch.vue";


const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            name: 'home',
            path: '/',
            component: Home,
            meta: {
                requiresAuth: false, //guard the home route
            }
        },
        {
            name: 'dashboard',
            path: '/dashboard',
            component: DashboardNew,
            meta: {
                navigation: true,
                requiresAuth: true, //guard the dashboard route
            }
        },
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
                    name: 'browsePartners',
                    path: 'partners',
                    component: PartnerSearch
                },{
                    name: 'browseEvents',
                    path: 'events',
                    component: EventSearch
                },
            ]
        },
        {
            name: 'schools',
            path: '/schools',
            component: Schools,
            meta: {
                navigation: true,
                requiresAuth: true,
            }
        },
        {
            name: 'school-single',
            path: '/schools/:name',
            component: SchoolSingle,
            meta: {
                requiresAuth: true,
            }
        },
        {
            name: 'advice',
            path: '/advice',
            component: Advice,
            meta: {
                navigation: true,
                requiresAuth: true,
            }
        },
        {
            name: 'advice-single',
            path: '/advice/resources/:id',
            component: AdviceSingle,
            params: true,
            meta: {
                requiresAuth: true,
            }
        },
        {
            name: 'software',
            path: '/software',
            component: Software,
            meta: {
                navigation: true,
                dropdownItem: true,
                requiresAuth: true,
            }
        },
        {
            name: "software-single",
            path: "/software/resources/:id",
            component: SoftwareSingle,
            params: true,
            meta: {
                requiresAuth: true,
            }

        },
        {
            name: 'hardware',
            path: '/hardware',
            component: Hardware,
            meta: {
                navigation: true,
                dropdownItem: true,
                requiresAuth: true,
            }
        },
        {
            name: 'hardware-single',
            path: '/hardware/resources/:id',
            component: HardwareSingle,
            params: true,
            meta: {
                requiresAuth: true,
            }
        },
        {
            name: 'community',
            path: '/community',
            component: Community,
            meta: {
                navigation: false,
                requiresAuth: true,
            }
        },
        {
            name: 'partners',
            path: '/partners',
            component: Partners,
            meta: {
                navigation: true,
                requiresAuth: true,
            }
        },
        {
            name: 'partner-single',
            path: '/partner/:id',
            component: PartnerSingle,
            meta: {
                navigation: false
            }
        },
        {
            name: 'events',
            path: '/events',
            component: Events,
            meta: {
                navigation: true,
                requiresAuth: true,
            }
        },
        {
            name: 'event-single',
            path: '/event/resources/:id',
            component: EventSingle,
            params: true,
            meta: {
                requiresAuth: true,
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
            ],
            meta: {
                requiresAuth: true,
            }
        },
        {
            name: 'userMessage',
            path: '/message/:userId',
            component: UserMessage,
            meta: {
                requiresAuth: true,
            }
        },
        {
            name: 'forbidden',
            path: '/forbidden',
            component: Forbidden,
        },
        {
            path: '/:pathMatch(.*)*',
            name: 'not-found',
            component: EdsparkPageNotFound
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
// router.afterEach((to,from) =>{
//     if(!['home','login'].includes(to.name)){
//         const userStore= useUserStore()
//         console.log('inside if inside router aftereach')
//         userStore.populateUserLikesAndBookmark()
//     }
// })

//TODO using auth store
// router.beforeEach(async (to, from, next) => {
//     const authStore = useAuthStore();
//     if (to.meta.requiresAuth) {
//         if (!authStore.isAuthenticated) {
//             await authStore.checkAuthenticationStatus();
//         }
//
//         if (authStore.isAuthenticated) {
//             next();
//         } else {
//             next('/forbidden');
//         }
//     } else {
//         next();
//     }
// });

//working code
// router.beforeEach((to, from, next) => {
//     if(to.meta.requiresAuth) {
//         // console.log('REQUIRES AUTHENTICATION');
//         try {
//             // Make a request to laravel backend for authentication check
//             const response = axios.get(`${appURL}/auth/check`, { async: false });
//             if (response.data.authenticated) {
//                 next();
//             } else {
//                 // Redirect to the 403 forbidden page
//                 next('/forbidden');
//             }
//         } catch (error) {
//             // Handle error
//             //TODO show an error page
//             console.log(error);
//         }

//     } else {
//         // console.log("DOESNOT REQUIRES AUTHENTICATION");
//         next();
//     }
// });

export default router;
