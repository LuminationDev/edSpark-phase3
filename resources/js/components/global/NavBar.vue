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
import { appURL } from "@/js/constants/serverUrl";

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
    <div class="w-full h-[240px] relative z-40">
        <div
            class="nav-background w-full h-full pt-7 bg-[url(http://localhost:5173/resources/assets/images/children-vr.png)] bg-no-repeat bg-cover">
            <nav class="bg-[#002856]/50 py-2 px-12 w-full" v-if="isAuthenticated">
                <ul class="flex flex-row flex-wrap gap-8 text-white text-[24px] font-semibold font-['Poppins']">
                    <NavItems v-for="(route, i) in navLinks" :key="i" :route="route" />
                </ul>
            </nav>
        </div>

        <ProfileDropdown :key="currentUser" :current-user="currentUser" :profile-dropdown="profileDropdown"
            :avatar-url="avatarUrl" @handle-avatar-click="handleAvatarClick" v-if="isAuthenticated" />

        <!-- <Logo
            class="absolute right-20 top-36 z-30 md:w-44 md:h-44 md:top-24 sm:w-36 sm:h-36 sm:top-32 w-36 h-36 lg:top-24" /> -->
        <!-- Just rempving for demo purposes TODO: fix for mobile screen etc. -->
        <!-- class="absolute right-20 top-36 z-30 md:w-56 md:h-56 md:top-24 sm:w-36 sm:h-36 sm:top-32 w-36 h-36 lg:top-24" -->
        <button>
            <router-link :to="{ name: 'dashboard' }" v-if="isAuthenticated">
                <Logo class="absolute right-20 top-8 z-30 w-56 h-56 nav-logo transition-all" />
            </router-link>
            <Logo class="absolute right-20 top-8 z-30 w-56 h-56 nav-logo transition-all" v-else />
        </button>
        <NavSwoosh class="w-full absolute -bottom-6 left-0 right-0 pointer-events-none" />
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
