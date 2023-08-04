<script setup>

import NavBar from './components/global/NavBar.vue';
import Footer from './components/global/Footer/Footer.vue';
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";
import {useRouter} from 'vue-router';
import {onBeforeMount, onBeforeUnmount, onMounted, reactive, ref} from "vue";
import recommenderEdsparkSingletonFactory from "@/js/recommender/recommenderEdspark";
import {isObjectEmpty} from "@/js/helpers/objectHelpers";
import {useWindowStore} from "@/js/stores/useWindowStore";
import NavbarMobileMenu from "@/js/components/global/NavbarMobileMenu.vue";
import {useAuthStore} from "@/js/stores/useAuthStore";


const router = useRouter();
let recommender

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const windowStore = useWindowStore()
const {isMobile, isTablet,windowWidth, showMobileNavbar} = storeToRefs(windowStore)

const authStore = useAuthStore()
const {isAuthenticated} = storeToRefs(authStore)


const setWindowWidth = () => {
    windowWidth.value = window.innerWidth
    windowStore.updateIsMobile()
    windowStore.updateIsTablet()
}



onMounted(async () => {

    // if currentUser / local storage is not empty check auth status
    if(!isObjectEmpty(currentUser.value)){
        await authStore.checkAuthenticationStatus()
        /* here means there is data inside localStorage but not logged in
         * auto redirect to login and should automatically login if okta still recognize
         */
        if(!isAuthenticated.value){
            console.log('taking you to login because you are recognised')
            window.location= '/login'
        } else{
            await router.push('/dashboard')
        }
    }else{
        /*
        Here means local storage is empty, potentially new user. expect user to click login with okta
         */
    }
    if(currentUser.value?.id){
        recommender = recommenderEdsparkSingletonFactory().getInstance(currentUser.value.id,'Partner', 100)
    }
    console.log('user auth status is: ' + isAuthenticated.value)
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


    <div class="pageBodyContentContainer">
        <router-view />
    </div>
    <footer class="mt-auto">
        <Footer />
    </footer>
</template>
