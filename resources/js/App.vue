<script setup>

import {storeToRefs} from "pinia";
import {onBeforeMount, onBeforeUnmount} from "vue";
import {onMounted, ref} from "vue";
import {useRoute, useRouter} from 'vue-router';

import NavbarMobileMenu from "@/js/components/global/navbar/NavbarMobileMenu.vue";
import GlobalSearch from "@/js/components/search/GlobalSearch.vue";
import {appURL} from "@/js/constants/serverUrl";
import {isObjectEmpty} from "@/js/helpers/objectHelpers";
import recommenderEdsparkSingletonFactory from "@/js/recommender/recommenderEdspark";
import {useAuthStore} from "@/js/stores/useAuthStore";
import {useUserStore} from "@/js/stores/useUserStore";
import {useWindowStore} from "@/js/stores/useWindowStore";

import Footer from './components/global/Footer/Footer.vue';
import NavBar from './components/global/navbar/NavBar.vue';


const router = useRouter();
const route = useRoute();
let recommender;

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const windowStore = useWindowStore()
const {isMobile, windowWidth,showGlobalSearch} = storeToRefs(windowStore)

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
                axios.get(`${appURL}/sanctum/csrf-cookie`).then(response => {
                    router.push('/dashboard')
                })
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


// const userStore = useUserStore();
const navScrolled = ref(false);

onMounted(() => {
    if (!Object.keys(userStore.getUser).length <= 0) {
        currentUser.value = userStore.getUser;
    }
    
    // could probably make this more computationally efficient
    // but it seems to be more reliable than it was previously
    window.document.onscroll = () => {
        
        // this is only present on mobile
        let navbar = document.getElementById('navbarMobileBurger');

        // if it's not there, get the default navbar
        if(navbar == null){
            navbar = document.getElementById('navbarFullsize');
        }

        // avoiding errors, but shouldn't typically happen
        if(navbar != null) {
            // have we scrolled?
            navScrolled.value = window.scrollY > navbar.offsetTop;

            // adjust classes accordingly
            if (navScrolled.value){
                navbar.classList.add('navbarScrolled');
            } else {
                navbar.classList.remove('navbarScrolled');
            }
        }
        //console.log("Scroll: " + navbar.id +", "+navScrolled.value);
    }
});

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
