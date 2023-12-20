<script setup>
import {storeToRefs} from "pinia";
import {computed, ref} from 'vue';
import {useRouter} from 'vue-router';

import ProfileDropdown from "@/js/components/global/ProfileDropdown.vue";
import Logo from '@/js/components/svg/Logo.vue';
import NavSwoosh from '@/js/components/svg/NavSwoosh.vue';
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

</script>

<template>
    <div class="h-32 relative w-full z-50 md:!h-40 lg:!h-56">
        <nav
            v-if="isAuthenticated"
            id="navbarFullsize"
            class="container h-16 hidden navbarFullsize px-12 text-black  lg:block lg:z-20"
        >
            <ul
                class="
                    2xl:gap-4
                    2xl:text-2xl
                    font-semibold
                    h-full
                    hidden
                    ml-48
                    text-lg
                    lg:text-xl
                    gap-4
                    lg:flex
                    lg:flex-row
                    lg:items-center
                    "
            >
                <NavItems
                    v-for="(route, i) in navLinks"
                    :key="i"
                    :route="route"
                />
                <li
                    class="cursor-pointer"
                    @click="handleGlobalsearchClick"
                >
                    <div
                        id="searchIconMain"
                        class="svg-container"
                    >
                        <Search />
                    </div>
                </li>
            </ul>
        </nav>

        <ProfileDropdown
            v-if="isAuthenticated"
            :key="currentUser"
            :current-user="currentUser"
            :avatar-url="avatarUrl"
        />
        <div
            id="edSparkLogo"
            title="edSpark logo"
        >
            <router-link
                :to="{name: 'dashboard'}"
                title="Go to dashboard"
            >
                <Logo
                    class="
                        absolute
                        top-4
                        right-2
                        h-32
                        nav-logo
                        transition-all
                        w-40
                        z-30
                        md:!right-12
                        md:!top-2
                        md:!w-44
                        lg:!left-10
                        lg:!top-8
                        xl:!h-36
                        xl:!right-20
                        xl:!top-2
                        "
                />
            </router-link>
        </div>
    </div>
</template>

<style>

#searchIconMain {
    width: 28px;
    margin-top: 2px;
    z-index: -1;
}

.svg-content {
    display: inline-block;
    position: absolute;
    top: 0;
    left: 0;
}

.svg-container {
    display: inline-block;
    position: relative;
    width: 100%;
    padding-bottom: 100%;
    vertical-align: middle;
    overflow: hidden;
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

.navbarFullsize {
    font-size: 1.5rem;
}

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
        color: white !important;
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
