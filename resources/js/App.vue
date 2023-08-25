<script setup>

import GlobalSearch from "@/js/components/search/GlobalSearch.vue";
import NavBar from './components/global/navbar/NavBar.vue';
import Footer from './components/global/Footer/Footer.vue';
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";
import {useRoute, useRouter} from 'vue-router';
import {onBeforeMount, onBeforeUnmount, onMounted, reactive, ref} from "vue";
import recommenderEdsparkSingletonFactory from "@/js/recommender/recommenderEdspark";
import {isObjectEmpty} from "@/js/helpers/objectHelpers";
import {useWindowStore} from "@/js/stores/useWindowStore";
import NavbarMobileMenu from "@/js/components/global/navbar/NavbarMobileMenu.vue";
import {useAuthStore} from "@/js/stores/useAuthStore";


const router = useRouter();
const route = useRoute();
let recommender

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const windowStore = useWindowStore()
const {isMobile, windowWidth} = storeToRefs(windowStore)

const authStore = useAuthStore()
const {isAuthenticated} = storeToRefs(authStore)


const setWindowWidth = () => {
    windowWidth.value = window.innerWidth
    windowStore.updateIsMobile()
    windowStore.updateIsTablet()
}


onBeforeMount(async () => {
    // if currentUser / local storage is not empty check auth status
    if (!isObjectEmpty(currentUser.value) && !window.location.origin.includes('test.edspark.sa.edu.au')) {
        await authStore.checkAuthenticationStatus()
        /**
         * here means there is data inside localStorage but not logged in
         * auto redirect to login and should automatically login if okta still recognize
         */
        if (!isAuthenticated.value) {
            window.location = '/login'
        } else {
            if (route.name === 'home') {
                await router.push('/dashboard')
            }
        }
    } else {
        /*
        Here means local storage is empty, potentially new user. expect user to click login with okta
         */
    }
    if (currentUser.value?.id) {
        recommender = recommenderEdsparkSingletonFactory().getInstance(currentUser.value.id, 'Partner', 100)
    }
    setWindowWidth()
    window.addEventListener('resize', setWindowWidth)
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', setWindowWidth);
})

</script>

<template>
    <div class="relative w-full z-50">
        <NavBar
            :key="router.currentRoute.value"
        />
        <NavbarMobileMenu
            v-if="isMobile"
            class="absolute top-2 left-2 lg:hidden"
        />
    </div>
    <GlobalSearch />


    <div class="pageBodyContentContainer">
        <router-view />
    </div>
    <footer class="mt-auto">
        <Footer />
    </footer>
</template>
