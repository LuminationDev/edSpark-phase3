// Import router dependencies
import EdsparkPageNotFound from "@/js/pages/EdsparkPageNotFound.vue";
import SchoolSingle from "@/js/pages/SchoolSingle.vue";
import TheAdvice from "@/js/pages/TheAdvice.vue";
import TheCommunity from "@/js/pages/TheCommunity.vue";
import TheEvent from "@/js/pages/TheEvent.vue";
import TheForbidden from "@/js/pages/TheForbidden.vue";
import TheHardware from "@/js/pages/TheHardware.vue";
import TheHome from "@/js/pages/TheHome.vue";
import ThePartner from "@/js/pages/ThePartner.vue";
import TheSchool from "@/js/pages/TheSchool.vue";
import TheSoftware from "@/js/pages/TheSoftware.vue";
import {createRouter, createWebHistory} from 'vue-router';


import DashboardNew from './pages/DashboardNew.vue';
import AdviceSingle from "@/js/pages/AdviceSingle.vue";
import SoftwareSingle from "@/js/components/software/softwareSingle/SoftwareSingle.vue";
import UserProfile from '@/js/components/userprofile/UserProfile.vue';
import HardwareSingle from '@/js/pages/HardwareSingle.vue';
import EventSingle from "@/js/pages/EventSingle.vue";

import ProfileWork from '@/js/components/userprofile/ProfileWork.vue'
import ProfileInfo from '@/js/components/userprofile/ProfileInfo.vue'
import ProfileMessages from '@/js/components/userprofile/ProfileMessages.vue'
import SchoolSearch from "@/js/components/search/SchoolSearch.vue";
import SoftwareSearch from "@/js/components/search/SoftwareSearch.vue";
import AdviceSearch from "@/js/components/search/AdviceSearch.vue";
import HardwareSearch from "@/js/components/search/HardwareSearch.vue";
import PartnerSingle from "@/js/pages/PartnerSingle.vue";
import PartnerSearch from "@/js/components/search/PartnerSearch.vue";
import EventSearch from "@/js/components/search/EventSearch.vue";

import {useUserStore} from "@/js/stores/useUserStore";
import {useAuthStore} from './stores/useAuthStore';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            name: 'home',
            path: '/',
            component: TheHome,
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
            children: [
                {
                    name: 'browseSchools',
                    path: 'school',
                    component: SchoolSearch,
                    meta: {
                        requiresAuth: true,
                    }
                }, {
                    name: 'browseAdvices',
                    path: 'advice',
                    component: AdviceSearch,
                    meta: {
                        requiresAuth: true,
                    }
                }, {
                    name: 'browseSoftwares',
                    path: 'software/:filter?',
                    component: SoftwareSearch,
                    meta: {
                        requiresAuth: true,
                    }
                }, {
                    name: 'browseHardwares',
                    path: 'hardware',
                    component: HardwareSearch,
                    meta: {
                        requiresAuth: true,
                    }
                }, {
                    name: 'browsePartners',
                    path: 'partner',
                    component: PartnerSearch,
                    meta: {
                        requiresAuth: true,
                    }
                }, {
                    name: 'browseEvents',
                    path: 'event',
                    component: EventSearch,
                    meta: {
                        requiresAuth: true,
                    }
                },
            ]
        },
        {
            name: 'schools',
            path: '/schools',
            component: TheSchool,
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
            component: TheAdvice,
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
            component: TheSoftware,
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
            component: TheHardware,
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
            component: TheCommunity,
            meta: {
                navigation: false,
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
            component: TheEvent,
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
            name: 'forbidden',
            path: '/forbidden',
            component: TheForbidden,
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
        } else if (to.meta.skipScrollTop) {

        } else {
            return {top: 0}
        }
    },
});

router.afterEach((to, from) => {
    if (!['home', 'login', 'forbidden'].includes(to.name)) {
        const userStore = useUserStore()
        userStore.populateUserLikesAndBookmark()
    }
})

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    if (to.meta.requiresAuth) {
        if (!authStore.isAuthenticated) {
            await authStore.checkAuthenticationStatus();
        }

        if (authStore.isAuthenticated) {
            next();
        } else {
            next('/forbidden');
        }
    } else {
        next();
    }
});

export default router;
