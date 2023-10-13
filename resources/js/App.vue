<script setup>

import {throttle} from "lodash";
import {storeToRefs} from "pinia";
import {onBeforeMount, onBeforeUnmount} from "vue";
import {onMounted, ref} from "vue";
import {useRoute, useRouter} from 'vue-router';

import Footer from '@/js/components/global/Footer/Footer.vue';
import Navbar from '@/js/components/global/navbar/Navbar.vue';
import NavbarMobileMenu from "@/js/components/global/navbar/NavbarMobileMenu.vue";
import GlobalSearch from "@/js/components/search/GlobalSearch.vue";
import {appURL} from "@/js/constants/serverUrl";
import {isObjectEmpty} from "@/js/helpers/objectHelpers";
import {useAuthStore} from "@/js/stores/useAuthStore";
import {useUserStore} from "@/js/stores/useUserStore";
import {useWindowStore} from "@/js/stores/useWindowStore";


const router = useRouter();
const route = useRoute();

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const windowStore = useWindowStore()
const {isMobile, windowWidth, showGlobalSearch} = storeToRefs(windowStore)

const authStore = useAuthStore()
const {isAuthenticated} = storeToRefs(authStore)


const setWindowWidth = () => {
    windowWidth.value = window.innerWidth
    windowStore.updateIsMobile()
    windowStore.updateIsTablet()
}


onMounted(async () => {
    console.log('on mounted called')
    // if currentUser / local storage is not empty check auth status
    if ( !window.location.origin.includes('test.edspark.sa.edu.au')) {
        await authStore.checkAuthenticationStatus();
        if (!isAuthenticated.value) {
            window.location = '/login'
        } else {
            axios.get(`${appURL}/sanctum/csrf-cookie`).then(async () => {
                await userStore.fetchCurrentUserAndLoadIntoStore()

                if (route.name === 'home') {
                    await router.push('/dashboard')
                }
            })

        }

    } else {
        // LocalStorage is empty, potentially new user. expect user to click login with okta
        // Do nothing
    }
    setWindowWidth()
    window.addEventListener('resize', setWindowWidth)
})


// const userStore = useUserStore();
const navScrolled = ref(false);
let scrollHandler;

onMounted(() => {
    if (Object.keys(userStore.getUser).length > 0) {
        currentUser.value = userStore.getUser;
    }

    scrollHandler = throttle(() => {
        const navbar = document.getElementById('navbarMobileBurger') || document.getElementById('navbarFullsize');

        if (navbar) {
            navScrolled.value = window.scrollY > navbar.offsetTop;
            if (navScrolled.value) {
                navbar.classList.add('navbarScrolled');
            } else {
                navbar.classList.remove('navbarScrolled');
            }
        }
    }, 100);

    window.addEventListener('scroll', scrollHandler);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', setWindowWidth);
    window.removeEventListener('scroll', scrollHandler);
})

</script>

<template>
    <div class="relative w-full z-50">
        <Navbar
            :key="router.currentRoute.value"
        />
        <NavbarMobileMenu
            v-if="isMobile"
            class="absolute top-2 left-2 lg:hidden"
        />
    </div>
    <template v-if="showGlobalSearch">
        <GlobalSearch />
    </template>


    <div class="pageBodyContentContainer">
        <router-view />
    </div>
    <footer class="mt-auto">
        <Footer />
    </footer>
</template>
