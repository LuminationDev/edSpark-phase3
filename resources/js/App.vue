<script setup>

import {throttle} from "lodash";
import {storeToRefs} from "pinia";
import {onBeforeMount, onBeforeUnmount} from "vue";
import {onMounted, ref} from "vue";
import {useRouter} from 'vue-router';

import FeedbackForm from "@/js/components/feedbackform/FeedbackForm.vue";
import Footer from '@/js/components/global/Footer/Footer.vue';
import Navbar from '@/js/components/global/navbar/Navbar.vue';
import NavbarMobileMenu from "@/js/components/global/navbar/NavbarMobileMenu.vue";
import GlobalSearch from "@/js/components/search/GlobalSearch.vue";
import {appURL} from "@/js/constants/serverUrl";
import {useAuthStore} from "@/js/stores/useAuthStore";
import {useUserStore} from "@/js/stores/useUserStore";
import {useWindowStore} from "@/js/stores/useWindowStore";

const router = useRouter();

const userStore = useUserStore()
const {currentUser, userEntryLink} = storeToRefs(userStore)

const windowStore = useWindowStore()
const {isMobile, windowWidth, showGlobalSearch} = storeToRefs(windowStore)

const authStore = useAuthStore()


const setWindowWidth = () => {
    windowWidth.value = window.innerWidth
    windowStore.updateIsMobile()
    windowStore.updateIsTablet()
}
const setEventListeners = () => {
    setWindowWidth();
    window.addEventListener('resize', setWindowWidth);
    window.addEventListener('scroll', scrollHandler);
};

const removeEventListeners = () => {
    window.removeEventListener('resize', setWindowWidth);
    window.removeEventListener('scroll', scrollHandler);
};

/**
 * Handles the user's authentication process.
 *
 * 1. Checks if the current URL matches a specified origin.
 * 2. Checks the authentication status of the user.
 * 3. If the user isn't authenticated, they are redirected to the login page.
 * 4. If authenticated, the function ensures CSRF cookies are set,
 *    fetches the current user details and navigates to the appropriate route
 *    (either the user's entry link or the dashboard).
 *
 * @async
 * @function
 * @throws Will throw an error if network requests within the function fail.
 */
const handleAuth = async () => {
    // // Check if the URL contains the desired origin
    // if (window.location.origin.includes('test.edspark.sa.edu.au')) return;
    userStore.fetchCurrentUserAndLoadIntoStore();
    await authStore.checkAuthenticationStatus(); // populate isAuth with promise

    if (!authStore.isAuthenticated) {
        window.location = '/login';
    } else {
        await axios.get(`${appURL}/sanctum/csrf-cookie`);
        await userStore.fetchCurrentUserAndLoadIntoStore();
        if (userStore.userEntryLink === 'finished') {
        }
        // only trigger the redirect if entry link exists and not /
        else if (userStore.userEntryLink && userStore.userEntryLink !== '/') {
            let urlObj;
            try {
                urlObj = new URL(userStore.userEntryLink).pathname
            } catch (_) {
                urlObj = userStore.userEntryLink + ""
            } finally {
                userEntryLink.value = "finished" // this will stop redirection
                await router.push(urlObj)
            }
        } else {
            userEntryLink.value = "finished"
            await router.push('/dashboard')
        }
    }


};


onBeforeMount(async () => {
    await handleAuth()
});


// const userStore = useUserStore();
const navScrolled = ref(false);
let scrollHandler;

onMounted(() => {
    if (Object.keys(userStore.getUser).length > 0) {
        currentUser.value = userStore.getUser;
    }
    // This logic remains as it's specific to onMounted
    scrollHandler = throttle(() => {
        const navbar = document.getElementById('navbarMobileBurger') || document.getElementById('navbarFullsize');

        if (navbar) {
            navScrolled.value = window.scrollY > navbar.offsetTop;
            navbar.classList.toggle('navbarScrolled', navScrolled.value);
        }
    }, 100);
    setEventListeners();

});

onBeforeUnmount(() => {
    removeEventListeners()
})

onMounted(()=>{
    // remove navbar hidden when JS is loaded
    const navbarWrapper = document.getElementById('navbar-wrapper');
    if (navbarWrapper) {
        navbarWrapper.classList.remove('hidden');
    }
})
</script>

<template>
    <div>
        <div
            id="navbar-wrapper"
            class="hidden mobileBody relative w-full z-50"
        >
            <Navbar
                :key="router.currentRoute.value"
            />
            <NavbarMobileMenu
                v-if="isMobile"
            />
        </div>
        <div class="relative w-full z-50">
            <FeedbackForm />
        </div>
        <template v-if="showGlobalSearch">
            <GlobalSearch />
        </template>


        <div
            class="min-h-[80vh] pageBodyContentContainer"
        >
            <router-view />
        </div>

        <footer
            class="mt-auto"
        >
            <Footer />
        </footer>
    </div>
</template>
<style>
.tox-promotion {
    display: none;
}


.tox-statusbar__branding {
    display: none;

}


</style>
