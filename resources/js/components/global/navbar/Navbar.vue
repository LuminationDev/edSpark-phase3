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
    <div class="h-32 navbarParentContainer relative w-full z-40 md:!h-40 lg:!h-56">
        <img
            src="@/assets/images/navbar.png"
            alt="Image of children writing and playing VR"
            class="absolute top-0 left-0 h-full nav-background object-cover w-full"
        >
        <nav
            v-if="isAuthenticated"
            id="navbarFullsize"
            class="bg-[#002856]/50 container hidden navbarFullsize px-12 py-2 z-40 lg:block"
        >
            <ul
                class="2xl:gap-8 2xl:text-2xl font-semibold gap-4 hidden items-center text-white lg:flex lg:flex-row"
            >
                <NavItems
                    v-for="(route, i) in navLinks"
                    :key="i"
                    :route="route"
                />
                <li
                    class="cursor-pointer ml-auto mr-64 xl:!mr-[21rem]"
                    @click="handleGlobalsearchClick"
                >
                    <div
                        id="searchIconMain"
                        class="scale-[110%] svg-container"
                    >
                        <Search />
                    </div>
                </li>
            </ul>
        </nav>
        <div
            id="edSparkLogo"
            title="edSpark logo "
            class="absolute top-2 right-2 z-[45]  md:!right-12 md:!top-2 lg:!right-10 lg:!top-4 xl:!right-20 xl:!top-4"
        >
            <router-link
                :to="{name: 'dashboard'}"
                title="Go to dashboard"
            >
                <Logo
                    class="h-32 nav-logo transition-all w-40 z-30 md:!h-44 md:!w-44 xl:!h-56 xl:!w-56"
                />
            </router-link>
        </div>
        <div class="absolute top-6 right-60 xl:!right-80">
            <ProfileDropdown
                v-if="isAuthenticated"
                :key="currentUser"
                :current-user="currentUser"
                :avatar-url="avatarUrl"
            />
        </div>



        <NavSwoosh
            class="absolute -bottom-0 left-0 pointer-events-none scale-y-[1.2] w-full z-20"
        />
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
