<script setup>
/**
 * Import Dependencies
 */
import {ref, onMounted, computed} from 'vue';
import {useRouter} from 'vue-router';

/**
 * Import SVG's
 */
import NavSwoosh from '../../svg/NavSwoosh.vue';
import Logo from '../../svg/Logo.vue';
import Profile from '../../svg/Profile.vue';

/**
 * Import Stores
 */
import {useUserStore} from '@/js/stores/useUserStore';
import {useAuthStore} from '@/js/stores/useAuthStore';

/**
 * Import Components
 */
import ProfileDropdown from '../ProfileDropdown.vue';
import NavItems from './NavItems.vue';
import {isObjectEmpty} from "@/js/helpers/objectHelpers";
import {storeToRefs} from "pinia";
import {useWindowStore} from "@/js/stores/useWindowStore";

const router = useRouter();
const userStore = useUserStore();
const navScrolled = ref(false);
const profileDropdown = ref(false);
const currentUser = ref({});
const navLinks = ref([]);

const authStore = useAuthStore();
const {isAuthenticated} = storeToRefs(authStore)

onMounted(() => {
    if (!Object.keys(userStore.getUser).length <= 0) {
        currentUser.value = userStore.getUser;
    }

    window.document.onscroll = () => {
        let navbar = document.getElementById('navbarFullsize')
        navScrolled.value = window.scrollY > navbar.offsetTop;
    }
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

const {isMobile, isTablet} = storeToRefs(useWindowStore)
</script>

<template>
    <div class="h-32 relative w-full z-50 md:!h-40 lg:!h-56">
        <img
            src="@/assets/images/navbar.png"
            alt="Image of children writing and playing VR"
            class="absolute top-0 left-0 h-full nav-background object-cover w-full"
        >
        <nav
            v-if="isAuthenticated"
            id="navbarFullsize"
            class="bg-[#002856]/50 container hidden navbarFullsize px-12 py-2 lg:!z-20 lg:block"
            :class="{navbarScrolled : navScrolled}"
        >
            <ul
                class="2xl:gap-8 2xl:text-2xl font-['Poppins'] font-semibold gap-4 hidden text-white xl:text-xl lg:flex lg:flex-row"
            >
                <NavItems
                    v-for="(route, i) in navLinks"
                    :key="i"
                    :route="route"
                />
            </ul>
        </nav>

        <ProfileDropdown
            v-if="isAuthenticated"
            :key="currentUser"
            :current-user="currentUser"
            :profile-dropdown="profileDropdown"
            :avatar-url="avatarUrl"
            @handle-avatar-click="handleAvatarClick"
        />
        <button>
            <router-link :to="{name: 'dashboard'}">
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
                        md:!h-44
                        md:!right-12
                        md:!top-6
                        md:!w-44
                        lg:!right-10
                        lg:!top-12
                        xl:!h-56
                        xl:!right-20
                        xl:!top-8
                        xl:!w-56
                        "
                />
            </router-link>
        </button>
        <NavSwoosh
            class="
                2xl:!top-0
                absolute
                top-12
                -bottom-6
                left-0
                pointer-events-none
                scale-y-150
                w-full
                md:!scale-y-100
                md:!top-12
                lg:!top-14
                xl:!top-4
                "
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

@media screen and (min-width: 1024px) {
    .navbarFullsize {
        position: fixed;
        top: 20px;
    }

    .navbarScrolled {
        transition: 150ms;
        top: 0 !important;
        background-color: #002856 !important;
    }
}

</style>
