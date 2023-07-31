<script setup>
/**
 * Import Dependencies
 */
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';

/**
 * Import SVG's
 */
import NavSwoosh from '../svg/NavSwoosh.vue';
import Logo from '../svg/Logo.vue';
import Profile from '../svg/Profile.vue';

/**
 * Import Stores
 */
import { useUserStore } from '@/js/stores/useUserStore';
import { useAuthStore } from '@/js/stores/useAuthStore';

/**
 * Import Components
 */
import ProfileDropdown from './ProfileDropdown.vue';
import NavItems from './NavItems.vue';
import { isObjectEmpty } from "@/js/helpers/objectHelpers";
import axios from 'axios';
import {appURL, serverURL, imageURL} from "@/js/constants/serverUrl";

const router = useRouter();
const userStore = useUserStore();
const navDropdownToggle = ref(false);
const profileDropdown = ref(false);
const currentUser = ref({});
const navLinks = ref([]);
// const isAuthenticated = ref(false);

const authStore = useAuthStore();
//Access the authentication status
const isAuthenticated = authStore.isAuthenticated;

onMounted(() => {
    if (!Object.keys(userStore.getUser).length <= 0) {
        currentUser.value = userStore.getUser;
    }

    // Make an API call to check the user's authentication status
    // update the 'isAuthenticated' ref accordingly

    // axios.get(`${appURL}/auth/check`).then(response => {
    //     isAuthenticated.value = response.data.authenticated;
    // });
});

const avatarUrl = computed(() => {
    let url = ''

    if (Object.keys(currentUser.value).length < 0 && !isObjectEmpty(currentUser.value) && !isObjectEmpty(currentUser.value['metadata'])) {
        currentUser.value['metadata'].forEach(meta => {
            if (meta['user_meta_key'] === 'userAvatar') {
                url = meta['user_meta_value'][0].replace(/\\\//g, "/");
            }
        })
    }
    return url
})

const handleAvatarClick = () => {
    profileDropdown.value = !profileDropdown.value;
};

const setupRoutes = () => {
    const tempNavArray = [];
    router.options.routes.forEach(route => {
        if (Object.keys(route).includes('meta') && route.meta['navigation']) {
            // TODO: Make the dropdown element dynamic
            tempNavArray.push(route);
        }
    });
    navLinks.value = tempNavArray;
};

setupRoutes();
</script>

<template>
    <div class="h-28 relative w-full z-40 md:!h-40 lg:!h-56">
        <div
            class="bg-cover bg-no-repeat h-full nav-background pt-7 w-full"
            :style="`background-image: url(${imageURL}/uploads/image/navbar.png) `"
        >
            <!--            <nav-->
            <!--                v-if="isAuthenticated"-->
            <!--                class="bg-[#002856]/50 py-2 px-12 w-full"-->
            <!--            >-->
            <nav
                class="bg-[#002856]/50 hidden px-12 py-2 w-full lg:block"
            >
                <ul
                    class="2xl:gap-8
                        2xl:text-2xl
                        font-['Poppins']
                        font-semibold
                        hidden
                        text-white
                        xl:text-xl
                        
                        
                        gap-4
                        lg:flex
                        lg:flex-row"
                >
                    <NavItems
                        v-for="(route, i) in navLinks"
                        :key="i"
                        :route="route"
                    />
                </ul>
            </nav>
        </div>

        <!--        <ProfileDropdown-->
        <!--            v-if="isAuthenticated"-->
        <!--            :key="currentUser"-->
        <!--            :current-user="currentUser"-->
        <!--            :profile-dropdown="profileDropdown"-->
        <!--            :avatar-url="avatarUrl"-->
        <!--            @handle-avatar-click="handleAvatarClick"-->
        <!--        />        -->
        <ProfileDropdown
            :key="currentUser"
            :current-user="currentUser"
            :profile-dropdown="profileDropdown"
            :avatar-url="avatarUrl"
            @handle-avatar-click="handleAvatarClick"
        />

        <!--        <Logo-->
        <!--            class="absolute top-36 right-20 z-30 h-36 w-36 sm:top-32 sm:h-36 sm:w-36 md:top-24 md:h-44 md:w-44 lg:top-24" />-->
        <!-- Just rempving for demo purposes TODO: fix for mobile screen etc. -->
        <!-- class="absolute top-36 right-20 z-30 h-36 w-36 sm:top-32 sm:h-36 sm:w-36 md:top-24 md:h-56 md:w-56 lg:top-24" -->
        <button>
            <router-link :to="{name: 'dashboard'}">
                <Logo
                    class="absolute
                        top-4
                        right-2
                        h-32
                        nav-logo
                        transition-all
                        w-40
                        z-30
                        
                        
                        md:!h-44
                        md:!right-12
                        md:!top-6
                        md:!w-44
                        lg:!right-10
                        lg:!top-12
                        xl:!h-56
                        xl:!right-20
                        xl:!top-8
                        xl:!w-56"
                />
            </router-link>
        </button>
        <NavSwoosh
            class="absolute right-0 -bottom-6 left-0 pointer-events-none w-full"
        />
    </div>
</template>

<style>
.nav-background {
    clip-path: inset(0 0 round 0 0 75%);
}

.navDropdown {
    width: 170px;
    left: 50%;
    transform: translateX(-50%);
}

.nav-logo:hover {
    filter: drop-shadow(0px 0px 15px rgba(0, 0, 0, 0.5));
}
</style>
