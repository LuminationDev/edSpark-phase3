<script setup>
import {storeToRefs} from "pinia";
import {computed, onBeforeUnmount, onMounted, ref} from 'vue';
import {useRouter} from 'vue-router';

import ProfileDropdown from "@/js/components/global/ProfileDropdown.vue";
import Logo from '@/js/components/svg/Logo.vue';
import Search from "@/js/components/svg/Search.vue";
import {useAuthStore} from '@/js/stores/useAuthStore';
import {useUserStore} from '@/js/stores/useUserStore';
import {useWindowStore} from "@/js/stores/useWindowStore";

import NavItems from './NavItems.vue';

const router = useRouter();
const userStore = useUserStore()
const authStore = useAuthStore();
const windowStore = useWindowStore();

const {currentUser} = storeToRefs(userStore);
const {isAuthenticated} = storeToRefs(authStore);
const {showGlobalSearch} = storeToRefs(windowStore);

const navLinks = ref([]);

const avatarUrl = computed(() => {
    const meta = currentUser.value?.metadata?.find(m => m.user_meta_key === 'userAvatar');
    return meta ? meta.user_meta_value[0].replace(/\\\//g, "/") : '';
});

const handleGlobalsearchClick = () => {
    showGlobalSearch.value = true
}

const setupRoutes = () => {
    const tempNavArray = [];
    const currentUserIsPartner = userStore.getUserRoleName === 'SITESUPP' // TODO
    router.options.routes.forEach(route => {
        if (Object.keys(route).includes('meta') && route.meta['navigation']) {
            if (currentUserIsPartner && Object.keys(route.meta).includes('partnerCanAccess')) {
                if (route.meta['partnerCanAccess']) {
                    tempNavArray.push(route);
                }
            } else { // no partnerCanAccess meta here
                tempNavArray.push(route)
            }
        }
    });
    navLinks.value = tempNavArray;
};
setupRoutes();
const scrollPosition = ref(0);

const setScrollPosition = () => {
    scrollPosition.value = window.scrollY
}


onMounted(() => {
    window.addEventListener('scroll', setScrollPosition)
})

onBeforeUnmount(() => {
    window.removeEventListener('scroll', setScrollPosition)
})

const logoClass = computed(() => {
    return {
        'absolute nav-logo transition-all -top-4 w-28 h-28 z-30': true,
        'md:!w-36 md:!h-36 lg:left-10 lg:visible xl:!w-44 xl:!h-44': scrollPosition.value === 0,
        '!w-14 !h-14 !top-1 !left-12': scrollPosition.value > 0,
    };
})
</script>

<template>
    <div class="h-[84px] hidden relative w-full z-50 lg:block">
        <nav
            v-if="isAuthenticated"
            id="navbarFullsize"
            :class="{ 'navbarScrolled' : scrollPosition > 0 }"
            class="bg-white container h-16 hidden navbarFullsize px-12 text-main-darkGrey  lg:block lg:z-20"
        >
            <ul
                class="
                    font-semibold
                    h-full
                    hidden
                    ml-32
                    lg:ml-36
                    xl:ml-48
                    gap-2
                    lg:flex
                    lg:flex-row
                    lg:gap-6
                    lg:items-center
                    lg:text-xl
                    xl:text-xl
                    "
                :class="{'text-white !ml-24': scrollPosition > 0}"
            >
                <NavItems
                    v-for="(route, i) in navLinks"
                    :key="i"
                    :route="route"
                />
                <li
                    class="cursor-pointer flex items-center flex-row gap-2 ml-auto mr-8"
                    @click="handleGlobalsearchClick"
                >
                    <div class="hidden searchText xl:!block">
                        Search
                    </div>
                    <div>
                        <Search
                            class="h-7 stroke-main-darkGrey w-7"
                            :class="{'stroke-white': scrollPosition > 0}"
                        />
                    </div>
                </li>

                <ProfileDropdown
                    v-if="isAuthenticated"
                    :key="currentUser"
                    :current-user="currentUser"
                    :avatar-url="avatarUrl"
                />
            </ul>

            <div
                id="edSparkLogo"
                title="edSpark logo"
            >
                <router-link
                    :to="{name: 'dashboard'}"
                    title="Go to dashboard"
                >
                    <Logo
                        :class="logoClass"
                    />
                </router-link>
            </div>
        </nav>
    </div>
</template>

<style>

#searchIconMain {
    width: 28px;
}

.nav-background {
    clip-path: inset(0 0 round 0 0 75%);
    height: 100%;
}

.navDropdown {
    width: 170px;
    left: 50%;
    transform: translateX(-50%);
}

.nav-logo:hover {
    filter: drop-shadow(0px 0px 15px rgba(0, 0, 0, 0.5));
}

/*.navbarFullsize {*/
/*    font-size: 1.5rem;*/
/*}*/

@media screen and (max-width: 654px) {
    .nav-background {
        clip-path: inset(0 0 round 0 0 55%) !important;
    }
}

@media screen and (min-width: 1024px) {
    .navbarFullsize {
        transition: 300ms;
        position: fixed;
        top: 20px;
    }

    .navbarScrolled {
        top: 0 !important;
        background-color: #002856 !important;
        z-index: 60 !important;
        position: fixed;
    }
}

/* MB added the below to tidy up responsive nav bars */
@media screen and (max-width: 1024px) {
    #app, #app .container {
        min-width: 360px; /*was 320px */
        max-width: 1024px;
        width: 100%;
        margin: auto;
    }
}

</style>
