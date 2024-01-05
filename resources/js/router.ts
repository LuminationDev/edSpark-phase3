// Import router dependencies
import {storeToRefs} from "pinia";
import {createRouter, createWebHistory} from 'vue-router';

import AdviceForm from "@/js/components/bases/frontendform/types/AdviceForm.vue";
import EventForm from "@/js/components/bases/frontendform/types/EventForm.vue";
import SoftwareForm from "@/js/components/bases/frontendform/types/SoftwareForm.vue";
import UserPosts from "@/js/components/create/UserPosts.vue";
import AdviceSearch from "@/js/components/search/AdviceSearch.vue";
import EventSearch from "@/js/components/search/EventSearch.vue";
import HardwareSearch from "@/js/components/search/HardwareSearch.vue";
import PartnerSearch from "@/js/components/search/PartnerSearch.vue";
import SchoolSearch from "@/js/components/search/SchoolSearch.vue";
import SoftwareSearch from "@/js/components/search/SoftwareSearch.vue";
import SoftwareSingle from "@/js/components/software/softwareSingle/SoftwareSingle.vue";
import ProfileInfo from '@/js/components/userprofile/ProfileInfo.vue'
import ProfileMessages from '@/js/components/userprofile/ProfileMessages.vue'
import ProfileWork from '@/js/components/userprofile/ProfileWork.vue'
import UserProfile from '@/js/components/userprofile/UserProfile.vue';
import AdviceSingle from "@/js/pages/AdviceSingle.vue";
import EdsparkPageNotFound from "@/js/pages/EdsparkPageNotFound.vue";
import EventSingle from "@/js/pages/EventSingle.vue";
import HardwareSingle from '@/js/pages/HardwareSingle.vue';
import InspirationAndGuides from "@/js/pages/InspirationAndGuides.vue";
import PartnerSingle from "@/js/pages/PartnerSingle.vue";
import SchoolSingle from "@/js/pages/SchoolSingle.vue";
import TheAdvice from "@/js/pages/TheAdvice.vue";
import TheCreator from "@/js/pages/TheCreator.vue";
import TheForbidden from "@/js/pages/TheForbidden.vue";
import TheHardware from "@/js/pages/TheHardware.vue";
import TheHome from "@/js/pages/TheHome.vue";
import ThePartner from "@/js/pages/ThePartner.vue";
import ThePartnerWelcome from "@/js/pages/ThePartnerWelcome.vue";
import TheSchool from "@/js/pages/TheSchool.vue";
import TheSoftware from "@/js/pages/TheSoftware.vue";
import {useAuthStore} from '@/js/stores/useAuthStore';
import {useUserStore} from "@/js/stores/useUserStore";

import DashboardNew from './pages/DashboardNew.vue';


type RouteMeta = {
    requiresAuth?: boolean;
    navigation?: boolean;
    dropdownItem?: boolean;
    skipScrollTop?: boolean;
    customText?: string
};

const routes: any = [
    {
        name: 'home',
        path: '/',
        component: TheHome,
        meta: {
            requiresAuth: false, //guard the home route
        } as RouteMeta
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: DashboardNew,
        meta: {
            navigation: true,
            requiresAuth: true, //guard the dashboard route
            customText: "Home"
        } as RouteMeta
    },
    {
        name: 'create-pages',
        path: '/create',
        component: TheCreator,
        children: [
            {
                name: "userPosts",
                path: "",
                component: UserPosts,
                meta: {
                    requiresAuth: true,
                } as RouteMeta
            },
            {
                name: 'createSoftware',
                path: 'software',
                component: SoftwareForm,
                meta: {
                    requiresAuth: true,
                } as RouteMeta
            }, {
                name: 'createGuide',
                path: 'guide',
                component: AdviceForm,
                meta: {
                    requiresAuth: true,
                } as RouteMeta
            }, {
                name: 'createEvent',
                path: 'event',
                component: EventForm,
                meta: {
                    requiresAuth: true,
                } as RouteMeta
            }
        ]
    },
    {
        name: 'browse-pages',
        path: '/browse',
        children: [
            {
                name: 'browseSchools',
                path: 'school/:filter?',
                component: SchoolSearch,
                meta: {
                    requiresAuth: true,
                } as RouteMeta
            }, {
                name: 'browseAdvices',
                path: 'guide/:filter?',
                component: AdviceSearch,
                meta: {
                    requiresAuth: true,
                } as RouteMeta
            }, {
                name: 'browseSoftwares',
                path: 'software/:filter?',
                component: SoftwareSearch,
                meta: {
                    requiresAuth: true,
                } as RouteMeta
            }, {
                name: 'browseHardwares',
                path: 'hardware',
                component: HardwareSearch,
                meta: {
                    requiresAuth: true,
                } as RouteMeta
            }, {
                name: 'browsePartners',
                path: 'partner',
                component: PartnerSearch,
                meta: {
                    requiresAuth: true,
                } as RouteMeta
            }, {
                name: 'browseEvents',
                path: 'event',
                component: EventSearch,
                meta: {
                    requiresAuth: true,
                } as RouteMeta
            },
        ]
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
    name: 'school',
        path: '/school',
        component: TheSchool,
        meta: {
            navigation: true,
            requiresAuth: true,
            partnerCanAccess: false
        }
    },
    {
        name: 'guides',
        path: '/guides',
        component: InspirationAndGuides,
        meta: {
            navigation: true,
            requiresAuth: true,
            partnerCanAccess: false
        }
    },
    {
        name: 'guide-single',
        path: '/guide/resources/:id/:slug?',
        alias: '/guide/resources/:id',
        component: AdviceSingle,
        params: true,
        meta: {
            requiresAuth: true,
        }
    },
    {
        name: 'software',
        path: '/software',
        component: TheSoftware,
        meta: {
            navigation: true,
            dropdownItem: true,
            requiresAuth: true,
            partnerCanAccess: false
        }
    },
    {
        name: "software-single",
        path: "/software/resources/:id/:slug?",
        alias: "/software/resources/:id",
        component: SoftwareSingle,
        params: true,
        meta: {
            requiresAuth: true,
        }

    },
    {
        name: 'hardware',
        path: '/hardware',
        component: TheHardware,
        meta: {
            navigation: true,
            dropdownItem: true,
            requiresAuth: true,
        }
    },
    {
        name: 'hardware-single',
        path: '/hardware/resources/:id/:slug?',
        alias: '/hardware/resources/:id',
        component: HardwareSingle,
        params: true,
        meta: {
            requiresAuth: true,
        }
    },

    {
        name: 'partners',
        path: '/partners',
        component: ThePartner,
        meta: {
            navigation: true,
            requiresAuth: true,
            customText: 'Partners'
        }
    },
    {
        name: 'partner-single',
        path: '/partner/:id/:slug?',
        alias: '/partner/:id',
        component: PartnerSingle,
        meta: {
            navigation: false
        }
    },
    {
        name: 'event-single',
        path: '/event/resources/:id/:slug?',
        alias: '/event/resources/:id',
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
                name: 'userProfileInfo',
                component: ProfileInfo,
                meta: {
                    skipScrollTop: true
                }
            },
            {
                path: 'work',
                name: 'userProfileWork',
                component: ProfileWork,
                meta: {
                    skipScrollTop: true
                }
            },
            {
                path: 'messages',
                name: 'userProfileMessages',
                component: ProfileMessages,
                meta: {
                    skipScrollTop: true
                }
            }
        ],
        meta: {
            requiresAuth: true,
        }
    },
    {
        name: 'publicPartnerWelcome',
        path: '/welcome/partner',
        component: ThePartnerWelcome,
        meta:{
            skipScrollTop: false,
            requiresAuth: false
        }
    },
    {
        name: 'forbidden',
        path: '/forbidden',
        component: TheForbidden,
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: EdsparkPageNotFound
    }]

const router = createRouter({
    history: createWebHistory(),
    routes: routes,

    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else if (to.meta.skipScrollTop) {

        } else {
            return {top: 0}
        }
    }
});
const partnerRouteChecker = (to, from,next) =>{
    const userStore = useUserStore();
    if(userStore.getUserRoleName === 'SITESUPP'){ // meant to be Partner
        if(to.meta.partnerCanAccess === false){
            next('/forbidden')
        } else{
            next();
        }
    } else{
        next();
    }
}


router.beforeEach(partnerRouteChecker)
// regular authentication
router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    const userStore = useUserStore();
    const {userEntryLink} = storeToRefs(userStore)
    // it will only fill in userEntryLink if the entry link is null or not 'finished
    if(!userEntryLink.value && userEntryLink.value !== 'finished'){
        userEntryLink.value = to.fullPath
    }

    if (!to.meta.requiresAuth) {
        return next();
    }
    if (authStore.isAuthenticated instanceof Promise) {
        await authStore.isAuthenticated?.then(() => {
            if (authStore.isAuthenticated) {
                next();
            } else {
                console.log(authStore.isAuthenticated)
                window.location = '/login'
            }
        })

    } else { // authStore.isAuthenticated is boolean
        if (authStore.isAuthenticated) {
            next();
        } else {
            console.log(authStore.isAuthenticated)
            window.location = '/login'
        }
    }
    // If the route doesn't require authentication, move on.

});

export default router;
